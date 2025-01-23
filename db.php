<?php
$host = 'localhost';
$port = '3307'; // Updated port number
$db = 'user_management';
$user = 'root';
$pass = ''; // Update with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
