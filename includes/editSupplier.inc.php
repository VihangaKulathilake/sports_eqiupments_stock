<?php
session_start();
require_once 'db.php';

if(!isset($_POST['submit'])){
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: ../suppliers.php?error=InvalidRequest");
    exit();
}

$id = intval($_POST['supplier_id']);
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$redirectPage = "suppliers.php";

if(isset($_POST['from']) && $_POST['from'] === "topSuppliers"){
    $redirectPage = "topSuppliers.php";
}

// Validate fields
if(empty($name) || empty($email) || empty($phone) || empty($address)){
    $_SESSION['error_msg'] = "All fields are required.";
    header("Location: ../editSupplier.php?id=$id&from=" . ($_POST['from'] ?? '') . "&error=EmptyFields");
    exit();
}

// Update supplier
$sql = "UPDATE suppliers SET name=?, email=?, phone=?, address=? WHERE supplier_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $address, $id);
$executed = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if($executed){
    $_SESSION['success_msg'] = "Supplier updated successfully.";
    header("Location: ../$redirectPage?success=SupplierUpdated");
    exit();
} else {
    $_SESSION['error_msg'] = "Failed to update supplier.";
    header("Location: ../editSupplier.php?id=$id&from=" . ($_POST['from'] ?? '') . "&error=UpdateFailed");
    exit();
}
