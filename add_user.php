<?php

require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("INSERT INTO 'users' ('id', 'username' , 'password' ) VALUES (?, ?, ?)");
    $stmt->execute([$id][$username][$password]);
    header("Location: manage_users.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div>&nbsp</div>
<form method="POST">
    <input type="text" name="id" required>
    <input type="text" name="username" required>
    <input type="text" name="password" required>
    <button type="submit">Add User</button>
</form>

<?php include 'footer.php'; ?>
