<main>
    <?php
    switch ($currentPage) {
        case 'dashboard':
            include('./dashboard/dashboard.php');
            break;
        case 'users':
            if ($level == 'Admin' || $level == 'Owner') {
                include('./users/users.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tusers':
            if ($level == 'Admin' || $level == 'Owner') {
                include('./users/tambah.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
            case 'eusers':
                if ($level == 'Admin' || $level == 'Owner') {
                    include('./users/edit.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
        case 'pendapatan':
            if ($level == 'Admin' || $level == 'Owner') {
                include('./pendapatan/pendapatan.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'analytics':
            if ($level == 'Admin') {
                include('./analytics/analytics.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'kategori':
            if ($level == 'Admin') {
                include('./kategori/kategori.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tkategori':
            if ($level == 'Admin') {
                include('./kategori/kategori_tambah.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
            case 'ekategori':
                if ($level == 'Admin') {
                    include('./kategori/kategori_edit.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
        case 'uk_baju':
            if ($level == 'Admin') {
                include('./kategori/uk_baju.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case'tbaju';
            if ($level == 'Admin') {
                include('./kategori/uk_baju_tambah.php');
            }else{
                echo"<h1>Akses Ditotak</h1>";
            }
            break;
        case'ebaju';
            if ($level == 'Admin') {
                include('./kategori/uk_baju_edit.php');
            } else{
                echo '<h1>Akses Ditolak';
            }
            break;
        case 'uk_celana':
            if ($level == 'Admin') {
                include('./kategori/uk_celana.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tcelana';
            if ($level == 'Admin') {
                include('./kategori/uk_celana_tambah.php');
            } else{
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case'ecelana';
            if ($level == 'Admin') {
                include('./kategori/uk_celana_edit.php');
            }else{
                echo"<h1>Akses Ditolak</h1>";
            }
            break;
        case 'stok':
            if ($level == 'Admin') {
                include('./stok/produk.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tstok':
            if ($level == 'Admin') {
                include('./stok/produk_tambah.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'estok':
            if ($level == 'Admin') {
                include('./stok/produk_edit.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'bahan';
            if ($level == 'Admin') {
                include('./stok/bahan.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
            break;
        case 'tbahan';
            if ($level == 'Admin') {
                include('./stok/bahan_tambah.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
        break;
        case 'ebahan';
            if ($level == 'Admin') {
                include('./stok/bahan_edit.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
        break;
        case 'transaksi':
            if ($level == 'Admin' || $level == 'Kasir') {
                include('./transaksi/transaksi.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'laporan':
            if ($level == 'Admin' || $level == 'Owner') {
                include('./laporan/laporan.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'settings':
            include('./setting/pengaturan.php');
            break;
        case 'logout':
            include('./logout.php');
            break;
        default:
            include('./dashboard/dashboard.php');
            break;
    }
    ?>
</main>