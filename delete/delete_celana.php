<?php
session_start();

require '../app/koneksi.php';

if (isset($_GET['id'])) {
    $id_ukcelana = $_GET['id'];
    $sql = "DELETE FROM uk_celana WHERE id_ukcelana = ?";
    
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("i", $id_ukcelana);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['delete_status'] = 'success';
                $_SESSION['delete_message'] = 'Type Ukuran berhasil dihapus!';
            } else {
                $_SESSION['delete_status'] = 'error';
                $_SESSION['delete_message'] = 'Tidak ada Type Ukuran dengan ID tersebut.';
            }
        } else {
            $_SESSION['delete_status'] = 'error';
            $_SESSION['delete_message'] = 'Terjadi kesalahan saat menghapus Type Ukuran.';
        }
        
        $stmt->close();
    } else {
        $_SESSION['delete_status'] = 'error';
        $_SESSION['delete_message'] = 'Terjadi kesalahan dalam persiapan query.';
    }
} else {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'ID ukuran tidak ditemukan.';
}

$koneksi->close();

header("Location: ../index.php?page=uk_celana");
exit();
?>