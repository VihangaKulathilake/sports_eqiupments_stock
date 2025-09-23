<?php
    $pageTitle="My Favourites";
    $cssFile="css/myFavourites.css";
    $extraCss="css/toast.css";
    include_once 'userHeader.php';
    require_once 'includes/db.php';
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

<?php

if(isset($_SESSION['customer_id'])){
    $customerId = $_SESSION['customer_id'];

    echo '<h2 style="margin-left:20px;">Your Favourites</h2>';
    echo '<div class="favourite-products-container">';
    
    $sql = "SELECT p.product_id, p.name, p.category, p.price, p.image_path, COALESCE(SUM(s.quantity),0) AS quantity FROM products p
            INNER JOIN user_favourites uf ON p.product_id = uf.product_id LEFT JOIN stocks s ON p.product_id = s.product_id
            WHERE uf.customer_id = $customerId GROUP BY p.product_id, p.name, p.category, p.price, p.image_path ORDER BY p.name ASC";
        
        $stmt=mysqli_prepare($connect, $sql);
        mysqli_stmt_execute($stmt);
        $productResults=mysqli_stmt_get_result($stmt);

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
        echo "<h1 style='float:center'>No favourites yet.</h1>";
        }
} else {
    echo "<p>Please log in to see your favourite products.</p>";
}

    mysqli_stmt_close($stmt);
    mysqli_close($connect); 

    echo '</div>';
?>

<?php
    include_once 'footer.php';
?>

<script src="js/toast.js"></script>