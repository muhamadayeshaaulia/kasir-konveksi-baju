<?php
require '../app/koneksi.php';

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
    $stmt_check = $koneksi->prepare("SELECT COUNT(*) FROM produk WHERE nama_produk = ?");
    $stmt_check->bind_param("s", $data['nama_produk']);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    
    if ($count > 0) {
        $errors['nama_produk'] = 'Nama produk sudah ada';
    }
}
if (!empty($errors)) {
    $query_string = http_build_query([
        'error' => 'Nama produk sudah ada',
        'errors' => json_encode($errors),
        'old' => json_encode($old_input)
    ]);
    header("Location: ../index.php?page=tstok&" . $query_string);
    exit();
}

if (!empty($errors)) {
    $query = http_build_query([
        'errors' => $errors,
        'data_lama' => $data
    ]);
    header("Location: ../index.php?page=tstok&$query");
    exit;
}

try {
    $stmt = $koneksi->prepare("INSERT INTO produk (nama_produk, kategori, stok, harga) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssid", $data['nama_produk'], $data['nama_kategori'], $data['stok'], $data['harga']);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?page=tstok&sukses=".urlencode("Produk berhasil ditambahkan!"));
    } else {
        throw new Exception("Gagal menyimpan produk: ".$stmt->error);
    }
} catch (Exception $e) {
    header("Location: ../index.php?page=tstok&error=".urlencode($e->getMessage()));
} finally {
    if (isset($stmt)) $stmt->close();
    $koneksi->close();
}
?>