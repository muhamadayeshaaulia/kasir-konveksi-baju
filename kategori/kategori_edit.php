<?php
require './app/koneksi.php';

$kategori = [
    'id_kategori' => '',
    'nama_kategori' => '',
];
$pesan_error = '';

if (isset($_GET['id'])) {
    $id_kategori = $koneksi->real_escape_string($_GET['id']);
    $sql = "SELECT id_kategori, nama_kategori  FROM kategori WHERE id_kategori = '$id_kategori'";
    $hasil = $koneksi->query($sql);
    
    if ($hasil->num_rows > 0) {
        $kategori = $hasil->fetch_assoc();
    } else {
        $pesan_error = "Kategori tidak ditemukan!";
    }
}

$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
?>
<link rel="stylesheet" href="./style/kategorie.css">
<div class="form-container">
    <h1 class="form-title"><?= isset($_GET['id']) ? 'Edit Data Kategori' : 'Tambah Kategori Baru' ?></h1>
    
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
            window.location.href = 'index.php?page=kategori';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_kategori.php">
        <input type="hidden" name="id_kategori" value="<?= htmlspecialchars($kategori['id_kategori']) ?>">
        
        <div class="form-group">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-input" 
                   value="<?= htmlspecialchars($kategori['nama_kategori']) ?>" required>
        </div>

        <div class="button-group">
                <button type="submit" class="btn btn-simpan"
                <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin yang bisa mengubah ini"'; ?>>
                    <i class="fas fa-save"></i> Update Kategori
                </button>
                <a href="index.php?page=kategori" class="btn btn-batal">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">