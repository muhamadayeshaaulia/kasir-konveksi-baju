<main>
    <?php
    switch ($currentPage) {
        case'page':
            include('page');
            break;
        case 'dashboard':
            include('./dashboard/dashboard.php');
            break;
        case 'users':
            if ($level == 'Admin' || $level == 'Owner' || $level == 'Demo') {
                include('./users/users.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tusers':
            if ($level == 'Admin' || $level == 'Owner' || $level == 'Demo') {
                include('./users/tambah.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
            case 'eusers':
                if ($level == 'Admin' || $level == 'Owner' || $level == 'Demo') {
                    include('./users/edit.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
        case 'pendapatan':
            if ($level == 'Admin' || $level == 'Owner' || $level == 'Demo') {
                include('./pendapatan/pendapatan.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'chat':
            if ($level == 'Admin' || $level =='Owner' || $level == 'Kasir' || $level == 'Demo') {
                include('./chat/chat.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'kategori':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/kategori.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tkategori':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/kategori_tambah.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
            case 'ekategori':
                if ($level == 'Admin' || $level == 'Demo') {
                    include('./kategori/kategori_edit.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
        case 'uk_baju':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_baju.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case'tbaju';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_baju_tambah.php');
            }else{
                echo"<h1>Akses Ditotak</h1>";
            }
            break;
        case'ebaju';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_baju_edit.php');
            } else{
                echo '<h1>Akses Ditolak';
            }
            break;
        case 'uk_celana':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_celana.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tcelana';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_celana_tambah.php');
            } else{
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case'ecelana';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_celana_edit.php');
            }else{
                echo"<h1>Akses Ditolak</h1>";
            }
            break;
        case 'stok':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/produk.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'tstok':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/produk_tambah.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'estok':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/produk_edit.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
        case 'bahan';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/bahan.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
            break;
        case 'tbahan';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/bahan_tambah.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
        break;
        case 'ebahan';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/bahan_edit.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
        break;
        case 'custom';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/custom.php');
            } else {
                echo "<h1>Akses Ditolak</>";
            }
        break;
        case 'tcustom';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/custom_tambah.php');
            } else{
                echo '<h1>Akses Ditolak</>';
            }
        break;
        case 'ecustom';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/custom_edit.php');
            }else{
                echo '<h1>Akses Ditotal</>';
            }
        break;
        case 'transaksi':
            if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                include('./transaksi/transaksi.php');
            } else {
                echo "<h1>Akses Ditolak</h1>";
            }
            break;
            case 'pelunasan':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./transaksi/pelunasan.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
            case 'lunas':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/lunas.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
            case 'lcustom':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/lunas_cstm.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
            case 'dcustom':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/dp_cstm.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
            case 'dp':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/dp.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
            case 'print':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/print_lunas.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
            case 'pcustom':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/print_lunas_cstm.php');
                } else {
                    echo "<h1>Akses Ditolak</h1>";
                }
                break;
        case 'laporan':
            if ($level == 'Admin' || $level == 'Owner' || $level == 'Kasir' || $level == 'Demo') {
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
        header("HTTP/1.0 404 Not Found");
        include('./ErrorDocument/404.php');
            break;
    }
    ?>
</main>