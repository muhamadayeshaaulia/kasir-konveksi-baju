<?php
session_start();

require '../app/koneksi.php';

if (isset($_GET['id'])) {
    $id_bahan = $_GET['id'];
    $sql = "DELETE FROM bahan WHERE id_bahan = ?";
    
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("i", $id_bahan);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['delete_status'] = 'success';
                $_SESSION['delete_message'] = 'Bahan berhasil dihapus!';
            } else {
                $_SESSION['delete_status'] = 'error';
                $_SESSION['delete_message'] = 'Tidak ada bahan dengan ID tersebut.';
            }
        } else {
            $_SESSION['delete_status'] = 'error';
            $_SESSION['delete_message'] = 'Terjadi kesalahan saat menghapus bahan.';
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

$koneksi->close();

header("Location: ../index.php?page=bahan");
exit();
?>