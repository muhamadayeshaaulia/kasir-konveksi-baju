<?php
require 'app.php';
$pdo = new PDO($dsn, $user, $pass, $opt);

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$hashed_password = md5($password);

$level = 'kasir';

$sql = "INSERT INTO user (username, email, password, level) VALUES (?, ?, ?, ?)";
$stmt= $pdo->prepare($sql);
$stmt->execute([$username, $email, $hashed_password, $level]);

header('Location: ../login.php?login=true');
exit;
?>