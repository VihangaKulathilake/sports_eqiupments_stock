<?php
$pageTitle = "Purchases";
$cssFile = "css/purchases.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<div class="purchases-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Supplier ID</th>
                <th>Order Date</th>
                <th>Order Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql="SELECT * FROM supplier_orders;";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['supplier_order_id']."</td>";
            echo"<td>".$row['supplier_id']."</td>";
            echo"<td>".$row['order_date']."</td>";
            echo"<td>".$row['order_status']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='edit' onclick=\"location.href='edit.php?id=".$row['supplier_order_id']."'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this user?')) location.href='delete.php?id=".$row['supplier_order_id']."'\">Delete</button>
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