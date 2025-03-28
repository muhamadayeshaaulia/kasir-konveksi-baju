<div class="recent-orders">
    <h2>Ukuran Baju</h2>
    
    <div style="margin-bottom: 10px;">
        <a href="index.php?page=uk_baju" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'uk_baju') ? 'active' : '';
                ?>" style="margin-right: 5px; background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
            <i class="fas fa-tshirt"></i> Tambah Ukuran Baju
        </a>
        <a href="index.php?page=uk_celana" class="<?php
                $currentPage = isset($_GET['page'])? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'uk_celana') ? 'active' : '';
                ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
            <i class="fas fa-vest"></i> Ukuran Celana
        </a>
    </div>

    <table style="text-align:left; width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background-color: var(--color-background);">
                <th style="padding:12px; border:1px solid #ddd;">ID</th>
                <th style="padding:12px; border:1px solid #ddd;">UKURAN BAJU</th>
                <th style="padding:12px; border:1px solid #ddd;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data baju -->
            <tr>
                <td style="padding:12px; border:1px solid #ddd;">1</td>
                <td style="padding:12px; border:1px solid #ddd;">L</td>
                <td style="padding:12px; border:1px solid #ddd;">
                    <button style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
            </tr>
            
            <!-- Contoh data celana -->
            <tr>
            <td style="padding:12px; border:1px solid #ddd;">2</td>
            <td style="padding:12px; border:1px solid #ddd;">XL</td>
                <td style="padding:12px; border:1px solid #ddd;">
                    <button style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
            </tr>
            
            <!-- Contoh data lainnya -->
            <tr>
            <td style="padding:12px; border:1px solid #ddd;">3</td>
            <td style="padding:12px; border:1px solid #ddd;">XXL</td>
                <td style="padding:12px; border:1px solid #ddd;">
                    <button style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <a href="#" style="display:inline-block; margin-top:15px; color:#4CAF50; text-decoration:none;">Show All</a>
</div>

<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">