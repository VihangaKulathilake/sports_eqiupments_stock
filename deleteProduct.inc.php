<?php
session_start();
require_once 'db.php';

$id=isset($_GET['id'])?intval($_GET['id']):null;

if(!$id){
    header('Location: ../products.php?error=NoProductSelected');
    exit();
}

//check if product exists in any order_items
$sql="SELECT COUNT(*) FROM order_items WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$orderItemCount);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if($orderItemCount>0){
    $_SESSION['error_msg'] = "Product in Orders.";
    header("Location: ../products.php?error=ProductInOrders");
    exit();
}

//Delete related supplier_order_items
$sql="DELETE FROM supplier_order_items WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//Delete related stocks
$sql="DELETE FROM stocks WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//Get product image
$sql="SELECT image_path FROM products WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$imagePath);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

//Delete product
$sql="DELETE FROM products WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
$executed=mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//Delete image if exists
if($executed&&!empty($imagePath)&&file_exists("../productImgs/".$imagePath)){
    unlink("../productImgs/".$imagePath);
}

//Redirect
if($executed){
    $_SESSION['success_msg'] = "Product deleted successfully.";
    header("Location: ../products.php?deleted=1");
    exit();
}else{
    $_SESSION['error_msg'] = "Delete failed.";
    header("Location: ../products.php?error=DeleteFailed");
    exit();
}
?>
