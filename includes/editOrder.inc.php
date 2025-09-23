<?php
session_start();
require_once 'db.php';

$redirectPage = "orders.php";

if(isset($_POST['submit'])){
    $orderId = intval($_POST['orderId']);
    $orderStatus = $_POST['orderStatus'];
    $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

    // Determine redirect page from hidden input
    if(isset($_POST['from']) && $_POST['from'] === "recentOrders"){
        $redirectPage = "recentOrders.php";
    }

    // Update order status
    $sqlOrder = "UPDATE orders SET order_status=? WHERE order_id=?";
    $stmtOrder = mysqli_prepare($connect, $sqlOrder);
    mysqli_stmt_bind_param($stmtOrder, "si", $orderStatus, $orderId);
    $executedOrder = mysqli_stmt_execute($stmtOrder);
    mysqli_stmt_close($stmtOrder);

    // Update each order item quantity
    $allExecuted = true;
    foreach($quantities as $orderItemId => $qty){
        $qty = intval($qty);
        if($qty < 1) $qty = 1;

        $sqlItem = "UPDATE order_items SET quantity=? WHERE order_item_id=? AND order_id=?";
        $stmtItem = mysqli_prepare($connect, $sqlItem);
        mysqli_stmt_bind_param($stmtItem, "iii", $qty, $orderItemId, $orderId);
        if(!mysqli_stmt_execute($stmtItem)){
            $allExecuted = false;
        }
        mysqli_stmt_close($stmtItem);
    }

    // Set session messages
    if($executedOrder && $allExecuted){
        $_SESSION['success_msg'] = "Order #$orderId updated successfully.";
        header("Location: ../$redirectPage?success=OrderUpdated");
        exit();
    } else {
        $_SESSION['error_msg'] = "Failed to update Order #$orderId.";
        header("Location: ../editOrder.php?id=$orderId&error=UpdateFailed");
        exit();
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: ../$redirectPage?error=InvalidRequest");
    exit();
}
