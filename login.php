<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php include 'footer.php'; ?>

<style>

    body {
        background-image: url('mdc.webp'); /* Replace with your image file */
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
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

    .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 350px;
            width: 100%;
    }

    h2 {
            margin-bottom: 20px;
            color: #007bff;
    }

    .input-group {
            margin-bottom: 15px;
            text-align: left;
    }

    label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
    }

    input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
    }

    .btn-login {
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
    }

    .btn-login:hover {
            background: #0056b3;
    }
</style>