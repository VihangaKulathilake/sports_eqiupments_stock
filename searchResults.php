<?php
$pageTitle = "Search Results";
$cssFile = "css/searchResults.css";
include_once 'userHeader.php';
require_once 'includes/db.php';

$query = trim($_GET['query'] ?? '');
$category = $_GET['category'] ?? '';

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

<div class="search-results-container">
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
        if ($productResults->num_rows > 0) {
            while ($row = $productResults->fetch_assoc()) {
                echo '<div class="product-block">';
                echo '<button class="like-btn"><img src="imgs/heart.png" alt="like"></button>';
                echo '<a href="viewProductPurchase.php?product_id=' . urlencode($row['product_id']) . '" class="product-link">';
                
                echo '<img src="productImgs/' . htmlspecialchars($row['image_path'] ?? 'default.png') . '" alt="product-image" class="product-block-image">';
                
                echo '<div class="product-block-details">';
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                echo '<p class="product-category">Category: ' . htmlspecialchars($row['category']) . '</p>';
                echo '<p class="product-price">LKR ' . number_format($row['price'], 2) . '</p>';
                echo "</div>";

                if ($row['quantity'] == 0) {
                    echo '<div class="out-of-stock-overlay"><p>OUT OF STOCK</p></div>';
                }

                echo "</a>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>

<!-- Like button toggle -->
<script src="js/like.js"></script>

<?php
$stmt->close();
$connect->close();
include_once 'footer.php';
?>
