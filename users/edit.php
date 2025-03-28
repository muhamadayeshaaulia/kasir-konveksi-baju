<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

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

<div class="recent-orders">
    <h2><?= isset($_GET['id']) ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h2>
    
    <?php if ($pesan_error): ?>
    <div style="background-color:#f44336; color:white; padding:15px; border-radius:5px; margin-bottom:20px;">
        <?= htmlspecialchars($pesan_error) ?>
    </div>
    <?php endif; ?>
    
    <?php if ($pesan_sukses): ?>
    <div style="background-color:#4CAF50; color:white; padding:15px; border-radius:5px; margin-bottom:20px;">
        <?= htmlspecialchars(urldecode($pesan_sukses)) ?>
        <script>
            setTimeout(function(){
                window.location.href = 'index.php?page=users';
            }, 1500);
        </script>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_user.php" style="max-width:500px;">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($pengguna['user_id']) ?>">
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($pengguna['username']) ?>" 
                   style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;" required>
        </div>
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($pengguna['email']) ?>" 
                   style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;" required>
        </div>
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Hak Akses</label>
            <select name="level" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                <option value="Owner" <?= $pengguna['level'] === 'Owner' ? 'selected' : '' ?>>Owner</option>
                <option value="Admin" <?= $pengguna['level'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Kasir" <?= $pengguna['level'] === 'Kasir' ? 'selected' : '' ?>>Kasir</option>
            </select>
        </div>
        
        <div style="display:flex; gap:10px;">
            <button type="submit" style="background-color:#4CAF50; color:white; padding:10px 15px; border:none; border-radius:4px; cursor:pointer;">
                Simpan
            </button>
            
            <button type="button" onclick="window.location.href='index.php?page=users'" 
                    style="background-color:#f44336; color:white; padding:10px 15px; border:none; border-radius:4px; cursor:pointer;">
                Batal
            </button>
        </div>
    </form>
</div>