<?php
require '../app/koneksi.php';

$nama_customer = $_GET['nama_customer'];
$query = "SELECT COUNT(*) as jumlah FROM transaksi WHERE nama_customer = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $nama_customer);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

echo json_encode([
    'diskon' => ($row['jumlah'] >= 5)
]);

mysqli_close($koneksi);
?>