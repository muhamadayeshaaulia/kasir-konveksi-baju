<?php
require '../app/koneksi.php';

$id = $_GET['id'];
$query = "SELECT harga, stok FROM cstm_pbahn WHERE id_cstm = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
?>
