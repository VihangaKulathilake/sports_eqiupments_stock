<?php
$pageTitle = "Register Product";
$cssFile = "css/insertProduct.css";
$extraCss="css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error">
        <?= htmlspecialchars($_SESSION['error_msg']); ?>
        <span class="toast-close">&times;</span>
    </div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success">
        <?= htmlspecialchars($_SESSION['success_msg']); ?>
        <span class="toast-close">&times;</span>
    </div>
<?php unset($_SESSION['success_msg']); endif; ?>

<div class="insert-product-container">
        <form class="insert-product-form" action="includes/insertProduct.inc.php" method="post" enctype="multipart/form-data">
            <h1>Register Product</h1>
            <input type="text" name="pName" id="pName" placeholder="Product Name">
            <input type="text" name="pDes" id="pDes" placeholder="Description">
            <input type="text" name="pCat" id="pCat" placeholder="Category">
            <input type="text" name="pPrice" id="pPrice" placeholder="Price">
            <input type="text" name="pSup" id="pSup" placeholder="Supplier ID">
            <label for="pImg" class="product-image-upload">Upload Cover Image</label>
            <input type="file" name="pImg" id="pImg" accept="image/*">
            <input class="insert-product" type="submit" name="submit" value="Register Product">
            <a href="adminDashboard.php" class="cancel-btn">Cancel</a>
        </form>
</div>

<?php
include_once 'footer.php';
?>

<script src="js/toast.js"></script>
