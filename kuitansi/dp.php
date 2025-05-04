<?php
require './app/koneksi.php';

// Filter tanggal
$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Menyusun query SQL dengan prepared statement
$sql = "SELECT t.id_trx, t.kode_transaksi, t.nama_customer, t.pembelian, t.kategori, p.nama_produk, 
               b.bahan_kain, u.ukuran_bj, c.ukuran_cln, t.jumlah, t.diskon, t.harga, t.tax, t.total, 
               t.subtotal, t.tanggal_transaksi, t.status_pembayaran, t.dp_amount, t.remaining_amount, 
               t.bukti_dp, t.bukti_lunas, t.tanggal_pelunasan, t.status_pengiriman, t.alamat, t.email, 
               t.nohp, t.resi, t.pembayaran
        FROM transaksi t
        LEFT JOIN produk p ON t.produk = p.id_produk
        LEFT JOIN bahan b ON t.bahan = b.id_bahan
        LEFT JOIN uk_baju u ON t.uk_baju = u.id_ukbaju
        LEFT JOIN uk_celana c ON t.uk_celana = c.id_ukcelana
        WHERE t.status_pembayaran = 'dp' AND t.pembelian = 'siap pakai' 
        AND DATE(t.tanggal_transaksi) = ?
        ORDER BY CAST(SUBSTRING(t.kode_transaksi, 4) AS UNSIGNED) ASC";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $tanggal_filter);
$stmt->execute();
$result = $stmt->get_result();

$all_users = [];
if ($result->num_rows > 0) {
    $all_users = $result->fetch_all(MYSQLI_ASSOC);
}

$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';
$users_to_display = $show_all ? $all_users : array_slice($all_users, 0, 3);
$total_users = count($all_users);
?>
<link rel="stylesheet" href="./style/dp.css">
<div class="recent-orders">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Deposit siap pakai </h1>
        
        <div style="display: flex; align-items: center; flex-wrap: wrap;">
            <div style="width: 100%; margin-bottom: 10px; order: 1;">
                <input type="text" id="liveSearch" placeholder="Cari Transaksi." 
                       style="padding: 8px 12px; width: 100%; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="desktop-view" style="display: flex; align-items: center; order: 2;">
            <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_trx) as lunas_pakai FROM transaksi Where status_pembayaran='lunas' AND pembelian = 'siap pakai'";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_trx = mysqli_fetch_assoc($result_user);
                    $lunas_pakai = $data_trx['lunas_pakai'];
                    ?>
                <a href="index.php?page=lunas" class="<?php 
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                        echo ($currentPage == 'lunas') ? 'active' : '';
                        ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                        <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Lunas Siap Pakai (<?php echo $lunas_pakai; ?>)
                </a>
                <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_trx) as dp_jahit FROM transaksi Where status_pembayaran='dp' AND pembelian = 'jahit'";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_trx = mysqli_fetch_assoc($result_user);
                    $dp_jahit = $data_trx['dp_jahit'];
                    ?>
                <a href="index.php?page=dcustom" style="background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>Deposit Jahit (<?php echo $dp_jahit; ?>)
                </a>
                <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_trx) as lunas_jahit FROM transaksi Where status_pembayaran='lunas' AND pembelian = 'jahit'";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_trx = mysqli_fetch_assoc($result_user);
                    $lunas_jahit = $data_trx['lunas_jahit'];
                    ?>
                <a href="index.php?page=lcustom" style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>Lunas Jahit (<?php echo $lunas_jahit; ?>)
                </a>
            </div>
            
            <div class="dropdown" style="position: relative; display: none; order: 2;">
                <button class="dropbtn" style="background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                    All Menu ▼
                </button>
                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 200px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;">
                    <a href="index.php?page=lunas" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'lunas') ? 'active' : '';
                            ?>" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                            <i class="fas fa-tshirt" style="margin-right: 8px;"></i>Lunas Siap Pakai (<?php echo $lunas_pakai; ?>)
                    </a>
                    <a href="index.php?page=dcustom" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'dcustom') ? 'active' : '';
                            ?>" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                            <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Deposit Jahit (<?php echo $dp_jahit; ?>)
                    </a>
                    <a href="index.php?page=lcustom" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'lcustom') ? 'active' : '';
                            ?>" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                            <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Lunas Jahit (<?php echo $lunas_jahit; ?>)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="calendar-container">
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="<?= $_GET['page'] ?? 'dashboard' ?>">
        <label for="tanggal">📅 Pilih Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" value="<?= $tanggal_filter ?>" onchange="this.form.submit()">
    </form>
