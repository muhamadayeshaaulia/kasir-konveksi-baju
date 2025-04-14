<?php
session_start();
include '../app/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    $sender_id = $_SESSION['user_id'] ?? null;

    if ($message !== '' && $sender_id) {
        $sql = "INSERT INTO messages (sender_id, message, created_at) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sender_id, $message]);

        $lastInsertId = $pdo->lastInsertId();

        echo json_encode(['status' => 'sukses', 'message_id' => $lastInsertId]);
    } else {
        http_response_code(400);
        echo "Gagal: Pesan kosong atau belum login";
    }
}
?>
