<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_id = $_POST['department_id'];
    $direction = $_POST['direction']; // 'next' or 'prev'

    // Get the active queue in the department
    $query = "SELECT id, number FROM queue WHERE department_id = ? AND status = 'active' ORDER BY number ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$department_id]);
    $queues = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($queues)) {
        $currentQueue = $queues[0];
        $currentNumber = $currentQueue['number'];
        $currentId = $currentQueue['id'];

        if ($direction === 'next') {
            // Find the next queue number in the department
            $query = "SELECT id, number FROM queue WHERE department_id = ? AND number > ? ORDER BY number ASC LIMIT 1";
        } else {
            // Find the previous queue number
            $query = "SELECT id, number FROM queue WHERE department_id = ? AND number < ? ORDER BY number DESC LIMIT 1";
        }
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$department_id, $currentNumber]);
        $nextQueue = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($nextQueue) {
            // Update the current queue status to 'completed'
            $updateQuery = "UPDATE queue SET status = 'completed' WHERE id = ?";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([$currentId]);

            // Update the next queue to be the active one
            $updateQuery = "UPDATE queue SET status = 'active' WHERE id = ?";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([$nextQueue['id']]);

            // Log the queue change
            $logQuery = "INSERT INTO queue_logs (queue_id, action, timestamp) VALUES (?, ?, NOW())";
            $stmt = $pdo->prepare($logQuery);
            $stmt->execute([$nextQueue['id'], $direction === 'next' ? 'moved forward' : 'moved back']);

            echo json_encode(['success' => true, 'new_active' => $nextQueue['number']]);
            exit;
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'No more queues available']);
    exit;
}
?>
