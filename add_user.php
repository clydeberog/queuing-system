<?php

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the next ID
    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM `users`");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_id = $row['max_id'] + 1;

    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $stmt = $pdo->prepare("INSERT INTO `users` (`id`, `username`, `password`) VALUES (?, ?, ?)");
    $stmt->execute([$next_id, $username, $password]); // Use commas to separate parameters

    header("Location: manage_users.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div>&nbsp</div>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required> <!-- Use type="password" for password input -->
    <button type="submit">Add User</button>
</form>

<?php include 'footer.php'; ?>
