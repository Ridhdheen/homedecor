<?php
// Start session
session_start();

// Include database connection
require_once 'db_connection.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['admin_id']);
if (!$isLoggedIn) {
    header("Location: admin-login.php");
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-login.php");
    exit;
}

// Handle product delete
if (isset($_GET['delete_product'])) {
    $productId = (int)$_GET['delete_product'];
    deleteProduct($productId);
    header("Location: index.php?section=products");
    exit;
}

// Handle category delete
if (isset($_GET['delete_category'])) {
    $categoryId = (int)$_GET['delete_category'];
    deleteCategory($categoryId);
    header("Location: index.php?section=categories");
    exit;
}

// Handle product edit form submission
if (isset($_POST['edit_product'])) {
    $productData = [
        'id' => $_POST['productId'] ?? 0,
        'name' => $_POST['productName'] ?? '',
        'category_id' => $_POST['productCategory'] ?? '',
        'price' => $_POST['productPrice'] ?? 0,
        'stock' => $_POST['productStock'] ?? 0,
        'description' => $_POST['productDescription'] ?? '',
        'image' => $_POST['currentImage'] ?? 'placeholder.jpg'
    ];
    updateProduct($productData);
    header("Location: index.php?section=products");
    exit;
}

// Handle category edit form submission
if (isset($_POST['edit_category'])) {
    $categoryData = [
        'id' => $_POST['categoryId'] ?? 0,
        'name' => $_POST['categoryName'] ?? '',
        'description' => $_POST['categoryDescription'] ?? ''
    ];
    updateCategory($categoryData);
    header("Location: index.php?section=categories");
    exit;
}

// Handle subcategory edit form submission
if (isset($_POST['edit_subcategory'])) {
    $subcategoryData = [
        'id' => $_POST['subcategoryId'] ?? 0,
        'name' => $_POST['subcategoryName'] ?? '',
        'parent_id' => $_POST['parentCategory'] ?? null,
        'description' => $_POST['subcategoryDescription'] ?? ''
    ];
    updateCategory($subcategoryData);
    header("Location: index.php?section=categories");
    exit;
}

// Handle add product form submission
if (isset($_POST['add_product'])) {
    $imagePath = 'placeholder.jpg';
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imagePath = $targetDir . basename($_FILES["productImage"]["name"]);
        move_uploaded_file($_FILES["productImage"]["tmp_name"], $imagePath);
    }
    
    $productData = [
        'name' => $_POST['productName'] ?? '',
        'category_id' => $_POST['productCategory'] ?? '',
        'price' => $_POST['productPrice'] ?? 0,
        'stock' => $_POST['productStock'] ?? 0,
        'description' => $_POST['productDescription'] ?? '',
        'image' => $imagePath
    ];
    addProduct($productData);
    $product_success = "Product added successfully!";
}

// Handle add category form submission
if (isset($_POST['add_category'])) {
    $categoryData = [
        'name' => $_POST['categoryName'] ?? '',
        'description' => $_POST['categoryDescription'] ?? '',
        'parent_id' => null
    ];
    addCategory($categoryData);
    $category_success = "Category added successfully!";
}

// Handle add subcategory form submission
if (isset($_POST['add_subcategory'])) {
    $subcategoryData = [
        'name' => $_POST['subcategoryName'] ?? '',
        'description' => $_POST['subcategoryDescription'] ?? '',
        'parent_id' => $_POST['parentCategory'] ?? null
    ];
    addCategory($subcategoryData);
    $subcategory_success = "Subcategory added successfully!";
}

// Get current section from URL
$current_section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

