<?php
require 'db.php';

if (isset($_GET['department_id'])) {
    $department_id = $_GET['department_id'];

    $query = "SELECT MAX(number) AS max_number FROM queue WHERE department_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$department_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_number = $result['max_number'] ? $result['max_number'] + 1 : 1;

    echo json_encode(['next_number' => $next_number]);
    exit;
}
?>
