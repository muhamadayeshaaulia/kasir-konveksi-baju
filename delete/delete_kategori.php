<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_kategori = $_GET['id'];
    $sql = "DELETE FROM kategori WHERE id_kategori = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_kategori);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['delete_status'] = 'success';
                $_SESSION['delete_message'] = 'Kategori berhasil dihapus!';
            } else {
                $_SESSION['delete_status'] = 'error';
                $_SESSION['delete_message'] = 'Tidak ada kategori dengan ID tersebut.';
            }
        } else {
            $_SESSION['delete_status'] = 'error';
            $_SESSION['delete_message'] = 'Terjadi kesalahan saat menghapus kategori.';
        }
        
        $stmt->close();
    } else {
        $_SESSION['delete_status'] = 'error';
        $_SESSION['delete_message'] = 'Terjadi kesalahan dalam persiapan query.';
    }
} else {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'ID kategori tidak ditemukan.';
}

$conn->close();

header("Location: ../index.php?page=kategori");
exit();
?>