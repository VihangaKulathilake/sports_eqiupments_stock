<?php
session_start();
require_once 'db.php';

// Determine redirect page from GET parameter
$redirectPage = "orders.php";
if(isset($_GET['from']) && $_GET['from'] === "recentOrders"){
    $redirectPage = "recentOrders.php";
}


$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if(!$id){
    $_SESSION['error_msg']="No order selected.";
    header("Location:../orders.php?error=NoOrderSelected");
    exit();
}

//check if order exists and status
$sqlCheck="SELECT order_status FROM orders WHERE order_id=?";
$stmtCheck=mysqli_prepare($connect, $sqlCheck);
mysqli_stmt_bind_param($stmtCheck, "i", $id);
mysqli_stmt_execute($stmtCheck);
$resultCheck=mysqli_stmt_get_result($stmtCheck);
$order=mysqli_fetch_assoc($resultCheck);
mysqli_stmt_close($stmtCheck);

if(!$order){
    $_SESSION['error_msg']="Order not found.";
    header("Location:../$redirectPage?error=OrderNotFound");
    exit();
}

//prevent deleting completed orders
if($order['order_status']==='completed'){
    $_SESSION['error_msg']="Cannot delete a completed order.";
    header("Location:../$redirectPage?error=CannotDeleteCompleted");
    exit();
}

//delete order items
$sqlItems="DELETE FROM order_items WHERE order_id=?";
$stmtItems=mysqli_prepare($connect, $sqlItems);
mysqli_stmt_bind_param($stmtItems, "i", $id);
$executedItems=mysqli_stmt_execute($stmtItems);
mysqli_stmt_close($stmtItems);

//delete the order
$sqlOrder="DELETE FROM orders WHERE order_id=?";
$stmtOrder=mysqli_prepare($connect, $sqlOrder);
mysqli_stmt_bind_param($stmtOrder, "i", $id);
$executedOrder=mysqli_stmt_execute($stmtOrder);
mysqli_stmt_close($stmtOrder);

//redirect to orders table
if($executedItems && $executedOrder){
    $_SESSION['success_msg']="Order deleted successfully.";
    header("Location: ../$redirectPage?deleted=1");
    exit();
} else {
    $_SESSION['error_msg']="Failed to delete order.";
    header("Location: ../$redirectPage?error=DeleteFailed");
    exit();
}
