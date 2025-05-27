<?php
session_start();

require '../app/koneksi.php';
$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ukbaju = $koneksi->real_escape_string($_POST['id_ukbaju']);
    $ukuran_bj = $koneksi->real_escape_string($_POST['ukuran_bj']);    
    if (empty($ukuran_bj)) {
        $pesan_error = "Type ukuran harus diisi!";
    } else {
        $sql_cek = "SELECT id_ukbaju FROM uk_baju WHERE (ukuran_bj = '$ukuran_bj') AND id_ukbaju != '$id_ukbaju'";
        $hasil_cek = $koneksi->query($sql_cek);
        
        if ($hasil_cek->num_rows > 0) {
            $pesan_error = "Type ukuran sudah ada!";
        } else {
            $sql_update = "UPDATE uk_baju SET ukuran_bj = '$ukuran_bj' WHERE id_ukbaju = '$id_ukbaju'";
            
            if ($koneksi->query($sql_update)) {
                $pesan_sukses = "Type ukuran berhasil diperbarui!";
            } else {
                $pesan_error = "Error: " . $koneksi->error;
            }
        }
    }
}

$koneksi->close();

if (!empty($pesan_error)) {
    $_SESSION['error'] = $pesan_error;
    header("Location: ../index.php?page=ebaju&id=" . urlencode($id_ukbaju));
} elseif (!empty($pesan_sukses)) {
    $_SESSION['sukses'] = $pesan_sukses;
    header("Location: ../index.php?page=ebaju&id=" . urlencode($id_ukbaju) . "&sukses=" . urlencode($pesan_sukses));
} else {
    header("Location: ../index.php?page=uk_baju");
}
exit();
?>