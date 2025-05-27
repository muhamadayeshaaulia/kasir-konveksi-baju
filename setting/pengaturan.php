<?php
require './app/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user WHERE user_id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User tidak ditemukan.");
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Token CSRF tidak valid.";
        header("Location: ./index.php?page=settings");
        exit;
    }
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $image    = $user['image'];
    $level    = $user['level'];

    $can_change = in_array($_SESSION['level'], ['Admin', 'Owner', 'Kasir']);
    if (!$can_change) {
        $_SESSION['error'] = "Anda tidak memiliki izin untuk mengubah data ini.";
        header("Location: ./index.php?page=settings");
        exit;
    }

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/user/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = "Hanya file gambar yang diperbolehkan.";
            header("Location: ./index.php?page=settings");
            exit;
        }

        $image = 'user_' . $user_id . '.' . $file_ext;
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $_SESSION['error'] = "Gagal mengunggah gambar.";
            header("Location: ./index.php?page=settings");
            exit;
        }
    }
    if ($_SESSION['level'] === 'Admin' && isset($_POST['level'])) {
        $level = $_POST['level'];
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE user SET username=?, email=?, password=?, level=?, image=? WHERE user_id=?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $username, $email, $hashed, $level, $image, $user_id);
    } else {
        $sql = "UPDATE user SET username=?, email=?, level=?, image=? WHERE user_id=?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $level, $image, $user_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data berhasil diupdate.";
        $_SESSION['username'] = $username;
        $_SESSION['email']    = $email;
        $_SESSION['level']    = $level;
        if (!empty($image)) {
            $_SESSION['image'] = $image;
        }
    } else {
        $_SESSION['error'] = "Gagal update user: " . mysqli_stmt_error($stmt);
    }

    header("Location: ./index.php?page=settings");
    exit;
}
?>

<link rel="stylesheet" href="./style/setting.css">

<main class="form-container">
    <h1>Edit Profil Saya</h1>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes($_SESSION['success']) ?>',
            timer: 2000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= addslashes($_SESSION['error']) ?>'
        });
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>">
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="form-group">
            <label>Password (kosongkan jika tidak ingin diganti):</label>
            <input type="password" name="password">
        </div>

        <?php if ($_SESSION['level'] === 'Admin'): ?>
        <div class="form-group">
            <label>Level:</label>
            <select name="level" required>
                <option value="Admin" <?= $user['level'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Kasir" <?= $user['level'] === 'Kasir' ? 'selected' : '' ?>>Kasir</option>
                <option value="Owner" <?= $user['level'] === 'Owner' ? 'selected' : '' ?>>Owner</option>
            </select>
        </div>
        <?php else: ?>
        <input type="hidden" name="level" value="<?= $user['level'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Foto Profil:</label><br>
            <?php if (!empty($user['image'])): ?>
<<<<<<< HEAD
           <img src="uploads/user/<?= $user['image'] ?>?v=<?= time() ?>" class="round-profile">
=======
            <img src="uploads/user/<?= $user['image'] ?>" width="80" style="border-radius: 50px; max-width: 100px;"><br>
>>>>>>> 70f1bb4aa6e09dbf458f3eac02249cc90b5ca55e
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn">Simpan Perubahan</button>
    </form>
</main>
