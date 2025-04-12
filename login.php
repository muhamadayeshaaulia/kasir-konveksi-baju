<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="description" content="Konveksi Yesha menyediakan layanan konveksi berkualitas tinggi dengan harga terjangkau. Kami menawarkan pakaian custom untuk berbagai keperluan seperti event, bisnis, dan promosi.">
    <meta name="keywords" content="konveksi, pakaian custom, konveksi murah, konveksi Yesha, konveksi berkualitas, produksi pakaian custom, konveksi untuk event, konveksi untuk bisnis">
    <meta name="author" content="Yesha Konveksi">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Konveksi Yesha - Layanan Konveksi Berkualitas dengan Harga Terjangkau">
    <meta property="og:description" content="Konveksi Yesha menyediakan layanan konveksi dengan kualitas terbaik dan harga yang sangat terjangkau. Kami menyediakan pakaian custom untuk berbagai kebutuhan Anda.">
    <meta property="og:image" content="./img/erasebg-transformed.png">
    <meta property="og:url" content="https://www.yourdomain.com/konveksi-yesha">
    <link rel="icon" href="./img/erasebg-transformed.png" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="./style/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <form action="./app/signup_procces.php" method="POST">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="username" placeholder="User name" required>
                <input type="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <input type="password" name="password" placeholder="Password" required minlength="6">

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <button type="submit">Sign up</button>
            </form>
        </div>

        <div class="login">
            <form action="./app/login_procces.php" method="POST">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <input type="password" name="password" placeholder="Password" required>

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
    <?php
    if (isset($_GET['error']) && $_GET['error'] == 'invalid_login') :
    ?>
    <div class="notification" id="notification">
    Email atau Password salah!
    </div>
    <?php endif; ?>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');

        if (error === 'invalid_login') {
            document.getElementById('notification').classList.add('show');
            setTimeout(() => {
                document.getElementById('notification').classList.remove('show');
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
