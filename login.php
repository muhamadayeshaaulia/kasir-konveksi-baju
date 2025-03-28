<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta Tags untuk SEO -->
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

    <link rel="stylesheet" type="text/css" href="./style/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="icon" href="./img/erasebg-transformed.png" type="image/png">
    
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
                <button type="submit">Sign up</button>
            </form>
        </div>

        <div class="login">
            <form action="./app/login_procces.php" method="POST">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <input type="password" name="password" placeholder="Password" required="">
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>