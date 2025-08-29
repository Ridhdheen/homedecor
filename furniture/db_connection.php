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
    // die("Database connection failed: " . $e->getMessage());
}

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}

// Authentication function (temporarily commented out)
// function login($email, $password) {
//     global $pdo;
//     $stmt = $pdo->prepare("SELECT id, username, email, password FROM admins WHERE email = ?");
//     $stmt->execute([$email]);
//     $user = $stmt->fetch(PDO::FETCH_ASSOC);
//     
//     file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Login attempt for email: $email, User found: " . json_encode($user) . "\n", FILE_APPEND);
//     
//     if ($user && password_verify($password, $user['password'])) {
//         file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Login successful for $email\n", FILE_APPEND);
//         return ['id' => $user['id'], 'username' => $user['username'], 'email' => $user['email']];
//     }
//     file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Login failed for $email\n", FILE_APPEND);
//     return false;
// }

// Product CRUD functions (unchanged)
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

function updateProduct($id, $data) {
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
        $id
    ]);
}

function deleteProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}

// Category CRUD functions (unchanged)
function getCategories($parent_id = null) {
    global $pdo;
    $sql = "
        SELECT c.*, 
               (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id OR p.subcategory_id = c.id) AS product_count
        FROM categories c";
    $params = [];
    
    if ($parent_id !== null) {
        $sql .= " WHERE c.parent_id = ?";
        $params[] = $parent_id;
    } else {
        $sql .= " WHERE c.parent_id IS NULL";
    }
    
    $sql .= " ORDER BY c.name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllCategories() {
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

function updateCategory($id, $data) {
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
        $id
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

// Create admin user if none exists (temporarily commented out)
// function ensureAdminUser() {
//     global $pdo;
//     $stmt = $pdo->query("SELECT COUNT(*) AS count FROM admins");
//     $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

//     if ($count == 0) {
//         $username = 'Admin User';
//         $email = 'admin@gmail.com';
//         $password = 'shubh123';
//         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//         $stmt = $pdo->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
//         $stmt->execute([$username, $email, $hashed_password]);
//         file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Admin user created: $email\n", FILE_APPEND);
//     }
// }

// // Run on first load to ensure admin user exists
// ensureAdminUser();
?>