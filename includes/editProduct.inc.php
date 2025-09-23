<?php
session_start();
require_once 'db.php';
require_once 'functions.inc.php';

if(!isset($_POST["submit"])) {
    $_SESSION['error_msg'] = "No data submitted.";
    header("Location: ../products.php");
    exit();
}

// Get input values
$productId = intval($_POST["productId"]);
$pName = trim($_POST["pName"]);
$pDes = trim($_POST["pDes"]);
$pCat = trim($_POST["pCat"]);
$pPrice = trim($_POST["pPrice"]);
$pSup = trim($_POST["pSup"]);
$newFileName = null;

// Check empty fields
if(inputsEmptyProduct($pName, $pDes, $pCat, $pPrice, $pSup)) {
    $_SESSION['error_msg'] = "Please fill in all required fields.";
    header("Location: ../editProduct.php?id=$productId");
    exit();
}

// Handle optional image upload
if(isset($_FILES["pImg"]) && $_FILES["pImg"]["error"] !== 4) {
    $pImg = $_FILES["pImg"];
    $fileName = $pImg["name"];
    $fileTmpName = $pImg["tmp_name"];
    $fileSize = $pImg["size"];
    $fileError = $pImg["error"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ["jpg","jpeg","png","gif"];

    if(!in_array($fileExt, $allowed)) {
        $_SESSION['error_msg'] = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.";
        header("Location: ../editProduct.php?id=$productId");
        exit();
    }

    if($fileError !== 0) {
        $_SESSION['error_msg'] = "Error uploading file. Try again.";
        header("Location: ../editProduct.php?id=$productId");
        exit();
    }

    if($fileSize > 5 * 1024 * 1024) {
        $_SESSION['error_msg'] = "File too large. Max 5MB.";
        header("Location: ../editProduct.php?id=$productId");
        exit();
    }

    // Upload file
    $newFileName = uniqid("", true) . "." . $fileExt;
    $uploadPath = "../productImgs/";
    if(!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
    $fileDestination = $uploadPath . $newFileName;

    if(!move_uploaded_file($fileTmpName, $fileDestination)) {
        $_SESSION['error_msg'] = "Failed to upload image.";
        header("Location: ../editProduct.php?id=$productId");
        exit();
    }
}

// Build update query
if($newFileName) {
    $sql = "UPDATE products SET name=?, description=?, image_path=?, category=?, price=?, supplier_id=? WHERE product_id=?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ssssdii", $pName, $pDes, $newFileName, $pCat, $pPrice, $pSup, $productId);
} else {
    $sql = "UPDATE products SET name=?, description=?, category=?, price=?, supplier_id=? WHERE product_id=?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "sssdii", $pName, $pDes, $pCat, $pPrice, $pSup, $productId);
}

// Execute update
if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success_msg'] = "Product updated successfully.";
    header("Location: ../products.php");
    exit();
} else {
    $_SESSION['error_msg'] = "Failed to update product.";
    header("Location: ../editProduct.php?id=$productId");
    exit();
}
?>
