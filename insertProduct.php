<?php
$pageTitle = "Register Product";
$cssFile = "css/insertProduct.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

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