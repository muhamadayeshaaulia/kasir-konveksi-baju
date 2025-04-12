<?php
session_start();
require 'app.php';

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../login.php?error=Token CSRF tidak valid');
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: ../login.php?error=Input tidak boleh kosong');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../login.php?error=Format email tidak valid');
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM user WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['level'] = $user['level'];
    session_regenerate_id(true);

    switch ($user['level']) {
        case 'Owner':
        case 'Admin':
        case 'Kasir':
            header("Location: ../index.php?login=success");
            break;
        default:
            header('Location: ../login.php?error=invalid_login');
            break;
    }
    exit();
} else {
    header('Location: ../login.php?error=invalid_login');
    exit();
}
?>
