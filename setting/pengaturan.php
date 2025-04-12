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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF tidak valid");
    }
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $image    = $user['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/user/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_ext)) {
            die("Hanya file gambar yang diperbolehkan.");
        }

        $image = 'user_' . $user_id . '.' . $file_ext;
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    $level = $user['level'];
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
        header("Location: ./login.php");
        exit;
    } else {
        $error = "Gagal update user: " . mysqli_error($koneksi);
    }
}
?>
<link rel="stylesheet" href="./style/setting.css">
<main class="form-container">
    <h1>Edit Profil Saya</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
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
                <img src="uploads/user/<?= $user['image'] ?>" width="80" style="border-radius: 50px; max-width: 100px;"><br>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn">Simpan Perubahan</button>
    </form>
</main>
