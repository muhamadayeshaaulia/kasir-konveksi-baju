<?php
require './app/koneksi.php';
$mode = $_GET['mode'] ?? 'harian';
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

$where = "";
$label = "";
switch ($mode) {
    case 'mingguan':
        $start = date('Y-m-d', strtotime('monday this week', strtotime($tanggal)));
        $end = date('Y-m-d', strtotime('sunday this week', strtotime($tanggal)));
        $where = "DATE(tanggal_transaksi) BETWEEN '$start' AND '$end'";
        $label = "Minggu: $start s.d $end";
        break;
    case 'bulanan':
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $where = "MONTH(tanggal_transaksi) = $bulan AND YEAR(tanggal_transaksi) = $tahun";
        $label = "Bulan: " . date('F Y', strtotime($tanggal));
        break;
    case 'tahunan':
        $tahun = date('Y', strtotime($tanggal));
        $where = "YEAR(tanggal_transaksi) = $tahun";
        $label = "Tahun: $tahun";
        break;
    default:
        $where = "DATE(tanggal_transaksi) = '$tanggal'";
        $label = "Tanggal: $tanggal";
}

$sql = "SELECT pembayaran, SUM(subtotal) as total, SUM(diskon) as diskon
        FROM transaksi
        WHERE status_pembayaran = 'lunas' AND $where
        GROUP BY pembayaran";

$result = mysqli_query($koneksi, $sql);

$data = [
    'cash' => ['total' => 0, 'diskon' => 0],
    'transfer' => ['total' => 0, 'diskon' => 0],
    'qris' => ['total' => 0, 'diskon' => 0]
];

while ($row = mysqli_fetch_assoc($result)) {
    $metode = strtolower($row['pembayaran']);
    if (isset($data[$metode])) {
        $data[$metode]['total'] = (float) $row['total'];
        $data[$metode]['diskon'] = (float) $row['diskon'];
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./style/pendapatan.css">
<h1>Laporan Pendapatan - <?= $label ?></h1>
<div class="filter">
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="pendapatan">
        
        <select name="mode" id="mode" onchange="this.form.submit()">
            <option value="harian" <?= $mode == 'harian' ? 'selected' : '' ?>>Harian</option>
            <option value="mingguan" <?= $mode == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
            <option value="bulanan" <?= $mode == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
            <option value="tahunan" <?= $mode == 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
        </select>

        <input type="date" name="tanggal" value="<?= $tanggal ?>" onchange="this.form.submit()">
    </form>
</div>


<div class="summary-box">
    <?php foreach ($data as $key => $val): ?>
        <div class="box">
            <h4><?= strtoupper($key) ?></h4>
            <p>Total: <strong>Rp <?= number_format($val['total'], 0, ',', '.') ?></strong></p>
            <p>Diskon: <strong>Rp <?= number_format($val['diskon'], 0, ',', '.') ?></strong></p>
        </div>
    <?php endforeach; ?>
</div>

<canvas id="chart" width="400" height="200"></canvas>

<script>
const ctx = document.getElementById('chart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Cash', 'Transfer', 'QRIS'],
        datasets: [
            {
                label: 'Total Pendapatan',
                data: [<?= $data['cash']['total'] ?>, <?= $data['transfer']['total'] ?>, <?= $data['qris']['total'] ?>],
                backgroundColor: ['#4CAF50', '#2196F3', '#FFC107'],
                borderRadius: 6
            },
            {
                label: 'Diskon (Pengeluaran)',
                data: [<?= $data['cash']['diskon'] ?>, <?= $data['transfer']['diskon'] ?>, <?= $data['qris']['diskon'] ?>],
                backgroundColor: ['#A5D6A7', '#90CAF9', '#FFE082'],
                borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        plugins: {
            legend: { position: 'top' },
            title: {
                display: true,
                text: 'Ringkasan Pendapatan per Metode Pembayaran',
                font: { size: 16 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
