<?php
$pageTitle = "Orders";
$cssFile = "css/orders.css";
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

<div class="orders-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Products</th>
                <th>Order Date</th>
                <th>Order Status</th>
                <th>Order Amount (Rs.)</th>
                <th>Manage Orders</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //get orders with product prices
        $sql = "SELECT o.order_id,o.customer_id,c.name AS customer_name,o.order_date,
                o.order_status,p.name AS product_name,p.price,oi.quantity
                FROM orders o
                JOIN customers c ON o.customer_id = c.customer_id
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                ORDER BY o.order_id DESC";

        $result = mysqli_query($connect, $sql);

        $orders = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $orderId = $row['order_id'];
                if (!isset($orders[$orderId])) {
                    $orders[$orderId] = [
                        'customer_name'=> $row['customer_name'],
                        'customer_id'=> $row['customer_id'],
                        'order_date'=> $row['order_date'],
                        'order_status'=> $row['order_status'],
                        'items'=> [],
                        'amount'=> 0
                    ];
                }

                //calculate the order amount
                $itemTotal = $row['price'] * $row['quantity'];
                $orders[$orderId]['items'][] = $row['product_name']." (x".$row['quantity'].") - Rs.".number_format($itemTotal, 2);
                $orders[$orderId]['amount'] += $itemTotal;
            }
        }

        //orders table
        if (!empty($orders)) {
            foreach ($orders as $orderId => $order) {
                echo "<tr>";
                echo "<td>".$orderId."</td>";
                echo "<td>".$order['customer_name']." (ID: ".$order['customer_id'].")</td>";
                echo "<td><ul>";
                foreach ($order['items'] as $item) {
                    echo "<li>".$item."</li>";
                }
                echo "</ul></td>";
                echo "<td>".$order['order_date']."</td>";
                echo "<td>".$order['order_status']."</td>";
                echo "<td>Rs. ".number_format($order['amount'], 2)."</td>";
                echo "<td>
                        <div class='actions-container'>
                            <button class='edit' onclick=\"location.href='editOrder.php?id={$orderId}&from=orders'\"><img src='imgs/edit.png' class='btn-icon' alt='view-icon'>Edit</button>
                            <button class='delete' onclick=\"location.href='includes/deleteOrder.inc.php?id={$orderId}&from=orders'\"><img src='imgs/trash.png' class='btn-icon' alt='view-icon'>Delete</button>
                        </div>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No orders found</td></tr>";
        }
        ?>
        </tbody>
        </table>
    </div>
    <a href="adminDashboard.php" class="btn-back">Back</a>
</div>

<!-- animate toast message -->
<script src="js/toast.js"></script>

<?php
include 'footer.php';
?>
