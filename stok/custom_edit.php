<?php
require './app/koneksi.php';

$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

$pesan_error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
$pesan_sukses = isset($_GET['sukses']) ? urldecode($_GET['sukses']) : '';
$errors = $_GET['errors'] ?? [];
$data_lama = $_GET['data_lama'] ?? [];

$produk = [];
if ($id_produk > 0) {
    $stmt = $koneksi->prepare("SELECT * FROM cstm_pbahn WHERE id_cstm = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();
    
    if (!$produk) {
        header("Location: index.php?page=custom&error=Produk tidak ditemukan");
        exit();
    }
    
    if (empty($data_lama)) {
        $data_lama = [
            'cstm_bahan' => $produk['cstm_bahan'],
            'stok' => $produk['stok'],
            'harga' => $produk['harga']
        ];
    }
}
?>
<link rel="stylesheet" href="./style/ecustom.css">
<div class="form-container">
    <h1 class="form-title">Edit Custom bahan</h1>
    
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
            window.location.href = 'index.php?page=custom';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_custom.php">
        <input type="hidden" name="id_cstm" value="<?= $id_produk ?>">
        
        <div class="form-group">
            <label class="form-label">Custom Bahan</label>
            <input type="text" name="cstm_bahan" class="form-input" required 
                   value="<?= htmlspecialchars($data_lama['cstm_bahan'] ?? '') ?>">
            <?php if (isset($errors['cstm_bahan'])): ?>
                <div class="error-message" style="color: #d00000; margin-top: 5px; font-size: 0.85rem;">
                    <?= htmlspecialchars($errors['cstm_bahan']) ?>
                </div>
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
            <button type="submit" class="btn btn-simpan"
            <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin yang bisa mengubah ini"'; ?>>
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <button type="button" onclick="window.location.href='index.php?page=custom'" 
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