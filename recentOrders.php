<?php
$pageTitle = "Orders";
$cssFile = "css/recentOrders.css";
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

<div class="recent-orders-container">
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //fetch the last 5 order IDs
        $lastOrders = [];
        $lastOrdersSql = "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 5";
        $res = mysqli_query($connect, $lastOrdersSql);
        while($row = mysqli_fetch_assoc($res)){
            $lastOrders[] = $row['order_id'];
        }

        if(empty($lastOrders)){
            echo "<tr><td colspan='7'>No orders found</td></tr>";
        } else {
            //Fetch order details and items
            $orderIdsStr = implode(',', $lastOrders);
            $sql = "
            SELECT o.order_id, o.customer_id, c.name AS customer_name,
                   o.order_date, o.order_status,
                   p.name AS product_name, oi.quantity, p.price
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN products p ON oi.product_id = p.product_id
            WHERE o.order_id IN ($orderIdsStr)
            ORDER BY o.order_id DESC
            ";

            $result = mysqli_query($connect, $sql);

            //Group orders by order_id
            $orders = [];
            while($row = mysqli_fetch_assoc($result)){
                $orderId = $row['order_id'];
                if(!isset($orders[$orderId])){
                    $orders[$orderId] = [
                        'customer_name' => $row['customer_name'],
                        'customer_id' => $row['customer_id'],
                        'order_date' => $row['order_date'],
                        'order_status' => $row['order_status'],
                        'items' => [],
                        'amount' => 0
                    ];
                }
                $itemTotal = $row['price'] * $row['quantity'];
                $orders[$orderId]['items'][] = $row['product_name']." (x".$row['quantity'].") - Rs.".number_format($itemTotal,2);
                $orders[$orderId]['amount'] += $itemTotal;
            }

            //display orders in the table
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
                echo "<td>LKR. ".number_format($order['amount'], 2)."</td>";
                echo "<td>
                        <div class='actions-container'>
                            <button class='edit' onclick=\"location.href='editOrder.php?id={$orderId}&from=recentOrders'\">Edit</button>
                            <button class='delete' onclick=\"if(confirm('Delete this order?')) location.href='includes/deleteOrder.inc.php?id={$orderId}&from=recentOrders'\">Delete</button>
                        </div>
                    </td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>
        </table>
    </div>
</div>

<!-- animate toast message -->
<script src="js/toast.js"></script>

<?php
include 'footer.php';
?>
