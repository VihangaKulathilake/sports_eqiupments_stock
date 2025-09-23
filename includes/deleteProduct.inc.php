<?php
session_start();
require_once 'db.php';

// Determine redirect page from GET parameter
$redirectPage = "products.php";
if(isset($_GET['from']) && $_GET['from'] === "topProducts"){
    $redirectPage = "topproducts.php";
}


$id=isset($_GET['id'])?intval($_GET['id']):null;

if(!$id){
    $_SESSION['error_msg'] = "No Products Selected.";
    header("Location: ../$redirectPage?error=NoProductsSelected");
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
    header("Location: ../$redirectPage?error=ProductInOrders");
    exit();
}

//delete related supplier_order_items
$sql="DELETE FROM supplier_order_items WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//delete related stocks
$sql="DELETE FROM stocks WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);


$sql="SELECT image_path FROM products WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$imagePath);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

//delete product
$sql="DELETE FROM products WHERE product_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$id);
$executed=mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//delete image if exists
if($executed&&!empty($imagePath)&&file_exists("../productImgs/".$imagePath)){
    unlink("../productImgs/".$imagePath);
}

if($executed){
    $_SESSION['success_msg']="Product deleted successfully.";
    header("Location: ../$redirectPage?deleted=1");
    exit();
} else {
    $_SESSION['error_msg']="Failed to delete the product.";
    header("Location: ../$redirectPage?error=DeleteFailed");
    exit();
}
?>
