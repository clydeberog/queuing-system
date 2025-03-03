<?php
require 'db.php';
$query = "SELECT d.name AS department, q.number, q.holder 
          FROM queue q
          JOIN departments d ON q.department_id = d.id
          WHERE q.status = 'active'
          ORDER BY q.department_id, q.number ASC";
$stmt = $pdo->query($query);
$queues = $stmt->fetchAll(PDO::FETCH_ASSOC);
$department_queues = [];
foreach ($queues as $queue) {
    $department_queues[$queue['department']][] = $queue;
}
foreach ($department_queues as $department => $queues) {
    echo "<h3>" . htmlspecialchars($department) . "</h3>";
    foreach ($queues as $queue) {
        echo "<p>Number: <strong>" . htmlspecialchars($queue['number']) . "</strong> - Holder: " . htmlspecialchars($queue['holder']) . "</p>";
    }
    echo "<hr>";
}
?>