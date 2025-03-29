<?php
require '../app/koneksi.php';

$bahan_kain = trim($koneksi->real_escape_string($_POST['bahan_kain'] ?? ''));

$errors = [];
$old_input = ['bahan_kain' => $bahan_kain];

if (empty($bahan_kain)) {
    $errors['bahan_kain'] = "Nama bahan wajib diisi";
}

if (empty($errors)) {
    $check_query = "SELECT id_bahan FROM bahan WHERE bahan_kain = ?";
    $stmt = $koneksi->prepare($check_query);
    
    if ($stmt === false) {
        die("Error dalam persiapan query: " . $koneksi->error);
    }
    
    $stmt->bind_param("s", $bahan_kain);
    
    if (!$stmt->execute()) {
        die("Error dalam eksekusi query: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors['bahan_kain'] = "Nama Bahan sudah ada";
    }
    $stmt->close();
}

if (!empty($errors)) {
    $query_string = http_build_query([
        'error' => 'Terdapat kesalahan dalam pengisian form',
        'errors' => json_encode($errors),
        'old' => json_encode($old_input)
    ]);
    header("Location: ../index.php?page=tbahan&" . $query_string);
    exit();
}

$insert_query = "INSERT INTO bahan (bahan_kain) VALUES (?)";
$stmt = $koneksi->prepare($insert_query);

if ($stmt === false) {
    die("Error dalam persiapan query: " . $koneksi->error);
}

$stmt->bind_param("s", $bahan_kain);

if ($stmt->execute()) {
    $last_id = $koneksi->insert_id;
    $verify_query = "SELECT bahan_kain FROM bahan WHERE id_bahan = ?";
    $verify_stmt = $koneksi->prepare($verify_query);
    $verify_stmt->bind_param("i", $last_id);
    $verify_stmt->execute();
    $verify_stmt->store_result();
    
    if ($verify_stmt->num_rows > 0) {
        $pesan_sukses = "Data Bahan berhasil disimpan!";
        header("Location: ../index.php?page=tbahan&sukses=" . urlencode($pesan_sukses));
    } else {
        $pesan_error = "Gagal memverifikasi penyimpanan data";
        header("Location: ../index.php?page=tbahan&error=" . urlencode($pesan_error));
    }
    $verify_stmt->close();
} else {
    $pesan_error = "Gagal menyimpan data bahan: " . $stmt->error;
    header("Location: ../index.php?page=tbahan&error=" . urlencode($pesan_error));
}

$stmt->close();
$koneksi->close();
?>