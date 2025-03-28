<?php
session_start();
require '../app/app.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'Hanya admin yang dapat menghapus user';
    header("Location: ../index.php?page=users");
    exit();
}
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php?page=users';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'ID user tidak valid';
    header("Location: " . $referer);
    exit();
}
$id = (int)$_GET['id'];
try {
    $check = $pdo->prepare("SELECT user_id FROM user WHERE user_id = ?");
    $check->execute([$id]);
    if ($check->rowCount() === 0) {
        $_SESSION['delete_status'] = 'error';
        $_SESSION['delete_message'] = 'User tidak ditemukan';
        header("Location: " . $referer);
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['delete_status'] = 'success';
    $_SESSION['delete_message'] = 'User berhasil dihapus';
} catch (PDOException $e) {
    $_SESSION['delete_status'] = 'error';
    $_SESSION['delete_message'] = 'Gagal menghapus user: ' . $e->getMessage();
}

header("Location: " . $referer);
exit();
?>