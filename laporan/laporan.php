<?php
require './app/koneksi.php';

$tanggal_filter = $_GET['tanggal'] ?? date('Y-m-d');
$jenis_filter = $_GET['filter'] ?? 'harian';

$startDate = $endDate = $tanggal_filter;

switch ($jenis_filter) {
    case 'mingguan':
        $dayOfWeek = date('N', strtotime($tanggal_filter));
        $startDate = date('Y-m-d', strtotime($tanggal_filter . " -" . ($dayOfWeek - 1) . " days"));
        $endDate = date('Y-m-d', strtotime($startDate . " +6 days"));
        break;
    case 'bulanan':
        $startDate = date('Y-m-01', strtotime($tanggal_filter));
        $endDate = date('Y-m-t', strtotime($tanggal_filter));
        break;
    case 'tahunan':
    if (strpos($tanggal_filter, '/') !== false) {
        list($startYear, $endYear) = explode('/', $tanggal_filter);
        $startDate = "$startYear-01-01";
        $endDate = "$endYear-12-31";
    } else {
        $year = preg_replace('/[^0-9]/', '', $tanggal_filter);
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";
    }
    break;
}

$checkDataQuery = "SELECT COUNT(*) as total FROM transaksi 
                   WHERE status_pembayaran = 'lunas' 
                   AND DATE(tanggal_transaksi) BETWEEN '$startDate' AND '$endDate'";
$checkDataResult = mysqli_query($koneksi, $checkDataQuery);
$dataCount = mysqli_fetch_assoc($checkDataResult)['total'];

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
          AND DATE(t.tanggal_transaksi) BETWEEN '$startDate' AND '$endDate'
        ORDER BY CAST(SUBSTRING(t.kode_transaksi, 4) AS UNSIGNED) ASC";

$result = mysqli_query($koneksi, $sql);

$metode_pembayaran = ['cash', 'transfer', 'qris'];
$totals = [];

foreach ($metode_pembayaran as $metode) {
    $query = "SELECT SUM(t.total) AS total_pendapatan 
              FROM transaksi t 
              WHERE t.status_pembayaran = 'lunas' 
                AND DATE(t.tanggal_transaksi) BETWEEN '$startDate' AND '$endDate'
                AND t.pembayaran = '$metode'";
    $result_total = mysqli_query($koneksi, $query);
    $row_total = mysqli_fetch_assoc($result_total);
    $totals[$metode] = $row_total['total_pendapatan'] ?? 0;
}
?>

<link rel="stylesheet" href="./style/laporan.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<style>
@media print {
    form, .print-btn {
        display: none !important;
    }
}
</style>

<main id="laporan-page">
    <h1>Laporan Transaksi - <?= ucfirst($jenis_filter) ?> (<?= $startDate ?> s.d. <?= $endDate ?>)</h1>

    <form method="GET" action="index.php" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; margin-bottom: 20px;">
        <input type="hidden" name="page" value="laporan">

        <input type="text" id="tanggal" name="tanggal" value="<?= $tanggal_filter ?>" placeholder="Pilih tanggal" readonly style="padding: 10px 14px; border: 1.5px solid var(--color-light); border-radius: 8px; background-color: var(--color-white); color: var(--color-dark); font-size: 16px; box-shadow: var(--box-shadow); min-width: 180px; cursor: pointer; transition: all 0.3s ease;">

        <select id="filter" name="filter" onchange="ubahModeDatepicker()" style="padding: 10px 14px; border: 1.5px solid var(--color-light); border-radius: 8px; background-color: var(--color-white); color: var(--color-dark); font-size: 16px; box-shadow: var(--box-shadow); min-width: 160px; cursor: pointer; transition: all 0.3s ease;">
            <option value="harian" <?= $jenis_filter == 'harian' ? 'selected' : '' ?>>Harian</option>
            <option value="mingguan" <?= $jenis_filter == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
            <option value="bulanan" <?= $jenis_filter == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
            <option value="tahunan" <?= $jenis_filter == 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
        </select>

        <button type="submit" style="padding: 10px 20px; background-color: var(--color-primary); color: var(--color-white); border: none; border-radius: 8px; font-weight: 600; cursor: pointer; box-shadow: var(--box-shadow); transition: all 0.3s ease;">Terapkan</button>
    </form>

    <button class="print-btn" onclick="window.print()"
        <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Owner' && $_SESSION['level'] !== 'Kasir') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin, owner dan kasir yang bisa melakukan print laporan"'; ?>>
        🖨 Print
    </button>

    <?php if ($dataCount > 0): ?>
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
            <tfoot>
                <tr><td colspan="2" style="text-align: right; font-weight: bold;">Total Cash</td><td colspan="2">Rp <?= number_format($totals['cash'], 0, ',', '.') ?></td></tr>
                <tr><td colspan="2" style="text-align: right; font-weight: bold;">Total Transfer</td><td colspan="2">Rp <?= number_format($totals['transfer'], 0, ',', '.') ?></td></tr>
                <tr><td colspan="2" style="text-align: right; font-weight: bold;">Total QRIS</td><td colspan="2">Rp <?= number_format($totals['qris'], 0, ',', '.') ?></td></tr>
                <tr><td colspan="2" style="text-align: right; font-weight: bold; color: var(--color-success);">Total Semua</td><td colspan="2"><strong>Rp <?= number_format(array_sum($totals), 0, ',', '.') ?></strong></td></tr>
            </tfoot>
        </table>
    </div>
    <?php else: ?>
        <p style="margin-top: 20px; font-size: 18px; color: gray;">Tidak ada data transaksi untuk rentang tanggal yang dipilih.</p>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script>
let dateInput = document.getElementById("tanggal");
let filterSelect = document.getElementById("filter");
let picker;

function ubahModeDatepicker() {
    const filter = filterSelect.value;
    if (picker) picker.destroy();

    if (filter === "harian") {
        picker = flatpickr(dateInput, {
            dateFormat: "Y-m-d",
            defaultDate: dateInput.value || new Date()
        });
    } else if (filter === "mingguan") {
        picker = flatpickr(dateInput, {
            dateFormat: "Y-m-d",
            defaultDate: dateInput.value || new Date(),
            weekNumbers: true,
            locale: { firstDayOfWeek: 1 }
        });
    } else if (filter === "bulanan") {
        picker = flatpickr(dateInput, {
            dateFormat: "Y-m",
            defaultDate: dateInput.value || new Date(),
            plugins: [new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m",
                altFormat: "F Y"
            })]
        });
    } else if (filter === "tahunan") {
        picker = flatpickr(dateInput, {
            dateFormat: "Y",
            defaultDate: dateInput.value || new Date(),
            plugins: [new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y",
                altFormat: "Y"
            })]
        });
    }
}

ubahModeDatepicker();
</script>
