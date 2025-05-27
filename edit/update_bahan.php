<?php
session_start();

require '../app/koneksi.php';
$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bahan = $koneksi->real_escape_string($_POST['id_bahan']);
    $bahan_kain = $koneksi->real_escape_string($_POST['bahan_kain']);    
    if (empty($bahan_kain)) {
        $pesan_error = "Bahan harus diisi!";
    } else {
        $sql_cek = "SELECT id_bahan FROM bahan WHERE (bahan_kain = '$bahan_kain') AND id_bahan != '$id_bahan'";
        $hasil_cek = $koneksi->query($sql_cek);
        
        if ($hasil_cek->num_rows > 0) {
            $pesan_error = "Bahan sudah ada!";
        } else {
            $sql_update = "UPDATE bahan SET bahan_kain = '$bahan_kain' WHERE id_bahan = '$id_bahan'";
            
            if ($koneksi->query($sql_update)) {
                $pesan_sukses = "Bahan berhasil diperbarui!";
            } else {
                $pesan_error = "Error: " . $koneksi->error;
            }
        }
    }
}

$koneksi->close();

if (!empty($pesan_error)) {
    $_SESSION['error'] = $pesan_error;
    header("Location: ../index.php?page=ebahan&id=" . urlencode($id_bahan));
} elseif (!empty($pesan_sukses)) {
    $_SESSION['sukses'] = $pesan_sukses;
    header("Location: ../index.php?page=ebahan&id=" . urlencode($id_bahan) . "&sukses=" . urlencode($pesan_sukses));
} else {
    header("Location: ../index.php?page=bahan");
}
exit();
?>