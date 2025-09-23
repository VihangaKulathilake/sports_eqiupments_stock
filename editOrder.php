<?php
$pageTitle = "Edit Order";
$cssFile = "css/editOrder.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<!-- toast messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php
if (!isset($_GET['id'])) {
    $_SESSION['error_msg'] = "No order selected.";
    header("Location: orders.php?error=NoOrderSelected");
    exit();
}

$redirectPage = "orders.php";
if(isset($_GET['from']) && $_GET['from']==="recentOrders"){
    $redirectPage = "recentOrders.php";
}

$orderId = intval($_GET['id']);

//fetch order and customer
$sql = "SELECT o.order_id, o.customer_id, o.order_status, c.name AS customer_name 
        FROM orders o 
        JOIN customers c ON o.customer_id = c.customer_id 
        WHERE o.order_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $orderId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if(!$order){
    $_SESSION['error_msg'] = "Order not found.";
    header("Location: $redirectPage?error=OrderNotFound");
    exit();
}

if($order['order_status'] === 'completed'){
    $_SESSION['error_msg'] = "Cannot edit a completed order.";
    header("Location: $redirectPage?error=CannotEditCompleted");
    exit();
}

//fetch order items
$sqlItems = "SELECT oi.order_item_id, p.product_id, p.name, oi.quantity, p.price 
             FROM order_items oi
             JOIN products p ON oi.product_id = p.product_id
             WHERE oi.order_id=?";
$stmtItems = mysqli_prepare($connect, $sqlItems);
mysqli_stmt_bind_param($stmtItems, "i", $orderId);
mysqli_stmt_execute($stmtItems);
$resultItems = mysqli_stmt_get_result($stmtItems);

$orderItems = [];
while($row = mysqli_fetch_assoc($resultItems)){
    $orderItems[] = $row;
}
mysqli_stmt_close($stmtItems);
?>

<!-- Edit order form -->
<div class="edit-order-container">
    <h1>Edit Order #<?= $order['order_id'] ?></h1>
    <p><strong>Customer: <?= htmlspecialchars($order['customer_name']) ?> (ID: <?= $order['customer_id'] ?>)</strong></p>

    <form action="includes/editOrder.inc.php" method="post">
        <input type="hidden" name="orderId" value="<?= $order['order_id'] ?>">
        <input type="hidden" name="from" value="<?= isset($_GET['from']) ? $_GET['from'] : '' ?>">

        <label for="orderStatus">Order Status:</label>
        <select name="orderStatus" id="orderStatus">
            <option value="pending" <?= $order['order_status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="completed" <?= $order['order_status']=='completed'?'selected':'' ?>>Completed</option>
        </select>

        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (LKR.)</th>
                    <th>Total (LKR.)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orderItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>
                        <input type="number" name="quantities[<?= $item['order_item_id'] ?>]" 
                               value="<?= $item['quantity'] ?>" min="1">
                    </td>
                    <td><?= number_format($item['price'],2) ?></td>
                    <td><?= number_format($item['price']*$item['quantity'],2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" name="submit">Save Changes</button>
        <a href="<?= $redirectPage ?>" class="cancel-btn">Cancel</a>
    </form>
</div>

<?php
    include 'footer.php';
?>

<script src="js/toast.js"></script>
