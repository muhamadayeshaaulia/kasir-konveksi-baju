<?php
require './app/koneksi.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    die('Pegawai belum login.');
}

if (!isset($_GET['id'])) {
    die('ID transaksi tidak ditemukan.');
}

$id = intval($_GET['id']);
$sql = "SELECT t.kode_transaksi, t.nama_customer,p.nama_produk, b.bahan_kain,t.jumlah, t.diskon, t.harga, t.tax, t.total, t.subtotal,
        t.tanggal_transaksi, t.status_pembayaran, t.dp_amount, t.remaining_amount,
        t.status_pengiriman, t.pembayaran, t.pembelian
        FROM transaksi t
        LEFT JOIN produk p ON t.produk = p.id_produk
        LEFT JOIN bahan b ON t.bahan = b.id_bahan
        WHERE t.id_trx = $id";

$result = $koneksi->query($sql);
if ($result->num_rows < 1) {
    die('Data transaksi tidak ditemukan.');
}
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailTujuan = $_POST['email'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shaxyyy.03@gmail.com';
        $mail->Password = 'iumx pysk nrkk vqye';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('emailanda@gmail.com', 'Toko Konveksi Yesha');
        $mail->addAddress($emailTujuan, 'Customer');

        $mail->isHTML(true);
        $mail->Subject = 'Kuitansi Pembayaran Transaksi ' . $data['kode_transaksi'];
        $mail->Body    = '
            <h1>TOKO KONVEKSI-YESHA</h1>
            <h2>Kuitansi Pembayaran</h2>
            <h2>_____________________________________</h2>
            <div><strong>Kode Transaksi:</strong> ' . htmlspecialchars($data['kode_transaksi']) . '</div>
            <div><strong>Nama Customer:</strong> ' . htmlspecialchars($data['nama_customer']) . '</div>
            <div><strong>Tanggal Transaksi:</strong> ' . htmlspecialchars($data['tanggal_transaksi']) . '</div>
            <div><strong>Status Pembayaran:</strong> ' . htmlspecialchars($data['status_pembayaran']) . '</div>
            <div><strong>Status Pengiriman:</strong> ' . htmlspecialchars($data['status_pengiriman']) . '</div>
            <div><strong>Metode Pembayaran:</strong> ' . htmlspecialchars($data['pembayaran']) . '</div>
            <div><strong>Pembelian:</strong> ' . htmlspecialchars($data['pembelian']) . '</div>
            <table>
                <tr><th>Produk</th><th>Bahan</th><th>Jumlah</th></tr>
                <tr><td>' . htmlspecialchars($data['nama_produk']) . '</td><td>' . htmlspecialchars($data['bahan_kain']) . '</td><td>' . htmlspecialchars($data['jumlah']) . '</td></tr>
            </table>
            <table>
                <tr><td>Harga</td><td class="right">Rp ' . number_format($data['harga'], 0, ',', '.') . '</td></tr>
                <tr><td>Diskon</td><td class="right">Rp ' . number_format($data['diskon'], 0, ',', '.') . '</td></tr>
                <tr><td>Pajak (Tax)</td><td class="right">Rp ' . number_format($data['tax'], 0, ',', '.') . '</td></tr>
                <tr><td>Total</td><td class="right">Rp ' . number_format($data['subtotal'], 0, ',', '.') . '</td></tr>
                <tr><td>DP</td><td class="right">Rp ' . number_format($data['dp_amount'], 0, ',', '.') . '</td></tr>
                <tr><td>SubTotal</td><td class="right total">Rp ' . number_format($data['total'], 0, ',', '.') . '</td></tr>
                <tr><td>Sudah Dibayarkan</td><td class="right">Rp ' . number_format($data['remaining_amount'], 0, ',', '.') . '</td></tr>
            </table>';

        $mail->send();
        $kode_transaksi = $data['kode_transaksi'];
        $id_trx = $id;
        header("Location: ./index.php?page=print&id=$id_trx&success=1&kode=" . urlencode($kode_transaksi));
        exit;

    } catch (Exception $e) {
        echo "Terjadi kesalahan: {$mail->ErrorInfo}";
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="./style/print.css">
<div class="kuitansi">
    <h1>TOKO KONVEKSI-YESHA</h1>
    <h2>Kuitansi Pembayaran</h2>
    <h2>_____________________________________</h2>

    <div class="info">
        <div><strong>Kode Transaksi:</strong> <?= htmlspecialchars($data['kode_transaksi']) ?></div>
        <div><strong>Nama Customer:</strong> <?= htmlspecialchars($data['nama_customer']) ?></div>
        <div><strong>Tanggal Transaksi:</strong> <?= htmlspecialchars($data['tanggal_transaksi']) ?></div>
        <div><strong>Status Pembayaran:</strong> <?= htmlspecialchars($data['status_pembayaran']) ?></div>
        <div><strong>Status Pengiriman:</strong> <?= htmlspecialchars($data['status_pengiriman']) ?></div>
        <div><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($data['pembayaran']) ?></div>
        <div><strong>Pembelian:</strong> <?= htmlspecialchars($data['pembelian']) ?></div>
    </div>

    <table>
        <tr>
            <th>Produk</th>
            <th>Bahan</th>
            <th>Jumlah</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($data['nama_produk']) ?></td>
            <td><?= htmlspecialchars($data['bahan_kain']) ?></td>
            <td><?= htmlspecialchars($data['jumlah']) ?></td>
        </tr>
    </table>

    <table>
        <tr>
            <td>Harga</td>
            <td class="right">Rp <?= number_format($data['harga'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="right">Rp <?= number_format($data['diskon'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Pajak (Tax)</td>
            <td class="right">Rp <?= number_format($data['tax'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Total</td>
            <td class="right">Rp <?= number_format($data['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>DP</td>
            <td class="right">Rp <?= number_format($data['dp_amount'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>SubTotal</td>
            <td class="right total">Rp <?= number_format($data['total'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Sudah Dibayarkan</td>
            <td class="right">Rp <?= number_format($data['remaining_amount'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div style="margin-top: 50px;">
        <div style="float: left; text-align: center;">
            <p>Penerima,</p><br><br><br>
            <p>__________________</p>
        </div>
        <div style="float: right; text-align: center;">
            <p>Pegawai,</p><br><br><br>
            <p><?= htmlspecialchars($_SESSION['username']) ?></p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <button onclick="window.print()">🖨️ Cetak Kuitansi</button>
    </div>
</div>

<form method="POST" action="">
    <label for="email">Email Tujuan:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit"
    <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Kasir') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin dan kasir yang bisa mengirimkan email ini"'; ?>>Kirim Kuitansi via Email</button>
</form>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Kuitansi berhasil dikirim ke email.\nKode: <?= htmlspecialchars($_GET['kode']) ?>',
        icon: 'success',
        confirmButtonText: 'Oke'
    });
</script>
<?php endif; ?>