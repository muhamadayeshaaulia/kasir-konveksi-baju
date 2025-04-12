<?php
require './app/koneksi.php';

$pesan_error = isset($_GET['error']) ? $_GET['error'] : '';
$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
$errors = $_GET['errors'] ?? [];

$query_kategori = "SELECT id_kategori, nama_kategori FROM kategori";
$result_kategori = $koneksi->query($query_kategori);

?>
    <link rel="stylesheet" href="./style/tproduk.css">
    <div class="form-container">
    <h1 class="form-title">Tambah Produk Baru</h1>
    
    <?php if ($pesan_error): ?>
    <div class="alert alert-error">
        <span><?= htmlspecialchars($pesan_error) ?></span>
        <button class="close-btn" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>
    
    <?php if ($pesan_sukses): ?>
    <div class="alert alert-success">
        <span><?= htmlspecialchars($pesan_sukses) ?></span>
        <button class="close-btn" onclick="this.parentElement.remove()">×</button>
    </div>
    <script>
        setTimeout(function(){
            window.location.href = 'index.php?page=stok';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./tambah/tambah_produk.php">
        <div class="form-group">
            <label class="form-label">Nama produk</label>
            <input type="text" name="nama_produk" class="form-input" required value="<?= htmlspecialchars($_POST['nama_produk'] ?? '') ?>">
            <?php if (isset($errors['nama_produk'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['nama_produk']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="nama_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($kategori = $result_kategori->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($kategori['nama_kategori']) ?>"
                        <?= ($data_lama['nama_kategori'] ?? '') == $kategori['nama_kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kategori['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (isset($errors['nama_kategori'])): ?>
                <small class="error"><?= $errors['nama_kategori'] ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="text" name="stok" class="form-input" required value="<?= htmlspecialchars($_POST['stok'] ?? '') ?>">
            <?php if (isset($errors['stok'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['stok']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Harga</label>
            <input type="text" name="harga" class="form-input" required value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>">
            <?php if (isset($errors['harga'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['harga']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn btn-simpan">
                <i class="fas fa-save"></i> Simpan Pengguna
            </button>
            <button type="button" onclick="window.location.href='index.php?page=stok'" 
                    class="btn btn-batal">
                <i class="fas fa-times"></i> Batalkan
            </button>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>