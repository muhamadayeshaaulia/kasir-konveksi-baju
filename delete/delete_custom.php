<?php
session_start();

require '../app/koneksi.php';

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    $sql = "DELETE FROM cstm_pbahn WHERE id_cstm = ?";
    
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("i", $id_produk);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['delete_status'] = 'success';
                $_SESSION['delete_message'] = 'Custom bahan berhasil dihapus!';
            } else {
                $_SESSION['delete_status'] = 'error';
                $_SESSION['delete_message'] = 'Tidak ada Custom bahan dengan ID tersebut.';
            }
        } else {
            $_SESSION['delete_status'] = 'error';
            $_SESSION['delete_message'] = 'Terjadi kesalahan saat menghapus Custom bahan.';
        }
        
        $stmt->close();
    } else {
        $_SESSION['delete_status'] = 'error';
        $_SESSION['delete_message'] = 'Terjadi kesalahan dalam persiapan query.';
    }
} else {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'ID custom bahan tidak ditemukan.';
}

$koneksi->close();

header("Location: ../index.php?page=custom");
exit();
?>