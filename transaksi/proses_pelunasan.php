<?php
require './app/koneksi.php';

$kode_transaksi = $_POST['kode_transaksi'];
$tanggal_pelunasan = date('Y-m-d H:i:s');
$bukti_lunas = '';

$query_pembayaran = "SELECT pembayaran FROM transaksi WHERE kode_transaksi = ?";
$stmt_pembayaran = mysqli_prepare($koneksi, $query_pembayaran);
mysqli_stmt_bind_param($stmt_pembayaran, "s", $kode_transaksi);
mysqli_stmt_execute($stmt_pembayaran);
$result = mysqli_stmt_get_result($stmt_pembayaran);
$row = mysqli_fetch_assoc($result);
$pembayaran = strtolower($row['pembayaran']);

if ($pembayaran === 'cash') {
    $bukti_lunas = 'CASH-' . date('YmdHis');
} else {
    if ($_FILES['bukti_lunas']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/bukti_lunas/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES['bukti_lunas']['name'], PATHINFO_EXTENSION);
        $bukti_lunas = $kode_transaksi . '_lunas.' . $file_ext;
        move_uploaded_file($_FILES['bukti_lunas']['tmp_name'], $target_dir . $bukti_lunas);
    } else {
        die("Error: Bukti pelunasan wajib diunggah untuk metode pembayaran non-cash.");
    }
}

$query = "UPDATE transaksi SET 
    status_pembayaran = 'lunas',
    remaining_amount = 0,
    bukti_lunas = ?,
    tanggal_pelunasan = ?
    WHERE kode_transaksi = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "sss", $bukti_lunas, $tanggal_pelunasan, $kode_transaksi);

if (mysqli_stmt_execute($stmt)) {
    echo "Pelunasan berhasil diproses untuk transaksi: " . $kode_transaksi;
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

mysqli_close($koneksi);
?>