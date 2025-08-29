<?php
session_start();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/tiny-slider.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    .checkout-section {
      padding: 60px 0;
      background-color: #f8f9fa;
    }
    .checkout-card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }
    .checkout-card h3 {
      font-weight: 600;
      color: #2c2c2c;
    }
    .form-control {
      border-radius: 8px;
      border: 1px solid #ced4da;
      padding: 12px;
      transition: border-color 0.3s ease;
    }
    .form-control:focus {
      border-color: #3b5d50;
      box-shadow: 0 0 0 0.2rem rgba(59, 93, 80, 0.25);
    }
    .btn-primary {
      background-color: #3b5d50;
      border-color: #3b5d50;
      border-radius: 8px;
      padding: 12px 30px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #2c2c2c;
      border-color: #2c2c2c;
    }
    .order-summary {
      background-color: #3b5d50;
      color: #fff;
      border-radius: 10px;
      padding: 30px;
    }
    .order-summary h4 {
      font-weight: 600;
    }
    .order-summary .list-unstyled li {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 15px;
      margin-bottom: 15px;
    }
    .order-summary .total {
      font-size: 1.25rem;
      font-weight: 700;
    }
    .alert {
      border-radius: 8px;
    }
  </style>
  <title>Checkout - Furni</title>
</head>

<body>
  <!-- Start Header/Navigation -->
  <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.php">Furni<span>.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li><a class="nav-link" href="index.php">Home</a></li>
          <li><a class="nav-link" href="shop1.php">Shop</a></li>
          <li><a class="nav-link" href="about.php">About us</a></li>
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
  <!-- End Header/Navigation -->

  <!-- Start Checkout Section -->
  <div class="checkout-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-title mb-5">Complete Your Order</h2>
        </div>
      </div>
      <!-- Display Success or Error Messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php echo htmlspecialchars($_SESSION['success']); ?>
          <?php unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?php echo htmlspecialchars($_SESSION['error']); ?>
          <?php unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
      <div class="row justify-content-between">
        <!-- Customer Information Form -->
        <div class="col-lg-7">
          <div class="checkout-card p-4">
            <h3 class="mb-4">Delivery Details</h3>
            <form action="process_order.php" method="POST" class="row g-3">
              <div class="col-md-6">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name" required>
              </div>
              <div class="col-md-6">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required>
              </div>
              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
              </div>
              <div class="col-12">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
              </div>
              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" required>
              </div>
              <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Enter your city" required>
              </div>
              <div class="col-md-4">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" placeholder="Enter your state" required>
              </div>
              <div class="col-md-2">
                <label for="zip" class="form-label">Zip</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip code" required>
              </div>
              <div class="col-12 mt-4">
                <h4 class="mb-3">Payment Method</h4>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="cod" checked disabled>
                  <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                </div>
              </div>
              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary">Place Order</button>
              </div>
            </form>
          </div>
        </div>
        <!-- Order Summary -->
        <!-- Order Summary -->
<div class="col-lg-4">
  <div class="order-summary">
    <h3 class="mb-4">Order Summary</h3>
    <ul class="list-unstyled">
      <?php
      $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
      $total = 0;
      if (empty($cart)): ?>
        <li>No items in cart.</li>
      <?php else: ?>
        <?php foreach ($cart as $product_id => $item): ?>
          <?php
          // Validate required keys
          if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity'])) {
            continue; // Skip invalid items
          }
          $item_name = $item['name']; // Use 'name' directly as per cart.php
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
          ?>
          <li class="d-flex justify-content-between">
            <div>
              <h5><?php echo htmlspecialchars($item_name); ?></h5>
              <p class="text-light">Qty: <?php echo htmlspecialchars($item['quantity']); ?></p>
            </div>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
          </li>
        <?php endforeach; ?>
        <?php if ($total == 0): ?>
          <li>No valid items in cart.</li>
        <?php else: ?>
          <li class="d-flex justify-content-between total">
            <h4>Total</h4>
            <h4>$<?php echo number_format($total, 2); ?></h4>
          </li>
        <?php endif; ?>
      <?php endif; ?>
    </ul>
  </div>
</div>
  <!-- End Checkout Section -->

  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <div class="sofa-img">
        <img src="images/sofa.png" alt="Image" class="img-fluid">
      </div>
      <div class="row">
        <div class="col-lg-8">
          <div class="subscription-form">
            <h3 class="d-flex align-items-center"><span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>
            <form action="#" class="row g-3">
              <div class="col-auto">
                <input type="text" class="form-control" placeholder="Enter your name">
              </div>
              <div class="col-auto">
                <input type="email" class="form-control" placeholder="Enter your email">
              </div>
              <div class="col-auto">
                <button class="btn btn-primary">
                  <span class="fa fa-paper-plane"></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="row g-5 mb-5">
        <div class="col-lg-4">
          <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
          <p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p>
          <ul class="list-unstyled custom-social">
            <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
            <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
          </ul>
        </div>
        <div class="col-lg-8">
          <div class="row links-wrap">
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">About us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Support</a></li>
                <li><a href="#">Knowledge base</a></li>
                <li><a href="#">Live chat</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Jobs</a></li>
                <li><a href="#">Our team</a></li>
                <li><a href="#">Leadership</a></li>
                <li><a href="#">Privacy Policy</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Nordic Chair</a></li>
                <li><a href="#">Kruzo Aero</a></li>
                <li><a href="#">Ergonomic Chair</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="border-top copyright">
        <div class="row pt-4">
          <div class="col-lg-6">
            <p class="mb-2 text-center text-lg-start">Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co">Untree.co</a></p>
          </div>
          <div class="col-lg-6 text-center text-lg-end">
            <ul class="list-unstyled d-inline-flex ms-auto">
              <li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
              <li><a href="#">Privacy Policy</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer Section -->

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
</body>
</html>