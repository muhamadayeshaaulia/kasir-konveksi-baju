<?php
require './app/koneksi.php';

$pesan_error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
$pesan_sukses = isset($_GET['sukses']) ? urldecode($_GET['sukses']) : '';
$errors = isset($_GET['errors']) ? json_decode(urldecode($_GET['errors']), true) : [];
$old_input = isset($_GET['old']) ? json_decode(urldecode($_GET['old']), true) : [];

?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./style/tbahan.css">
     <div class="form-container">
        <h1 class="form-title">Tambah Bahan Baru</h1>
        
        <?php if ($pesan_error): ?>
    <div class="alert alert-error">
        <span><?= htmlspecialchars($pesan_error) ?></span>
        <button class="close-btn" onclick="this.parentElement.style.display='none'">×</button>
    </div>
    <?php endif; ?>
    
    <?php if ($pesan_sukses): ?>
    <div class="alert alert-success" id="success-notification">
        <span><?= htmlspecialchars($pesan_sukses) ?></span>
        <button class="close-btn" onclick="hideNotificationAndRedirect()">×</button>
    </div>
    <script>
        function hideNotificationAndRedirect() {
            document.getElementById('success-notification').style.display = 'none';
            setTimeout(function() {
                window.location.href = 'index.php?page=bahan';
            }, 500);
        }
        setTimeout(function() {
            hideNotificationAndRedirect();
        }, 3000);
    </script>
    <?php endif; ?>
        <form method="POST" action="./tambah/tambah_bahan.php">
            <div class="form-group">
                <label class="form-label">Nama Bahan</label>
                <input type="text" name="bahan_kain" class="form-input" required 
                       value="<?= htmlspecialchars($old_input['bahan_kain'] ?? '') ?>"
                       placeholder="Masukkan bahan kain baru">
                <?php if (isset($errors['bahan_kain'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($errors['bahan_kain']) ?>
                    </div>
                <?php endif; ?>
            </div>
                    
            <div class="button-group">
                <button type="submit" class="btn btn-simpan"
                <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin yang bisa menyimpan"'; ?>>
                    <i class="fas fa-save"></i> Simpan Bahan
                </button>
                <a href="index.php?page=bahan" class="btn btn-batal">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }, 5000);
            });
            
            <?php if (!empty($errors)): ?>
                const firstErrorElement = document.querySelector('.error-message');
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            <?php endif; ?>
        });
    </script>