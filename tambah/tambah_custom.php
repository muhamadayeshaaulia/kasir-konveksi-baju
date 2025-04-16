<?php
require '../app/koneksi.php';

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
    $stmt_check = $koneksi->prepare("SELECT COUNT(*) FROM cstm_pbahn WHERE cstm_bahan = ?");
    $stmt_check->bind_param("s", $data['cstm_bahan']);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    
    if ($count > 0) {
        $errors['cstm_bahan'] = 'Custom bahan sudah ada';
    }
}
if (!empty($errors)) {
    $query_string = http_build_query([
        'error' => 'Masukan Data yg benar!',
        'errors' => json_encode($errors),
        'old' => json_encode($old_input)
    ]);
    header("Location: ../index.php?page=tcustom&" . $query_string);
    exit();
}

if (!empty($errors)) {
    $query = http_build_query([
        'errors' => $errors,
        'data_lama' => $data
    ]);
    header("Location: ../index.php?page=tcustom&$query");
    exit;
}

try {
    $stmt = $koneksi->prepare("INSERT INTO cstm_pbahn (cstm_bahan, stok, harga) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $data['cstm_bahan'], $data['stok'], $data['harga']);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?page=tcustom&sukses=".urlencode("Cutom bahan baru berhasil ditambahkan!"));
    } else {
        throw new Exception("Gagal menyimpan Custom bahan baru: ".$stmt->error);
    }
} catch (Exception $e) {
    header("Location: ../index.php?page=tcustom&error=".urlencode($e->getMessage()));
} finally {
    if (isset($stmt)) $stmt->close();
    $koneksi->close();
}
?>