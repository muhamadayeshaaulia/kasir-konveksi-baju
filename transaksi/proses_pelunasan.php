<?php
require '../app/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_transaksi'])) {
    $kode_transaksi = $_POST['kode_transaksi'];
    $error = '';
    $bukti_lunas = '';
    $target_file = '';

    $stmt = mysqli_prepare($koneksi, "SELECT pembayaran FROM transaksi WHERE kode_transaksi = ?");
    mysqli_stmt_bind_param($stmt, "s", $kode_transaksi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $metode_pembayaran = strtolower($row['pembayaran']);

    if ($metode_pembayaran === 'cash') {
        $bukti_lunas = 'CASH-' . date('YmdHis');
    } else {
        if ($_FILES['bukti_lunas']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/bukti_lunas/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['bukti_lunas']['name'], PATHINFO_EXTENSION));
            $bukti_lunas = $kode_transaksi . '_lunas.' . $file_ext;
            $target_file = $target_dir . $bukti_lunas;

            if (!in_array($file_ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
                $error = "Format tidak didukung.";
            } elseif ($_FILES['bukti_lunas']['size'] > 2000000) {
                $error = "Ukuran file maksimal 2MB.";
            } elseif (!move_uploaded_file($_FILES['bukti_lunas']['tmp_name'], $target_file)) {
                $error = "Gagal upload file.";
            }
        } else {
            $error = "Bukti pelunasan wajib diunggah.";
        }
    }

    if ($error) {
        header("Location: ../index.php?page=pelunasan&error=" . urlencode($error) . "&kode=" . urlencode($kode_transaksi));
        exit();
    }

    $query = "UPDATE transaksi SET 
        status_pembayaran = 'lunas',
        remaining_amount = 0,
        bukti_lunas = ?,
        tanggal_pelunasan = NOW()
        WHERE kode_transaksi = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $bukti_lunas, $kode_transaksi);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../index.php?page=pelunasan&success=1&kode=" . urlencode($kode_transaksi));
    } else {
        if (!empty($target_file) && file_exists($target_file)) {
            unlink($target_file);
        }
        $error = "Gagal menyimpan ke database.";
        header("Location: ../index.php?page=pelunasan&error=" . urlencode($error) . "&kode=" . urlencode($kode_transaksi));
    }
} else {
    header("Location: ../index.php?page=pelunasan");
}
