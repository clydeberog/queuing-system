<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']); // Sanitize input
    $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
    $stmt->execute([$name]);
    header("Location: manage_departments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div>&nbsp</div>
    <form method="POST">
        <input type="text" name="name" placeholder="Department Name" required>
        <button type="submit">Add Department</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
