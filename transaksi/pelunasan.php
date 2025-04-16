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
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="./style/pelunasan.css">

<div class="from-container">
    <h1>Form Pelunasan Transaksi</h1>

    <form method="get" action="">
        <input type="hidden" name="page" value="pelunasan">
        <div class="form-group">
            <label for="kode">Kode Transaksi:</label>
            <input type="text" id="kode" name="kode" required 
                   value="<?= isset($_GET['kode']) ? htmlspecialchars($_GET['kode']) : '' ?>">
            <button type="submit" class="btn">Cari</button>
        </div>
        <a href="./index.php?page=transaksi" class="btn" style="text-decoration: none;">
            <button type="button" class="btn">Transaksi</button>
        </a>
    </form>

    <br>

    <?php if ($transaksi): ?>
    <form method="post" action="./transaksi/proses_pelunasan.php" enctype="multipart/form-data">
        <input type="hidden" name="kode_transaksi" value="<?= htmlspecialchars($transaksi['kode_transaksi']) ?>">
        
        <h3>Detail Transaksi :</h3>
        <div class="form-group">
            <label>Kode:</label>
            <span><?= htmlspecialchars($transaksi['kode_transaksi']) ?></span>
        </div>
        <div class="form-group">
            <label>Jenis Pembelian:</label>
            <span><?= htmlspecialchars($transaksi['pembelian']) ?></span>
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
        const metode = document.getElementById('metodePembayaran')?.textContent.trim().toLowerCase();
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

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Pelunasan berhasil untuk transaksi <?= htmlspecialchars($_GET['kode']) ?>',
        icon: 'success',
        confirmButtonText: 'Oke'
    });
</script>
<?php elseif (isset($_GET['error'])): ?>
<script>
    Swal.fire({
        title: 'Gagal!',
        text: '<?= htmlspecialchars($_GET['error']) ?>',
        icon: 'error',
        confirmButtonText: 'Tutup'
    });
</script>
<?php endif; ?>
