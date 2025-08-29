<?php
session_start();

$host = 'localhost';
$dbname = 'home_decor_db';
$username = 'root'; // Change as per your setup
$password = ''; // Change as per your setup

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        // Check if there's a redirect destination
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']); // Clear the redirect
            header("Location: $redirect");
        } else {
            header("Location: index.php"); // Default redirect
        }
        exit();
    } else {
        $_SESSION['login_error'] = 'Invalid email or password.';
        header("Location: login.php");
        exit();
    }
}
?>