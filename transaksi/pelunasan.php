<?php
include './app/koneksi.php';
$transaksi = null;
if (isset($_GET['kode'])) {
    $kode = $_GET['kode'];
    $query = "SELECT * FROM transaksi WHERE kode_transaksi = ? AND status_pembayaran = 'dp'";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $kode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $transaksi = mysqli_fetch_assoc($result);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kode_transaksi'])) {
    $kode_transaksi = $_POST['kode_transaksi'];
    
    $query_pembayaran = "SELECT pembayaran FROM transaksi WHERE kode_transaksi = ?";
    $stmt_pembayaran = mysqli_prepare($koneksi, $query_pembayaran);
    mysqli_stmt_bind_param($stmt_pembayaran, "s", $kode_transaksi);
    mysqli_stmt_execute($stmt_pembayaran);
    $result_pembayaran = mysqli_stmt_get_result($stmt_pembayaran);
    $row_pembayaran = mysqli_fetch_assoc($result_pembayaran);
    $metode_pembayaran = strtolower($row_pembayaran['pembayaran']);

    $bukti_lunas = '';
    $target_file = '';

    if ($metode_pembayaran === 'cash') {
        $bukti_lunas = 'CASH-' . date('YmdHis');
    } else {
        if ($_FILES['bukti_lunas']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/bukti_lunas/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_ext = pathinfo($_FILES['bukti_lunas']['name'], PATHINFO_EXTENSION);
            $bukti_lunas = $kode_transaksi . '_lunas.' . $file_ext;
            $target_file = $target_dir . $bukti_lunas;
            $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];

            if (!in_array(strtolower($file_ext), $allowed_types)) {
                $error = "Hanya file JPG, JPEG, PNG, atau PDF yang diizinkan.";
            } elseif ($_FILES['bukti_lunas']['size'] > 2000000) {
                $error = "Ukuran file terlalu besar. Maksimal 2MB.";
            } elseif (!move_uploaded_file($_FILES['bukti_lunas']['tmp_name'], $target_file)) {
                $error = "Gagal mengupload file.";
            }
        } else {
            $error = "Harap upload bukti pelunasan.";
        }
    }

    if (!isset($error)) {
        $query = "UPDATE transaksi SET 
            status_pembayaran = 'lunas',
            remaining_amount = 0,
            bukti_lunas = ?,
            tanggal_pelunasan = NOW()
            WHERE kode_transaksi = ?";

        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ss", $bukti_lunas, $kode_transaksi);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Pelunasan berhasil diproses!";
            $transaksi = null;
        } else {
            if (!empty($target_file) && file_exists($target_file)) {
                unlink($target_file);
            }
            $error = "Error: " . mysqli_error($koneksi);
        }
    }
}

?>
<link rel="stylesheet" href="./style/pelunasan.css">
<div class="from-container">
    <h1>Form Pelunasan Transaksi</h1>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="get" action="">
        <input type="hidden" name="page" value="pelunasan">
        <div class="form-group">
            <label for="kode">Kode Transaksi:</label>
            <input type="text" id="kode" name="kode" required 
                   value="<?= isset($_GET['kode']) ? htmlspecialchars($_GET['kode']) : '' ?>">
            <button type="submit" class="btn">Cari</button>
        </div>
        <a href="./index.php?page=transaksi" class="btn" style="text-decoration: none; display: inline-block;">
    <button type="button" class="btn">
        Transaksi
    </button>
</a>
    </form>
    <br>
    <?php if ($transaksi): ?>
    <form method="post" action="./index.php?page=pelunasan" enctype="multipart/form-data">
        <input type="hidden" name="kode_transaksi" value="<?= htmlspecialchars($transaksi['kode_transaksi']) ?>">
        
        <h3>Detail Transaksi :</h3>
        <br>
        <div class="form-group">
            <label>Kode:</label>
            <span><?= htmlspecialchars($transaksi['kode_transaksi']) ?></span>
        </div>
        
        <div class="form-group">
            <label>Customer:</label>
            <span><?= htmlspecialchars($transaksi['nama_customer']) ?></span>
        </div>
        
        <div class="form-group">
            <label>Total:</label>
            <span>Rp <?= number_format($transaksi['total'], 2) ?></span>
        </div>
        
        <div class="form-group">
            <label>DP (50%):</label>
            <span>Rp <?= number_format($transaksi['dp_amount'], 2) ?></span>
        </div>
        
        <div class="form-group">
            <label>Sisa:</label>
            <span>Rp <?= number_format($transaksi['remaining_amount'], 2) ?></span>
        </div>

        <div class="form-group">
            <label>Pembayaran:</label>
            <span id="metodePembayaran"><?= htmlspecialchars($transaksi['pembayaran']) ?></span>
        </div>

        
        <div class="form-group" id="uploadBuktiGroup">
            <label for="bukti_lunas">Bukti Pelunasan:</label>
            <input type="file" id="bukti_lunas" name="bukti_lunas" required>
        </div>
        
        <button type="submit" class="btn">Proses Pelunasan</button>
    </form>
    <?php elseif (isset($_GET['kode'])): ?>
        <div class="alert alert-danger">Transaksi tidak ditemukan atau sudah lunas</div>
    <?php endif; ?>
</div>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const metode = document.getElementById('metodePembayaran').textContent.trim().toLowerCase();
        const uploadGroup = document.getElementById('uploadBuktiGroup');
        const buktiInput = document.getElementById('bukti_lunas');

        if (metode === 'cash') {
            uploadGroup.style.display = 'none';
            buktiInput.removeAttribute('required');
        } else {
            uploadGroup.style.display = 'block';
            buktiInput.setAttribute('required', 'required');
        }
    });
</script>
