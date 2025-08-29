<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'home_decor_db';
$username = 'root'; // Change as per your setup
$password = ''; // Change as per your setup

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user data if logged in
$user = null;
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] && isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Load cart from database into session if user is logged in
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['cart'] = [];
        foreach ($cartItems as $item) {
            $_SESSION['cart'][$item['product_id']] = [
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $item['quantity']
            ];
        }
    }
} else {
    // For non-logged-in users, keep cart in session only
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Function to sync cart with database
function syncCartWithDatabase($pdo, $userId, $cart) {
    // Clear existing cart items for the user
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);

    // Insert updated cart items
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

// Handle adding a product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = floatval($_POST['product_price']);
    $product_image = $_POST['product_image'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

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
    if ($user) {
        syncCartWithDatabase($pdo, $_SESSION['user_id'], $_SESSION['cart']);
    }
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } elseif ($quantity <= 0 && isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    if ($user) {
        syncCartWithDatabase($pdo, $_SESSION['user_id'], $_SESSION['cart']);
    }
    header('Location: cart.php');
    exit();
}

// Handle removing a product
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    if ($user) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
    }
    header('Location: cart.php');
    exit();
}

// Calculate cart totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal;
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="css/tiny-slider.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co</title>
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li><a class="nav-link" href="shop1.php">Shop</a></li>
                    <li><a class="nav-link" href="about.php">About us</a></li>
            
                    
                    <li><a class="nav-link" href="contact.php">Contact us</a></li>
                </ul>
                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
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

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Cart</h1>
                    </div>
                </div>
                <div class="col-lg-7"></div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" method="post" action="cart.php">
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($_SESSION['cart'])): ?>
                                    <tr><td colspan="6" class="text-center">Your cart is empty.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                                        <tr>
                                            <td class="product-thumbnail">
                                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Image" class="img-fluid">
                                            </td>
                                            <td class="product-name">
                                                <h2 class="h5 text-black"><?php echo htmlspecialchars($item['name']); ?></h2>
                                            </td>
                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            <td>
                                                <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-black decrease" type="button">&minus;</button>
                                                    </div>
                                                    <input type="text" class="form-control text-center quantity-amount" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" aria-label="Quantity">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-black increase" type="button">&plus;</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            <td><a href="cart.php?remove=<?php echo $product_id; ?>" class="btn btn-black btn-sm">X</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <button type="submit" name="update_cart" class="btn btn-black btn-sm btn-block">Update Cart</button>
                        </div>
                        <div class="col-md-6">
                            <a href="shop1.php" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-black h4" for="coupon">Coupon</label>
                            <p>Enter your coupon code if you have one.</p>
                        </div>
                        <div class="col-md-8 mb-3 mb-md-0">
                            <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-black">Apply Coupon</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-5">
                                    <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="text-black">Subtotal</span></div>
                                <div class="col-md-6 text-right"><strong class="text-black">$<?php echo number_format($subtotal, 2); ?></strong></div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6"><span class="text-black">Total</span></div>
                                <div class="col-md-6 text-right"><strong class="text-black">$<?php echo number_format($total, 2); ?></strong></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="checkout.php" class="btn btn-black btn-lg py-3 btn-block">Proceed To Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">
            <div class="sofa-img"><img src="images/sofa.png" alt="Image" class="img-fluid"></div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="subscription-form">
                        <h3 class="d-flex align-items-center"><span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>
                        <form action="#" class="row g-3">
                            <div class="col-auto"><input type="text" class="form-control" placeholder="Enter your name"></div>
                            <div class="col-auto"><input type="email" class="form-control" placeholder="Enter your email"></div>
                            <div class="col-auto"><button class="btn btn-primary"><span class="fa fa-paper-plane"></span></button></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
                    <p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>
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
    <script>
        document.querySelectorAll('.quantity-container').forEach(container => {
            const input = container.querySelector('.quantity-amount');
            const decreaseBtn = container.querySelector('.decrease');
            const increaseBtn = container.querySelector('.increase');

            decreaseBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                if (value > 1) input.value = value - 1;
            });

            increaseBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                input.value = value + 1;
            });
        });
    </script>
</body>
</html>