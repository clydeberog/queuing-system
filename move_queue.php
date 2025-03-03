<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $queueId = $_POST['queue_id'] ?? null;
    $newDepartmentId = $_POST['new_department_id'] ?? null;

    if ($queueId && $newDepartmentId) {
        // Update the queue's department
        $stmt = $pdo->prepare("UPDATE queue SET department_id = ? WHERE id = ?");
        if ($stmt->execute([$newDepartmentId, $queueId])) {
            echo "Queue moved successfully.";
        } else {
            echo "Error moving queue.";
        }
    } else {
        echo "Invalid request.";
    }
    exit;
}

// Fetch active queues
$queueStmt = $pdo->query("SELECT q.id, q.number, q.holder, d.name AS department FROM queue q JOIN departments d ON q.department_id = d.id WHERE q.status = 'active'");
$queues = $queueStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch departments
$deptStmt = $pdo->query("SELECT id, name FROM departments");
$departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Move Queue</h3>
<form id="moveQueueForm">
    <label for="queue_id">Select Queue:</label>
    <select name="queue_id" id="queue_id" required>
        <option value="">Select a queue</option>
        <?php foreach ($queues as $queue): ?>
            <option value="<?= $queue['id'] ?>">#<?= $queue['number'] ?> - <?= htmlspecialchars($queue['holder']) ?> (<?= htmlspecialchars($queue['department']) ?>)</option>
        <?php endforeach; ?>
    </select>

    <label for="new_department_id">Move to Department:</label>
    <select name="new_department_id" id="new_department_id" required>
        <option value="">Select a department</option>
        <?php foreach ($departments as $dept): ?>
            <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Move Queue</button>
</form>

<script>
document.getElementById('moveQueueForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    fetch('move_queue.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();
    })
    .catch(error => console.error('Error:', error));
});
</script>
