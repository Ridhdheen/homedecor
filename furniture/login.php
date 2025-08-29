<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    header("Location: ../furniture/index.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="Login or Register to Furni - Modern Interior Design Studio" />
  <meta name="keywords" content="furniture, interior design, login, register" />

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/tiny-slider.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Login or Register - Furni</title>

  <style>
    .login-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/why-choose-us-img.jpg') no-repeat center center/cover;
      color: #fff;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      max-width: 450px;
      width: 100%;
      margin: auto;
    }
    .login-card h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      color: #2c2c2c;
      text-align: center;
    }
    .form-control {
      border-radius: 8px;
      border: 1px solid #ced4da;
      padding: 0.75rem;
    }
    .form-control:focus {
      border-color: #343a40;
      box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.25);
    }
    .btn-primary {
      background-color: #343a40;
      border: none;
      border-radius: 8px;
      padding: 0.75rem;
      width: 100%;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #495057;
    }
    .toggle-form {
      color: #343a40;
      text-decoration: underline;
      cursor: pointer;
    }
    .toggle-form:hover {
      color: #495057;
    }
    .error-message {
      color: #dc3545;
      font-size: 0.875rem;
      text-align: center;
    }
    .form-section {
      display: none;
    }
    .form-section.active {
      display: block;
    }
  </style>
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
            <li><a class="nav-link" href="logout.php"><img src="images/logout.svg" title="Logout"></a></li>
          <?php else: ?>
            <li><a class="nav-link" href="login.php"><img src="images/user.svg" title="Login"></a></li>
          <?php endif; ?>
          <li><a class="nav-link" href="cart.php"><img src="images/cart.svg"></a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Header/Navigation -->

  <!-- Start Login/Register Section -->
  <div class="login-section">
    <div class="container">
      <div class="login-card">
        <!-- Login Form -->
        <div id="login-form" class="form-section active">
          <h2>Login to Furni</h2>
          <form action="process_login.php" method="POST" class="mt-4">
            <div class="mb-3">
              <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <?php if (isset($_SESSION['login_error'])): ?>
              <p class="error-message"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Login</button>
          </form>
          <p class="mt-3 text-center">Don't have an account? <span class="toggle-form" onclick="toggleForm('register-form')">Register</span></p>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="form-section">
          <h2>Register with Furni</h2>
          <form action="register.php" method="POST" class="mt-4">
            <div class="mb-3">
              <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <?php if (isset($_SESSION['register_error'])): ?>
              <p class="error-message"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Register</button>
          </form>
          <p class="mt-3 text-center">Already have an account? <span class="toggle-form" onclick="toggleForm('login-form')">Login</span></p>
        </div>
      </div>
    </div>
  </div>
  <!-- End Login/Register Section -->

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
    function toggleForm(formId) {
      document.querySelectorAll('.form-section').forEach(form => form.classList.remove('active'));
      document.getElementById(formId).classList.add('active');
    }
  </script>
</body>
</html>