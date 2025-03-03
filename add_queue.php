<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_id = $_POST['department_id'];
    $holder = $_POST['holder'];

    // Get the last queue number for the selected department
    $query = "SELECT MAX(number) AS max_number FROM queue WHERE department_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$department_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $new_number = $result['max_number'] ? $result['max_number'] + 1 : 1;

    // Insert the new queue entry
    $insertQuery = "INSERT INTO queue (department_id, number, holder, status) VALUES (?, ?, ?, 'active')";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([$department_id, $new_number, $holder]);

    // Get the last inserted queue ID
    $queue_id = $pdo->lastInsertId();

    // Log the new queue addition
    $logQuery = "INSERT INTO queue_logs (queue_id, action, timestamp) VALUES (?, 'added', NOW())";
    $stmt = $pdo->prepare($logQuery);
    $stmt->execute([$queue_id]);

    echo json_encode(['success' => true, 'new_number' => $new_number]);
    exit;
}

// Fetch departments for selection
$departments = $pdo->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="addQueueForm" method="POST">
    <label for="department_id">Select Department:</label>
    <select name="department_id" id="department_id" required onchange="updateQueueNumber()">
        <option value="" disabled selected>Select Department</option>
        <?php foreach ($departments as $dept): ?>
            <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="holder">Enter Name:</label>
    <input type="text" name="holder" id="holder" required>

    <p>Next Queue Number: <strong id="queueNumber">-</strong></p>

    <button type="submit">Add to Queue</button>
</form>

<script>
document.getElementById('addQueueForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('add_queue.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Queue added successfully. Queue number: ' + data.new_number);
            document.getElementById('queueNumber').textContent = data.new_number;
            document.getElementById('holder').value = ''; // Clear input field
        } else {
            alert('Error adding queue.');
        }
    });
});

// Function to get the next queue number when department is selected
function updateQueueNumber() {
    const departmentId = document.getElementById('department_id').value;

    fetch('get_next_queue.php?department_id=' + departmentId)
    .then(response => response.json())
    .then(data => {
        document.getElementById('queueNumber').textContent = data.next_number;
    });
}
</script>
