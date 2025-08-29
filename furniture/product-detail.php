<?php
// Start the session for cart functionality
session_start();

// Database connection (update with your credentials)
$host = 'localhost';
$dbname = 'home_decor_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details with category from the database
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found, show error
if (!$product) {
    die("Product not found.");
}

// Fetch related products (same category, excluding current product)
$stmt = $pdo->prepare("
    SELECT p.* 
    FROM products p 
    WHERE p.category_id = ? AND p.id != ? 
    ORDER BY RAND() 
    LIMIT 4
");
$stmt->execute([$product['category_id'], $product_id]);
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
  <meta name="keywords" content="<?php echo htmlspecialchars($product['tags'] ?? ''); ?>" />
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/tiny-slider.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <title><?php echo htmlspecialchars($product['name'] ?? 'Product'); ?> | Furni</title>
  <style>
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
    .product-detail { padding: 4rem 0; }
    .product-image-main {
      height: 500px;
      object-fit: cover;
      border-radius: 15px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .product-image-main:hover { transform: scale(1.02); }
    .product-thumbnails img {
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
      cursor: pointer;
      opacity: 0.7;
      transition: opacity 0.3s ease;
    }
    .product-thumbnails img:hover, .product-thumbnails img.active { opacity: 1; }
    .product-title { font-size: 2.5rem; color: var(--primary); }
    .product-price { font-size: 1.8rem; color: var(--accent); font-weight: 600; }
    .old-price { text-decoration: line-through; color: #999; margin-left: 1rem; }
    .rating { color: var(--accent); }
    .add-to-cart {
      background: var(--primary);
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .add-to-cart:hover { background: var(--secondary); transform: translateY(-3px); }
    .wishlist { color: var(--dark); text-decoration: none; margin-left: 2rem; font-weight: 600; }
    .wishlist i { color: var(--accent); }
    .tab-pane { padding: 2rem 0; }
    .review-item { border-bottom: 1px solid #eee; padding: 1.5rem 0; }
    .review-author { font-weight: 600; color: var(--primary); }
    .related-products .product-item { transition: all 0.3s ease; }
    .related-products .product-item:hover { transform: translateY(-10px); }
  </style>
</head>
<body>
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

  <section class="product-detail">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6">
          <img src="<?php echo htmlspecialchars($product['image'] ?? '../Uploads/'); ?>" class="img-fluid product-image-main" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>">
          <div class="d-flex gap-3 mt-3 product-thumbnails">
            <?php
            $thumbnails = isset($product['thumbnails']) ? explode(',', $product['thumbnails']) : [$product['image']];
            foreach ($thumbnails as $index => $thumb):
            ?>
              <img src="<?php echo htmlspecialchars(trim($thumb)); ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" alt="Thumbnail <?php echo $index + 1; ?>">
            <?php endforeach; ?>
          </div>
        </div>
        <div class="col-lg-6">
          <h1 class="product-title"><?php echo htmlspecialchars($product['name'] ?? 'Product'); ?></h1>
          <div class="d-flex align-items-center mb-3">
            <span class="product-price">$<?php echo number_format($product['price'] ?? 0, 2); ?></span>
            <?php if (!empty($product['old_price'])): ?>
              <span class="old-price">$<?php echo number_format($product['old_price'], 2); ?></span>
            <?php endif; ?>
          </div>
          <div class="rating mb-3">
            <?php
            $rating = floatval($product['rating'] ?? 4.7);
            $full_stars = floor($rating);
            $half_star = $rating - $full_stars >= 0.5 ? 1 : 0;
            for ($i = 1; $i <= 5; $i++):
                if ($i <= $full_stars) {
                    echo '<i class="fas fa-star"></i>';
                } elseif ($half_star && $i == $full_stars + 1) {
                    echo '<i class="fas fa-star-half-alt"></i>';
                } else {
                    echo '<i class="far fa-star"></i>';
                }
            endfor;
            ?>
            <span>(<?php echo $product['review_count'] ?? 0; ?> reviews)</span>
          </div>
          <p class="mb-4"><?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?></p>
          <ul class="mb-4">
            <?php if (!empty($product['material'])): ?>
              <li>Material: <?php echo htmlspecialchars($product['material']); ?></li>
            <?php endif; ?>
            <?php if (!empty($product['dimensions'])): ?>
              <li>Dimensions: <?php echo htmlspecialchars($product['dimensions']); ?></li>
            <?php endif; ?>
            <?php if (!empty($product['weight_capacity'])): ?>
              <li>Weight Capacity: <?php echo htmlspecialchars($product['weight_capacity']); ?></li>
            <?php endif; ?>
            <?php if (!empty($product['assembly'])): ?>
              <li>Assembly: <?php echo htmlspecialchars($product['assembly']); ?></li>
            <?php endif; ?>
            <?php if (!empty($product['color'])): ?>
              <li>Color: <?php echo htmlspecialchars($product['color']); ?></li>
            <?php endif; ?>
          </ul>
          <form action="product-detail.php?id=<?php echo $product_id; ?>" method="post">
            <div class="d-flex align-items-center mb-4">
              <input type="hidden" name="add_to_cart" value="1">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
              <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
              <input type="hidden" name="product_price" value="<?php echo $product['price'] ?? 0; ?>">
              <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image'] ?? ''); ?>">
              <input type="number" name="quantity" value="1" min="1" class="form-control me-3" style="width: 100px;">
              <button type="submit" class="btn add-to-cart">Add to Cart</button>
              <a href="#" class="wishlist"><i class="far fa-heart me-2"></i>Add to Wishlist</a>
            </div>
          </form>
          <p class="mb-2">SKU: <?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></p>
          <p class="mb-2">Category: <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
          <p>Tags: <?php echo htmlspecialchars($product['tags'] ?? 'None'); ?></p>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-12">
          <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab" aria-controls="specs" aria-selected="false">Specifications</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews (<?php echo $product['review_count'] ?? 0; ?>)</button>
            </li>
          </ul>
          <div class="tab-content" id="productTabContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
              <p><?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?></p>
              <?php if (!empty($product['extra_description'])): ?>
                <p><?php echo htmlspecialchars($product['extra_description']); ?></p>
              <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
              <p>Technical Specifications</p>
              <table class="table">
                <tbody>
                  <?php if (!empty($product['material'])): ?>
                    <tr><td>Frame Material</td><td><?php echo htmlspecialchars($product['material']); ?></td></tr>
                  <?php endif; ?>
                  <?php if (!empty($product['upholstery'])): ?>
                    <tr><td>Upholstery</td><td><?php echo htmlspecialchars($product['upholstery']); ?></td></tr>
                  <?php endif; ?>
                  <?php if (!empty($product['dimensions'])): ?>
                    <tr><td>Dimensions</td><td><?php echo htmlspecialchars($product['dimensions']); ?></td></tr>
                  <?php endif; ?>
                  <?php if (!empty($product['seat_height'])): ?>
                    <tr><td>Seat Height</td><td><?php echo htmlspecialchars($product['seat_height']); ?></td></tr>
                  <?php endif; ?>
                  <?php if (!empty($product['weight_capacity'])): ?>
                    <tr><td>Weight Capacity</td><td><?php echo htmlspecialchars($product['weight_capacity']); ?></td></tr>
                  <?php endif; ?>
                  <?php if (!empty($product['assembly'])): ?>
                    <tr><td>Assembly</td><td><?php echo htmlspecialchars($product['assembly']); ?></td></tr>
                  <?php endif; ?>
                  <?php if (!empty($product['warranty'])): ?>
                    <tr><td>Warranty</td><td><?php echo htmlspecialchars($product['warranty']); ?></td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
              <p>Customer Reviews</p>
              <?php
              $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ?");
              $stmt->execute([$product_id]);
              $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
              ?>
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <h4><?php echo number_format($product['rating'] ?? 4.7, 1); ?></h4>
                  <div class="rating">
                    <?php
                    $rating = floatval($product['rating'] ?? 4.7);
                    $full_stars = floor($rating);
                    $half_star = $rating - $full_stars >= 0.5 ? 1 : 0;
                    for ($i = 1; $i <= 5; $i++):
                        if ($i <= $full_stars) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif ($half_star && $i == $full_stars + 1) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    endfor;
                    ?>
                  </div>
                  <p><?php echo count($reviews); ?> reviews</p>
                </div>
              </div>
              <div class="review-form mb-5">
                <h5>Write a Review</h5>
                <form action="submit_review.php" method="post">
                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                  <div class="mb-3">
                    <label class="form-label">Your Rating</label>
                    <div class="rating-input">
                      <input type="radio" name="rating" value="5" id="star5"><label for="star5"><i class="far fa-star"></i></label>
                      <input type="radio" name="rating" value="4" id="star4"><label for="star4"><i class="far fa-star"></i></label>
                      <input type="radio" name="rating" value="3" id="star3"><label for="star3"><i class="far fa-star"></i></label>
                      <input type="radio" name="rating" value="2" id="star2"><label for="star2"><i class="far fa-star"></i></label>
                      <input type="radio" name="rating" value="1" id="star1"><label for="star1"><i class="far fa-star"></i></label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Review</label>
                    <textarea name="review" class="form-control" rows="4" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
              </div>
              <?php if (empty($reviews)): ?>
                <p>No reviews yet. Be the first to review this product!</p>
              <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                  <div class="review-item">
                    <div class="d-flex align-items-center mb-2">
                      <h5 class="review-author me-3"><?php echo htmlspecialchars($review['name']); ?></h5>
                      <div class="rating">
                        <?php
                        $review_rating = intval($review['rating']);
                        for ($i = 1; $i <= 5; $i++):
                            if ($i <= $review_rating) {
                                echo '<i class="fas fa-star"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        endfor;
                        ?>
                      </div>
                    </div>
                    <p><?php echo date('F d, Y', strtotime($review['created_at'])); ?></p>
                    <p><?php echo htmlspecialchars($review['review']); ?></p>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-12">
          <h2>You May Also Like</h2>
          <div class="row related-products g-4">
            <?php foreach ($related_products as $related): ?>
              <div class="col-md-3">
                <a class="product-item" href="product-detail.php?id=<?php echo $related['id']; ?>">
                  <img src="<?php echo htmlspecialchars($related['image'] ?? 'images/default.png'); ?>" class="img-fluid product-thumbnail">
                  <h3 class="product-title"><?php echo htmlspecialchars($related['name'] ?? 'Product'); ?></h3>
                  <strong class="product-price">$<?php echo number_format($related['price'] ?? 0, 2); ?></strong>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer-section">
    <div class="container relative">
      <!-- Footer content -->
    </div>
  </footer>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
  <script>
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