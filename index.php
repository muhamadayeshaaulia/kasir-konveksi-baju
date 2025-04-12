<?php
session_start();
include('./app/log_access.php');

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
    <title>Konveksi Yesha - Layanan Konveksi Berkualitas dengan Harga Terjangkau</title>
    <meta name="description" content="Konveksi Yesha menyediakan layanan konveksi berkualitas tinggi dengan harga terjangkau. Kami menawarkan pakaian custom untuk berbagai keperluan seperti event, bisnis, dan promosi.">
    <meta name="keywords" content="konveksi, pakaian custom, konveksi murah, konveksi Yesha, konveksi berkualitas, produksi pakaian custom, konveksi untuk event, konveksi untuk bisnis">
    <meta name="author" content="Yesha Konveksi">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Konveksi Yesha - Layanan Konveksi Berkualitas dengan Harga Terjangkau">
    <meta property="og:description" content="Konveksi Yesha menyediakan layanan konveksi dengan kualitas terbaik dan harga yang sangat terjangkau. Kami menyediakan pakaian custom untuk berbagai kebutuhan Anda.">
    <meta property="og:image" content="./img/erasebg-transformed.png">
    <meta property="og:url" content="https://www.yesha.ftik.my.id/konveksi-yesha">
    <link rel="icon" href="./img/erasebg-transformed.png" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="./style/index.css">
</head>
<body>
    <div class="container">
        <?php include('./asset/sidebar.php'); ?>

        <?php include('./asset/main.php'); ?>

        <?php include('./asset/navbar.php'); ?>
    </div>

    <?php
    if (isset($_GET['login']) && $_GET['login'] == 'success') :
    ?>
        <div id="notification-login" class="notification-login">
            <span class="material-icons check-icon">✔️</span>
            <b style="color: white"><?php echo htmlspecialchars($_SESSION['username']); ?></b>. berhasil login!
        </div>
    <?php endif; ?>
    <?php include('./asset/footer.php'); ?>
    <script src="./js/index.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const loginSuccess = urlParams.get('login');
        if (loginSuccess === 'success') {
            const notification = document.getElementById('notification-login');
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function(e) {
            if (
                e.key === "F12" ||
                (e.ctrlKey && e.shiftKey && (e.key === "I" || e.key === "J" || e.key === "C")) ||
                (e.ctrlKey && e.key === "U")
            ) {
                return false;
            }
        };
    </script>
</body>
</html>
