<?php
session_start();

require '../app/koneksi.php';
$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ukcelana = $koneksi->real_escape_string($_POST['id_ukcelana']);
    $ukuran_cln = $koneksi->real_escape_string($_POST['ukuran_cln']);    
    if (empty($ukuran_cln)) {
        $pesan_error = "Type ukuran harus diisi!";
    } else {
        $sql_cek = "SELECT id_ukcelana FROM uk_celana WHERE (ukuran_cln = '$ukuran_cln') AND id_ukcelana != '$id_ukcelana'";
        $hasil_cek = $koneksi->query($sql_cek);
        
        if ($hasil_cek->num_rows > 0) {
            $pesan_error = "Type ukuran sudah ada!";
        } else {
            $sql_update = "UPDATE uk_celana SET ukuran_cln = '$ukuran_cln' WHERE id_ukcelana = '$id_ukcelana'";
            
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
    header("Location: ../index.php?page=ecelana&id=" . urlencode($id_ukcelana));
} elseif (!empty($pesan_sukses)) {
    $_SESSION['sukses'] = $pesan_sukses;
    header("Location: ../index.php?page=ecelana&id=" . urlencode($id_ukcelana) . "&sukses=" . urlencode($pesan_sukses));
} else {
    header("Location: ../index.php?page=uk_celana");
}
exit();
?>