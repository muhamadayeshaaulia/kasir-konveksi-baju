<?php
session_start();
require 'app.php';
$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username; 
$_SESSION['level'] = $user_level; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';


    if (empty($email) || empty($password)) {
        header('Location: ../login.php?error=Input tidak boleh kosong');
        exit();
    }


    $stmt = $pdo->prepare('SELECT * FROM user WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && md5($password) === $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        switch ($user['level']) {
            case 'Owner':
                header('Location: ../index.php');
                break;
            case 'Admin':
                header('Location: ../index.php');
                break;
            case 'Kasir':
                header('Location: ../index.php');
                break;
            default:
                header('Location: ../login.php');
                break;
        }
        exit();
    } else {
        header('Location: ../login.php?error=Email atau password salah');
        exit();
    }
} else {
    header('Location: ../login.php');
    exit();
}
?>