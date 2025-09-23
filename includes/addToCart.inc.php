<?php
session_start();
require_once 'db.php'; 

// Debugging session 
var_dump($_SESSION);


if (!isset($_POST['add_to_cart'])) {
    echo "Error: No form data received.";
    exit();
}

// Validate input
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (isset($_POST['fromProduct']) && $_POST['fromProduct'] == "1") {
    $_SESSION['fromProduct'] = true;
    $_SESSION['backProductId'] = $productId;
}

if ($productId <= 0 || $quantity <= 0) {
    echo "Error: Invalid product or quantity.";
    exit();
}

// Initialize session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update session cart
if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] += $quantity;
} else {
    $_SESSION['cart'][$productId] = $quantity;
}

// If customer is logged in, update database
if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
    $sql = "INSERT INTO customer_cart (customer_id, product_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?";
    if ($stmt = mysqli_prepare($connect, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiii", $customerId, $productId, $quantity, $quantity);
        if (!mysqli_stmt_execute($stmt)) {
            echo "Database error: " . mysqli_error($connect);
            exit();
        }
    } else {
        echo "Database prepare error: " . mysqli_error($connect);
        exit();
    }
}

// Save "from product page" reference for Back button
if (isset($_POST['fromProduct']) && $_POST['fromProduct'] == "1") {
    $_SESSION['fromProduct']=true;
    $_SESSION['backProductId']=$productId;
}

// Redirect back or to cart page
header("Location: ../cart.php?product_id=$productId&added=true");
exit();
?>
