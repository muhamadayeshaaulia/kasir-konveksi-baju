<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$level = $_POST['level'] ?? '';

$errors = [];

$check_username = $koneksi->prepare("SELECT username FROM user WHERE username = ?");
$check_username->bind_param("s", $username);
$check_username->execute();
$check_username->store_result();

if ($check_username->num_rows > 0) {
    $errors['username'] = "Username sudah digunakan";
}

$check_email = $koneksi->prepare("SELECT email FROM user WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$check_email->store_result();

if ($check_email->num_rows > 0) {
    $errors['email'] = "Email sudah digunakan";
}

if (strlen($password) < 8) {
    $errors['password'] = "Password minimal 8 karakter";
}

if ($password !== $confirm_password) {
    $errors['confirm_password'] = "Password dan konfirmasi password tidak sama";
}

if (empty($errors)) {

    $hashed_password = md5($password);
    $stmt = $koneksi->prepare("INSERT INTO user (username, email, password, level) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $level);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?page=tusers&sukses=" . urlencode("Pengguna berhasil ditambahkan"));
        exit();
    } else {
        header("Location: ../index.php?page=tusers&error=" . urlencode("Gagal menambahkan pengguna: " . $koneksi->error));
        exit();
    }
} else {
    $error_messages = [];
    foreach ($errors as $field => $message) {
        $error_messages[] = "$message";
    }
    $error_query = http_build_query(['errors' => $errors]);
    header("Location: ../index.php?page=tusers&" . $error_query);
    exit();
}

$check_username->close();
$check_email->close();
$koneksi->close();
?>