<?php
$pageTitle = "Edit Product";
$cssFile = "css/editProduct.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';

//determine redirect page
$redirectPage="products.php";
if (isset($_GET['from']) && $_GET['from'] === "topProducts") {
    $redirectPage="topProducts.php";
}
?>

<!-- toast messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php

//get product
if (!isset($_GET['id'])) {
    header("Location: $redirectPage");
    exit();
}

$productId = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE product_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $productId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    $_SESSION['error_msg'] = "No Product Found.";
    header("Location:products.php?error=ProductNotFound");
    exit();
}
?>

<!-- Edit Product Form -->
<div class="edit-product-container">
    <form class="edit-product-form" action="includes/editProduct.inc.php" method="post" enctype="multipart/form-data">
        <h1>Edit Product</h1>
        <input type="hidden" name="productId" value="<?= $product['product_id']; ?>">

        <label for="pName" class="product-label">Product Name:</label>
        <input type="text" name="pName" id="pName" value="<?= htmlspecialchars($product['name']); ?>">

        <label for="pDes" class="product-label">Description:</label>
        <textarea name="pDes" id="pDes"><?= htmlspecialchars($product['description']); ?></textarea>

        <label for="pCat" class="product-label">Category:</label>
        <input type="text" name="pCat" id="pCat" value="<?= htmlspecialchars($product['category']); ?>">

        <label for="pPrice" class="product-label">Price:</label>
        <input type="text" name="pPrice" id="pPrice" value="<?= htmlspecialchars($product['price']); ?>">

        <label for="pSup" class="product-label">Supplier ID:</label>
        <input type="text" name="pSup" id="pSup" value="<?= htmlspecialchars($product['supplier_id']); ?>">

        <label class="product-image-upload">Current Image:</label>
        <?php 
        $currentImg = !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : null;
        if ($currentImg && file_exists("productImgs/" . $currentImg)) { ?>
            <div class="current-image-container">
                <img src="productImgs/<?= $currentImg; ?>" alt="Current Product Image" class="product-preview">
                <span class="image-filename"><?= $currentImg; ?></span>
            </div>
        <?php } else { ?>
            <p>No image uploaded.</p>
        <?php } ?>

        <label for="pImg" class="product-image-upload" style="margin-top:15px;">Upload New Image (optional)</label>
        <input type="file" name="pImg" id="pImg" accept="image/*"><br>

        <input type="hidden" name="from" value="<?= isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '' ?>">
        <input class="edit-product" type="submit" name="submit" value="Save Changes">
        <a href="<?= $redirectPage ?>" class="cancel-product">Cancel</a>
    </form>
</div>

<?php
include_once 'footer.php'; 
?>

<!-- animate toast message -->
<script src="js/toast.js"></script>
