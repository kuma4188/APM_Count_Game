<?php
require 'db.php';

$sql = "
    SELECT u.username, r.time, MIN(r.diff) AS best_diff
    FROM users u
    JOIN records r ON u.id = r.user_id
    GROUP BY u.id
    ORDER BY best_diff ASC
    LIMIT 5
";

$stmt = $pdo->query($sql);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>

