<?php
session_start();
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$redirectPage = "stocks.php";

if (isset($_GET['from']) && $_GET['from'] === "lowStocks") {
    $redirectPage = "lowStockItems.php";
}

if (!$id) {
    $_SESSION['error_msg'] = "No stock selected.";
    header("Location: ../$redirectPage?error=NoStockSelected");
    exit();
}

//delete stock
$sql = "DELETE FROM stocks WHERE stock_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
$executed = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($executed) {
    $_SESSION['success_msg'] = "Stock deleted successfully.";
    header("Location: ../$redirectPage?deleted=1");
    exit();
} else {
    $_SESSION['error_msg'] = "Stock deletion failed.";
    header("Location: ../$redirectPage?error=DeleteFailed");
    exit();
}
?>
