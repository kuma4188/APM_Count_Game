<?php
$host = 'localhost';
$db   = 'stopwatch_game';
$user = 'root';         //  본인이 설정한 root 계정이름 입력
$pass = 'password';     // 비밀번호 입력
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit('DB 연결 실패: ' . $e->getMessage());
}
?>

