<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Include database connection
    require_once 'db_connection.php'; // Assume this file contains your PDO setup

    // Define syncCartWithDatabase function
    function syncCartWithDatabase($pdo, $userId, $cart) {
        // Clear existing cart items for the user
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Insert updated cart items
        if (!empty($cart)) {
            foreach ($cart as $product_id => $item) {
                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $userId,
                    $product_id,
                    $item['name'],
                    $item['price'],
                    $item['image'],
                    $item['quantity']
                ]);
            }
        }
    }

    // Sync cart to database before logout
    if (isset($_SESSION['cart'])) {
        syncCartWithDatabase($pdo, $_SESSION['user_id'], $_SESSION['cart']);
    }
}

session_destroy();
header('Location: login.php');
exit();
?>