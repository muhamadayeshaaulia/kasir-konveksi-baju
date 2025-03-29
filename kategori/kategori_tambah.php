<?php
require './app/koneksi.php';

$pesan_error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
$pesan_sukses = isset($_GET['sukses']) ? urldecode($_GET['sukses']) : '';
$errors = isset($_GET['errors']) ? json_decode(urldecode($_GET['errors']), true) : [];
$old_input = isset($_GET['old']) ? json_decode(urldecode($_GET['old']), true) : [];

?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-hover: #3a56d4;
            --secondary: #3f37c9;
            --danger: #FF0060;
            --danger-hover: #e6005a;
            --success: #4cc9f0;
            --success-hover: #3ab5d9;
            --text: #2b2d42;
            --light: #f8f9fa;
            --gray: #adb5bd;
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
            --form-bg: #202528;
            --form-text: #edeffd;
            --form-border: #434343;
            --form-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            --input-bg: #2d2d2d;
            --text: #edeffd;
            --light: #2d2d2d;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-background);
            color: var(--color-dark);
            transition: background-color 0.3s ease, color 0.3s ease;
            margin: 0;
            padding: 0;
        }
        
        .form-container {
            width: 90%;
            margin: 2rem auto;
            padding: 2.5rem;
            background: var(--form-bg);
            color: var(--form-text);
            border-radius: 16px;
            box-shadow: var(--form-shadow);
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
            animation: slideIn 0.3s ease-out forwards;
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInRight {
            from { 
                opacity: 0;
                transform: translateX(20px);
            }
            to { 
                opacity: 1;
                transform: translateX(0);
            }
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
            animation: slideInRight 0.3s ease-out forwards;
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
        
        .error-message {
            color: #d00000;
            margin-top: 5px;
            font-size: 0.85rem;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @media (max-width: 640px) {
            .form-container {
                padding: 1.5rem;
                margin: 1rem auto;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
     <div class="form-container">
        <h1 class="form-title">Tambah Kategori Baru</h1>
        
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
                window.location.href = 'index.php?page=kategori';
            }, 500);
        }
        setTimeout(function() {
            hideNotificationAndRedirect();
        }, 3000);
    </script>
    <?php endif; ?>
        <form method="POST" action="./tambah/tambah_kategori.php">
            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-input" required 
                       value="<?= htmlspecialchars($old_input['nama_kategori'] ?? '') ?>"
                       placeholder="Masukkan nama kategori">
                <?php if (isset($errors['nama_kategori'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($errors['nama_kategori']) ?>
                    </div>
                <?php endif; ?>
            </div>
                    
            <div class="button-group">
                <button type="submit" class="btn btn-simpan">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
                <a href="index.php?page=kategori" class="btn btn-batal">
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