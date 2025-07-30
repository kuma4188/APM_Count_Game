<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'];
$time = $data['time'];
$diff = $data['diff'];

$stmt = $pdo->prepare("INSERT INTO records (user_id, time, diff) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $time, $diff]);

echo json_encode(['success' => true]);
?>

