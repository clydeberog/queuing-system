<?php
$host = 'localhost';
$dbname = 'queue_system';
$username = 'root';
$password = '';


// filepath: /c:/xampp/htdocs/db.php

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>