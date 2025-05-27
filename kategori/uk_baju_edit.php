<?php
require './app/koneksi.php';

$baju = [
    'id_ukbaju' => '',
    'ukuran_bj' => '',
];
$pesan_error = '';

if (isset($_GET['id'])) {
    $id_ukbaju = $koneksi->real_escape_string($_GET['id']);
    $sql = "SELECT id_ukbaju, ukuran_bj  FROM uk_baju WHERE id_ukbaju = '$id_ukbaju'";
    $hasil = $koneksi->query($sql);
    
    if ($hasil->num_rows > 0) {
        $baju = $hasil->fetch_assoc();
    } else {
        $pesan_error = "Type ukuran tidak ditemukan!";
    }
}
$pesan_sukses = isset($_GET['sukses']) ? $_GET['sukses'] : '';
?>

<link rel="stylesheet" href="./style/ebaju.css">
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
            window.location.href = 'index.php?page=uk_baju';
        }, 1500);
    </script>
    <?php endif; ?>
    
    <form method="POST" action="./edit/update_baju.php">
        <input type="hidden" name="id_ukbaju" value="<?= htmlspecialchars($baju['id_ukbaju']) ?>">
        
        <div class="form-group">
            <label class="form-label">Type Ukuran</label>
            <input type="text" name="ukuran_bj" class="form-input" 
                   value="<?= htmlspecialchars($baju['ukuran_bj']) ?>" required>
        </div>

        <div class="button-group">
                <button type="submit" class="btn btn-simpan"
                <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin yang bisa mengubah ini"'; ?>>
                    <i class="fas fa-save"></i> Update Ukuran
                </button>
                <a href="index.php?page=uk_baju" class="btn btn-batal">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">