<?php
// Start the session for cart functionality
// Start the session for cart functionality
session_start();

// Database connection (update with your credentials)
$host = 'localhost';
$dbname = 'home_decor_db';
$username = 'root';
$password = '';

// Define static products (replace with your actual static data)
$static_products = [
    1 => [
        'id' => 1,
        'name' => 'Modern Chair',
        'price' => 658.00,
        'old_price' => 799.00,
        'image' => 'images/chair1.png',
        'category' => 'Chairs',
        'description' => 'A luxurious modern chair with leather upholstery.',
        'material' => 'Leather',
        'dimensions' => '24"W x 26"D x 33"H',
        'weight_capacity' => '300 lbs',
        'rating' => 4.8,
        'review_count' => 15
    ],
    2 => [
        'id' => 2,
        'name' => 'Wooden Table',
        'price' => 1299.00,
        'image' => 'images/table1.png',
        'category' => 'Tables',
        'description' => 'Sturdy wooden table with a minimalist design.',
        'material' => 'Oak Wood',
        'dimensions' => '60"W x 30"D x 29"H',
        'weight_capacity' => '500 lbs',
        'rating' => 4.5,
        'review_count' => 10
    ],
    3 => [
        'id' => 3,
        'name' => 'Sofa Set',
        'price' => 2499.00,
        'old_price' => 2999.00,
        'image' => 'images/sofa1.png',
        'category' => 'Sofas',
        'description' => 'Comfortable 3-seater sofa with plush cushions.',
        'material' => 'Fabric',
        'dimensions' => '78"W x 35"D x 36"H',
        'weight_capacity' => '800 lbs',
        'rating' => 4.7,
        'review_count' => 20
    ]
];




try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found, show error
if (!$product) {
    die("Product not found.");
}

