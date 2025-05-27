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
            if ($level == 'Admin' || $level == 'Demo') {
                include('./users/users.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'tusers':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./users/tambah.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
            case 'eusers':
                if ($level == 'Admin' || $level == 'Demo') {
                    include('./users/edit.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
        //case 'pendapatan':
            //if ($level == 'Admin' || $level == 'Owner' || $level == 'Demo') {
                //include('./pendapatan/pendapatan.php');
            //} else {
                //include("./ErrorDocument/403log.php");
            //}

            if ($level == 'Admin' || $level =='Owner' || $level == 'Kasir' || $level == 'Demo') {
                include('./chat/chat.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'kategori':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/kategori.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'tkategori':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/kategori_tambah.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
            case 'ekategori':
                if ($level == 'Admin' || $level == 'Demo') {
                    include('./kategori/kategori_edit.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
        case 'uk_baju':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_baju.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case'tbaju';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_baju_tambah.php');
            }else{
                include("./ErrorDocument/403log.php");
            }
            break;
        case'ebaju';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_baju_edit.php');
            } else{
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'uk_celana':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_celana.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'tcelana';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_celana_tambah.php');
            } else{
                include("./ErrorDocument/403log.php");
            }
            break;
        case'ecelana';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./kategori/uk_celana_edit.php');
            }else{
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'stok':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/produk.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'tstok':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/produk_tambah.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'estok':
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/produk_edit.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'bahan';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/bahan.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
        case 'tbahan';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/bahan_tambah.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
        break;
        case 'ebahan';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/bahan_edit.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
        break;
        case 'custom';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/custom.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
        break;
        case 'tcustom';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/custom_tambah.php');
            } else{
                include("./ErrorDocument/403log.php");
            }
        break;
        case 'ecustom';
            if ($level == 'Admin' || $level == 'Demo') {
                include('./stok/custom_edit.php');
            }else{
                include("./ErrorDocument/403log.php");
            }
        break;
        case 'transaksi':
            if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                include('./transaksi/transaksi.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;
            case 'pelunasan':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./transaksi/pelunasan.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
            case 'lunas':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/lunas.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
            case 'lcustom':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/lunas_cstm.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
            case 'dcustom':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/dp_cstm.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
            case 'dp':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/dp.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
            case 'print':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/print_lunas.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
            case 'pcustom':
                if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                    include('./kuitansi/print_lunas_cstm.php');
                } else {
                    include("./ErrorDocument/403log.php");
                }
                break;
        case 'laporan':
            if ($level == 'Admin' || $level == 'Owner' || $level == 'Kasir' || $level == 'Demo') {
                include('./laporan/laporan.php');
            } else {
                include("./ErrorDocument/403log.php");
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