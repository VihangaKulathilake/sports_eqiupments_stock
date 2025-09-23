<?php
session_start();

if (!isset($_POST['submit'])) {
    header("Location:../stocks.php");
    exit();
}

$stockId = $_POST['stockId'];
$stockQty = $_POST['stockQty'];

// Determine redirect page
$redirectPage = "stocks.php";
if (isset($_POST['from']) && $_POST['from'] === "lowStocks") {
    $redirectPage = "lowStockItems.php";
}

require_once 'db.php';

// Validation
if (!isset($stockQty) || !is_numeric($stockQty) || $stockQty < 0) {
    $_SESSION['error_msg'] = "Invalid stock.";
    header("Location:../updateStock.php?id=$stockId&from=" . ($_POST['from'] ?? ''));
    exit();
}

// Update stock
$sql = "UPDATE stocks SET quantity=?, last_updated=NOW() WHERE stock_id=?";
$stmt = mysqli_prepare($connect, $sql);

if (!$stmt) {
    $_SESSION['error_msg'] = "SQL prepare failed.";
    header("Location:../updateStock.php?id=$stockId&from=" . ($_POST['from'] ?? ''));
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $stockQty, $stockId);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success_msg'] = "Stock updated successfully.";
    header("Location:../$redirectPage?success=StockUpdated");
    exit();
} else {
    $_SESSION['error_msg'] = "Stock update failed.";
    header("Location:../updateStock.php?id=$stockId&from=" . ($_POST['from'] ?? ''));
    exit();
}
