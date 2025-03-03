<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
    $stmt->execute([$name]);
    header("Location: manage_departments.php");
    exit();
}
?>
<form method="POST">
    <input type="text" name="name" required>
    <button type="submit">Add Department</button>
</form>
