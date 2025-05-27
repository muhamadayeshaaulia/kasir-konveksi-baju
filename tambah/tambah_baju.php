<?php
require '../app/koneksi.php';

$ukuran_bj = trim($koneksi->real_escape_string($_POST['ukuran_bj'] ?? ''));

$errors = [];
$old_input = ['ukuran_bj' => $ukuran_bj];

if (empty($ukuran_bj)) {
    $errors['ukuran_bj'] = "Type Ukuran wajib diisi";
}

if (empty($errors)) {
    $check_query = "SELECT id_ukbaju FROM uk_baju WHERE ukuran_bj = ?";
    $stmt = $koneksi->prepare($check_query);
    
    if ($stmt === false) {
        die("Error dalam persiapan query: " . $koneksi->error);
    }
    
    $stmt->bind_param("s", $ukuran_bj);
    
    if (!$stmt->execute()) {
        die("Error dalam eksekusi query: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors['ukuran_bj'] = "Type ukuran sudah ada";
    }
    $stmt->close();
}

if (!empty($errors)) {
    $query_string = http_build_query([
        'error' => 'Terdapat kesalahan dalam pengisian form',
        'errors' => json_encode($errors),
        'old' => json_encode($old_input)
    ]);
    header("Location: ../index.php?page=tbaju&" . $query_string);
    exit();
}

$insert_query = "INSERT INTO uk_baju (ukuran_bj) VALUES (?)";
$stmt = $koneksi->prepare($insert_query);

if ($stmt === false) {
    die("Error dalam persiapan query: " . $koneksi->error);
}

$stmt->bind_param("s", $ukuran_bj);

if ($stmt->execute()) {
    $last_id = $koneksi->insert_id;
    $verify_query = "SELECT ukuran_bj FROM uk_baju WHERE id_ukbaju = ?";
    $verify_stmt = $koneksi->prepare($verify_query);
    $verify_stmt->bind_param("i", $last_id);
    $verify_stmt->execute();
    $verify_stmt->store_result();
    
    if ($verify_stmt->num_rows > 0) {
        $pesan_sukses = "Type ukuran berhasil disimpan!";
        header("Location: ../index.php?page=tbaju&sukses=" . urlencode($pesan_sukses));
    } else {
        $pesan_error = "Gagal memverifikasi penyimpanan data";
        header("Location: ../index.php?page=tbaju&error=" . urlencode($pesan_error));
    }
    $verify_stmt->close();
} else {
    $pesan_error = "Gagal menyimpan data ukuran: " . $stmt->error;
    header("Location: ../index.php?page=tbaju&error=" . urlencode($pesan_error));
}

$stmt->close();
$koneksi->close();
?>