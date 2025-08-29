<?php
// Start session and include db connection
session_start();
require_once 'db_connection.php';

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = login($email, $password);
    
    if ($user) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['username'];
        $_SESSION['admin_email'] = $user['email'];
    } else {
        $login_error = "Invalid email or password!";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Handle product form submission
if (isset($_POST['add_product'])) {
    // In a real application, you would handle file uploads here
    $productData = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'category_id' => $_POST['category_id'],
        'stock' => $_POST['stock'],
        'image' => 'placeholder.jpg' // Replace with actual uploaded image path
    ];
    addProduct($productData);
    $product_success = "Product added successfully!";
}

// Handle category form submission
if (isset($_POST['add_category'])) {
    $categoryData = [
        'name' => $_POST['name'],
        'parent_id' => $_POST['parent_id'] ?: null,
        'description' => $_POST['description']
    ];
    addCategory($categoryData);
    $category_success = "Category added successfully!";
}

// Get data from database
$products = getProducts();
$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegant Decor | Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6d4c41;
            --primary-light: #9c786c;
            --primary-dark: #40241a;
            --secondary: #7e57c2;
            --accent: #ff7043;
            --light: #f5f5f5;
            --dark: #263238;
            --success: #66bb6a;
            --warning: #ffca28;
            --danger: #ef5350;
            --gray: #90a4ae;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
            position: fixed;
            height: 100vh;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            background: white;
            border-radius: 8px;
            padding: 5px;
        }

        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
        }

        .nav-links {
            padding: 0 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-item i {
            font-size: 18px;
            margin-right: 15px;
            width: 24px;
            text-align: center;
        }

        .nav-item span {
            font-size: 15px;
            font-weight: 500;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            animation: slideDown 0.5s ease;
        }

        .welcome h2 {
            font-size: 24px;
            color: var(--primary);
            font-weight: 600;
        }

        .welcome p {
            color: var(--gray);
            font-size: 14px;
        }

        .admin-actions {
            display: flex;
            align-items: center;
        }

        .search-box {
            position: relative;
            margin-right: 20px;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border-radius: 50px;
            border: 1px solid #e0e0e0;
            background: #f5f5f5;
            font-size: 14px;
            width: 250px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(109, 76, 65, 0.1);
            width: 300px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 50px;
            background: #f5f5f5;
            transition: all 0.3s;
        }

        .user-profile:hover {
            background: #e0e0e0;
        }

        .user-profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid var(--primary);
        }

        .user-profile span {
            font-weight: 500;
            color: var(--dark);
        }

        /* Content Sections */
        .content-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.6s ease;
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .section-header h3 {
            font-size: 20px;
            color: var(--primary);
            font-weight: 600;
        }

        .section-header .btn {
            padding: 8px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .section-header .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section-header .btn i {
            margin-right: 8px;
        }

        /* Dashboard Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-card.orders {
            border-left-color: var(--secondary);
        }

        .stat-card.products {
            border-left-color: var(--accent);
        }

        .stat-card.users {
            border-left-color: var(--success);
        }

        .stat-card.revenue {
            border-left-color: var(--warning);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-header i {
            font-size: 24px;
            padding: 15px;
            border-radius: 10px;
            background: rgba(109, 76, 65, 0.1);
            color: var(--primary);
        }

        .stat-card.orders .stat-header i {
            background: rgba(126, 87, 194, 0.1);
            color: var(--secondary);
        }

        .stat-card.products .stat-header i {
            background: rgba(255, 112, 67, 0.1);
            color: var(--accent);
        }

        .stat-card.users .stat-header i {
            background: rgba(102, 187, 106, 0.1);
            color: var(--success);
        }

        .stat-card.revenue .stat-header i {
            background: rgba(255, 202, 40, 0.1);
            color: var(--warning);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-title {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
        }

        .stat-trend {
            margin-top: 10px;
            font-size: 13px;
            display: flex;
            align-items: center;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        .trend-up i, .trend-down i {
            margin-right: 5px;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #eee;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .data-table tr:hover td {
            background: rgba(109, 76, 65, 0.03);
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status.completed {
            background: rgba(102, 187, 106, 0.15);
            color: var(--success);
        }

        .status.pending {
            background: rgba(255, 202, 40, 0.15);
            color: var(--warning);
        }

        .status.processing {
            background: rgba(66, 133, 244, 0.15);
            color: #4285f4;
        }

        .status.cancelled {
            background: rgba(239, 83, 80, 0.15);
            color: var(--danger);
        }

        .action-btn {
            padding: 6px 10px;
            background: rgba(109, 76, 65, 0.1);
            border: none;
            border-radius: 5px;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
        }

        .action-btn.delete {
            background: rgba(239, 83, 80, 0.1);
            color: var(--danger);
            margin-left: 5px;
        }

        .action-btn.delete:hover {
            background: var(--danger);
            color: white;
        }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(109, 76, 65, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .submit-btn i {
            margin-right: 8px;
        }

        /* Login Page */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-dark) 100%);
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            animation: scaleIn 0.6s ease;
        }

        .login-header {
            background: var(--primary);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            opacity: 0.9;
        }

        .login-body {
            padding: 30px;
        }

        .login-footer {
            padding: 20px 30px;
            text-align: center;
            background: #f9f9f9;
            font-size: 14px;
            color: var(--gray);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }
            .sidebar .logo h1, .sidebar .nav-item span {
                display: none;
            }
            .sidebar .logo {
                justify-content: center;
                padding: 20px 0;
            }
            .sidebar .logo img {
                margin: 0;
            }
            .sidebar .nav-item {
                justify-content: center;
                padding: 15px;
            }
            .sidebar .nav-item i {
                margin: 0;
            }
            .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .admin-actions {
                width: 100%;
                margin-top: 15px;
                justify-content: space-between;
            }
            .search-box {
                flex: 1;
                margin-right: 10px;
            }
            .search-box input {
                width: 100%;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['admin_id'])): ?>
    <!-- Login Page -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Elegant Decor</h2>
                <p>Admin Portal - Exclusive Access</p>
            </div>
            <div class="login-body">
                <?php if (isset($login_error)): ?>
                    <div style="color: #ef5350; margin-bottom: 15px; text-align: center;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $login_error; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Admin Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="admin@elegantdecor.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="login" class="submit-btn">
                        <i class="fas fa-lock"></i> Secure Login
                    </button>
                </form>
            </div>
            <div class="login-footer">
                <p>Restricted Access - For authorized personnel only</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Admin Panel -->
    <div class="admin-container">
        <!-- Sidebar -->
       <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%236d4c41' d='M12,3L2,12H5V20H19V12H22L12,3M12,7.7C14.1,7.7 15.8,9.4 15.8,11.5C15.8,14.5 12,18 12,18C12,18 8.2,14.5 8.2,11.5C8.2,9.4 9.9,7.7 12,7.7M12,10C11.17,10 10.5,10.67 10.5,11.5A1.5,1.5 0 0,0 12,13A1.5,1.5 0 0,0 13.5,11.5C13.5,10.67 12.83,10 12,10Z'/%3E%3C/svg%3E" alt="Logo">
                <h1>Elegant Decor</h1>
            </div>
            <div class="nav-links">
                <div class="nav-item" data-target="dashboard">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </div>
                <div class="nav-item" data-target="orders">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Order Details</span>
                </div>
                <div class="nav-item active" data-target="products">
                    <i class="fas fa-couch"></i>
                    <span>Product Management</span>
                </div>
                <div class="nav-item" data-target="categories">
                    <i class="fas fa-layer-group"></i>
                    <span>Categories</span>
                </div>
                <div class="nav-item" data-target="users">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </div>
                <div class="nav-item" data-target="payments">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment Details</span>
                </div>
                <div class="nav-item" data-target="feedback">
                    <i class="fas fa-comment-alt"></i>
                    <span>Feedback</span>
                </div>
                <div class="nav-item" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </div>
            </div>
        </div>


        <!-- Main Content -->
         <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="welcome">
                    <h2>Product Management</h2>
                    <p>Manage your home décor products, categories and inventory</p>
                </div>
                <div class="admin-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search products...">
                    </div>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=6d4c41&color=fff" alt="Admin">
                        <span>Admin User</span>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="content-section active" id="products">
                <div class="section-header">
                    <h3>Product Management</h3>
                    <button class="btn" id="addProductBtn"><i class="fas fa-plus"></i> Add Product</button>
                </div>
                
                <!-- Product Form -->
                <div class="product-form" id="productForm" style="<?php echo isset($_POST['add_product']) ? 'display:block;' : 'display:none;'; ?> background: #f9f9f9; padding: 25px; border-radius: 10px; margin-bottom: 30px;">
                    <h4 style="margin-bottom: 20px; color: var(--primary);">Add New Product</h4>
                    <?php if (isset($product_success)): ?>
                        <div style="background: rgba(102, 187, 106, 0.15); color: #66bb6a; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                            <i class="fas fa-check-circle"></i> <?php echo $product_success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="productName">Product Name</label>
                                <input type="text" name="name" id="productName" class="form-control" placeholder="Velvet Armchair" required>
                            </div>
                            <div class="form-group">
                                <label for="productCategory">Category</label>
                                <select name="category_id" id="productCategory" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Price ($)</label>
                                <input type="number" name="price" id="productPrice" class="form-control" placeholder="249.99" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="productStock">Stock Quantity</label>
                                <input type="number" name="stock" id="productStock" class="form-control" placeholder="50" required>
                            </div>
                            <div class="form-group">
                                <label for="productImage">Product Image</label>
                                <input type="file" name="image" id="productImage" class="form-control">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="productDescription">Description</label>
                                <textarea name="description" id="productDescription" class="form-control" placeholder="Describe the product..." required></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <button type="submit" name="add_product" class="submit-btn">
                                <i class="fas fa-save"></i> Save Product
                            </button>
                            <button type="button" class="action-btn" id="cancelProductBtn" style="background: #f1f1f1; color: var(--dark);">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Products List -->
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 5px; margin-right: 10px; 
                                            background-image: url('<?php echo htmlspecialchars($product['image']); ?>'); background-size: cover;">
                                        </div>
                                        <div>
                                            <div style="font-weight: 500;"><?php echo htmlspecialchars($product['name']); ?></div>
                                            <div style="font-size: 12px; color: var(--gray);">#PD<?php echo str_pad($product['id'], 3, '0', STR_PAD_LEFT); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $category_name = "Uncategorized";
                                    foreach ($categories as $category) {
                                        if ($category['id'] == $product['category_id']) {
                                            $category_name = $category['name'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($category_name);
                                    ?>
                                </td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $product['stock']; ?></td>
                                <td><span class="status <?php echo $product['stock'] > 10 ? 'completed' : 'pending'; ?>">
                                    <?php echo $product['stock'] > 10 ? 'Active' : 'Low Stock'; ?>
                                </span></td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="content-section" id="categories">
                <div class="section-header">
                    <h3>Category Management</h3>
                    <button class="btn" id="addCategoryBtn"><i class="fas fa-plus"></i> Add Category</button>
                </div>
                
                <!-- Category Form -->
                <div class="category-form" id="categoryForm" style="<?php echo isset($_POST['add_category']) ? 'display:block;' : 'display:none;'; ?> background: #f9f9f9; padding: 25px; border-radius: 10px; margin-bottom: 30px;">
                    <h4 style="margin-bottom: 20px; color: var(--primary);">Add New Category</h4>
                    <?php if (isset($category_success)): ?>
                        <div style="background: rgba(102, 187, 106, 0.15); color: #66bb6a; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                            <i class="fas fa-check-circle"></i> <?php echo $category_success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" name="name" id="categoryName" class="form-control" placeholder="Furniture" required>
                            </div>
                            <div class="form-group">
                                <label for="parentCategory">Parent Category</label>
                                <select name="parent_id" id="parentCategory" class="form-control">
                                    <option value="">None (Main Category)</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="categoryDescription">Description</label>
                                <textarea name="description" id="categoryDescription" class="form-control" placeholder="Describe the category..."></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <button type="submit" name="add_category" class="submit-btn">
                                <i class="fas fa-save"></i> Save Category
                            </button>
                            <button type="button" class="action-btn" id="cancelCategoryBtn" style="background: #f1f1f1; color: var(--dark);">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Categories List -->
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Parent Category</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo htmlspecialchars(substr($category['description'], 0, 50) . '...'); ?></td>
                                <td>
                                    <?php 
                                    $parent_name = "None";
                                    if ($category['parent_id']) {
                                        foreach ($categories as $parent) {
                                            if ($parent['id'] == $category['parent_id']) {
                                                $parent_name = $parent['name'];
                                                break;
                                            }
                                        }
                                    }
                                    echo htmlspecialchars($parent_name);
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $product_count = 0;
                                    foreach ($products as $product) {
                                        if ($product['category_id'] == $category['id']) {
                                            $product_count++;
                                        }
                                    }
                                    echo $product_count;
                                    ?>
                                </td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
                     <div class="content-section" id="orders">
                <div class="section-header">
                    <h3>Order Management</h3>
                    <button class="btn"><i class="fas fa-plus"></i> Export Data</button>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#DE1258</td>
                                <td>Robert Taylor</td>
                                <td>Aug 12, 2025</td>
                                <td>3</td>
                                <td>$845.25</td>
                                <td><span class="status pending">Pending</span></td>
                                <td>
                                    <button class="action-btn">Details</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DE1257</td>
                                <td>Jennifer Lee</td>
                                <td>Aug 11, 2025</td>
                                <td>5</td>
                                <td>$1,420.50</td>
                                <td><span class="status processing">Processing</span></td>
                                <td>
                                    <button class="action-btn">Details</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DE1256</td>
                                <td>David Wilson</td>
                                <td>Aug 10, 2025</td>
                                <td>2</td>
                                <td>$345.99</td>
                                <td><span class="status completed">Completed</span></td>
                                <td>
                                    <button class="action-btn">Details</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DE1255</td>
                                <td>Lisa Anderson</td>
                                <td>Aug 9, 2025</td>
                                <td>1</td>
                                <td>$199.99</td>
                                <td><span class="status completed">Completed</span></td>
                                <td>
                                    <button class="action-btn">Details</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="content-section" id="users">
                <div class="section-header">
                    <h3>User Management</h3>
                    <button class="btn"><i class="fas fa-plus"></i> Add User</button>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=6d4c41&color=fff" alt="User" style="width: 36px; height: 36px; border-radius: 50%; margin-right: 10px;">
                                        <div>
                                            <div style="font-weight: 500;">John Doe</div>
                                        </div>
                                    </div>
                                </td>
                                <td>john@example.com</td>
                                <td>Customer</td>
                                <td><span class="status completed">Active</span></td>
                                <td>Jul 15, 2025</td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Smith&background=7e57c2&color=fff" alt="User" style="width: 36px; height: 36px; border-radius: 50%; margin-right: 10px;">
                                        <div>
                                            <div style="font-weight: 500;">Sarah Smith</div>
                                        </div>
                                    </div>
                                </td>
                                <td>sarah@example.com</td>
                                <td>Customer</td>
                                <td><span class="status completed">Active</span></td>
                                <td>Aug 2, 2025</td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=ff7043&color=fff" alt="User" style="width: 36px; height: 36px; border-radius: 50%; margin-right: 10px;">
                                        <div>
                                            <div style="font-weight: 500;">Mike Johnson</div>
                                        </div>
                                    </div>
                                </td>
                                <td>mike@example.com</td>
                                <td>Admin</td>
                                <td><span class="status completed">Active</span></td>
                                <td>Jun 20, 2025</td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="https://ui-avatars.com/api/?name=Emma+Brown&background=66bb6a&color=fff" alt="User" style="width: 36px; height: 36px; border-radius: 50%; margin-right: 10px;">
                                        <div>
                                            <div style="font-weight: 500;">Emma Brown</div>
                                        </div>
                                    </div>
                                </td>
                                <td>emma@example.com</td>
                                <td>Customer</td>
                                <td><span class="status cancelled">Inactive</span></td>
                                <td>May 12, 2025</td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn delete">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ... [Other sections] ... -->
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Login functionality
        document.getElementById('loginBtn')?.addEventListener('click', function(e) {
            // Form submission handled by PHP
        });
        
        // Navigation functionality
        const navItems = document.querySelectorAll('.nav-item');
        const contentSections = document.querySelectorAll('.content-section');
        
        navItems.forEach(item => {
            if(item.id !== 'logoutBtn') {
                item.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    
                    // Update active nav item
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update active content section
                    contentSections.forEach(section => {
                        section.classList.remove('active');
                        if(section.id === target) {
                            section.classList.add('active');
                            
                            // Update top bar title based on section
                            const sectionTitle = this.querySelector('span').textContent;
                            document.querySelector('.welcome h2').textContent = sectionTitle;
                        }
                    });
                });
            }
        });
        
        // Logout functionality
        document.getElementById('logoutBtn')?.addEventListener('click', function() {
            window.location.href = 'admin.php?logout=true';
        });
        
        // Add Product Form Toggle
        document.getElementById('addProductBtn')?.addEventListener('click', function() {
            const form = document.getElementById('productForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
        
        document.getElementById('cancelProductBtn')?.addEventListener('click', function() {
            document.getElementById('productForm').style.display = 'none';
        });
        
        // Add Category Form Toggle
        document.getElementById('addCategoryBtn')?.addEventListener('click', function() {
            const form = document.getElementById('categoryForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
        
        document.getElementById('cancelCategoryBtn')?.addEventListener('click', function() {
            document.getElementById('categoryForm').style.display = 'none';
        });
    </script>
</body>
</html>