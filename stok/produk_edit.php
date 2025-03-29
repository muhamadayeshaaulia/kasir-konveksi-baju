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

$koneksi->close();
?>
<style>
        :root {
            --primary: #4361ee;
            --primary-hover: #3a56d4;
            --secondary: #3f37c9;
            --danger: #FF0060;
            --danger-hover: #FF0060;
            --success: #4cc9f0;
            --text: #2b2d42;
            --light: #f8f9fa;
            --gray: #adb5bd;
            
            /* Variabel untuk form light mode */
            --form-bg: #ffffff;
            --form-text: #2b2d42;
            --form-border: #e2e8f0;
            --form-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --input-bg: #f8f9fa;
        }
        
        .dark-mode-variables {
            --color-background: #181a1e;
            --color-white: #202528;
            --color-dark: #edeffd;
            --color-dark-variant: #a3bdcc;
            --color-light: rgba(0, 0, 0, 0.4);
            --box-shadow: 0 2rem 3rem var(--color-light);
            
            /* Variabel untuk form dark mode */
            --form-bg: #202528;
            --form-text: #edeffd;
            --form-border: #434343;
            --form-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            --input-bg: #2d2d2d;
            --text: #edeffd;
            --light: #2d2d2d;
        }
        
        body {
            background-color: var(--color-background);
            color: var(--color-dark);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .form-container {
            width: 90%;
            margin: 2rem auto;
            padding: 2.5rem;
            background: var(--form-bg);
            color: var(--form-text);
            border-radius: 16px;
            box-shadow: var(--form-shadow);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .form-title {
            text-align: center;
            color: var(--primary);
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
        }
        
        .form-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--success));
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--form-text);
            font-size: 0.95rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border: 2px solid var(--form-border);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--form-text);
            background-color: var(--input-bg);
            transition: all 0.3s ease;
        }
        
        .form-input::placeholder {
            color: var(--gray);
        }
        
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
            outline: none;
            background-color: var(--form-bg);
        }
        
        .form-select {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border: 2px solid var(--form-border);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--form-text);
            background-color: var(--input-bg);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }
        
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
            outline: none;
            background-color: var(--form-bg);
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }
        
        .btn {
            padding: 0.85rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-simpan {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
        }
        
        .btn-simpan:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.25);
        }
        
        .btn-simpan:active {
            transform: translateY(0);
        }
        
        .btn-batal {
            background-color: var(--danger);
            color: white;
            box-shadow: 0 4px 6px rgba(247, 37, 133, 0.2);
        }
        
        .btn-batal:hover {
            background-color: var(--danger-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(247, 37, 133, 0.25);
        }
        
        .btn-batal:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-error {
            background-color: #fff0f3;
            color: #d00000;
            border-left: 4px solid #d00000;
        }
        
        .alert-success {
            background-color: #f0fff4;
            color: #2b9348;
            border-left: 4px solid #2b9348;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .close-btn:hover {
            opacity: 1;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .password-strength.weak {
            color: #d00000;
        }

        .password-strength.medium {
            color: #ffaa00;
        }

        .password-strength.strong {
            color: #2b9348;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray);
        }
    </style>

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