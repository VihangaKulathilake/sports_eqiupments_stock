<?php
session_start();
require_once 'db.php';

$purchaseId = isset($_GET['id']) ? intval($_GET['id']) : null;

if(!$purchaseId){
    $_SESSION['error_msg'] = "No purchase selected.";
    header("Location: ../purchases.php?error=NoPurchaseSelected");
    exit();
}

//Check if there is a purchase
$sqlCheck = "SELECT order_status FROM supplier_orders WHERE supplier_order_id=?";
$stmtCheck = mysqli_prepare($connect, $sqlCheck);
mysqli_stmt_bind_param($stmtCheck, "i", $purchaseId);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$purchase = mysqli_fetch_assoc($resultCheck);
mysqli_stmt_close($stmtCheck);

if(!$purchase){
    $_SESSION['error_msg'] = "Purchase not found.";
    header("Location: ../purchases.php?error=PurchaseNotFound");
    exit();
}

//Prevent completed purchases from being deleted
if($purchase['order_status'] === 'completed'){
    $_SESSION['error_msg'] = "Cannot delete a completed purchase.";
    header("Location: ../purchases.php?error=CannotDeleteCompleted");
    exit();
}

//delete purchase items
$sqlItems = "DELETE FROM supplier_order_items WHERE supplier_order_id=?";
$stmtItems = mysqli_prepare($connect, $sqlItems);
mysqli_stmt_bind_param($stmtItems, "i", $purchaseId);
$executedItems = mysqli_stmt_execute($stmtItems);
mysqli_stmt_close($stmtItems);

//delete the purchase
$sqlPurchase = "DELETE FROM supplier_orders WHERE supplier_order_id=?";
$stmtPurchase = mysqli_prepare($connect, $sqlPurchase);
mysqli_stmt_bind_param($stmtPurchase, "i", $purchaseId);
$executedPurchase = mysqli_stmt_execute($stmtPurchase);
mysqli_stmt_close($stmtPurchase);

//redirect session message
if($executedItems && $executedPurchase){
    $_SESSION['success_msg'] = "Purchase deleted successfully.";
    header("Location: ../purchases.php?deleted=1");
    exit();
} else {
    $_SESSION['error_msg'] = "Failed to delete purchase.";
    header("Location: ../purchases.php?error=DeleteFailed");
    exit();
}
?>
