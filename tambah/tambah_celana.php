<?php
require '../app/koneksi.php';

$ukuran_cln = trim($koneksi->real_escape_string($_POST['ukuran_cln'] ?? ''));

$errors = [];
$old_input = ['ukuran_cln' => $ukuran_cln];

if (empty($ukuran_cln)) {
    $errors['ukuran_cln'] = "Type Ukuran wajib diisi";
}

if (empty($errors)) {
    $check_query = "SELECT id_ukcelana FROM uk_celana WHERE ukuran_cln = ?";
    $stmt = $koneksi->prepare($check_query);
    
    if ($stmt === false) {
        die("Error dalam persiapan query: " . $koneksi->error);
    }
    
    $stmt->bind_param("s", $ukuran_cln);
    
    if (!$stmt->execute()) {
        die("Error dalam eksekusi query: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors['ukuran_cln'] = "Type ukuran sudah ada";
    }
    $stmt->close();
}

if (!empty($errors)) {
    $query_string = http_build_query([
        'error' => 'Terdapat kesalahan dalam pengisian form',
        'errors' => json_encode($errors),
        'old' => json_encode($old_input)
    ]);
    header("Location: ../index.php?page=tcelana&" . $query_string);
    exit();
}

$insert_query = "INSERT INTO uk_celana (ukuran_cln) VALUES (?)";
$stmt = $koneksi->prepare($insert_query);

if ($stmt === false) {
    die("Error dalam persiapan query: " . $koneksi->error);
}

$stmt->bind_param("s", $ukuran_cln);

if ($stmt->execute()) {
    $last_id = $koneksi->insert_id;
    $verify_query = "SELECT ukuran_cln FROM uk_celana WHERE id_ukcelana = ?";
    $verify_stmt = $koneksi->prepare($verify_query);
    $verify_stmt->bind_param("i", $last_id);
    $verify_stmt->execute();
    $verify_stmt->store_result();
    
    if ($verify_stmt->num_rows > 0) {
        $pesan_sukses = "Type ukuran berhasil disimpan!";
        header("Location: ../index.php?page=tcelana&sukses=" . urlencode($pesan_sukses));
    } else {
        $pesan_error = "Gagal memverifikasi penyimpanan data";
        header("Location: ../index.php?page=tcelana&error=" . urlencode($pesan_error));
    }
    $verify_stmt->close();
} else {
    $pesan_error = "Gagal menyimpan data ukuran: " . $stmt->error;
    header("Location: ../index.php?page=tcelana&error=" . urlencode($pesan_error));
}

$stmt->close();
$koneksi->close();
?>