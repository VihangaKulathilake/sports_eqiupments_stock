<?php
$pageTitle = "Home";
$cssFile = "css/index.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'userHeader.php';
?>

<div class="index-container">

  <!-- Home -->
  <section class="home" id="home">
    <div class="overlay"></div>
    <div class="home-content">
      <img src="imgs\E2-removebg-preview-2.png" alt="SPORTIVO LOGO">
      <a href="displayProducts.php" class="btn-primary">Shop Now</a>
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
      premium sports equipment for athletes of all levels...
    </p>
    <a href="about.php" class="btn-primary">Learn More</a>
  </div>
</section>


<!-- Products Section -->
<?php
echo '<section class="products" id="products">';
echo '<div class="heading">
        <h1>Our Popular Products</h1>
        <p>Discover our top-selling items loved by athletes worldwide</p>
      </div>';

echo '<div class="scroll-wrapper">';
echo '<button class="scrollbtnleft products-left"><img src="imgs/la.png" alt="leftarrow"></button>';
echo '<div class="display-products-container">';

$sql = "SELECT p.product_id, p.name, p.category, p.price, p.image_path,
              COALESCE(s.quantity,0) AS quantity,
              SUM(oi.quantity) AS total_sold
        FROM products p
        LEFT JOIN stocks s ON p.product_id = s.product_id
        LEFT JOIN order_items oi ON p.product_id = oi.product_id
        GROUP BY p.product_id
        ORDER BY total_sold DESC
        LIMIT 5";

$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_execute($stmt);
$productResults = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($productResults)>0) {
            
            while ($row=mysqli_fetch_assoc($productResults)) {
                echo '<div class="product-block">';

                $isFav = false;
                if (isset($_SESSION['customer_id'])) {
                    $customerId=$_SESSION['customer_id'];
                    $productId=$row['product_id'];

                    $favSql="SELECT * FROM user_favourites WHERE customer_id=$customerId AND product_id=$productId LIMIT 1";
                    $favResult=mysqli_query($connect, $favSql);
                    if (mysqli_num_rows($favResult)>0) {
                        $isFav=true;
                    }
                }

                echo '<form method="POST" action="includes/userFavourites.inc.php" style="display:inline;">
                        <input type="hidden" name="product_id" value="'.$row['product_id'].'">
                        <button type="submit" class="like-btn">
                            <img src="imgs/'.($isFav?"like.png":"heart.png").'" alt="like">
                        </button>
                    </form>';


                echo '<a href="viewProductPurchase.php?product_id=' . urlencode($row['product_id']) . '&name=' . urlencode($row['name']) . '" class="product-link">';
                
                echo '<img src="productImgs/' . htmlspecialchars($row['image_path'] ?? 'default.png') . '" alt="product-image" class="product-block-image">';
                

                    echo '<div class="product-block-details">';
                        echo "<h2>".htmlspecialchars($row['name'])."</h2>";
                        echo '<p class="product-category">Category: ' . htmlspecialchars($row['category']) . '</p>';
                        echo '<p class="product-price">LKR ' . number_format($row['price'],2) . '</p>';
                    echo "</div>";

                    if ($row['quantity']==0) {
                        echo '<div class="out-of-stock-overlay">';
                        echo '<p>OUT OF STOCK</p>';
                        echo '</div>';                        
                    }
                
                echo "</a>";
                echo "</div><br>";
            }           

        } else {
        echo "<p>No Products to purchase yet.</p>";
        }

mysqli_stmt_close($stmt);

echo '</div>';
echo '<button class="scrollbtnright products-right"><img src="imgs/ra.png" alt="rightarrow"></button>';
echo '</div>';
echo '</section>';
?>


<!-- Customers -->
<div class="top-customers-container">
    <h1 class="header">Top Customers</h1>

    <div class="scroll-wrapper">
        <button class="scrollbtnleft customers-left"><img src="imgs/la.png" alt="leftarrow"></button>
        <div class="customers-grid">
            <?php
            $sql = "SELECT c.customer_id, c.name,c.email,c.phone,c.image_path,SUM(oi.quantity * p.price) AS total_spent
                    FROM customers c
                    JOIN orders o ON c.customer_id = o.customer_id
                    JOIN order_items oi ON o.order_id = oi.order_id
                    JOIN products p ON oi.product_id = p.product_id
                    GROUP BY c.customer_id
                    ORDER BY total_spent DESC
                    LIMIT 5
                    ";

            $result = mysqli_query($connect, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $profileImgPath = "userImgs/" . htmlspecialchars($row['image_path']);
                    if (!empty($row['image_path']) && file_exists($profileImgPath)) {
                        $profileImg = $profileImgPath;
                    } else {
                        $profileImg = "userImgs/default.jpg";
                    }

                    echo "
                    <div class='customer-card'>
                        <div class='img-container'>
                            <img src='" . $profileImg . "' alt='" . htmlspecialchars($row['name']) . "' class='customer-profile-img'>
                        </div>
                        <div class='customer-details'>
                            <h3 class='customer-name'>" . htmlspecialchars($row['name']) . "</h3>
                            <p class='customer-phone'>Phone: " . htmlspecialchars($row['phone']) . "</p>
                            <h2 class='total-spent'>Total Spent: LKR " . number_format($row['total_spent'], 2) . "</h2>
                        </div>
                    </div>"; 
                }
            } else {
                echo "<p class='no-data'>No customer data found.</p>";
            }
            mysqli_close($connect);
            ?>
        </div>
        <button class="scrollbtnright customers-right"><img src="imgs/ra.png" alt="rightarrow"></button>
    </div>
</div>

<?php
include 'footer.php';
?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Scroll function
    function setupScroller(containerSelector, leftBtnSelector, rightBtnSelector) {
        const container = document.querySelector(containerSelector);
        const leftBtn = document.querySelector(leftBtnSelector);
        const rightBtn = document.querySelector(rightBtnSelector);

        if (container && leftBtn && rightBtn) {
            leftBtn.addEventListener("click", () => {
                container.scrollBy({ left: -300, behavior: "smooth" });
            });
            rightBtn.addEventListener("click", () => {
                container.scrollBy({ left: 300, behavior: "smooth" });
            });
        }
    }

    setupScroller(".display-products-container", ".products-left", ".products-right");
    setupScroller(".customers-grid", ".customers-left", ".customers-right");
});
</script>
