<?php
session_start();
require 'app.php';
$pdo = new PDO($dsn, $user, $pass, $opt);

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../signup.php?error=Token CSRF tidak valid');
    exit();
}

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($email) || empty($password)) {
    header('Location: ../login.php?error=Input tidak boleh kosong');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../login.php?error=Format email tidak valid');
    exit();
}
if (strlen($password) < 6) {
    header('Location: ../login.php?error=Password minimal 6 karakter');
    exit();
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$level = 'Kasir';

$stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    header('Location: ../login.php?error=Email sudah terdaftar');
    exit();
}

$sql = "INSERT INTO user (username, email, password, level) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $email, $hashed_password, $level]);

header('Location: ../login.php?signup=true');
exit();
?>
