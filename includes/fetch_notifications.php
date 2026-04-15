<?php
require_once "db.php";
require_once "auth.php";

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT * FROM notifications
    WHERE user_id=? AND is_read=0
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);

$notifications = $stmt->fetchAll();

echo json_encode($notifications);