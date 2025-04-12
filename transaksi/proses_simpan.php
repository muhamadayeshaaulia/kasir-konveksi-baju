<?php
require '../app/koneksi.php';

$kode_transaksi = $_POST['kode_transaksi'];
$nama_customer = $_POST['nama_customer'];
$produk = $_POST['produk'];
$bahan = $_POST['bahan'];
$uk_baju = !empty($_POST['uk_baju']) ? $_POST['uk_baju'] : "-";
$uk_celana = !empty($_POST['uk_celana']) ? $_POST['uk_celana'] : "-";
$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];
$subtotal = $_POST['subtotal'];
$tax = $_POST['tax'];
$diskon = $_POST['diskon'];
$total = $_POST['total'];
$metode_pembayaran = $_POST['metode_pembayaran'];
$status_pengiriman = $_POST['status_pengiriman'];
$pembayaran = $_POST['pembayaran'];
$tanggal = date('Y-m-d H:i:s');

$alamat = ($status_pengiriman == 'kirim') ? $_POST['alamat'] : "-";
$email = ($status_pengiriman == 'kirim') ? $_POST['email'] : "-";
$nohp = ($status_pengiriman == 'kirim') ? $_POST['nohp'] : "-";
$resi = ($status_pengiriman == 'kirim') ? $_POST['resi'] : "-";
$status_pembayaran = $metode_pembayaran;
$dp_amount = ($metode_pembayaran == 'dp') ? $total * 0.5 : 0;
$remaining_amount = ($metode_pembayaran == 'dp') ? $total - $dp_amount : 0;


$bukti_pembayaran = '';
if ($pembayaran == 'transfer' || $pembayaran == 'qris') {

    if ($_FILES['bukti_transaksi']['error'] !== UPLOAD_ERR_OK) {
        die("Error: Bukti pembayaran wajib diupload untuk metode pembayaran " . strtoupper($metode_pembayaran));
    }

    $target_dir = "uploads/bukti/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_ext = pathinfo($_FILES['bukti_transaksi']['name'], PATHINFO_EXTENSION);
    $bukti_pembayaran = $kode_transaksi . '_' . time() . '.' . $file_ext;
    $target_file = $target_dir . $bukti_pembayaran;
    $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];

    if (!in_array(strtolower($file_ext), $allowed_types)) {
        die("Error: Hanya file JPG, JPEG, PNG, atau PDF yang diizinkan.");
    }

    if ($_FILES['bukti_transaksi']['size'] > 2000000) {
        die("Error: Ukuran file terlalu besar. Maksimal 2MB.");
    }

    if (!move_uploaded_file($_FILES['bukti_transaksi']['tmp_name'], $target_file)) {
        die("Error: Gagal mengupload file bukti pembayaran.");
    }
} elseif ($pembayaran == 'cash') {

    $bukti_pembayaran = 'CASH-' . date('YmdHis');
} else {
    die("Error: Metode pembayaran tidak valid.");
}

$query_kategori = "SELECT kategori FROM produk WHERE id_produk = ?";
$stmt_kategori = mysqli_prepare($koneksi, $query_kategori);
mysqli_stmt_bind_param($stmt_kategori, "i", $produk);
mysqli_stmt_execute($stmt_kategori);
$result_kategori = mysqli_stmt_get_result($stmt_kategori);
$row_kategori = mysqli_fetch_assoc($result_kategori);
$kategori = $row_kategori['kategori'];

$nama_kolom_bukti = ($metode_pembayaran == 'dp') ? "bukti_dp" : "bukti_lunas";
$query = "INSERT INTO transaksi (
    kode_transaksi, nama_customer, kategori, produk, bahan, uk_baju, uk_celana, 
    jumlah, harga, diskon, tax, subtotal, total, tanggal_transaksi,
    pembayaran, status_pembayaran, dp_amount, remaining_amount, status_pengiriman, 
    alamat, email, nohp, resi, $nama_kolom_bukti
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param(
    $stmt, 
    "sssssssiiddddsssddssssss",
    $kode_transaksi,
    $nama_customer,
    $kategori,
    $produk,
    $bahan,
    $uk_baju,
    $uk_celana,
    $jumlah,
    $harga,
    $diskon,
    $tax,
    $subtotal,
    $total,
    $tanggal,
    $pembayaran,
    $status_pembayaran,
    $dp_amount,
    $remaining_amount,
    $status_pengiriman,
    $alamat,
    $email,
    $nohp,
    $resi,
    $bukti_pembayaran
);

if (mysqli_stmt_execute($stmt)) {
    $update_stok = "UPDATE produk SET stok = stok - ? WHERE id_produk = ?";
    $stmt_update = mysqli_prepare($koneksi, $update_stok);
    mysqli_stmt_bind_param($stmt_update, "ii", $jumlah, $produk);
    mysqli_stmt_execute($stmt_update);

    echo "<script>alert('Transaksi berhasil disimpan!'); window.location.href='../index.php?page=transaksi&success=1&kode=" . $kode_transaksi . "';</script>";
    exit();
} else {
    if (!empty($bukti_pembayaran) && file_exists($target_file)) {
        unlink($target_file);
    }
    die("Error: " . mysqli_error($koneksi));
}

mysqli_close($koneksi);
?>