</div>

    <div id="userTableContainer" style="overflow-x:auto">
        <table style="min: width: 1500px; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background-color:var(--color-background);">
                    <th style="padding:12px; border:1px solid #ddd;">KODE TRANSAKSI</th>
                    <th style="padding:12px; border:1px solid #ddd;">NAMA CUSTOMER</th>
                    <th style="padding:12px; border:1px solid #ddd;">JENIS PEMBELIAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">KATEGORI</th>
                    <th style="padding:12px; border:1px solid #ddd;">PRODUK</th>
                    <th style="padding:12px; border:1px solid #ddd;">BAHAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">UKURAN BAJU</th>
                    <th style="padding:12px; border:1px solid #ddd;">UKURAN CELANA</th>
                    <th style="padding:12px; border:1px solid #ddd;">JUMLAH</th>
                    <th style="padding:12px; border:1px solid #ddd;">DISKON</th>
                    <th style="padding:12px; border:1px solid #ddd;">HARGA</th>
                    <th style="padding:12px; border:1px solid #ddd;">TAX</th>
                    <th style="padding:12px; border:1px solid #ddd;">TOTAL</th>
                    <th style="padding:12px; border:1px solid #ddd;">SUBTOTAL</th>
                    <th style="padding:12px; border:1px solid #ddd;">TANGGAL TRANSAKSI</th>
                    <th style="padding:12px; border:1px solid #ddd;">STATUS PEMBAYARAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">DP</th>
                    <th style="padding:12px; border:1px solid #ddd;">DI BAYARKAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">BUKTI DP</th>
                    <th style="padding:12px; border:1px solid #ddd;">BUKTI LUNAS</th>
                    <th style="padding:12px; border:1px solid #ddd;">TANGGAL PELUNASAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">STATUS PENGIRIMAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">ALAMAT </th>
                    <th style="padding:12px; border:1px solid #ddd;">EMAIL</th>
                    <th style="padding:12px; border:1px solid #ddd;">NO HP</th>
                    <th style="padding:12px; border:1px solid #ddd;">RESI</th>
                    <th style="padding:12px; border:1px solid #ddd;">MOTEDE BAYAR</th>
                    <th style="padding:12px; border:1px solid #ddd;">AKSI</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php if (!empty($users_to_display)): ?>
                    <?php foreach($users_to_display as $row): ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["kode_transaksi"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["nama_customer"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["pembelian"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["kategori"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["nama_produk"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["bahan_kain"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["ukuran_bj"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["ukuran_cln"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["jumlah"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["diskon"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["harga"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["tax"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["subtotal"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["total"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["tanggal_transaksi"]) ?></td>                            
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["status_pembayaran"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["dp_amount"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">Rp <?= number_format($row["remaining_amount"], 0, ',', '.') ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">
                            <?php if ($row["pembayaran"] === "cash"): ?>
                                <?= htmlspecialchars($row["bukti_dp"]) ?: '-' ?>
                            <?php elseif (!empty($row["bukti_dp"])): ?>
                                <img src="./transaksi/uploads/bukti/<?= htmlspecialchars($row["bukti_dp"]) ?>" 
                                    alt="Bukti DP" width="100" style="cursor:pointer;" 
                                    class="img-thumb" onclick="openModal(this.src)">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                            </td>
                            <td style="padding:12px; border:1px solid #ddd;">
                            <?php if ($row["pembayaran"] === "cash"): ?>
                                <?= htmlspecialchars($row["bukti_lunas"]) ?: '-' ?>
                            <?php elseif (!empty($row["bukti_lunas"])): ?>
                                <?php
                                    $folder = (!empty($row["dp_amount"]) && $row["dp_amount"] > 0) ? './transaksi/uploads/bukti_lunas/' : './transaksi/uploads/bukti/';
                                ?>
                                <img src="<?= $folder . htmlspecialchars($row["bukti_lunas"]) ?>" 
                                    alt="Bukti Lunas" width="100" style="cursor:pointer;" 
                                    class="img-thumb" onclick="openModal(this.src)">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                            </td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["tanggal_pelunasan"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["status_pengiriman"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["alamat"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["email"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["nohp"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["resi"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["pembayaran"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button onclick="window.location.href='index.php?page=print&id=<?= $row["id_trx"] ?>'" 
                                        style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; margin-right:5px;">
                                    Print
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:12px; text-align:center;">Tidak ada data transaksi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($total_users > 3): ?>
        <div style="text-align: center; margin-top: 20px;">
            <?php if (!$show_all): ?>
                <button onclick="window.location.href='?page=<?= $_GET['page'] ?? '' ?>&tanggal=<?= $tanggal_filter ?>&show_all=1'"
                        style="background-color: #6C9BCF; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Semua (<?= $total_users ?>)
                </button>
            <?php else: ?>
                <button onclick="window.location.href='?page=<?= $_GET['page'] ?? '' ?>&tanggal=<?= $tanggal_filter ?>&show_all=0'"
                        style="background-color: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Sedikit
                </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div id="imageModal" class="modal">
  <div class="modal-box">
    <span class="close-baru" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" alt="Preview">
  </div>
</div>

<script src="./js/dp.js">
</script>