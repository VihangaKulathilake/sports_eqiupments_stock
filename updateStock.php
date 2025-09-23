<?php
$pageTitle = "Update Stock";
$cssFile = "css/updateStock.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<!-- toast messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php

if (!isset($_GET['id'])) {
    header("Location: stocks.php?error=NoStockSelected");
    exit();
}

$stockId = intval($_GET['id']);
$sql = "SELECT * FROM stocks WHERE stock_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $stockId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$stock = mysqli_fetch_assoc($result);

if (!$stock) {
    header("Location: stocks.php?error=StockNotFound");
    exit();
}

//determine redirect page
$redirectPage = "stocks.php";
if (isset($_GET['from']) && $_GET['from'] === "lowStocks") {
    $redirectPage = "lowStockItems.php";
}
?>

<!-- Stock update form -->
<div class="edit-stock-container">
    <form class="edit-stock-form" action="includes/updateStock.inc.php" method="post">
        <h1>Update Stock</h1>
        <input type="hidden" name="stockId" value="<?= $stock['stock_id'] ?>">
        <input type="hidden" name="from" value="<?= isset($_GET['from']) ? $_GET['from'] : '' ?>">

        <label for="productId" class="product-label">Product ID:</label>
        <input type="text" id="productId" value="<?= htmlspecialchars($stock['product_id']) ?>" disabled>

        <label for="stockQty" class="product-label">Quantity:</label>
        <input type="number" name="stockQty" id="stockQty"
               value="<?= htmlspecialchars($stock['quantity']) ?>"
               min="0">

        <input class="edit-product" type="submit" name="submit" value="Save Changes">
        <a href="<?= $redirectPage ?>" class="cancel-product">Cancel</a>
    </form>
</div>

<!-- animate toast messages -->
<script src="js/toast.js"></script>
<?php include 'footer.php'; ?>
