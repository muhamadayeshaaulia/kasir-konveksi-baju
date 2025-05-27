<?php
require './app/koneksi.php';

$kategori_id = $_GET['kategori_id'];
$query = "SELECT * FROM produk WHERE id_kategori = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $kategori_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

echo '<option value="">-- Pilih Produk --</option>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<option value="'.$row['id_produk'].'">'.$row['nama_produk'].'</option>';
}

mysqli_close($koneksi);
?>