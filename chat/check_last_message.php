<?php
include '../app/app.php'; 

$query = $pdo->query("SELECT id, message, created_at FROM messages ORDER BY created_at DESC LIMIT 1");

$row = $query->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'id' => $row['id'],
        'message' => $row['message'],
        'created_at' => $row['created_at']
    ]);
} else {
    echo json_encode(null);
}
?>
