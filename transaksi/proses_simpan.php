<?php
require '../app/koneksi.php';

$tanggal = date('Y-m-d H:i:s');
$kode_transaksi = $_POST['kode_transaksi'];
$nama_customer = $_POST['nama_customer'];
$pembelian = $_POST['pembelian'];

// Default values
$cstm_produk = $cstm_bahan = $cstm_ukuran = '-';
$produk = $bahan = $kategori = $uk_baju = $uk_celana = '-';
$estimasi = date('Y-m-d H:i:s', strtotime($tanggal . ' +7 days'));
$jumlah = $_POST['jumlah'];

// Handle pembelian type
if ($pembelian === 'jahit') {
    $cstm_produk = $_POST['cstm_produk'] ?? '-';
    $cstm_bahan = $_POST['cstm_bahan'] ?? '-';
    $cstm_ukuran = $_POST['cstm_ukuran'] ?? '-';
    $estimasi = date('Y-m-d H:i:s', strtotime($tanggal . ($jumlah >= 24 ? ' +14 days' : ' +7 days')));
} else {
    $produk = $_POST['produk'] ?? '-';
    $bahan = $_POST['bahan'] ?? '-';
    $uk_baju = $_POST['uk_baju'] ?? '-';
    $uk_celana = $_POST['uk_celana'] ?? '-';
    $estimasi = $_POST['estimasi'] ?? '-';

    // Get kategori from produk
    $stmt_kategori = mysqli_prepare($koneksi, "SELECT kategori FROM produk WHERE id_produk = ?");
    mysqli_stmt_bind_param($stmt_kategori, "i", $produk);
    mysqli_stmt_execute($stmt_kategori);
    $result_kategori = mysqli_stmt_get_result($stmt_kategori);
    $kategori = ($row = mysqli_fetch_assoc($result_kategori)) ? $row['kategori'] : '-';
}

// Transaction data
$harga = $_POST['harga'];
$subtotal = $_POST['subtotal'];
$tax = $_POST['tax'];
$diskon = $_POST['diskon'] ?? 0;
$total = $_POST['total'];

$metode_pembayaran = $_POST['metode_pembayaran'];
$pembayaran = $_POST['pembayaran'];
$status_pembayaran = $metode_pembayaran;

$dp_amount = ($metode_pembayaran == 'dp') ? $total * 0.5 : 0;
$remaining_amount = $total - $dp_amount;

$status_pengiriman = $_POST['status_pengiriman'];
$alamat = ($status_pengiriman == 'kirim') ? $_POST['alamat'] : "-";
$email = ($status_pengiriman == 'kirim') ? $_POST['email'] : "-";
$nohp = ($status_pengiriman == 'kirim') ? $_POST['nohp'] : "-";
$resi = ($status_pengiriman == 'kirim') ? $_POST['resi'] : "-";

$bukti_dp = '';
$bukti_lunas = '';
$target_file = '';

if ($pembayaran === 'transfer' || $pembayaran === 'qris') {
    if ($_FILES['bukti_transaksi']['error'] !== UPLOAD_ERR_OK) {
        die("Error: Bukti pembayaran wajib diupload.");
    }

    $target_dir = "uploads/bukti/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_ext = strtolower(pathinfo($_FILES['bukti_transaksi']['name'], PATHINFO_EXTENSION));
    $bukti_filename = $kode_transaksi . '_' . time() . '.' . $file_ext;
    $target_file = $target_dir . $bukti_filename;

    if (!in_array($file_ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
        die("Error: Format bukti tidak didukung (jpg/jpeg/png/pdf).");
    }
    if ($_FILES['bukti_transaksi']['size'] > 2000000) {
        die("Error: Maksimal ukuran file 2MB.");
    }
    if (!move_uploaded_file($_FILES['bukti_transaksi']['tmp_name'], $target_file)) {
        die("Error: Upload bukti gagal.");
    }

    if ($metode_pembayaran == 'dp') {
        $bukti_dp = $bukti_filename;
    } else {
        $bukti_lunas = $bukti_filename;
    }
} elseif ($pembayaran === 'cash') {
    $bukti_filename = 'CASH-' . date('YmdHis');
    if ($metode_pembayaran == 'dp') {
        $bukti_dp = $bukti_filename;
    } else {
        $bukti_lunas = $bukti_filename;
    }
}

$query = "INSERT INTO transaksi (
    kode_transaksi, nama_customer, kategori, pembelian,
    cstm_produk, produk, cstm_bahan, bahan,
    cstm_ukuran, uk_baju, uk_celana, jumlah,
    diskon, harga, tax, total, subtotal,
    tanggal_transaksi, estimasi,
    status_pembayaran, dp_amount, remaining_amount,
    bukti_dp, bukti_lunas,
    status_pengiriman, alamat, email, nohp, resi,
    pembayaran
) VALUES (
    ?, ?, ?, ?, 
    ?, ?, ?, ?,
    ?, ?, ?, ?,
    ?, ?, ?, ?, ?,
    ?, ?,
    ?, ?, ?,
    ?, ?,
    ?, ?, ?, ?, ?,
    ?
)";

$stmt = mysqli_prepare($koneksi, $query);
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($koneksi));
}

$params = [
    $kode_transaksi,
    $nama_customer,
    $kategori,
    $pembelian,
    $cstm_produk,
    $produk,
    $cstm_bahan,
    $bahan,
    $cstm_ukuran,
    $uk_baju,
    $uk_celana,
    $jumlah,
    $diskon,
    $harga,
    $tax,
    $total,
    $subtotal,
    $tanggal,
    $estimasi,
    $status_pembayaran,
    $dp_amount,
    $remaining_amount,
    $bukti_dp,
    $bukti_lunas,
    $status_pengiriman,
    $alamat,
    $email,
    $nohp,
    $resi,
    $pembayaran
];

$types = str_repeat('s', count($params));

if (!mysqli_stmt_bind_param($stmt, $types, ...$params)) {
    die("Error binding parameters: " . mysqli_stmt_error($stmt));
}

if (mysqli_stmt_execute($stmt)) {
    // Update stock
    if ($pembelian === 'siap pakai') {
        $stmt_update = mysqli_prepare($koneksi, "UPDATE produk SET stok = stok - ? WHERE id_produk = ?");
        mysqli_stmt_bind_param($stmt_update, "ii", $jumlah, $produk);
        mysqli_stmt_execute($stmt_update);
    } else {
        $stmt_update = mysqli_prepare($koneksi, "UPDATE cstm_pbahn SET stok = stok - ? WHERE id_cstm = ?");
        mysqli_stmt_bind_param($stmt_update, "ii", $jumlah, $cstm_bahan);
        mysqli_stmt_execute($stmt_update);
    }

    header("Location: ../index.php?page=transaksi&success=1&kode=" . $kode_transaksi);
    exit();
} else {
    if (!empty($target_file) && file_exists($target_file)) {
        unlink($target_file);
    }
    die("Gagal menyimpan transaksi: " . mysqli_stmt_error($stmt));
}

mysqli_close($koneksi);
?>