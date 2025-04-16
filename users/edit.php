<?php
require './app/koneksi.php';

$pengguna = [
    'user_id' => '',
    'username' => '',
    'email' => '',
    'level' => 'Kasir'
];
$pesan_error = '';

if (isset($_GET['id'])) {
    $user_id = $koneksi->real_escape_string($_GET['id']);
    $sql = "SELECT user_id, username, email, level FROM user WHERE user_id = '$user_id'";
    $hasil = $koneksi->query($sql);
    
    if ($hasil->num_rows > 0) {
        $pengguna = $hasil->fetch_assoc();
    } else {
        $pesan_error = "Pengguna tidak ditemukan!";
    }
}

$koneksi->close();

$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
?>
<link rel="stylesheet" href="./style/usere.css">

<div class="form-container">
    <h1 class="form-title"><?= isset($_GET['id']) ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' ?></h1>
    
    <?php if ($pesan_error): ?>
    <div class="alert alert-error">
        <span><?= htmlspecialchars($pesan_error) ?></span>
        <button class="close-btn" onclick="this.parentElement.style.display='none'">×</button>
    </div>
    <?php endif; ?>
    
    <?php if ($pesan_sukses): ?>
    <div class="alert alert-success">
        <span><?= htmlspecialchars(urldecode($pesan_sukses)) ?></span>
        <button class="close-btn" onclick="this.parentElement.style.display='none'">×</button>
    </div>
    <script>
        setTimeout(function(){
            window.location.href = 'index.php?page=users';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_user.php">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($pengguna['user_id']) ?>">
        
        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-input" 
                   value="<?= htmlspecialchars($pengguna['username']) ?>" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" 
                   value="<?= htmlspecialchars($pengguna['email']) ?>" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Hak Akses</label>
            <select name="level" class="form-select">
                <option value="Owner" <?= $pengguna['level'] === 'Owner' ? 'selected' : '' ?>>Owner</option>
                <option value="Admin" <?= $pengguna['level'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Kasir" <?= $pengguna['level'] === 'Kasir' ? 'selected' : '' ?>>Kasir</option>
                <option value="Demo" <?= $pengguna['level'] === 'Demo' ? 'selected' : '' ?>>Demo</option>
            </select>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn btn-simpan"
            <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Owner') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin dan owner yang bisa mengubah ini"'; ?>>
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <button type="button" onclick="window.location.href='index.php?page=users'" 
                    class="btn btn-batal">
                <i class="fas fa-times"></i> Batalkan
            </button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">