<?php
//Empty cart
session_start();
require_once 'db.php';

unset($_SESSION['cart']);

if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
    $sql = "DELETE FROM customer_cart WHERE customer_id=?";
    if ($stmt = mysqli_prepare($connect, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $customerId);

        if (!mysqli_stmt_execute($stmt)) {
            error_log("Error clearing cart: " . mysqli_error($connect));
        }

        mysqli_stmt_close($stmt);
    }
}

//Redirect to cart
header("Location:../cart.php");
exit();
?>
