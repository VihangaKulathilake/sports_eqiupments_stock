<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error_msg'] = "Please login first.";
    header("Location: ../login.php");
}

$customerId = $_SESSION['customer_id'];
$productId = intval($_POST['product_id']);

$sql = "SELECT * FROM user_favourites WHERE customer_id=$customerId AND product_id=$productId";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result)>0) {
    $deleteSql = "DELETE FROM user_favourites WHERE customer_id=$customerId AND product_id=$productId";
    mysqli_query($connect, $deleteSql);
    $_SESSION['success_msg'] = "Removed from the favourites successfully.";
} else {
    $insertSql = "INSERT INTO user_favourites (customer_id, product_id) VALUES ($customerId, $productId)";
    mysqli_query($connect, $insertSql);
    $_SESSION['success_msg'] = "Added to the favourites successfully.";
}

mysqli_close($connect);

header("Location: " .$_SERVER['HTTP_REFERER']);
exit;
?>
