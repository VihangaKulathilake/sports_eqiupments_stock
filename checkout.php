<?php
require_once 'includes/db.php';
$pageTitle="Checkout";
$cssFile="css/checkout.css";
include 'userHeader.php';

//check if user logged in
if(!isset($_SESSION['customer_id'])){
    echo "<p>Please <a href='login.php'>log in</a> to proceed to checkout.</p>";
    include 'footer.php';
    exit();
}

$customerId = $_SESSION['customer_id'];

//buy now option
if(isset($_POST['product_id']) && isset($_POST['quantity']) && !isset($_POST['confirm_order'])){
    $buyProductId = (int)$_POST['product_id'];
    $buyQuantity = (int)$_POST['quantity'];
    $_SESSION['cart'] = [$buyProductId => $buyQuantity];
}

//load cart
$cart = $_SESSION['cart'] ?? [];
if(empty($cart)){
    $sql = "SELECT product_id, quantity FROM customer_cart WHERE customer_id=?";
    if($stmt = mysqli_prepare($connect, $sql)){
        mysqli_stmt_bind_param($stmt,"i", $customerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $cart = [];
        while($row = mysqli_fetch_assoc($result)){
            $cart[$row['product_id']] = $row['quantity'];
        }
        $_SESSION['cart'] = $cart;
    }
}

if(empty($cart)){
    echo "<p>Your cart is empty.</p>";
    include 'footer.php';
    exit();
}

//fetch product details
$productIds = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$sql = "SELECT * FROM products WHERE product_id IN ($placeholders)";
$stmt = mysqli_prepare($connect, $sql);
$types = str_repeat('i', count($productIds));
mysqli_stmt_bind_param($stmt, $types, ...$productIds);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
while($row = mysqli_fetch_assoc($result)){
    $products[$row['product_id']] = $row;
}

//confirm order
if(isset($_POST['confirm_order'])){
    $error = false;
    mysqli_begin_transaction($connect);

    $sqlOrder = "INSERT INTO orders (customer_id, order_date, order_status) VALUES (?, CURDATE(), 'pending')";
    if($stmtOrder = mysqli_prepare($connect, $sqlOrder)){
        mysqli_stmt_bind_param($stmtOrder, "i", $customerId);
        if(mysqli_stmt_execute($stmtOrder)){
            $orderId = mysqli_insert_id($connect);

            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";

            foreach($cart as $pId => $qty){
                // Insert order item
                if($stmtItem = mysqli_prepare($connect, $sqlItem)){
                    mysqli_stmt_bind_param($stmtItem, "iii", $orderId, $pId, $qty);
                    if(!mysqli_stmt_execute($stmtItem)){
                        $error = true;
                        break;
                    }
                } else {
                    $error = true;
                    break;
                }

                $sqlGetStocks = "SELECT stock_id, quantity FROM stocks WHERE product_id=? AND quantity>0 ORDER BY last_updated ASC";
                $stmtGetStocks = mysqli_prepare($connect, $sqlGetStocks);
                mysqli_stmt_bind_param($stmtGetStocks, "i", $pId);
                mysqli_stmt_execute($stmtGetStocks);
                $resStocks = mysqli_stmt_get_result($stmtGetStocks);

                $remainingQty = $qty;

                while($remainingQty > 0 && ($stockRow = mysqli_fetch_assoc($resStocks))){
                    $stockId = $stockRow['stock_id'];
                    $stockQty = $stockRow['quantity'];

                    if($stockQty >= $remainingQty){
                        $newQty = $stockQty - $remainingQty;
                        $remainingQty = 0;
                    } else {
                        $newQty = 0;
                        $remainingQty -= $stockQty;
                    }

                    $sqlUpdateStock = "UPDATE stocks SET quantity=?, last_updated=NOW() WHERE stock_id=?";
                    $stmtUpdateStock = mysqli_prepare($connect, $sqlUpdateStock);
                    mysqli_stmt_bind_param($stmtUpdateStock, "ii", $newQty, $stockId);
                    if(!mysqli_stmt_execute($stmtUpdateStock)){
                        $error = true;
                        break 2;
                    }
                }

                mysqli_stmt_close($stmtGetStocks);

                if($remainingQty > 0){
                    $error = true;
                    break;
                }
            }

        } else {
            $error = true;
        }
    } else {
        $error = true;
    }

    if($error){
        mysqli_rollback($connect);
        echo "<script>alert('Failed to place the order. Please try again.');</script>";
    } else {
        mysqli_commit($connect);

        //clear cart
        $_SESSION['cart'] = [];
        $sql = "DELETE FROM customer_cart WHERE customer_id=?";
        if($stmt = mysqli_prepare($connect, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $customerId);
            mysqli_stmt_execute($stmt);
        }

        header("Location: thankyou.php?order_id=$orderId");
        exit();
    }
}

//display checkout table
$grandTotal = 0;
echo '<div class="checkout-container">';
echo '<h2>Checkout</h2>';
echo '<div class="checkout-table-container">';
echo '<table class="checkout-table">';
echo '<thead><tr>
        <th>Image</th>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
      </tr></thead><tbody>';

foreach($cart as $pId => $qty){
    if(!isset($products[$pId])) continue;
    $product = $products[$pId];
    $total = $product['price'] * $qty;
    $grandTotal += $total;

    echo '<tr>';
    echo '<td><img src="productImgs/'.htmlspecialchars($product['image_path']).'" alt="'.htmlspecialchars($product['name']).'"></td>';
    echo '<td>'.htmlspecialchars($product['name']).'</td>';
    echo '<td>LKR.'.number_format($product['price'],2).'</td>';
    echo '<td>'.$qty.'</td>';
    echo '<td>LKR.'.number_format($total,2).'</td>';
    echo '</tr>';
}

echo '<tr class="grand-total">';
echo '<td colspan="4">Grand Total</td>';
echo '<td>LKR.'.number_format($grandTotal,2).'</td>';
echo '</tr>';
echo '</tbody></table>';
echo '</div>';

//buttons
echo '<form method="POST" class="confirm-order-form">';
echo '<div class="form-buttons">';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $backProductId = (int)$_POST['product_id'];
    
    $backProductName = isset($products[$backProductId]) ? $products[$backProductId]['name'] : '';

    echo '<a href="viewProductPurchase.php?product_id=' . $backProductId . '&name=' . urlencode($backProductName) . '" class="btn-back">Back</a>';
} else {
    echo '<a href="cart.php" class="btn-back">Back</a>';
}

echo '<button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>';
echo '</div>';
echo '</form>';
echo '</div>';

include 'footer.php';
?>
