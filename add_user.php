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
<div class="container">
    <h2>Add User</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Password" required> <!-- Use type="password" for password input -->
        
        <button type="submit">Add User</button>
    </form>
    <button class="back-button" onclick="window.location.href='admin.php'">Back to Admin</button>
</div>

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

input[type="text"], input[type="password"], button {
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

<?php include 'footer.php'; ?>