<?php
require 'db.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']); // Sanitize input
    $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
    $stmt->execute([$name]);
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <style>
        .notification {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div>&nbsp</div>
    <?php if ($success): ?>
        <div class="notification" id="successNotification">Department added successfully! Redirecting to admin page...</div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Department Name" required>
        <button type="submit">Add Department</button>
    </form>
    <?php include 'footer.php'; ?>

    <script>
        // Show the notification if the department was added successfully
        <?php if ($success): ?>
            document.getElementById('successNotification').style.display = 'block';
            setTimeout(function() {
                window.location.href = 'admin.php';
            }, 5000); // Redirect after 5 seconds
        <?php endif; ?>
    </script>
</body>
</html>
