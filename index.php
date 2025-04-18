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
    <meta name="google-site-verification" content="gLCuiRU6JiwV_ZXtwspbx4kRu0A3-A9L_n83Je-E_sg" />
    <meta property="og:title" content="Konveksi Yesha - Layanan Konveksi Berkualitas dengan Harga Terjangkau">
    <meta property="og:description" content="Konveksi Yesha menyediakan layanan konveksi dengan kualitas terbaik dan harga yang sangat terjangkau. Kami menyediakan pakaian custom untuk berbagai kebutuhan Anda.">
    <meta property="og:image" content="./img/erasebg-transformed.png">
    <meta property="og:url" content="https://konveksi.eduzillen.id/">
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
    <?php include('./asset/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const loginSuccess = urlParams.get('login');
    if (loginSuccess === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            html: 'Selamat datang, <i><?php echo htmlspecialchars($_SESSION['username']); ?>!</i>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    }
</script>
<script>
let lastMessageId = null;
function checkNewChatMessage() {
  const currentPage = new URLSearchParams(window.location.search).get('page');
  if (currentPage === 'chat') return;

  fetch("./chat/check_last_message.php")
    .then(res => res.json())
    .then(data => {
      if (data && data.id !== lastMessageId) {
        if (lastMessageId !== null) {
          Swal.fire({
            icon: 'info',
            title: '💬 Pesan Baru!',
            text: 'Ada pesan baru di forum chat!',
            showCancelButton: true,
            confirmButtonText: 'Buka Chat',
            cancelButtonText: 'Nanti Saja',
            confirmButtonColor: '#00acc1',
            cancelButtonColor: '#aaa'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = '?page=chat';
            }
          });
        }
        lastMessageId = data.id;
      }
    });
}
setInterval(checkNewChatMessage, 5000);
checkNewChatMessage();
</script>
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
