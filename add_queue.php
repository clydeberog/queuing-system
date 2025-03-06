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

<?php include 'header.php'; ?>

<div>&nbsp </div>
<div class="container">
    <h2>Add to Queue</h2>
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
    <button class="back-button" onclick="window.location.href='admin.php'">Back to Admin</button>
    <button class="print-button" onclick="printQueueNumber()">Print Queue Number</button>
</div>

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
            showNotification('Queue added successfully. Queue number: ' + data.new_number);
            setTimeout(() => {
                window.location.href = 'admin.php';
            }, 3000); // Redirect after 3 seconds
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

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function printQueueNumber() {
    const department = document.getElementById('department_id').options[document.getElementById('department_id').selectedIndex].text;
    const queueNumber = document.getElementById('queueNumber').textContent;
    const holder = document.getElementById('holder').value;

    const printWindow = window.open('', '', 'height=400,width=600');
    printWindow.document.write('<html><head><title>Print Queue Number</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>Department: ' + department + '</h1>');
    printWindow.document.write('<h1>Queue Number: ' + queueNumber + '</h1>');
    printWindow.document.write('<h1>Holder: ' + holder + '</h1>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

<style>
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

select, input[type="text"], button {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

.notification {
    background-color: #4CAF50;
    color: white;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 5px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.back-button {
    background-color: #6c757d;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

.back-button:hover {
    background-color: #5a6268;
}

.print-button {
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

.print-button:hover {
    background-color: #218838;
}
</style>

<?php include 'footer.php'; ?>
