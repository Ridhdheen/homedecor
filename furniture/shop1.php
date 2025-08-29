<?php
// Start session and include database connection
session_start();
require_once 'db_connection.php';

// Get all active categories (top-level only)
$categories = getCategories(); // Returns top-level categories where parent_id is NULL

// Get all active products
$products = getProducts(); // Returns all products with category and subcategory names

// Handle category and subcategory filtering
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'all';
$selected_subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : 'all';

// Get subcategories for the selected category
if (isset($_GET['category_id'])) {
    $subcategories = getCategories($_GET['category_id']);
    echo json_encode($subcategories);
} else {
    echo json_encode([]);
}// Returns subcategories for the selected category

// Filter products based on category and subcategory
$filtered_products = $products;

if ($selected_category !== 'all') {
    $filtered_products = array_filter($products, function($product) use ($selected_category) {
        return isset($product['category_id']) && $product['category_id'] == $selected_category;
    });
}

if ($selected_subcategory !== 'all') {
    $filtered_products = array_filter($filtered_products, function($product) use ($selected_subcategory) {
        return isset($product['subcategory_id']) && $product['subcategory_id'] == $selected_subcategory;
    });
}
?>

<!doctype html>
<html lang="en">
<head>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/tiny-slider.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Shop | Furni</title>
  <style>
    /* Existing styles remain unchanged */
    .product-item {
      position: relative;
      display: block;
      margin-bottom: 30px;
      transition: all 0.3s ease;
    }
    .product-item:hover {
      transform: translateY(-5px);
    }
    .product-thumbnail {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .product-title {
      font-size: 1.1rem;
      margin-bottom: 5px;
      color: #2f2f2f;
    }
    .product-price {
      color: #2f2f2f;
      font-weight: 600;
    }
    .icon-cross {
      position: absolute;
      top: 10px;
      right: 10px;
      background: white;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: all 0.3s ease;
    }
    .product-item:hover .icon-cross {
      opacity: 1;
    }
    .category-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 20;
      list-style: none;
      margin-bottom: 30px;
    }
    .category-list li {
      margin: 0 10px 10px;
    }
    .category-list a {
      display: block;
      padding: 8px 20px;
      background: #f8f9fa;
      color: #2f2f2f;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .category-list li.active a,
    .category-list a:hover {
      background: #2f2f2f;
      color: white;
    }
    /* New styles for subcategory list */
    .subcategory-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 0;
      list-style: none;
      margin-bottom: 30px;
      margin-top: 15px;
    }
    .subcategory-list li {
      margin: 0 8px 8px;
    }
    .subcategory-list a {
      display: block;
      padding: 6px 16px;
      background: #f8f9fa;
      color: #2f2f2f;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }
    .subcategory-list li.active a,
    .subcategory-list a:hover {
      background: #4a4a4a;
      color: white;
    }
  </style>
</head>

<body>
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.php ">Furni<span>.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="active"><a class="nav-link" href="shop.php">Shop</a></li>
          <li><a class="nav-link" href="about.php">About us</a></li>
          <li><a class="nav-link" href="services.php">Services</a></li>
          <li><a class="nav-link" href="blog.php">Blog</a></li>
          <li><a class="nav-link" href="contact.php">Contact us</a></li>
        </ul>
        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
   <?php if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
            <li><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt" title="Logout"></i></a></li>
          <?php else: ?>
        <li><a class="nav-link" href="login.php"><img src="images/user.svg"></a></li>
    <?php endif; ?>
    <li><a class="nav-link" href="cart.php"><img src="images/cart.svg"></a></li>
</ul>
      </div>
    </div>
  </nav>
  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Shop</h1>
            <p class="mb-4">Discover our premium collection of furniture designed to elevate your living space.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Start Category Filter -->
  <div class="category-section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <ul class="category-list">
            <li class="<?php echo $selected_category === 'all' ? 'active' : ''; ?>">
              <a href="?category=all" data-filter="all">All</a>
            </li>
            <?php foreach ($categories as $category): ?>
              <li class="<?php echo $selected_category == $category['id'] ? 'active' : ''; ?>">
                <a href="?category=<?php echo $category['id']; ?>" data-filter="<?php echo $category['id']; ?>">
                  <?php echo htmlspecialchars($category['name']); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <!-- Start Subcategory Filter -->
      <?php if (!empty($subcategories) && $selected_category !== 'all'): ?>
        <div class="row">
          <div class="col-12">
            <ul class="subcategory-list">
              <li class="<?php echo $selected_subcategory === 'all' ? 'active' : ''; ?>">
                <a href="?category=<?php echo $selected_category; ?>&subcategory=all" data-filter="all">All</a>
              </li>
              <?php foreach ($subcategories as $subcategory): ?>
                <li class="<?php echo $selected_subcategory == $subcategory['id'] ? 'active' : ''; ?>">
                  <a href="?category=<?php echo $selected_category; ?>&subcategory=<?php echo $subcategory['id']; ?>" data-filter="<?php echo $subcategory['id']; ?>">
                    <?php echo htmlspecialchars($subcategory['name']); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
      <!-- End Subcategory Filter -->
    </div>
  </div>
  <!-- End Category Filter -->

  <!-- Start Product Section -->
  <!-- Start Product Section -->
<div class="untree_co-section product-section before-footer-section">
  <div class="container">
    <div class="row">
      <?php if (empty($filtered_products)): ?>
        <div class="col-12 text-center py-5">
          <h4>No products found in this category</h4>
          <a href="?category=all" class="btn btn-primary">View All Products</a>
        </div>
      <?php else: ?>
        <?php foreach ($filtered_products as $product): ?>
          <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="<?php echo $product['category_id']; ?>" data-subcategory="<?php echo $product['subcategory_id'] ?? ''; ?>">
            <a class="product-item" href="product-detail.php?id=<?php echo $product['id']; ?>">
              <img src="<?php echo htmlspecialchars($product['image'] ?? ''); ?>"    
                   class="img-fluid product-thumbnail" 
                   alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>">
              <h3 class="product-title"><?php echo htmlspecialchars($product['name'] ?? 'Unnamed Product'); ?></h3>
              <strong cla
              ss="product-price">$<?php echo number_format($product['price'] ?? 0, 2); ?></strong>
              <span class="icon-cross">
                <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
              </span>
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- End Product Section -->
  <!-- End Product Section -->

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
  <script>
    // Category and subcategory filter functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Highlight selected category
      const currentCategory = '<?php echo $selected_category; ?>';
      if (currentCategory !== 'all') {
        const activeLink = document.querySelector(`.category-list a[data-filter="${currentCategory}"]`);
        if (activeLink) {
          activeLink.parentElement.classList.add('active');
          document.querySelector('.category-list a[data-filter="all"]').parentElement.classList.remove('active');
        }
      }

      // Highlight selected subcategory
      const currentSubcategory = '<?php echo $selected_subcategory; ?>';
      if (currentSubcategory !== 'all') {
        const activeSubLink = document.querySelector(`.subcategory-list a[data-filter="${currentSubcategory}"]`);
        if (activeSubLink) {
          activeSubLink.parentElement.classList.add('active');
          document.querySelector('.subcategory-list a[data-filter="all"]').parentElement.classList.remove('active');
        }
      }

      // Add to cart functionality
      document.querySelectorAll('.icon-cross').forEach(icon => {
        icon.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          const productId = this.closest('.product-item').getAttribute('href').split('id=')[1];
          // AJAX call to add to cart
          fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Product added to cart!');
            } else {
              alert('Error: ' + data.message);
            }
          });
        });
      });
    });
  </script>
</body>
</html>