<?php
session_start();

// Include database connection
require_once 'db_connection.php';

// Check if user is logged in or guest
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Validate form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_STRING);
    $payment_method = 'cod'; // Hardcoded as per requirement

    // Combine first and last name
    $customer_name = trim($first_name . ' ' . $last_name);

    // Validate required fields
    if (empty($customer_name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zip)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: checkout.php");
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: checkout.php");
        exit;
    }

    // Get cart items from session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (empty($cart)) {
        $_SESSION['error'] = "Your cart is empty.";
        header("Location: checkout.php");
        exit;
    }

    // Validate cart structure
    $valid_cart = [];
    foreach ($cart as $product_id => $item) {
        if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity'])) {
            $_SESSION['error'] = "Invalid cart data.";
            header("Location: checkout.php");
            exit;
        }
        $valid_cart[] = [
            'product_id' => $product_id,
            'product_name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity']
        ];
    }

    // Calculate total amount
    $total_amount = 0;
    foreach ($valid_cart as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Verify database connection
        if (mysqli_connect_errno()) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }

        // Insert into orders table
        $query = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, customer_city, customer_state, customer_zip, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement for orders: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "issssssssd", $user_id, $customer_name, $email, $phone, $address, $city, $state, $zip, $payment_method, $total_amount);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement for orders: " . mysqli_stmt_error($stmt));
        }
        $order_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        // Insert cart items into order_items table
        $query = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement for order_items: " . mysqli_error($conn));
        }
        foreach ($valid_cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            mysqli_stmt_bind_param($stmt, "iisidd", $order_id, $item['product_id'], $item['product_name'], $item['quantity'], $item['price'], $subtotal);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to execute statement for order_items: " . mysqli_stmt_error($stmt));
            }
        }
        mysqli_stmt_close($stmt);

        // Commit transaction
        mysqli_commit($conn);

        // Clear cart after successful order
        unset($_SESSION['cart']);
        if ($user_id) {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
        }

        // Redirect to confirmation page
        $_SESSION['success'] = "Order placed successfully! Order ID: $order_id";
        header("Location: thankyou.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $_SESSION['error'] = "Failed to place order: " . $e->getMessage();
        header("Location: checkout.php");
        exit;
    }
} else {
    // Invalid request
    $_SESSION['error'] = "Invalid request.";
    header("Location: checkout.php");
    exit;
}
?>