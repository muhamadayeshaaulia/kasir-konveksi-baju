<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$nama_kategori = trim($koneksi->real_escape_string($_POST['nama_kategori'] ?? ''));

$errors = [];
$old_input = ['nama_kategori' => $nama_kategori];

if (empty($nama_kategori)) {
    $errors['nama_kategori'] = "Nama kategori wajib diisi";
}

if (empty($errors)) {
    $check_query = "SELECT id_kategori FROM kategori WHERE nama_kategori = ?";
    $stmt = $koneksi->prepare($check_query);
    
    if ($stmt === false) {
        die("Error dalam persiapan query: " . $koneksi->error);
    }
    
    $stmt->bind_param("s", $nama_kategori);
    
    if (!$stmt->execute()) {
        die("Error dalam eksekusi query: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors['nama_kategori'] = "Nama kategori sudah ada";
    }
    $stmt->close();
}

if (!empty($errors)) {
    $query_string = http_build_query([
        'error' => 'Terdapat kesalahan dalam pengisian form',
        'errors' => json_encode($errors),
        'old' => json_encode($old_input)
    ]);
    header("Location: ../index.php?page=tkategori&" . $query_string);
    exit();
}

$insert_query = "INSERT INTO kategori (nama_kategori) VALUES (?)";
$stmt = $koneksi->prepare($insert_query);

if ($stmt === false) {
    die("Error dalam persiapan query: " . $koneksi->error);
}

$stmt->bind_param("s", $nama_kategori);

if ($stmt->execute()) {
    $last_id = $koneksi->insert_id;
    $verify_query = "SELECT nama_kategori FROM kategori WHERE id_kategori = ?";
    $verify_stmt = $koneksi->prepare($verify_query);
    $verify_stmt->bind_param("i", $last_id);
    $verify_stmt->execute();
    $verify_stmt->store_result();
    
    if ($verify_stmt->num_rows > 0) {
        $pesan_sukses = "Data kategori berhasil disimpan!";
        header("Location: ../index.php?page=tkategori&sukses=" . urlencode($pesan_sukses));
    } else {
        $pesan_error = "Gagal memverifikasi penyimpanan data";
        header("Location: ../index.php?page=tkategori&error=" . urlencode($pesan_error));
    }
    $verify_stmt->close();
} else {
    $pesan_error = "Gagal menyimpan data kategori: " . $stmt->error;
    header("Location: ../index.php?page=tkategori&error=" . urlencode($pesan_error));
}

$stmt->close();
$koneksi->close();
?>