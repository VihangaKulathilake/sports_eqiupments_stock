<?php
session_start();
require_once 'db.php';

$id=isset($_GET['id'])?intval($_GET['id']):null;

if(!$id){
    $_SESSION['error_msg']="No supplier selected.";
    header("Location:../suppliers.php?error=NoSupplierSelected");
    exit();
}

//check if supplier has linked products
$sqlCheckProducts="SELECT COUNT(*) AS count FROM products WHERE supplier_id=?";
$stmtCheckProducts=mysqli_prepare($connect,$sqlCheckProducts);
mysqli_stmt_bind_param($stmtCheckProducts,"i",$id);
mysqli_stmt_execute($stmtCheckProducts);
$resultCheckProducts=mysqli_stmt_get_result($stmtCheckProducts);
$rowProducts=mysqli_fetch_assoc($resultCheckProducts);
mysqli_stmt_close($stmtCheckProducts);

if($rowProducts['count']>0){
    $_SESSION['error_msg']="Cannot delete supplier with existing products.";
    header("Location:../suppliers.php?error=SupplierHasProducts");
    exit();
}

//check if supplier has pending orders
$sqlCheckOrders="SELECT COUNT(*) AS count FROM supplier_orders WHERE supplier_id=? AND order_status='pending'";
$stmtCheckOrders=mysqli_prepare($connect,$sqlCheckOrders);
mysqli_stmt_bind_param($stmtCheckOrders,"i",$id);
mysqli_stmt_execute($stmtCheckOrders);
$resultCheckOrders=mysqli_stmt_get_result($stmtCheckOrders);
$rowOrders=mysqli_fetch_assoc($resultCheckOrders);
mysqli_stmt_close($stmtCheckOrders);

if($rowOrders['count']>0){
    $_SESSION['error_msg']="Cannot delete supplier with pending orders.";
    header("Location:../suppliers.php?error=SupplierHasPendingOrders");
    exit();
}

//delete supplier
$sqlDelete="DELETE FROM suppliers WHERE supplier_id=?";
$stmtDelete=mysqli_prepare($connect,$sqlDelete);
mysqli_stmt_bind_param($stmtDelete,"i",$id);
$executed=mysqli_stmt_execute($stmtDelete);
mysqli_stmt_close($stmtDelete);

if($executed){
    $_SESSION['success_msg']="Supplier deleted successfully.";
    header("Location:../suppliers.php?deleted=1");
    exit();
}else{
    $_SESSION['error_msg']="Failed to delete supplier.";
    header("Location:../suppliers.php?error=DeleteFailed");
    exit();
}
?>
