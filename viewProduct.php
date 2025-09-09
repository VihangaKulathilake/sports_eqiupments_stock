<?php
$pageTitle = "View Product";
$cssFile = "css/viewProduct.css";
include 'includes/db.php';
include 'adminHeader.php';

$pId=$_GET["product_id"] ?? null;

if(!$pId){
    echo "<p>No products selected.</p>";
    include_once 'footer.php';
    exit();
}

$sql = "SELECT * FROM products WHERE product_id=?;";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $pId);
mysqli_stmt_execute($stmt);
$pResults = mysqli_stmt_get_result($stmt);

if ($product=mysqli_fetch_assoc($pResults)) {

    echo '<div class="view-product-container">';
        echo '<div class="view-pro-cont-left">';
            $pImgPath = 'productImgs/' . htmlspecialchars($product['image_path'] ?? 'default.png');
            echo '<img src="' . $pImgPath . '" class="view-pro-img" alt="' . htmlspecialchars($product['name']) . '">';
            echo '<hr>';
        echo '</div>';
        echo '<div class="view-pro-cont-right">';
            echo "<h1>".htmlspecialchars($product['name'])."</h1>";
            echo "<p><strong>Category:</strong> ".htmlspecialchars($product['category'])."</p>";
            echo "<p><strong>Description:</strong> ".htmlspecialchars($product['description'])."</p><br>";
            echo "<h2>Unit Price: LKR ".number_format($product['price'],2)."</h2><br>";
            echo "<p><strong>Share:</strong></p><br>";
            echo "<div class='social-media-logos'>
                    <img src='imgs/twitter.png' alt='twitter-logo'>
                    <img src='imgs/facebook-logo.png' alt='facebook-logo'>
                    <img src='imgs/whatsapp.png' alt='whatsapp-logo'>
                </div>";

        echo '</div>';
    echo '</div>';

}else {
        echo "<p>Product not found.</p>";
    }

mysqli_stmt_close($stmt);
mysqli_close($connect);
include_once 'footer.php';