// Fetch products and categories for display
$products = getProducts();
$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content remains the same as before -->
    <!-- ... -->
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header animate__animated animate__fadeIn">
            <h3>Home Décor</h3>
            <p>Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <li class="active animate__animated animate__fadeInLeft">
                <a href="#dashboard" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-1s">
                <a href="#orders" data-section="orders"><i class="fas fa-shopping-bag"></i> Orders</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-2s">
                <a href="#products" data-section="products"><i class="fas fa-box-open"></i> Products</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-3s">
                <a href="#categories" data-section="categories"><i class="fas fa-list"></i> Categories</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-4s">
                <a href="#users" data-section="users"><i class="fas fa-users"></i> Users</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-5s">
                <a href="#payments" data-section="payments"><i class="fas fa-credit-card"></i> Payments</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-6s">
                <a href="#feedback" data-section="feedback"><i class="fas fa-comment-alt"></i> Feedback</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-7s">
                <a href="#settings" data-section="settings"><i class="fas fa-cog"></i> Settings</a>
            </li>
            <li class="animate__animated animate__fadeInLeft animate__delay-8s">
                <a href="?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="top-navbar navbar navbar-expand-lg navbar-light bg-white">
            <div class="container-fluid">
                <button class="btn btn-sm d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_name'] ?? 'Admin User'); ?>&background=6c5ce7&color=fff" alt="Admin User">
                        <div>
                            <h6 class="mb-0"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin User'); ?></h6>
                            <small class="text-muted">Administrator</small>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <!-- Dashboard Section -->
                       <div class="content-section" id="dashboard" style="display: block;">
                <div class="row mb-4">
                    <div class="col-md-3 fade-in">
                        <div class="dashboard-card card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="card-text">1,245</h2>
                                <p class="card-text"><small>+12% from last month</small></p>
                                <i class="fas fa-shopping-bag card-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 fade-in animate__delay-1s">
                        <div class="dashboard-card card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Revenue</h5>
                                <h2 class="card-text">$34,567</h2>
                                <p class="card-text"><small>+8% from last month</small></p>
                                <i class="fas fa-dollar-sign card-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 fade-in animate__delay-2s">
                        <div class="dashboard-card card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <h2 class="card-text">187</h2>
                                <p class="card-text"><small>15 new this month</small></p>
                                <i class="fas fa-box-open card-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 fade-in animate__delay-3s">
                        <div class="dashboard-card card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Pending Orders</h5>
                                <h2 class="card-text">24</h2>
                                <p class="card-text"><small>5 high priority</small></p>
                                <i class="fas fa-exclamation-circle card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row slide-in">
                    <div class="col-12">
                        <div class="data-table card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Recent Orders</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#ORD-2023-001</td>
                                                <td>John Doe</td>
                                                <td>2023-06-15</td>
                                                <td>$245.00</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#ORD-2023-002</td>
                                                <td>Jane Smith</td>
                                                <td>2023-06-14</td>
                                                <td>$189.50</td>
                                                <td><span class="badge bg-warning text-dark">Processing</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#ORD-2023-003</td>
                                                <td>Robert Johnson</td>
                                                <td>2023-06-14</td>
                                                <td>$320.75</td>
                                                <td><span class="badge bg-danger">Pending</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#ORD-2023-004</td>
                                                <td>Emily Davis</td>
                                                <td>2023-06-13</td>
                                                <td>$145.00</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#ORD-2023-005</td>
                                                <td>Michael Brown</td>
                                                <td>2023-06-12</td>
                                                <td>$275.50</td>
                                                <td><span class="badge bg-warning text-dark">Processing</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Products Section -->
            <div class="content-section" id="products" style="display: <?php echo $current_section === 'products' ? 'block' : 'none'; ?>;">
                <div class="row">
                     <div class="content-section" id="products" style="display: none;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="admin-form slide-in">
                            <h5>Add New Product</h5>
                            <?php if (isset($product_success)): ?>
                                <div class="alert alert-success"><?php echo $product_success; ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Product Name</label>
                                    <input type="text" name="productName" class="form-control" id="productName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productCategory" class="form-label">Category</label>
                                    <select name="productCategory" class="form-select" id="productCategory" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <?php if (!$category['parent_id']): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Price</label>
                                    <input type="number" name="productPrice" class="form-control" id="productPrice" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productStock" class="form-label">Stock Quantity</label>
                                    <input type="number" name="productStock" class="form-control" id="productStock" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productDescription" class="form-label">Description</label>
                                    <textarea name="productDescription" class="form-control" id="productDescription" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="productImage" class="form-label">Product Image</label>
                                    <input class="form-control" type="file" name="productImage" id="productImage">
                                </div>
                                <button type="submit" name="add_product" class="btn btn-primary">Save Product</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="data-table card slide-in">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
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
                                                <td><?php echo $product['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo $product['image'] ?: 'https://via.placeholder.com/40'; ?>" alt="Product" class="rounded me-2">
                                                        <span><?php echo htmlspecialchars($product['name']); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $category = array_filter($categories, function($cat) use ($product) { 
                                                        return $cat['id'] == $product['category_id']; 
                                                    });
                                                    echo htmlspecialchars(reset($category)['name'] ?? 'Unknown');
                                                    ?>
                                                </td>
                                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                <td><?php echo $product['stock']; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $product['stock'] > 0 ? ($product['stock'] <= 10 ? 'bg-warning text-dark' : 'bg-success') : 'bg-danger'; ?>">
                                                        <?php echo $product['stock'] > 0 ? ($product['stock'] <= 10 ? 'Low Stock' : 'In Stock') : 'Out of Stock'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-product-btn" 
                                                            data-id="<?php echo $product['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                            data-category="<?php echo $product['category_id']; ?>"
                                                            data-price="<?php echo $product['price']; ?>"
                                                            data-stock="<?php echo $product['stock']; ?>"
                                                            data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                                            data-image="<?php echo $product['image']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?delete_product=<?php echo $product['id']; ?>&section=products" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="content-section" id="categories" style="display: <?php echo $current_section === 'categories' ? 'block' : 'none'; ?>;">
                <div class="row">
                     <!-- Categories Section -->
            <div class="content-section" id="categories" style="display: none;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="admin-form slide-in">
                            <h5>Add New Category</h5>
                            <?php if (isset($category_success)): ?>
                                <div class="alert alert-success"><?php echo $category_success; ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Category Name</label>
                                    <input type="text" name="categoryName" class="form-control" id="categoryName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="categoryDescription" class="form-label">Description</label>
                                    <textarea name="categoryDescription" class="form-control" id="categoryDescription" rows="3"></textarea>
                                </div>
                                <button type="submit" name="add_category" class="btn btn-primary">Save Category</button>
                            </form>
                        </div>
                        <div class="admin-form slide-in mt-4">
                            <h5>Add New Subcategory</h5>
                            <?php if (isset($subcategory_success)): ?>
                                <div class="alert alert-success"><?php echo $subcategory_success; ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="subcategoryName" class="form-label">Subcategory Name</label>
                                    <input type="text" name="subcategoryName" class="form-control" id="subcategoryName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="parentCategory" class="form-label">Parent Category</label>
                                    <select name="parentCategory" class="form-select" id="parentCategory" required>
                                        <option value="">Select Parent Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <?php if (!$category['parent_id']): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="subcategoryDescription" class="form-label">Description</label>
                                    <textarea name="subcategoryDescription" class="form-control" id="subcategoryDescription" rows="3"></textarea>
                                </div>
                                <button type="submit" name="add_subcategory" class="btn btn-primary">Save Subcategory</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="data-table card slide-in">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Categories & Subcategories</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Parent Category</th>
                                                <th>Products</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($category['parent_id']) {
                                                        $parent = array_filter($categories, function($cat) use ($category) { 
                                                            return $cat['id'] == $category['parent_id']; 
                                                        });
                                                        echo htmlspecialchars(reset($parent)['name'] ?? 'None');
                                                    } else {
                                                        echo 'None';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $category['product_count'] ?? 0; ?></td>
                                                <td><span class="badge bg-success">Active</span></td>
                                                <td>
                                                    <?php if ($category['parent_id']): ?>
                                                        <button class="btn btn-sm btn-outline-primary edit-subcategory-btn" 
                                                                data-id="<?php echo $category['id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($category['name']); ?>"
                                                                data-parent="<?php echo $category['parent_id']; ?>"
                                                                data-description="<?php echo htmlspecialchars($category['description']); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-primary edit-category-btn" 
                                                                data-id="<?php echo $category['id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($category['name']); ?>"
                                                                data-description="<?php echo htmlspecialchars($category['description']); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="?delete_category=<?php echo $category['id']; ?>&section=categories" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other sections (Orders, Users, Payments, Feedback, Settings) remain the same -->
            <!-- ... -->

            <!-- Edit Product Modal -->
            <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="editProductForm">
                                <input type="hidden" name="productId" id="editProductId">
                                <input type="hidden" name="currentImage" id="editCurrentImage">
                                <div class="mb-3">
                                    <label for="editProductName" class="form-label">Product Name</label>
                                    <input type="text" name="productName" class="form-control" id="editProductName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editProductCategory" class="form-label">Category</label>
                                    <select name="productCategory" class="form-select" id="editProductCategory" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <?php if (!$category['parent_id']): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editProductPrice" class="form-label">Price</label>
                                    <input type="number" name="productPrice" class="form-control" id="editProductPrice" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editProductStock" class="form-label">Stock Quantity</label>
                                    <input type="number" name="productStock" class="form-control" id="editProductStock" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editProductDescription" class="form-label">Description</label>
                                    <textarea name="productDescription" class="form-control" id="editProductDescription" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editProductImage" class="form-label">Product Image</label>
                                    <input class="form-control" type="file" name="productImage" id="editProductImage">
                                </div>
                                <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Category Modal -->
            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="editCategoryForm">
                                <input type="hidden" name="categoryId" id="editCategoryId">
                                <div class="mb-3">
                                    <label for="editCategoryName" class="form-label">Category Name</label>
                                    <input type="text" name="categoryName" class="form-control" id="editCategoryName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editCategoryDescription" class="form-label">Description</label>
                                    <textarea name="categoryDescription" class="form-control" id="editCategoryDescription" rows="3"></textarea>
                                </div>
                                <button type="submit" name="edit_category" class="btn btn-primary">Update Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Subcategory Modal -->
            <div class="modal fade" id="editSubcategoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Subcategory</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="editSubcategoryForm">
                                <input type="hidden" name="subcategoryId" id="editSubcategoryId">
                                <div class="mb-3">
                                    <label for="editSubcategoryName" class="form-label">Subcategory Name</label>
                                    <input type="text" name="subcategoryName" class="form-control" id="editSubcategoryName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editParentCategory" class="form-label">Parent Category</label>
                                    <select name="parentCategory" class="form-select" id="editParentCategory" required>
                                        <option value="">Select Parent Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <?php if (!$category['parent_id']): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editSubcategoryDescription" class="form-label">Description</label>
                                    <textarea name="subcategoryDescription" class="form-control" id="editSubcategoryDescription" rows="3"></textarea>
                                </div>
                                <button type="submit" name="edit_subcategory" class="btn btn-primary">Update Subcategory</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Navigation functionality
        const navLinks = document.querySelectorAll('.sidebar-menu a');
        const contentSections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                if (section) {
                    // Update active menu item
                    navLinks.forEach(l => l.parentElement.classList.remove('active'));
                    this.parentElement.classList.add('active');

                    // Show selected section
                    contentSections.forEach(s => s.style.display = 'none');
                    document.getElementById(section).style.display = 'block';
                    
                    // Update URL
                    history.pushState(null, null, `?section=${section}`);
                }
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Product edit modal handling
        document.querySelectorAll('.edit-product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
                document.getElementById('editProductId').value = this.dataset.id;
                document.getElementById('editProductName').value = this.dataset.name;
                document.getElementById('editProductCategory').value = this.dataset.category;
                document.getElementById('editProductPrice').value = this.dataset.price;
                document.getElementById('editProductStock').value = this.dataset.stock;
                document.getElementById('editProductDescription').value = this.dataset.description;
                document.getElementById('editCurrentImage').value = this.dataset.image;
                modal.show();
            });
        });

        // Category edit modal handling
        document.querySelectorAll('.edit-category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                document.getElementById('editCategoryId').value = this.dataset.id;
                document.getElementById('editCategoryName').value = this.dataset.name;
                document.getElementById('editCategoryDescription').value = this.dataset.description;
                modal.show();
            });
        });

        // Subcategory edit modal handling
        document.querySelectorAll('.edit-subcategory-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('editSubcategoryModal'));
                document.getElementById('editSubcategoryId').value = this.dataset.id;
                document.getElementById('editSubcategoryName').value = this.dataset.name;
                document.getElementById('editParentCategory').value = this.dataset.parent;
                document.getElementById('editSubcategoryDescription').value = this.dataset.description;
                modal.show();
            });
        });

        // Show current section on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section') || 'dashboard';
            
            // Update active menu item
            navLinks.forEach(l => {
                if (l.getAttribute('data-section') === section) {
                    l.parentElement.classList.add('active');
                } else {
                    l.parentElement.classList.remove('active');
                }
            });
            
            // Show selected section
            contentSections.forEach(s => {
                s.style.display = s.id === section ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>