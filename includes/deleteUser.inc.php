<?php
session_start();
require_once 'db.php';

//determine the redirect page
$redirectPage = "users.php";
if (isset($_GET['from']) && $_GET['from'] === "topCustomers") {
    $redirectPage = "topCustomers.php";
}

//check if the user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "No user selected.";
    header("Location: ../$redirectPage");
    exit();
}

$userId = intval($_GET['id']);

//check if the user has pending orders, if he/she has cannot delete
$sqlPending = "SELECT COUNT(*) AS pendingCount FROM orders WHERE customer_id=? AND order_status='pending'";
$stmtPending = mysqli_prepare($connect, $sqlPending);
mysqli_stmt_bind_param($stmtPending, "i", $userId);
mysqli_stmt_execute($stmtPending);
$resultPending = mysqli_stmt_get_result($stmtPending);
$rowPending = mysqli_fetch_assoc($resultPending);

if ($rowPending['pendingCount'] > 0) {
    $_SESSION['error_msg'] = "Cannot delete user.They have pending orders.";
    header("Location: ../$redirectPage");
    exit();
}

//delete user's completed orders
$sqlDeleteOrders = "DELETE FROM orders WHERE customer_id=?";
$stmtDeleteOrders = mysqli_prepare($connect, $sqlDeleteOrders);
mysqli_stmt_bind_param($stmtDeleteOrders, "i", $userId);
mysqli_stmt_execute($stmtDeleteOrders);

//delete user
$sqlDeleteUser = "DELETE FROM customers WHERE customer_id=?";
$stmtDeleteUser = mysqli_prepare($connect, $sqlDeleteUser);
mysqli_stmt_bind_param($stmtDeleteUser, "i", $userId);

if (mysqli_stmt_execute($stmtDeleteUser)) {
    $_SESSION['success_msg'] = "User and all related data deleted successfully.";
} else {
    $_SESSION['error_msg'] = "User deletion failed.";
}

header("Location: ../$redirectPage");
exit();
?>
