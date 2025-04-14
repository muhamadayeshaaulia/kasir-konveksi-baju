<?php
include '../app/app.php';

$sql = "SELECT m.*, u.username 
        FROM messages m 
        JOIN user u ON m.sender_id = u.user_id 
        WHERE m.receiver_id IS NULL 
        ORDER BY m.created_at ASC";

$stmt = $pdo->query($sql);

foreach ($stmt as $row) {
    echo "<p><strong>{$row['username']}:</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";
    echo "<p><strong>{$row['created_at']}.</strong></p>";
}
?>
