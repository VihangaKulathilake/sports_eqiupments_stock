<?php
$pageTitle = "Home";
$cssFile = "css/index.css";
include 'includes/db.php';
include 'userHeader.php';
?>

  <!-- Home -->
<section class="home" id="home">
  <div class="overlay"></div>
  <div class="home-content">
    <h1 class="brand-name">SPORTIVO</h1>
    <p class="tagline">Your one-stop shop for premium sports goods.</p>
    <a href="#products" class="btn-primary">Shop Now</a>
  </div>
</section>
</div>

<!-- About -->
<section class="about" id="about">
  <div class="about-image">
    <img 
      src="imgs copy/epic-sport-layout-website-creative-web-design-sport-retails-items-shop-figma-concept-ideas_655090-1666885.jpg" 
      alt="About SPORTIVO"
    >
  </div>

  <div class="about-content">
    <h2><span>About</span> Our History</h2>
    
    <p>
      Since our founding, <strong>SPORTIVO</strong> has been dedicated to providing
      premium sports equipment for athletes of all levels. Our passion for
      excellence drives us to deliver quality, durability, and innovation in every
      product.
    </p>

    <p>
      From cricket bats to boxing gloves, we bring the best of global sports brands
      to your doorstep. Trusted by thousands of athletes, SPORTIVO is your one-stop
      destination for sports gear.
    </p>

    <p>
      At SPORTIVO, we believe that the right equipment can transform performance.
      That’s why we carefully source products from world-renowned brands and ensure
      that each item meets international standards of quality and safety. Whether
      you are a beginner, a professional athlete, or a fitness enthusiast, our wide
      range of collections is designed to suit every need.
    </p>

    <p>
      Our mission goes beyond selling products we aim to inspire a sporting
      lifestyle. With continuous innovation, expert recommendations, and a
      commitment to customer satisfaction, SPORTIVO is more than a store, it’s a
      trusted partner in every athlete’s journey.
    </p>

    <p>
      Join the SPORTIVO family today and experience sports shopping like never
      before where passion meets performance.
    </p>

    <a href="#" class="btn-primary">Learn More</a>
  </div>
</section>


<!-- Products Section -->
<?php
  echo '<section class="products" id="products">';
    echo'<div class="heading">
        <h1>Our Popular Products</h1>
        <p>Discover our top-selling items loved by athletes worldwide</p>
        </div>';

      echo '<div class="display-products-container">';

       $sql = "SELECT p.product_id, p.name, p.category, p.price, p.image_path,
        SUM(oi.quantity) AS total_sold FROM order_items oi JOIN products p ON oi.product_id = p.product_id 
       GROUP BY p.product_id ORDER BY total_sold DESC LIMIT 5";

        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_execute($stmt);
        $productResults = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($productResults)>0) {
            
            while ($row=mysqli_fetch_assoc($productResults)) {
                //echo '<a href="eventDisplay.php?eventId=' . urlencode($row['eventId']) . '" class="eventlink">';
                echo '<div class="product-block">';
                echo '<img src="productImgs/' . htmlspecialchars($row['image_path'] ?? 'default.png') . '" alt="product-image" class="product-block-image">';
                echo '<button class="like-btn"><img src="imgs/heart.png" alt="like"></button>';

                    echo '<div class="product-block-details">';
                        echo '<div class="product-block-details-left">';
                        echo "<h2>".htmlspecialchars($row['name'])."</h2>";
                        echo '<p class="product-category">Category: ' . htmlspecialchars($row['category']) . '</p>';
                        echo '<p class="product-price">LKR ' . number_format($row['price'],2) . '</p>';
                        echo "</div>";

                        echo '<div class="product-block-details-right">';
                        echo '<button class="cart-btn"><img src="imgs/shopping-cart-white.png" alt="cart"></button>';
                        echo "</div>";
                    echo "</div>";
                echo "</div><br>";
                //echo "</a>";
            }           

        } else {
        echo "<p>No Products to purchase yet.</p>";
        }

    mysqli_stmt_close($stmt);
    mysqli_close($connect); 

    echo '</div>';
  echo '</section>';
?>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".like-btn").forEach(function(button) {
            button.addEventListener("click", function() {
                let img = this.querySelector("img");
                if (img.getAttribute("src") === "imgs/heart.png") {
                    img.setAttribute("src", "imgs/like.png");
                } else {
                    img.setAttribute("src", "imgs/heart.png");
                }
            });
        });
    });
    </script>



<!-- Customers -->
<section class="customers" id="customers">
  <div class="heading">
    <h2>Our Happy Customers</h2>
    <p>Hear what athletes say about SPORTIVO</p>
  </div>

  <div class="customers-grid">
    <!-- Customer 1 -->
    <div class="customer-card">
      <div class="stars">
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star-half"></i>
      </div>
      <p>
        “The quality of SPORTIVO’s bats is unmatched! Durable, lightweight, and
        perfect for my game.”
      </p>
      <div class="customer-info">
        <img src="imgs/rev1.jpg" alt="Customer Review 1">
        <h3>Chamath Kavinda</h3>
      </div>
    </div>

    <!-- Customer 2 -->
    <div class="customer-card">
      <div class="stars">
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star-half"></i>
      </div>
      <p>
        “Best sports store in town. Affordable prices and amazing customer
        support!”
      </p>
      <div class="customer-info">
        <img src="imgs/rev2.jpg" alt="Customer Review 2">
        <h3>Kasun Sampath</h3>
      </div>
    </div>

    <!-- Customer 3 -->
    <div class="customer-card">
      <div class="stars">
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star"></i>
        <i class="bx bxs-star-half"></i>
      </div>
      <p>
        “I love my new football shoes! Stylish, comfortable, and great
        performance on the field.”
      </p>
      <div class="customer-info">
        <img src="imgs/rev3.jpg" alt="Customer Review 3">
        <h3>Kaveesha Gamage</h3>
      </div>
    </div>
  </div>
</section>

<?php
include 'footer.php';
?>










