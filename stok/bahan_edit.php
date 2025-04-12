<?php
require './app/koneksi.php';

$bahan = [
    'id_bahan' => '',
    'bahan_kain' => '',
];
$pesan_error = '';

if (isset($_GET['id'])) {
    $id_bahan = $koneksi->real_escape_string($_GET['id']);
    $sql = "SELECT id_bahan, bahan_kain  FROM bahan WHERE id_bahan = '$id_bahan'";
    $hasil = $koneksi->query($sql);
    
    if ($hasil->num_rows > 0) {
        $bahan = $hasil->fetch_assoc();
    } else {
        $pesan_error = "Bahan tidak ditemukan!";
    }
}


$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
?>
<link rel="stylesheet" href="./style/ebahan.css">
<div class="form-container">
    <h1 class="form-title"><?= isset($_GET['id']) ? 'Edit Data Bahan' : 'Tambah Bahan Kain Baru' ?></h1>
    
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
            window.location.href = 'index.php?page=bahan';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_bahan.php">
        <input type="hidden" name="id_bahan" value="<?= htmlspecialchars($bahan['id_bahan']) ?>">
        
        <div class="form-group">
            <label class="form-label">Nama Bahan</label>
            <input type="text" name="bahan_kain" class="form-input" 
                   value="<?= htmlspecialchars($bahan['bahan_kain']) ?>" required>
        </div>

        <div class="button-group">
                <button type="submit" class="btn btn-simpan">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
                <a href="index.php?page=bahan" class="btn btn-batal">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">