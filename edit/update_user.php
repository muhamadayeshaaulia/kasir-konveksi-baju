<?php
session_start();

require '../app/koneksi.php';
$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $koneksi->real_escape_string($_POST['user_id']);
    $username = $koneksi->real_escape_string($_POST['username']);
    $email = $koneksi->real_escape_string($_POST['email']);
    $level = $koneksi->real_escape_string($_POST['level']);
    $level_diizinkan = ['Owner', 'Admin', 'Kasir','Demo'];
    if (!in_array($level, $level_diizinkan)) {
        $level = 'Kasir';
    }
    
    if (empty($username) || empty($email)) {
        $pesan_error = "Username dan email harus diisi!";
    } else {
        $sql_cek = "SELECT user_id FROM user WHERE (username = '$username' OR email = '$email') AND user_id != '$user_id'";
        $hasil_cek = $koneksi->query($sql_cek);
        
        if ($hasil_cek->num_rows > 0) {
            $pesan_error = "Username atau email sudah digunakan!";
        } else {
            $sql_update = "UPDATE user SET username = '$username', email = '$email', level = '$level' WHERE user_id = '$user_id'";
            
            if ($koneksi->query($sql_update)) {
                $pesan_sukses = "Data pengguna berhasil diperbarui!";
            } else {
                $pesan_error = "Error: " . $koneksi->error;
            }
        }
    }
}

$koneksi->close();

if (!empty($pesan_error)) {
    $_SESSION['error'] = $pesan_error;
    header("Location: ../index.php?page=eusers&id=" . urlencode($user_id));
} elseif (!empty($pesan_sukses)) {
    $_SESSION['sukses'] = $pesan_sukses;
    header("Location: ../index.php?page=eusers&id=" . urlencode($user_id) . "&sukses=" . urlencode($pesan_sukses));
} else {
    header("Location: ../index.php?page=users");
}
exit();
?>