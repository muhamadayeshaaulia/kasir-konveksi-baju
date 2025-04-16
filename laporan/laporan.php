<?php
require './app/koneksi.php';

$tanggal_filter = $_GET['tanggal'] ?? date('Y-m-d');

$sql = "SELECT 
            t.kode_transaksi,
            t.pembelian,
            t.kategori,
            t.cstm_produk,
            p.nama_produk,
            s.cstm_bahan,
            b.bahan_kain,
            t.cstm_ukuran,
            u.ukuran_bj,
            c.ukuran_cln,
            t.jumlah,
            t.diskon,
            t.harga,
            t.tax,
            t.total,
            t.subtotal,
            t.tanggal_transaksi,
            t.status_pembayaran,
            t.pembayaran
        FROM transaksi t
        LEFT JOIN produk p ON t.produk = p.id_produk
        LEFT JOIN cstm_pbahn s ON t.cstm_bahan = s.id_cstm
        LEFT JOIN bahan b ON t.bahan = b.id_bahan
        LEFT JOIN uk_baju u ON t.uk_baju = u.id_ukbaju
        LEFT JOIN uk_celana c ON t.uk_celana = c.id_ukcelana
        WHERE t.status_pembayaran = 'lunas'
          AND DATE(t.tanggal_transaksi) = '$tanggal_filter'
        ORDER BY CAST(SUBSTRING(t.kode_transaksi, 4) AS UNSIGNED) ASC";

$result = mysqli_query($koneksi, $sql);

$metode_pembayaran = ['cash', 'transfer', 'qris'];
$totals = [];

foreach ($metode_pembayaran as $metode) {
    $query = "SELECT SUM(t.total) AS total_pendapatan 
              FROM transaksi t 
              WHERE t.status_pembayaran = 'lunas' 
                AND DATE(t.tanggal_transaksi) = '$tanggal_filter' 
                AND t.pembayaran = '$metode'";
    $result_total = mysqli_query($koneksi, $query);
    $row_total = mysqli_fetch_assoc($result_total);
    $totals[$metode] = $row_total['total_pendapatan'] ?? 0;
}
?>
<link rel="stylesheet" href="./style/laporan.css">
<main id="laporan-page">
    <h1>Laporan Transaksi - <?= $tanggal_filter ?></h1>
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="laporan">
        <input type="date" name="tanggal" value="<?= $tanggal_filter ?>" onchange="this.form.submit()">
    </form>

    <button class="print-btn" onclick="window.print()"
    <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Owner' && $_SESSION['level'] !=='Kasir') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin, owner dan kasir yang bisa melakukan print laporan"'; ?>>
    🖨 Print</button>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pembelian</th>
                    <th>Kategori</th>
                    <th>Custom Produk</th>
                    <th>Produk</th>
                    <th>Custom Bahan</th>
                    <th>Bahan</th>
                    <th>Custom Ukuran</th>
                    <th>Uk. Baju</th>
                    <th>Uk. Celana</th>
                    <th>Jumlah</th>
                    <th>Diskon</th>
                    <th>Harga</th>
                    <th>Tax</th>
                    <th>Total</th>
                    <th>Subtotal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['kode_transaksi'] ?></td>
                        <td><?= $row['pembelian'] ?></td>
                        <td><?= $row['kategori'] ?></td>
                        <td><?= $row['cstm_produk'] ?></td>
                        <td><?= $row['nama_produk'] ?></td>
                        <td><?= $row['cstm_bahan'] ?></td>
                        <td><?= $row['bahan_kain'] ?></td>
                        <td><?= $row['cstm_ukuran'] ?></td>
                        <td><?= $row['ukuran_bj'] ?></td>
                        <td><?= $row['ukuran_cln'] ?></td>
                        <td><?= $row['jumlah'] ?></td>
                        <td>Rp <?= number_format($row['diskon'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['tax'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td><?= $row['tanggal_transaksi'] ?></td>
                        <td><?= ucfirst($row['status_pembayaran']) ?></td>
                        <td><?= ucfirst($row['pembayaran']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot >
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total Cash</td>
                    <td colspan="2">Rp <?= number_format($totals['cash'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total Transfer</td>
                    <td colspan="2">Rp <?= number_format($totals['transfer'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total QRIS</td>
                    <td colspan="2">Rp <?= number_format($totals['qris'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold; color: var(--color-success);">Total Semua</td>
                    <td colspan="2"><strong>Rp <?= number_format(array_sum($totals), 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</main>
