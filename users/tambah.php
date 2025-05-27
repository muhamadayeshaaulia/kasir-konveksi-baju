<?php
require './app/koneksi.php';

$pesan_error = isset($_GET['error']) ? $_GET['error'] : '';
$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
$errors = $_GET['errors'] ?? [];

$koneksi->close();

?>
<link rel="stylesheet" href="./style/usert.css">
    <div class="form-container">
    <h1 class="form-title">Tambah Pengguna Baru</h1>
    
    <?php if ($pesan_error): ?>
    <div class="alert alert-error">
        <span><?= htmlspecialchars(urldecode($pesan_error)) ?></span>
        <button class="close-btn" onclick="this.parentElement.style.display='none'">×</button>
    </div>
    <?php endif; ?>
    
    <?php if ($pesan_sukses): ?>
    <div class="alert alert-success">
        <span><?= htmlspecialchars(urldecode($pesan_sukses)) ?></span>
        <button class="close-btn" onclick="this.parentElement.style.display='none'">×</button>
    </div>
    <script>setTimeout(function(){
        window.location.href = 'index.php?page=users';
    }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./tambah/tambah_user.php">
        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-input" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <?php if (isset($errors['username'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['username']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['email']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="password-toggle">
                <input type="password" name="password" id="password" class="form-input" required 
                       minlength="8" oninput="checkPasswordStrength(this.value)">
                <i class="fas fa-eye password-toggle-icon" onclick="togglePassword('password')"></i>
            </div>
            <div id="password-strength" class="password-strength"></div>
            <?php if (isset($errors['password'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['password']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <div class="password-toggle">
                <input type="password" name="confirm_password" id="confirm_password" class="form-input" required 
                       minlength="8">
                <i class="fas fa-eye password-toggle-icon" onclick="togglePassword('confirm_password')"></i>
            </div>
            <?php if (isset($errors['confirm_password'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['confirm_password']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Hak Akses</label>
            <select name="level" class="form-select" required>
                <option value="" disabled selected>-- Pilih Hak Akses --</option>
                <option value="Owner" <?= (isset($_POST['level']) && $_POST['level'] === 'Owner' ? 'selected' : '' )?>>Owner</option>
                <option value="Admin" <?= (isset($_POST['level']) && $_POST['level'] === 'Admin' ? 'selected' : '')?>>Admin</option>
                <option value="Kasir" <?= (isset($_POST['level']) && $_POST['level'] === 'Kasir' ? 'selected' : '') ?>>Kasir</option>
                <option value="Demo" <?= (isset($_POST['level']) && $_POST['level'] === 'Demo' ? 'selected' : '') ?>>Demo</option>
            </select>
        </div>
        
        <div class="button-group">
        <button type="submit" class="btn btn-simpan"
        <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Owner') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin dan owner yang bisa menyimpan"'; ?>>
            <i class="fas fa-save"></i> Simpan Pengguna
        </button>

            <button type="button" onclick="window.location.href='index.php?page=users'" 
                    class="btn btn-batal">
                <i class="fas fa-times"></i> Batalkan
            </button>
        </div>
    </form>
</div>
<script src="./js/usert.js"></script>