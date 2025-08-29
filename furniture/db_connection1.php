<?php
$host = 'localhost';
$dbname = 'home_decor_db';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=localhost;dbname=home_decor_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create users table if it doesn't exist
$stmt = $pdo->query("SHOW TABLES LIKE 'users'");
if ($stmt->rowCount() == 0) {
    $pdo->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}