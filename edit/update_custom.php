<?php
require '../app/koneksi.php';

$id_cstm = intval($_POST['id_cstm'] ?? 0);
$data = [
    'cstm_bahan' => trim($_POST['cstm_bahan'] ?? ''),
    'stok' => $_POST['stok'] ?? '',
    'harga' => $_POST['harga'] ?? ''
];

$errors = [];

if (empty($data['cstm_bahan'])) {
    $errors['cstm_bahan'] = 'Custom bahan wajib diisi';
}

if (!is_numeric($data['stok']) || $data['stok'] < 0) {
    $errors['stok'] = 'Stok harus angka positif';
}

if (!is_numeric($data['harga']) || $data['harga'] <= 0) {
    $errors['harga'] = 'Harga harus angka positif';
}

if (empty($errors['cstm_bahan'])) {
    $stmt_check = $koneksi->prepare("SELECT COUNT(*) FROM cstm_pbahn WHERE cstm_bahan = ? AND id_cstm != ?");
    $stmt_check->bind_param("si", $data['cstm_bahan'], $id_cstm);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    
    if ($count > 0) {
        $errors['cstm_bahan'] = 'Custom bahan sudah digunakan oleh Custom bahan lain';
    }
}

if (!empty($errors)) {
    $query = http_build_query([
        'id' => $id_cstm,
        'errors' => $errors,
        'data_lama' => $data
    ]);
    header("Location: ../index.php?page=ecustom&$query");
    exit;
}
try {
    $stmt = $koneksi->prepare("UPDATE cstm_pbahn SET 
        cstm_bahan = ?, 
        stok = ?, 
        harga = ? 
        WHERE id_cstm = ?");
    
    $stmt->bind_param("sidi", 
        $data['cstm_bahan'],
        $data['stok'],
        $data['harga'],
        $id_cstm
    );
    
    if ($stmt->execute()) {
        header("Location: ../index.php?page=ecustom&id=$id_produk&sukses=".urlencode("Custom bahan berhasil diperbarui!"));
    } else {
        throw new Exception("Gagal memperbarui custom bahan: ".$stmt->error);
    }
} catch (Exception $e) {
    header("Location: ../index.php?page=ecustom&id=$id_produk&error=".urlencode($e->getMessage()));
} finally {
    if (isset($stmt)) $stmt->close();
    $koneksi->close();
}
?>