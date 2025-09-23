<?php
session_start();
require_once 'db.php';

if(isset($_POST['submit'])){
    $purchaseId = intval($_POST['orderId']);
    $orderStatus = $_POST['orderStatus'];
    $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

    // Update purchase status
    $sqlPurchase = "UPDATE supplier_orders SET order_status=? WHERE supplier_order_id=?";
    $stmtPurchase = mysqli_prepare($connect, $sqlPurchase);
    mysqli_stmt_bind_param($stmtPurchase, "si", $orderStatus, $purchaseId);
    $executedPurchase = mysqli_stmt_execute($stmtPurchase);
    mysqli_stmt_close($stmtPurchase);

    // Update each purchase item quantity
    $allExecuted = true;
    foreach($quantities as $itemId => $qty){
        $qty = intval($qty);
        if($qty < 1) $qty = 1;

        $sqlItem = "UPDATE supplier_order_items SET quantity=? WHERE supplier_order_item_id=? AND supplier_order_id=?";
        $stmtItem = mysqli_prepare($connect, $sqlItem);
        mysqli_stmt_bind_param($stmtItem, "iii", $qty, $itemId, $purchaseId);
        if(!mysqli_stmt_execute($stmtItem)){
            $allExecuted = false;
        }
        mysqli_stmt_close($stmtItem);
    }

    // Set session messages
    if($executedPurchase && $allExecuted){
        $_SESSION['success_msg'] = "Purchase #$purchaseId updated successfully.";
        header("Location: ../purchases.php?updated=1");
        exit();
    } else {
        $_SESSION['error_msg'] = "Failed to update Purchase #$purchaseId.";
        header("Location: ../editPurchase.php?id=$purchaseId&error=UpdateFailed");
        exit();
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: ../purchases.php?error=InvalidRequest");
    exit();
}
?>
