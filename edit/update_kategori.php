<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kategori = $koneksi->real_escape_string($_POST['id_kategori']);
    $nama_kategori = $koneksi->real_escape_string($_POST['nama_kategori']);    
    if (empty($nama_kategori)) {
        $pesan_error = "Kategori harus diisi!";
    } else {
        $sql_cek = "SELECT id_kategori FROM kategori WHERE (nama_kategori = '$nama_kategori') AND id_kategori != '$id_kategori'";
        $hasil_cek = $koneksi->query($sql_cek);
        
        if ($hasil_cek->num_rows > 0) {
            $pesan_error = "Kategori sudah ada!";
        } else {
            $sql_update = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id_kategori'";
            
            if ($koneksi->query($sql_update)) {
                $pesan_sukses = "Kategori berhasil diperbarui!";
            } else {
                $pesan_error = "Error: " . $koneksi->error;
            }
        }
    }
}

$koneksi->close();

if (!empty($pesan_error)) {
    $_SESSION['error'] = $pesan_error;
    header("Location: ../index.php?page=ekategori&id=" . urlencode($id_kategori));
} elseif (!empty($pesan_sukses)) {
    $_SESSION['sukses'] = $pesan_sukses;
    header("Location: ../index.php?page=ekategori&id=" . urlencode($id_kategori) . "&sukses=" . urlencode($pesan_sukses));
} else {
    header("Location: ../index.php?page=kategori");
}
exit();
?>