// Fetch related products (use random products instead of category)
$stmt = $pdo->prepare("SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 4");
$stmt->execute([$product_id]);
$related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle add-to-cart form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $product_id = $product['id'];
    $product_name = $product['name'];
    $product_price = floatval($product['price']);
    $product_image = $product['image'];

    // Add to cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => $quantity
        ];
    }

    // Redirect to cart page
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">
    <meta name="description" content="<?php echo htmlspecialchars($product['description'] ?? ''); ?>" />
    <meta name="keywords" content="furniture, home decor" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="css/tiny-slider.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <title><?php echo htmlspecialchars($product['name'] ?? 'Product'); ?> | Furni</title>
    <style>
        /* Existing styles remain unchanged */
        :root {
      --primary: #6d4c41;
      --secondary: #7e57c2;
      --dark: #263238;
      --light: #f5f5f5;
      --accent: #d4af37;
    }
    body {
      font-family: 'Montserrat', sans-serif;
      background: var(--light);
      color: var(--dark);
    }
    h1, h2, h3 {
      font-family: 'Playfair Display', serif;
    }
        .product-image-main {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .product-thumbnails img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border: 2px solid transparent;
            border-radius: 5px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        .product-thumbnails img.active {
            border-color: #007bff;
        }
        .product-thumbnails img:hover {
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navbar (unchanged, assume it's included or static) -->
     <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.php">Furni<span>.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li><a class="nav-link" href="index.php">Home</a></li>
          <li class="active"><a class="nav-link" href="shop.php">Shop</a></li>
          <li><a class="nav-link" href="about.php">About us</a></li>
          <li><a class="nav-link" href="services.php">Services</a></li>
          <li><a class="nav-link" href="blog.php">Blog</a></li>
          <li><a class="nav-link" href="contact.php">Contact us</a></li>
        </ul>
        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          <li><a class="nav-link" href="#"><img src="images/user.svg"></a></li>
          <li><a class="nav-link" href="cart.php"><img src="images/cart.svg"></a></li>
        </ul>
      </div>
    </div>
  </nav>
    <!-- Start Product Detail -->
    <section class="product-detail py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <?php
                    $image_path = $product['image'] ?? 'images/default.png';
                    echo "<!-- Debug: Image path: " . htmlspecialchars($image_path) . " -->"; // Remove after testing
                    ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>" class="img-fluid product-image-main" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>">
                    <div class="d-flex gap-3 mt-3 product-thumbnails">
                        <?php
                        $thumbnails = [$image_path]; // Static products have one image for simplicity
                        foreach ($thumbnails as $index => $thumb):
                        ?>
                            <img src="<?php echo htmlspecialchars($thumb); ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" alt="Thumbnail <?php echo $index + 1; ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h1 class="product-title text-3xl font-bold mb-3"><?php echo htmlspecialchars($product['name'] ?? 'Product'); ?></h1>
                    <div class="d-flex align-items-center mb-3">
                        <span class="product-price text-2xl font-semibold text-gray-900">$<?php echo number_format($product['price'] ?? 0, 2); ?></span>
                        <?php if (isset($product['old_price'])): ?>
                            <span class="old-price text-xl text-gray-500 line-through ml-2">$<?php echo number_format($product['old_price'], 2); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="rating mb-3">
                        <?php
                        $rating = floatval($product['rating'] ?? 0);
                        $full_stars = floor($rating);
                        $half_star = $rating - $full_stars >= 0.5 ? 1 : 0;
                        for ($i = 1; $i <= 5; $i++):
                            if ($i <= $full_stars) {
                                echo '<i class="fas fa-star text-yellow-400"></i>';
                            } elseif ($half_star && $i == $full_stars + 1) {
                                echo '<i class="fas fa-star-half-alt text-yellow-400"></i>';
                            } else {
                                echo '<i class="far fa-star text-gray-300"></i>';
                            }
                        endfor;
                        ?>
                        <span class="ml-2 text-gray-600">(<?php echo $product['review_count'] ?? 0; ?> reviews)</span>
                    </div>
                    <p class="mb-4 text-gray-700"><?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?></p>
                    <ul class="mb-4 text-gray-600">
                        <?php if (isset($product['material'])): ?>
                            <li>Material: <?php echo htmlspecialchars($product['material']); ?></li>
                        <?php endif; ?>
                        <?php if (isset($product['dimensions'])): ?>
                            <li>Dimensions: <?php echo htmlspecialchars($product['dimensions']); ?></li>
                        <?php endif; ?>
                        <?php if (isset($product['weight_capacity'])): ?>
                            <li>Weight Capacity: <?php echo htmlspecialchars($product['weight_capacity']); ?></li>
                        <?php endif; ?>
                    </ul>
                    <form action="product-detail.php?id=<?php echo $product_id; ?>" method="post" class="mb-4">
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="add_to_cart" value="1">
                            <input type="number" name="quantity" value="1" min="1" class="form-control w-20 mr-3 p-2 border rounded" style="max-width: 100px;">
                            <button type="submit" class="btn btn-primary bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Add to Cart</button>
                            <a href="#" class="wishlist ml-3 text-blue-600 hover:underline"><i class="far fa-heart mr-1"></i>Add to Wishlist</a>
                        </div>
                    </form>
                    <p class="mb-2 text-gray-600">Category: <?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></p>
                </div>
            </div>

            <!-- Related Products (Static for now) -->
            <div class="row mt-5">
                <h2 class="text-2xl font-bold mb-4">You May Also Like</h2>
                <div class="row">
                    <?php
                    $related_products = array_filter($static_products, fn($p) => $p['id'] != $product_id);
                    $related_products = array_slice(array_values($related_products), 0, 4); // Get up to 4 related
                    foreach ($related_products as $related):
                    ?>
                        <div class="col-6 col-md-3 mb-4">
                            <a href="product-detail.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                <img src="<?php echo htmlspecialchars($related['image'] ?? 'images/default.png'); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($related['name'] ?? 'Product'); ?>" style="height: 150px; object-fit: cover;">
                                <h4 class="mt-2 text-gray-800"><?php echo htmlspecialchars($related['name'] ?? 'Product'); ?></h4>
                                <p class="text-gray-600">$<?php echo number_format($related['price'] ?? 0, 2); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer and scripts (unchanged, assume included) -->
    <footer class="footer-section">
    <div class="container relative">
      <!-- Footer content -->
    </div>
  </footer>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
  <script>
    // Thumbnail gallery functionality
    const thumbnails = document.querySelectorAll('.product-thumbnails img');
    const mainImage = document.querySelector('.product-image-main');
    thumbnails.forEach(thumb => {
      thumb.addEventListener('click', function() {
        thumbnails.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        mainImage.src = this.src;
      });
    });
  </script>
</body>
</html>