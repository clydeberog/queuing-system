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
    <div class="container">
        <div class="admin-content">
            <div class="left-column">
                <div class="boxed-container">
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
                            <li><a href="add_queue.php"><i class="fas fa-list"></i> Get Number</a></li>
                            <li><a onclick="showPopup('moveQueuePopup', 'move_queue.php')"><i class="fas fa-exchange-alt"></i> Move the Number</a></li>
                            <li><a href="manage_departments.php"><i class="fas fa-building"></i> Manage Departments</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="right-column">
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
            </div>
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
        
        <div id="notification" class="notification"></div>
    </div>

    <script>
    const buttonClickSound = new Audio('assets/queue-bell.m4a'); // Replace with your sound file path

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
        buttonClickSound.play(); // Play the sound when the button is clicked
        fetch('update_queue.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `department_id=${departmentId}&direction=${direction}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`activeQueue-${departmentId}`).textContent = data.new_active;
                showNotification('Queue updated successfully!');
                refreshQueueList(); // Refresh the queue list after updating
            } else {
                alert(data.message);
            }
        });
    }

    function refreshQueueList() {
    fetch('fetch_queues.php')
        .then(response => response.json())
        .then(data => {
            const queueList = document.getElementById('queueList');
            queueList.innerHTML = ''; // Clear existing content

            // Group queues by department
            const groupedQueues = data.reduce((acc, queue) => {
                if (!acc[queue.department_name]) {
                    acc[queue.department_name] = [];
                }
                acc[queue.department_name].push(queue);
                return acc;
            }, {});

            // Render queues
            for (const [department, queues] of Object.entries(groupedQueues)) {
                const queueBox = document.createElement('div');
                queueBox.className = 'queue-box';
                queueBox.innerHTML = `
                    <h3>${department}</h3>
                    ${queues.length > 0 ? `
                        <p>Active: <strong id="activeQueue-${queues[0].department_id}" class="active-queue">${queues[0].number}</strong></p>
                        <p>Name: ${queues[0].holder}</p>
                        <button class="animated-button" onclick="updateQueue(${queues[0].department_id}, 'prev')">⬅ Previous</button>
                        <button class="animated-button" onclick="updateQueue(${queues[0].department_id}, 'next')">Next ➡</button>
                    ` : '<p>No active queue</p>'}
                `;
                queueList.appendChild(queueBox);
            }
        })
        .catch(error => console.error('Error fetching queues:', error));
}

    function showNotification(message) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.display = 'block';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }

    setInterval(refreshQueueList, 5000); // Automatically refresh the queue list every 5 seconds
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background-image: url('assets/mdc.webp'); /* Replace with your image path */
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    margin: 0;
    padding: 0;
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

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: rgba(249, 249, 249, 0.9); /* Slightly transparent background */
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.admin-content {
    display: flex;
    gap: 20px;
}

.left-column {
    flex: 0 0 30%; /* Anchored on the left using 30% of the screen */
}

.right-column {
    flex: 1;
}

.boxed-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.admin-section {
    margin-bottom: 20px;
}

.admin-options {
    list-style: none;
    padding: 0;
}

.admin-options li {
    margin-bottom: 10px;
}

.admin-options a {
    text-decoration: none;
    color: #007BFF;
    font-weight: bold;
}

.admin-options a:hover {
    text-decoration: underline;
}

.queue-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.queue-box {
    flex: 1 1 calc(33.333% - 20px);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.queue-box:hover {
    transform: scale(1.05);
}

.queue-box h3 {
    margin-top: 0;
}

.animated-button {
    display: inline-block;
    padding: 10px 20px;
    font-family: 'Poppins', sans-serif;
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

.notification {
    background-color: #4CAF50;
    color: white;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 5px;
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}
</style>
<?php include 'footer.php'; ?>