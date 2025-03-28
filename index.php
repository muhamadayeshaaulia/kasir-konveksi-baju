<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$level = $_SESSION['level'];
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="./style/index.css">
    <title>Konveksi | Yesha</title>
</head>
<body>
    <div class="container">
        <?php include('./asset/sidebar.php'); ?>

        <?php include('./asset/main.php'); ?>
        
        <?php include('./asset/navbar.php'); ?>
    </div>

    <script src="./orders/orders.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>