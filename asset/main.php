<main>
    <?php
    switch ($currentPage) {
        case 'page':
            include('page');
            break;

        case 'dashboard':
            include('./dashboard/dashboard.php');
            break;

        case 'users':
        case 'tusers':
        case 'eusers':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'users') {
                    include('./users/users.php');
                } elseif ($currentPage == 'tusers') {
                    include('./users/tambah.php');
                } else {
                    include('./users/edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        // case 'pendapatan':
        //     if ($level == 'Admin' || $level == 'Owner' || $level == 'Demo') {
        //         include('./pendapatan/pendapatan.php');
        //     } else {
        //         include("./ErrorDocument/403log.php");
        //     }
        //     break;

        case 'chat':
            if (in_array($level, ['Admin', 'Owner', 'Kasir', 'Demo'])) {
                include('./chat/chat.php');
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'kategori':
        case 'tkategori':
        case 'ekategori':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'kategori') {
                    include('./kategori/kategori.php');
                } elseif ($currentPage == 'tkategori') {
                    include('./kategori/kategori_tambah.php');
                } else {
                    include('./kategori/kategori_edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'uk_baju':
        case 'tbaju':
        case 'ebaju':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'uk_baju') {
                    include('./kategori/uk_baju.php');
                } elseif ($currentPage == 'tbaju') {
                    include('./kategori/uk_baju_tambah.php');
                } else {
                    include('./kategori/uk_baju_edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'uk_celana':
        case 'tcelana':
        case 'ecelana':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'uk_celana') {
                    include('./kategori/uk_celana.php');
                } elseif ($currentPage == 'tcelana') {
                    include('./kategori/uk_celana_tambah.php');
                } else {
                    include('./kategori/uk_celana_edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'stok':
        case 'tstok':
        case 'estok':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'stok') {
                    include('./stok/produk.php');
                } elseif ($currentPage == 'tstok') {
                    include('./stok/produk_tambah.php');
                } else {
                    include('./stok/produk_edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'bahan':
        case 'tbahan':
        case 'ebahan':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'bahan') {
                    include('./stok/bahan.php');
                } elseif ($currentPage == 'tbahan') {
                    include('./stok/bahan_tambah.php');
                } else {
                    include('./stok/bahan_edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'custom':
        case 'tcustom':
        case 'ecustom':
            if ($level == 'Admin' || $level == 'Demo') {
                if ($currentPage == 'custom') {
                    include('./stok/custom.php');
                } elseif ($currentPage == 'tcustom') {
                    include('./stok/custom_tambah.php');
                } else {
                    include('./stok/custom_edit.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'transaksi':
        case 'pelunasan':
            if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                if ($currentPage == 'transaksi') {
                    include('./transaksi/transaksi.php');
                } else {
                    include('./transaksi/pelunasan.php');
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'lunas':
        case 'lcustom':
        case 'dcustom':
        case 'dp':
        case 'print':
        case 'pcustom':
            if ($level == 'Admin' || $level == 'Kasir' || $level == 'Demo') {
                switch ($currentPage) {
                    case 'lunas':
                        include('./kuitansi/lunas.php');
                        break;
                    case 'lcustom':
                        include('./kuitansi/lunas_cstm.php');
                        break;
                    case 'dcustom':
                        include('./kuitansi/dp_cstm.php');
                        break;
                    case 'dp':
                        include('./kuitansi/dp.php');
                        break;
                    case 'print':
                        include('./kuitansi/print_lunas.php');
                        break;
                    case 'pcustom':
                        include('./kuitansi/print_lunas_cstm.php');
                        break;
                }
            } else {
                include("./ErrorDocument/403log.php");
            }
            break;

        case 'laporan':
            if (in_array($level, ['Admin', 'Owner', 'Kasir', 'Demo'])) {
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
