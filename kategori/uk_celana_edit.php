<?php
require './app/koneksi.php';

$celana = [
    'id_ukcelana' => '',
    'ukuran_cln' => '',
];
$pesan_error = '';

if (isset($_GET['id'])) {
    $id_ukcelana = $koneksi->real_escape_string($_GET['id']);
    $sql = "SELECT id_ukcelana, ukuran_cln  FROM uk_celana WHERE id_ukcelana = '$id_ukcelana'";
    $hasil = $koneksi->query($sql);
    
    if ($hasil->num_rows > 0) {
        $celana = $hasil->fetch_assoc();
    } else {
        $pesan_error = "Type ukuran tidak ditemukan!";
    }
}
$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
?>

<link rel="stylesheet" href="./style/ecelana.css">
<div class="form-container">
    <h1 class="form-title"><?= isset($_GET['id']) ? 'Edit Type Ukuran ' : 'Tambah type Ukuran Baru' ?></h1>
    
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
            window.location.href = 'index.php?page=uk_celana';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_celana.php">
        <input type="hidden" name="id_ukcelana" value="<?= htmlspecialchars($celana['id_ukcelana']) ?>">
        
        <div class="form-group">
            <label class="form-label">Type Ukuran</label>
            <input type="text" name="ukuran_cln" class="form-input" 
                   value="<?= htmlspecialchars($celana['ukuran_cln']) ?>" required>
        </div>

        <div class="button-group">
                <button type="submit" class="btn btn-simpan">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
                <a href="index.php?page=uk_celana" class="btn btn-batal">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">