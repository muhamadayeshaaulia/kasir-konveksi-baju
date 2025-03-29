<?php
require '../app/koneksi.php';

$id_produk = intval($_POST['id_produk'] ?? 0);
$data = [
    'nama_produk' => trim($_POST['nama_produk'] ?? ''),
    'nama_kategori' => $_POST['nama_kategori'] ?? '',
    'stok' => $_POST['stok'] ?? '',
    'harga' => $_POST['harga'] ?? ''
];

$errors = [];

if (empty($data['nama_produk'])) {
    $errors['nama_produk'] = 'Nama produk wajib diisi';
}

if (empty($data['nama_kategori'])) {
    $errors['nama_kategori'] = 'Kategori wajib dipilih';
}

if (!is_numeric($data['stok']) || $data['stok'] < 0) {
    $errors['stok'] = 'Stok harus angka positif';
}

if (!is_numeric($data['harga']) || $data['harga'] <= 0) {
    $errors['harga'] = 'Harga harus angka positif';
}

if (empty($errors['nama_produk'])) {
    $stmt_check = $koneksi->prepare("SELECT COUNT(*) FROM produk WHERE nama_produk = ? AND id_produk != ?");
    $stmt_check->bind_param("si", $data['nama_produk'], $id_produk);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    
    if ($count > 0) {
        $errors['nama_produk'] = 'Nama produk sudah digunakan oleh produk lain';
    }
}

if (!empty($errors)) {
    $query = http_build_query([
        'id' => $id_produk,
        'errors' => $errors,
        'data_lama' => $data
    ]);
    header("Location: ../index.php?page=estok&$query");
    exit;
}
try {
    $stmt = $koneksi->prepare("UPDATE produk SET 
        nama_produk = ?, 
        kategori = ?, 
        stok = ?, 
        harga = ? 
        WHERE id_produk = ?");
    
    $stmt->bind_param("ssidi", 
        $data['nama_produk'],
        $data['nama_kategori'],
        $data['stok'],
        $data['harga'],
        $id_produk
    );
    
    if ($stmt->execute()) {
        header("Location: ../index.php?page=estok&id=$id_produk&sukses=".urlencode("Produk berhasil diperbarui!"));
    } else {
        throw new Exception("Gagal memperbarui produk: ".$stmt->error);
    }
} catch (Exception $e) {
    header("Location: ../index.php?page=estok&id=$id_produk&error=".urlencode($e->getMessage()));
} finally {
    if (isset($stmt)) $stmt->close();
    $koneksi->close();
}
?>