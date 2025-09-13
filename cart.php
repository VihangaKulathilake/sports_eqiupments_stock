<?php
require_once 'includes/db.php';
$pageTitle="Shopping Cart";
$cssFile="css/cart.css";
include 'userHeader.php';

//fetch products from user cart
$cart = $_SESSION['cart'] ?? [];

if(isset($_SESSION['customer_id'])){
    $customerId = $_SESSION['customer_id'];
    $sql = "SELECT product_id, quantity FROM customer_cart WHERE customer_id=?";
    if($stmt = mysqli_prepare($connect, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $customerId);
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
    echo "<p style='text-align:center;'>Your cart is empty.</p>";
    include 'footer.php';
    exit();
}

$productIds = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$sql = "SELECT * FROM products WHERE product_id IN ($placeholders)";
if($stmt = mysqli_prepare($connect, $sql)){
    $types = str_repeat('i', count($productIds));
    mysqli_stmt_bind_param($stmt, $types, ...$productIds);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

$products = [];
while($row = mysqli_fetch_assoc($result)){
    $products[$row['product_id']] = $row;
}

//display cart
$grandTotal = 0;
?>
<div class="cart-container">
    <h2>Your Cart</h2>
    <div class="cart-table-wrapper">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cart as $pId => $qty):
                    if(!isset($products[$pId])) continue;
                    $product = $products[$pId];
                    $total = $product['price'] * $qty;
                    $grandTotal += $total;
                ?>
                <tr>
                    <td><img src="productImgs/<?=htmlspecialchars($product['image_path'])?>" alt="<?=htmlspecialchars($product['name'])?>"></td>
                    <td><?=htmlspecialchars($product['name'])?></td>
                    <td>$<?=number_format($product['price'],2)?></td>
                    <td><?=$qty?></td>
                    <td>$<?=number_format($total,2)?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="grand-total">
                    <td colspan="4">Grand Total</td>
                    <td>$<?=number_format($grandTotal,2)?></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- cart actions -->
    <div class="cart-actions">
        <?php if (!empty($_SESSION['fromProduct']) && !empty($_SESSION['backProductId'])):
            $backProductId = (int)$_SESSION['backProductId']; ?>
            <a href="viewProductPurchase.php?product_id=<?=$backProductId?>" class="btn-back">Back</a>
        <?php endif; ?>
        <a href="checkout.php" class="btn-buy">Proceed to Checkout</a>
        <a href="includes/clearCart.inc.php" class="btn-clear">Clear Cart</a>
    </div>
</div>
<?php include 'footer.php'; ?>
