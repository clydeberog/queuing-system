<?php
require 'db.php';

$query = "SELECT queue_number, holder, department, action, timestamp FROM queue_logs ORDER BY timestamp DESC";
$stmt = $pdo->query($query);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="queue-logs-container" style="max-height: 400px; overflow-y: auto;">
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Queue Number</th>
                <th>Holder</th>
                <th>Department</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($logs)): ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['queue_number']) ?></td>
                        <td><?= htmlspecialchars($log['holder']) ?></td>
                        <td><?= htmlspecialchars($log['department']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['timestamp']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No logs available</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
