<?php
require './app/koneksi.php';

$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

$pesan_error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
$pesan_sukses = isset($_GET['sukses']) ? urldecode($_GET['sukses']) : '';
$errors = $_GET['errors'] ?? [];
$data_lama = $_GET['data_lama'] ?? [];

$produk = [];
if ($id_produk > 0) {
    $stmt = $koneksi->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();
    
    if (!$produk) {
        header("Location: index.php?page=stok&error=Produk tidak ditemukan");
        exit();
    }
    
    if (empty($data_lama)) {
        $data_lama = [
            'nama_produk' => $produk['nama_produk'],
            'nama_kategori' => $produk['kategori'],
            'stok' => $produk['stok'],
            'harga' => $produk['harga']
        ];
    }
}
$query_kategori = "SELECT id_kategori, nama_kategori FROM kategori";
$result_kategori = $koneksi->query($query_kategori);

?>
<link rel="stylesheet" href="./style/eproduk.css">
<div class="form-container">
    <h1 class="form-title">Edit Produk</h1>
    
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
    
    <form method="POST" action="./edit/update_produk.php">
        <input type="hidden" name="id_produk" value="<?= $id_produk ?>">
        
        <div class="form-group">
            <label class="form-label">Nama produk</label>
            <input type="text" name="nama_produk" class="form-input" required 
                   value="<?= htmlspecialchars($data_lama['nama_produk'] ?? '') ?>">
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
            <input type="number" name="stok" class="form-input" required 
                   value="<?= htmlspecialchars($data_lama['stok'] ?? '') ?>">
            <?php if (isset($errors['stok'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['stok']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-input" required 
                   value="<?= htmlspecialchars($data_lama['harga'] ?? '') ?>">
            <?php if (isset($errors['harga'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['harga']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn btn-simpan">
                <i class="fas fa-save"></i> Simpan Perubahan
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