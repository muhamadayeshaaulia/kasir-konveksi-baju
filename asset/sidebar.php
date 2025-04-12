<aside>
    <div class="toggle">
        <div class="logo">
            <img src="./img/erasebg-transformed.png" alt="Logo">
            <h2>Toko |<span class="danger"> Yesha</span></h2>
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp">close</span>
        </div>
    </div>

    <div class="sidebar">
        <a href="index.php?page=dashboard" class="<?php 
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            echo ($currentPage == 'dashboard') ? 'active' : '';
        ?>">
            <span class="material-icons-sharp">dashboard</span>
            <h3>Dashboard</h3>
        </a>

        <?php if ($level == 'Admin' || $level == 'Owner'): ?>
            <?php
            require_once 'app/koneksi.php';
            
            $query_user = "SELECT COUNT(user_id) as total_user FROM user";
            $result_user = mysqli_query($koneksi, $query_user);
            $data_user = mysqli_fetch_assoc($result_user);
            $total_user = $data_user['total_user'];
            ?>
            
            <a href="index.php?page=users" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'users') ? 'active' : '';
            ?>">
                <span class="material-icons-sharp">person_outline</span>
                <h3>Users</h3>
                <span class="message-count"><?php echo $total_user; ?></span>
            </a>
        <?php endif; ?>

        <?php if ($level == 'Admin' || $level == 'Owner'): ?>
            <a href="index.php?page=pendapatan" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'pendapatan') ? 'active' : '';
            ?>">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Pendapatan</h3>
            </a>
        <?php endif; ?>

        <?php if ($level == 'Admin'): ?>
            <a href="index.php?page=analytics" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'analytics') ? 'active' : '';
            ?>">
                <span class="material-icons-sharp">insights</span>
                <h3>Analytics</h3>
            </a>
        <?php endif; ?>

        <?php if ($level == 'Admin'): ?>
            <?php
            require_once 'app/koneksi.php';
            $tabel_kategori = ['uk_baju', 'uk_celana', 'kategori'];
            $total_kategori = 0;
            foreach ($tabel_kategori as $tabel) {
                $query = "SHOW TABLES LIKE '$tabel'";
                $result = mysqli_query($koneksi, $query);
                if (mysqli_num_rows($result) > 0) {
                    $total_kategori++;
                }
            }
            ?>
            <a href="index.php?page=kategori" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'kategori') ? 'active' : '';
            ?>">
                <span class="material-icons-sharp">add</span>
                <h3>Kategori Barang</h3>
                <span class="message-count"><?php echo $total_kategori; ?></span>
            </a>
        <?php endif; ?>

        <?php if ($level == 'Admin'): ?>
        <?php
        require_once 'app/koneksi.php';
        $tabel_bahan = ['produk', 'bahan'];
        $total_bahan = 0;
        foreach ($tabel_bahan as $tabel) {
            $query = "SHOW TABLES LIKE '$tabel'";
            $result = mysqli_query($koneksi, $query);
            if (mysqli_num_rows($result) > 0) {
                $total_bahan++;
            }
        }
        ?>
        <a href="index.php?page=stok" class="<?php 
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            echo ($currentPage == 'stok') ? 'active' : '';
        ?>">
            <span class="material-icons-sharp">edit</span>
            <h3>Produk bahan</h3>
            <span class="message-count"><?php echo $total_bahan; ?></span>
        </a>
        <?php endif; ?>

        <?php if ($level == 'Admin' || $level == 'Kasir'): ?>
            <a href="index.php?page=transaksi" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'transaksi') ? 'active' : '';
            ?>">
                <span class="material-icons-sharp">inventory</span>
                <h3>Transaksi</h3>
            </a>
        <?php endif; ?>

        <?php if ($level == 'Admin' || $level == 'Owner' || $level == 'Kasir'): ?>
            <a href="index.php?page=laporan" class="<?php 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                echo ($currentPage == 'laporan') ? 'active' : '';
            ?>">
                <span class="material-icons-sharp">report_gmailerrorred</span>
                <h3>Reports</h3>
            </a>
        <?php endif; ?>

        <a href="index.php?page=settings" class="<?php 
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            echo ($currentPage == 'settings') ? 'active' : '';
        ?>">
            <span class="material-icons-sharp">settings</span>
            <h3>Settings</h3>
        </a>

        <a href="index.php?page=logout" class="<?php 
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            echo ($currentPage == 'logout') ? 'active' : '';
        ?>">
            <span class="material-icons-sharp">logout</span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>