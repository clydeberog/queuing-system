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

        input[type="text"], button {
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
            display: none;
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
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div>&nbsp</div>
    <div class="container">
        <h2>Manage Departments</h2>
        <?php if ($success): ?>
            <div class="notification" id="successNotification">Department added successfully! Redirecting to admin page...</div>
        <?php endif; ?>
        <form method="POST">
            <label for="name">Department Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter Department Name" required>
            <button type="submit">Add Department</button>
        </form>
        <button class="back-button" onclick="window.location.href='admin.php'">Back to Admin</button>
    </div>
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
