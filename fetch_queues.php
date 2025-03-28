<?php
// filepath: c:\xampp\htdocs\fetch_queues.php
require 'db.php';

// Fetch queues grouped by department
$query = "SELECT d.name AS department, q.number, q.holder, q.department_id
          FROM queue q
          JOIN departments d ON q.department_id = d.id
          WHERE q.status = 'active'
          ORDER BY q.department_id, q.number ASC";
$stmt = $pdo->query($query);
$queues = $stmt->fetchAll(PDO::FETCH_ASSOC);

$department_queues = [];
foreach ($queues as $queue) {
    $department_queues[] = [
        'department_name' => $queue['department'],
        'number' => $queue['number'],
        'holder' => $queue['holder'],
        'department_id' => $queue['department_id']
    ];
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($department_queues);