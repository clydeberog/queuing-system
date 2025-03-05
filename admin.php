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
?>
<?php include 'header.php'; ?>
    <h2>Admin Panel</h2>
    <div class="admin-section">
        <h3>Manage System</h3>
        <ul class="admin-options">
            <li><a href="add_user.php">Add Admin/Attendant</a></li>
            <li><a onclick="showPopup('queueLogsPopup', 'queue_logs.php')">View Queue Logs</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
    </div>
    <div>&nbsp</div>
    <div class="admin-section">
        <h3>Queue Options</h3>
        <ul class="admin-options">
            <li><a onclick="showPopup('queuePopup', 'add_queue.php')"><i class="fas fa-list"></i> Add to Queue</a></li>
            <li><a onclick="showPopup('moveQueuePopup', 'move_queue.php')"><i class="fas fa-exchange-alt"></i> Move the Number</a></li>
            <li><a href="manage_departments.php"><i class="fas fa-building"></i> Manage Departments</a></li>
        </ul>
    </div>
    
    <h2>Current Queues</h2>
    <div class="queue-container" id="queueList">
        <?php foreach ($department_queues as $department => $queues): ?>
            <div class="queue-box">
                <h3><?= htmlspecialchars($department) ?></h3>
                <?php if (!empty($queues)): ?>
                    <p>Active: <strong id="activeQueue-<?= $queues[0]['department_id'] ?>" class="active-queue"> <?= htmlspecialchars($queues[0]['number']) ?></strong></p>
                    <p>Name: <?= htmlspecialchars($queues[0]['holder']) ?></p>

                    <button class="animated-button" onclick="updateQueue(<?= $queues[0]['department_id'] ?>, 'prev')">⬅ Previous</button>
                    <button class="animated-button" onclick="updateQueue(<?= $queues[0]['department_id'] ?>, 'next')">Next ➡</button>
                <?php else: ?>
                    <p>No active queue</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div>&nbsp</div>
    <div>&nbsp</div>
    <div>&nbsp</div>

    <div id="queuePopup" class="popup">
        <h3>Add Queue</h3>
        <div id="queueContent">
            <?php include 'add_queue.php'; ?>
        </div>
        <button onclick="closePopup('queuePopup')">Close</button>
    </div>
    <div id="moveQueuePopup" class="popup">
        <h3>Move Queue</h3>
        <div id="moveQueueContent">
            <?php include 'move_queue.php'; ?>
        </div>
        <button onclick="closePopup('moveQueuePopup')">Close</button>
    </div>
    <div id="queueLogsPopup" class="popup">
        <h3>Queue Logs</h3>
        <div id="queueLogsContent">
            <?php include 'queue_logs.php'; ?>
        </div>
        <button onclick="closePopup('queueLogsPopup')">Close</button>
    </div>
    
    <script>
    function showPopup(popupId, url) {
        document.getElementById(popupId).style.display = 'block';
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById(popupId + 'Content').innerHTML = data;
            });
    }

    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }

    function updateQueue(departmentId, direction) {
        fetch('update_queue.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `department_id=${departmentId}&direction=${direction}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`activeQueue-${departmentId}`).textContent = data.new_active;
                new Audio('notification.mp3').play();
                setTimeout(() => {
                    window.open('public_view.php', '_blank');
                }, 500);
            } else {
                alert(data.message);
            }
        });
    }

    function refreshQueueList() {
        fetch('admin.php')
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                document.getElementById('queueList').innerHTML = doc.getElementById('queueList').innerHTML;
            });
    }
    
    setInterval(refreshQueueList, 5000);
    </script>

<style>
    .animated-button {
        display: inline-block;
        padding: 10px 20px;
        font-family: 'Poppins';
        font-size: 12px;
        font-weight: normal;
        color: white;
        background-color: #007BFF;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .animated-button:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
    
    .animated-button:active {
        transform: scale(0.95);
    }
    
    .active-queue {
        color: red;
        font-size: 24px;
    }

    body {
        background-image: url(''); /* Replace with your image file */
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5); /* White overlay with 50% opacity */
    z-index: -1; /* Places it behind the content */
    }
    
</style>
<?php include 'footer.php'; ?>
