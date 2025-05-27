<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>403 - Akses Ditolak</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../style/403.css">
</head>
<body>
  <script>
    for (let i = 0; i < 100; i++) {
      const icon = document.createElement('div');
      icon.classList.add('floating');
      icon.textContent = '⚠️';
      icon.style.left = `${Math.random() * 100}vw`;
      icon.style.fontSize = `${18 + Math.random() * 20}px`;
      icon.style.animationDuration = `${10 + Math.random() * 10}s`;
      document.body.appendChild(icon);
    }
  </script>
  <div class="container">
    <svg class="warning-svg" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21.26 20H2.74a1 1 0 01-.87-1.5l9.26-16a1 1 0 011.74 0l9.26 16a1 1 0 01-.87 1.5z"/>
    </svg>
    <h1>403 - Akses Ditolak</h1>
    <p>Maaf! Kamu tidak punya izin untuk mengakses halaman ini secara langsung.</p>
    <a href="/kasir-konveksi-baju/login.php">🔐 Kembali ke Login</a>
  </div>
  <script>
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
