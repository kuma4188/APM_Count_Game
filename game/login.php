<?php
require 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = $data['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(['success' => true, 'user_id' => $user['id'], 'message' => '로그인 성공!']);
} else {
    echo json_encode(['success' => false, 'message' => '아이디 또는 비밀번호가 틀립니다.']);
}
?>

