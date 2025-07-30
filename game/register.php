<?php
require 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

// 아이디 중복 확인
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => '이미 존재하는 아이디입니다.']);
    exit;
}

// 회원가입 처리
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);
echo json_encode(['success' => true, 'message' => '회원가입 성공!']);
?>

