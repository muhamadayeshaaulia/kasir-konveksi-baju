<?php
require '../app/koneksi.php';

$produk_id = isset($_GET['produk_id']) ? (int)$_GET['produk_id'] : 0;

if ($produk_id > 0) {
    $query = "SELECT harga, stok FROM produk WHERE id_produk = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $produk_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'harga' => $row['harga'],
            'stok' => $row['stok']
        ]);
    } else {
        echo json_encode(['error' => 'Produk tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID Produk tidak valid']);
}

mysqli_close($koneksi);
?>