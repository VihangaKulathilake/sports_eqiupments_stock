<?php
$pageTitle = "Orders";
$cssFile = "css/orders.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<div class="orders-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Order Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql="SELECT * FROM orders;";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['order_id']."</td>";
            echo"<td>".$row['customer_id']."</td>";
            echo"<td>".$row['product_id']."</td>";
            echo"<td>".$row['quantity']."</td>";
            echo"<td>".$row['order_date']."</td>";
            echo"<td>".$row['order_status']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='edit' onclick=\"location.href='edit.php?id=".$row['order_id']."'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this user?')) location.href='delete.php?id=".$row['order_id']."'\">Delete</button>
                    </div>
                </td>";
            echo"</tr>";
            }
        }
    ?>
    </table>
    </div>
</div>

<?php
include 'footer.php';
?>