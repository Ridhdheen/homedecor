<?php
// Database configuration
$host = 'localhost';
$dbname = 'home_decor_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Database connection successful.\n", FILE_APPEND);
} catch (PDOException $e) {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Database connection failed: " . $e->getMessage() . "\n", FILE_APPEND);
    die("Database connection failed: " . $e->getMessage());
}

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}

// Product CRUD functions
function getProducts() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT p.*, 
               c.name AS category_name,
               sc.name AS subcategory_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN categories sc ON p.subcategory_id = sc.id
        ORDER BY p.id DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addProduct($data) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, price, category_id, subcategory_id, stock, image)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['category_id'],
        $data['subcategory_id'] ?? null,
        $data['stock'],
        $data['image'] ?? 'placeholder.jpg'
    ]);
}

function updateProduct($data) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE products 
        SET name = ?, description = ?, price = ?, category_id = ?, 
            subcategory_id = ?, stock = ?, image = ?
        WHERE id = ?
    ");
    return $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['category_id'],
        $data['subcategory_id'] ?? null,
        $data['stock'],
        $data['image'] ?? 'placeholder.jpg',
        $data['id']
    ]);
}

function deleteProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}

// Category CRUD functions
function getCategories() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT c.*, 
               (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id OR p.subcategory_id = c.id) AS product_count
        FROM categories c
        ORDER BY c.name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addCategory($data) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO categories (name, description, parent_id)
        VALUES (?, ?, ?)
    ");
    return $stmt->execute([
        $data['name'],
        $data['description'] ?? '',
        $data['parent_id'] ?: null
    ]);
}

function updateCategory($data) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE categories 
        SET name = ?, description = ?, parent_id = ?
        WHERE id = ?
    ");
    return $stmt->execute([
        $data['name'],
        $data['description'] ?? '',
        $data['parent_id'] ?: null,
        $data['id']
    ]);
}

function deleteCategory($id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS product_count FROM products WHERE category_id = ? OR subcategory_id = ?
    ");
    $stmt->execute([$id, $id]);
    $product_count = $stmt->fetch(PDO::FETCH_ASSOC)['product_count'];

    $stmt = $pdo->prepare("SELECT COUNT(*) AS subcat_count FROM categories WHERE parent_id = ?");
    $stmt->execute([$id]);
    $subcat_count = $stmt->fetch(PDO::FETCH_ASSOC)['subcat_count'];

    if ($product_count > 0 || $subcat_count > 0) {
        return false;
    }

    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    return $stmt->execute([$id]);
}

// Order functions
function getOrders() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {
        $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$order['id']]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Alias and defaults for missing fields
        $order['order_id'] = $order['id'];
        $order['email'] = $order['customer_email'] ?? '';
        $order['phone'] = $order['customer_phone'] ?? '';
        $order['address'] = $order['customer_address'] ?? '';
        $order['city'] = $order['customer_city'] ?? '';
        $order['state'] = $order['customer_state'] ?? '';
        $order['postcode'] = $order['customer_zip'] ?? '';
        $order['customer_name'] = $order['customer_name'] ?? '';
        $order['payment_method'] = $order['payment_method'] ?? 'Unknown';
        $order['company'] = $order['company'] ?? 'N/A';
        $order['address2'] = $order['address2'] ?? '';
        $order['country'] = $order['country'] ?? '';
        $order['shipping_cost'] = $order['shipping_cost'] ?? 0;
        $order['tax'] = $order['tax'] ?? 0;
        $order['notes'] = $order['notes'] ?? '';
        $order['status'] = $order['status'] ?? 'Pending';
    }

    return $orders;
}

function updateOrderStatus($orderId, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $orderId]);
}

// User functions
function getUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addUser($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['password'],
        $data['role'],
        $data['status']
    ]);
}

function updateUser($data) {
    global $pdo;
    $sql = "UPDATE users SET name = ?, email = ?, role = ?, status = ?";
    $params = [$data['name'], $data['email'], $data['role'], $data['status']];
    if (isset($data['password']) && !empty($data['password'])) {
        $sql .= ", password = ?";
        $params[] = $data['password'];
    }
    $sql .= " WHERE id = ?";
    $params[] = $data['id'];
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

function deleteUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}
?>