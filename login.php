<?php
session_start();
require 'db.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Debugging: Check the hashed password from the database
        error_log("Hashed password from DB: " . $user['password']);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin.php");
            exit();
        } else {
            // Debugging: Check the plain password and the result of password_verify
            error_log("Plain password: " . $password);
            error_log("Password verify result: " . (password_verify($password, $user['password']) ? 'true' : 'false'));
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }
}
?>

<div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
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