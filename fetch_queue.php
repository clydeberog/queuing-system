<?php
require 'db.php';

// Fetch queues grouped by department
$query = "SELECT d.name AS department, q.id, q.number, q.holder, q.status, d.id AS department_id
          FROM queue q
          JOIN departments d ON q.department_id = d.id
          ORDER BY q.department_id, q.number ASC";
$stmt = $pdo->query($query);
$queues = $stmt->fetchAll(PDO::FETCH_ASSOC);

$department_queues = [];
foreach ($queues as $queue) {
    $department_queues[$queue['department']][] = $queue;
}

$result = [];
foreach ($department_queues as $department => $queues) {
    $result[] = [
        'name' => $department,
        'queues' => $queues
    ];
}

echo json_encode($result);
?>