<?php
$pageTitle = "Search Results";
$cssFile = "css/searchResults.css";
$extraCss="css/toast.css";
include_once 'userHeader.php';
require_once 'includes/db.php';

$query = trim($_GET['query'] ?? '');
$category = $_GET['category'] ?? '';
?>

<!-- toast messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<div class="dispaly-results-container">

<?php
//sql for fetching results
$sql = "SELECT p.product_id, p.name, p.category, p.price, p.image_path, COALESCE(SUM(s.quantity),0) AS quantity
        FROM products p 
        LEFT JOIN stocks s ON p.product_id = s.product_id
        WHERE 1=1";

$params = [];
$types = "";

//keyword search
if (!empty($query)) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.category LIKE ?)";
    $like = "%$query%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sss";
}

//category filter
if (!empty($category)) {
    $sql .= " AND p.category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " GROUP BY p.product_id, p.name, p.category, p.price, p.image_path";

// Prepare statement
$stmt = $connect->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$productResults = $stmt->get_result();
?>

    <!-- Search heading -->
    <h2 class="search-results-tag">
        <?php
        if ($productResults->num_rows > 0) {
            if ($query && $category) {
                echo 'Search Results for "<span>' . htmlspecialchars($query) . '</span>" in <span>' . htmlspecialchars($category) . '</span>';
            } elseif ($query) {
                echo 'Search Results for "<span>' . htmlspecialchars($query) . '</span>"';
            } elseif ($category) {
                echo 'Search Results in <span>' . htmlspecialchars($category) . '</span>';
            } else {
                echo 'All Products';
            }
        } else {
            echo 'No results found.';
        }
        ?>
    </h2>

    <!-- Products grid -->
    <div class="display-products-container">
        <?php
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

                echo '<form method="POST" action="/sports_eqiupments_stock/includes/userFavourites.inc.php" style="display:inline;">
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

                    if ($row['quantity']<1) {
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

        ?>
    </div>
</div>

<?php
$stmt->close();
$connect->close();
include_once 'footer.php';
?>

<script src="js/toast.js"></script>