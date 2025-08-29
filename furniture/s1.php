<DOCUMENT filename="product-detail.html">
<!DOCTYPE html>
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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <title>Product Detail | Furni</title>
  <style>
    :root {
      --primary: #6d4c41;
      --secondary: #7e57c2;
      --dark: #263238;
      --light: #f5f5f5;
      --accent: #d4af37; /* Gold for classy look */
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: var(--light);
      color: var(--dark);
    }

    h1, h2, h3 {
      font-family: 'Playfair Display', serif;
    }

    .product-detail {
      padding: 4rem 0;
    }

    .product-image-main {
      height: 500px;
      object-fit: cover;
      border-radius: 15px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .product-image-main:hover {
      transform: scale(1.02);
    }

    .product-thumbnails img {
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
      cursor: pointer;
      opacity: 0.7;
      transition: opacity 0.3s ease;
    }

    .product-thumbnails img:hover, .product-thumbnails img.active {
      opacity: 1;
    }

    .product-title {
      font-size: 2.5rem;
      color: var(--primary);
    }

    .product-price {
      font-size: 1.8rem;
      color: var(--accent);
      font-weight: 600;
    }

    .old-price {
      text-decoration: line-through;
      color: #999;
      margin-left: 1rem;
    }

    .rating {
      color: var(--accent);
    }

    .add-to-cart {
      background: var(--primary);
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .add-to-cart:hover {
      background: var(--secondary);
      transform: translateY(-3px);
    }

    .wishlist {
      color: var(--dark);
      text-decoration: none;
      margin-left: 2rem;
      font-weight: 600;
    }

    .wishlist i {
      color: var(--accent);
    }

    .tab-pane {
      padding: 2rem 0;
    }

    .review-item {
      border-bottom: 1px solid #eee;
      padding: 1.5rem 0;
    }

    .review-author {
      font-weight: 600;
      color: var(--primary);
    }

    .related-products .product-item {
      transition: all 0.3s ease;
    }

    .related-products .product-item:hover {
      transform: translateY(-10px);
    }
  </style>
</head>

<body>

  <!-- Start Header/Navigation -->
  <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.html">Furni<span>.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li><a class="nav-link" href="index.html">Home</a></li>
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

  <!-- Start Product Detail -->
  <section class="product-detail">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6">
          <img src="images/product-1.png" class="img-fluid product-image-main" alt="Nordic Chair">
          <div class="d-flex gap-3 mt-3 product-thumbnails">
            <img src="images/product-1.png" class="active" alt="Thumbnail 1">
            <img src="images/product-2.png" alt="Thumbnail 2">
            <img src="images/product-3.png" alt="Thumbnail 3">
          </div>
        </div>
        <div class="col-lg-6">
          <h1 class="product-title">Nordic Chair</h1>
          <div class="d-flex align-items-center mb-3">
            <span class="product-price">$50.00</span>
            <span class="old-price">$70.00</span>
          </div>
          <div class="rating mb-3">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <span>(42 reviews)</span>
          </div>
          <p class="mb-4">The Nordic Chair combines minimalist design with exceptional comfort. Crafted from sustainably sourced oak and upholstered in premium wool fabric, this chair is perfect for modern living spaces.</p>
          <ul class="mb-4">
            <li>Material: Solid oak frame, wool upholstery</li>
            <li>Dimensions: 22"W x 25"D x 32"H</li>
            <li>Weight Capacity: 250 lbs</li>
            <li>Assembly: Required (tools included)</li>
            <li>Color: Natural oak / Gray wool</li>
          </ul>
          <div class="d-flex align-items-center mb-4">
            <button class="btn add-to-cart">Add to Cart</button>
            <a href="#" class="wishlist"><i class="far fa-heart me-2"></i>Add to Wishlist</a>
          </div>
          <p class="mb-2">SKU: NCH-2023</p>
          <p class="mb-2">Category: Chairs</p>
          <p>Tags: Modern, Scandinavian, Minimalist</p>
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
              <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews (42)</button>
            </li>
          </ul>
          <div class="tab-content" id="productTabContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
              <p>Product Description</p>
              <p>The Nordic Chair is inspired by Scandinavian design principles, focusing on simplicity, functionality, and beauty. Each piece is handcrafted by skilled artisans using traditional techniques combined with modern technology.</p>
              <p>The solid oak frame provides durability and stability, while the gently curved backrest offers ergonomic support. The wool upholstery is naturally stain-resistant and comes in a variety of neutral tones to complement any decor.</p>
              <p>Perfect for dining rooms, home offices, or as an accent chair in living spaces. The compact dimensions make it ideal for smaller apartments without sacrificing comfort.</p>
            </div>
            <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
              <p>Technical Specifications</p>
              <table class="table">
                <tbody>
                  <tr><td>Frame Material</td><td>Solid European oak</td></tr>
                  <tr><td>Upholstery</td><td>100% premium wool fabric</td></tr>
                  <tr><td>Dimensions</td><td>22"W x 25"D x 32"H</td></tr>
                  <tr><td>Seat Height</td><td>18" from floor</td></tr>
                  <tr><td>Weight Capacity</td><td>250 lbs</td></tr>
                  <tr><td>Assembly</td><td>Required (15-20 minutes)</td></tr>
                  <tr><td>Warranty</td><td>5 years limited</td></tr>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
              <p>Customer Reviews</p>
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <h4>4.7</h4>
                  <div class="rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                  </div>
                  <p>42 reviews</p>
                </div>
                <div class="progress-bars">
                  <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 76%" aria-valuenow="76" aria-valuemin="0" aria-valuemax="100">5 ★</div>
                  </div>
                  <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 14%" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">4 ★</div>
                  </div>
                  <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 5%" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">3 ★</div>
                  </div>
                  <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 2%" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">2 ★</div>
                  </div>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 2%" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">1 ★</div>
                  </div>
                </div>
              </div>
              <div class="review-form mb-5">
                <h5>Write a Review</h5>
                <form>
                  <div class="mb-3">
                    <label class="form-label">Your Rating</label>
                    <div class="rating-input">
                      <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Review</label>
                    <textarea class="form-control" rows="4"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
              </div>
              <div class="review-item">
                <div class="d-flex align-items-center mb-2">
                  <h5 class="review-author me-3">Michael S.</h5>
                  <div class="rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  </div>
                </div>
                <p>March 15, 2023</p>
                <p>Absolutely love this chair! The quality is exceptional and it's surprisingly comfortable. Perfect for my home office.</p>
              </div>
              <div class="review-item">
                <div class="d-flex align-items-center mb-2">
                  <h5 class="review-author me-3">Sarah J.</h5>
                  <div class="rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                  </div>
                </div>
                <p>February 28, 2023</p>
                <p>Beautiful design and very sturdy. The only reason I didn't give 5 stars is that the wool fabric shows pet hair more than I expected.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-12">
          <h2>You May Also Like</h2>
          <div class="row related-products g-4">
            <div class="col-md-3">
              <a class="product-item" href="#">
                <img src="images/product-2.png" class="img-fluid product-thumbnail">
                <h3 class="product-title">Kruzo Aero Chair</h3>
                <strong class="product-price">$78.00</strong>
              </a>
            </div>
            <div class="col-md-3">
              <a class="product-item" href="#">
                <img src="images/product-3.png" class="img-fluid product-thumbnail">
                <h3 class="product-title">Ergonomic Chair</h3>
                <strong class="product-price">$43.00</strong>
              </a>
            </div>
            <div class="col-md-3">
              <a class="product-item" href="#">
                <img src="images/product-4.png" class="img-fluid product-thumbnail">
                <h3 class="product-title">Modern Sofa</h3>
                <strong class="product-price">$250.00</strong>
              </a>
            </div>
            <div class="col-md-3">
              <a class="product-item" href="#">
                <img src="images/product-5.png" class="img-fluid product-thumbnail">
                <h3 class="product-title">Coffee Table</h3>
                <strong class="product-price">$120.00</strong>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Product Detail -->

  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <!-- Footer content -->
    </div>
  </footer>
  <!-- End Footer Section -->

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
</DOCUMENT>