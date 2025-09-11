<?php
require_once 'includes/db.php';
$pageTitle = "Shopping Cart";
$cssFile = "css/cart.css";
include 'adminHeader.php';

//show the cart if logged in
$cart = $_SESSION['cart'] ?? [];

if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
    $sql = "SELECT product_id, quantity FROM customer_cart WHERE customer_id = ?";
    if ($stmt = mysqli_prepare($connect, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $customerId);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $cart = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $cart[$row['product_id']] = $row['quantity'];
            }
            $_SESSION['cart'] = $cart;
        } else {
            echo "Error fetching cart: " . mysqli_error($connect);
            exit();
        }
    } else {
        echo "Database prepare error: " . mysqli_error($connect);
        exit();
    }
}

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    include 'footer.php';
    exit();
}

//fetch product details in the cart
$productIds = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$sql = "SELECT * FROM products WHERE product_id IN ($placeholders)";
if ($stmt = mysqli_prepare($connect, $sql)) {
    $types = str_repeat('i', count($productIds));
    mysqli_stmt_bind_param($stmt, $types, ...$productIds);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Error fetching products: " . mysqli_error($connect);
        exit();
    }
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "Database prepare error: " . mysqli_error($connect);
    exit();
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[$row['product_id']] = $row;
}

//display cart
$grandTotal = 0;
echo '<div class="cart-container">';
echo '<h2>Your Cart</h2>';
echo '<table class="cart-table">';
echo '<thead><tr>
        <th>Image</th>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
      </tr></thead><tbody>';

foreach ($cart as $pId => $qty) {
    if (!isset($products[$pId])) continue; // Skip missing products
    $product = $products[$pId];
    $total = $product['price'] * $qty;
    $grandTotal += $total;

    echo '<tr>';
    echo '<td><img src="productImgs/' . htmlspecialchars($product['image_path']) . '" alt="' . htmlspecialchars($product['name']) . '"></td>';
    echo '<td>' . htmlspecialchars($product['name']) . '</td>';
    echo '<td>$' . number_format($product['price'], 2) . '</td>';
    echo '<td>' . $qty . '</td>';
    echo '<td>$' . number_format($total, 2) . '</td>';
    echo '</tr>';
}

echo '<tr class="grand-total">';
echo '<td colspan="4">Grand Total</td>';
echo '<td>$' . number_format($grandTotal, 2) . '</td>';
echo '</tr>';

echo '</tbody></table>';
echo '<div class="cart-actions">';
echo '<a href="checkout.php" class="btn-buy">Proceed to Checkout</a>';
echo '<a href="clearCart.php" class="btn-clear">Clear Cart</a>';
echo '</div></div>';

include 'footer.php';
?>
