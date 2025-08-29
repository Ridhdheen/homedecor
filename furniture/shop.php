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
  <title>Shop | Furni</title>
  <style>
    /* Additional custom styles */
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
      padding: 0;
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
  </style>
</head>

<body>
  <!-- Start Header/Navigation -->
  <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.html">Furni<span>.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="active"><a class="nav-link" href="shop.html">Shop</a></li>
          <li><a class="nav-link" href="about.html">About us</a></li>
          <li><a class="nav-link" href="services.html">Services</a></li>
          <li><a class="nav-link" href="blog.html">Blog</a></li>
          <li><a class="nav-link" href="contact.html">Contact us</a></li>
        </ul>
        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          <li><a class="nav-link" href="#"><img src="images/user.svg"></a></li>
          <li><a class="nav-link" href="cart.html"><img src="images/cart.svg"></a></li>
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
            <li class="active"><a href="#" data-filter="all">All</a></li>
            <li><a href="#" data-filter="chairs">Chairs</a></li>
            <li><a href="#" data-filter="sofas">Sofas</a></li>
            <li><a href="#" data-filter="tables">Tables</a></li>
            <li><a href="#" data-filter="lighting">Lighting</a></li>
            <li><a href="#" data-filter="decor">Decor</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- End Category Filter -->

  <!-- Start Product Section -->
  <div class="untree_co-section product-section before-footer-section">
    <div class="container">
      <div class="row">

        <!-- Product 1 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="chairs">
          <a class="product-item" href="product-detail.html?id=1">
            <img src="images/product-1.png" class="img-fluid product-thumbnail" alt="Nordic Chair">
            <h3 class="product-title">Nordic Chair</h3>
            <strong class="product-price">$50.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 2 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="chairs">
          <a class="product-item" href="product-detail.html?id=2">
            <img src="images/product-2.png" class="img-fluid product-thumbnail" alt="Kruzo Aero Chair">
            <h3 class="product-title">Kruzo Aero Chair</h3>
            <strong class="product-price">$78.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 3 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="chairs">
          <a class="product-item" href="product-detail.html?id=3">
            <img src="images/product-3.png" class="img-fluid product-thumbnail" alt="Ergonomic Chair">
            <h3 class="product-title">Ergonomic Chair</h3>
            <strong class="product-price">$43.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 4 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="sofas">
          <a class="product-item" href="product-detail.html?id=4">
            <img src="images/product-4.png" class="img-fluid product-thumbnail" alt="Modern Sofa">
            <h3 class="product-title">Modern Sofa</h3>
            <strong class="product-price">$250.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 5 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="tables">
          <a class="product-item" href="product-detail.html?id=5">
            <img src="images/product-5.png" class="img-fluid product-thumbnail" alt="Coffee Table">
            <h3 class="product-title">Coffee Table</h3>
            <strong class="product-price">$120.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 6 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="lighting">
          <a class="product-item" href="product-detail.html?id=6">
            <img src="images/product-6.png" class="img-fluid product-thumbnail" alt="Floor Lamp">
            <h3 class="product-title">Floor Lamp</h3>
            <strong class="product-price">$78.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 7 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="decor">
          <a class="product-item" href="product-detail.html?id=7">
            <img src="images/product-7.png" class="img-fluid product-thumbnail" alt="Decorative Vase">
            <h3 class="product-title">Decorative Vase</h3>
            <strong class="product-price">$43.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

        <!-- Product 8 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5" data-category="tables">
          <a class="product-item" href="product-detail.html?id=8">
            <img src="images/product-8.png" class="img-fluid product-thumbnail" alt="Dining Table">
            <h3 class="product-title">Dining Table</h3>
            <strong class="product-price">$350.00</strong>
            <span class="icon-cross">
              <img src="images/cross.svg" class="img-fluid" alt="Add to cart">
            </span>
          </a>
        </div>

      </div>
    </div>
  </div>
  <!-- End Product Section -->

  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <!-- Footer content same as before -->
    </div>
  </footer>
  <!-- End Footer Section -->

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
  <script>
    // Category filter functionality
    document.addEventListener('DOMContentLoaded', function() {
      const filterLinks = document.querySelectorAll('.category-list a');
      const productItems = document.querySelectorAll('.product-item');
      
      filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Remove active class from all links
          filterLinks.forEach(item => item.parentElement.classList.remove('active'));
          // Add active class to clicked link
          this.parentElement.classList.add('active');
          
          const filterValue = this.getAttribute('data-filter');
          
          productItems.forEach(item => {
            const productCategory = item.closest('[data-category]').getAttribute('data-category');
            
            if (filterValue === 'all' || productCategory === filterValue) {
              item.closest('[data-category]').style.display = 'block';
            } else {
              item.closest('[data-category]').style.display = 'none';
            }
          });
        });
      });
    });
  </script>
</body>
</html>