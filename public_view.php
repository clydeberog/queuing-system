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
?>
<?php include 'header.php'; ?>

<style>
body {
    background-image: url('assets/mdc.webp'); /* Replace with your image file */
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
    background: rgba(255, 255, 255, 0.2); /* White overlay with 50% opacity */
    z-index: -1; /* Places it behind the content */
}

.container {
    display: flex;
    gap: 20px;
    padding: 20px;
}

.left-column {
    flex: 0 0 40%; /* Anchored on the left using 40% of the screen */
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px black;
}

.right-column {
    flex: 1;
}

.queue-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 20px;
    padding: 20px;
}

.queue-box {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px black;
    text-align: center;
    width: calc(50% - 20px); /* Two boxes per row with a gap */
    transition: transform 0.3s ease-in-out;
    animation: fadeInBounce 0.8s ease-in-out;
}

.holder-container {
    background: rgba(41, 132, 230, 0.65);
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
}

h1, h2, h3 {
    color: #007bff;
    text-align: center;
    animation: slideFadeIn 1s ease-in-out;
}

h1 {
    font-size: 3em;
    margin-bottom: 0.5em;
}

h2 {
    font-size: 1.5em;
    margin-bottom: 1em;
}

h3 {
    font-size: 1.2em;
}

.queue-box h2 strong {
    color: red; /* Highlight the active queue number */
    font-size: 36px;
}

@keyframes fadeInBounce {
    0% { opacity: 0; transform: scale(0.8); }
    50% { opacity: 1; transform: scale(1.1); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes slideFadeIn {
    0% { opacity: 0; transform: translateY(-30px); }
    100% { opacity: 1; transform: translateY(0); }
}

.video-container {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px black;
    text-align: center;
    margin-bottom: 20px;
}

.text-box {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px black;
    text-align: center;
}
</style>

<h1>Metro Dumaguete College</h1>
<p>&nbsp</p>
<div class="container">
    <div class="left-column">
        <div class="queue-container">
            <?php foreach ($department_queues as $department => $queues): ?>
                <div class="queue-box">
                    <h3><?= htmlspecialchars($department) ?></h3>
                    <?php if (!empty($queues)): ?>
                        <div class="holder-container">
                            <h2><strong><?= htmlspecialchars($queues[0]['number']) ?></strong></h2>
                            <p><?= htmlspecialchars($queues[0]['holder']) ?></p>
                        </div>
                    <?php else: ?>
                        <p>No active queue</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="right-column">
        <div class="video-container">
            <video width="100%" controls autoplay loop>
                <source src="assets/mdc-intro.mp4" type="video/mp4"> <!-- Replace with your video file path -->
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="text-box">
            <h2>Information</h2>
            <p>Here you can add any additional information or announcements.</p>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>