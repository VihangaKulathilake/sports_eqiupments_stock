<?php
$pageTitle = "Low Stock Items";
$cssFile = "css/lowStockItems.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<div class="low-stock-items-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Stock ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql = "SELECT * FROM stocks WHERE quantity < 10";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['stock_id']."</td>";
            echo"<td>".$row['product_id']."</td>";
            echo"<td>".$row['quantity']."</td>";
            echo"<td>".$row['last_updated']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='view-product' onclick=\"location.href='displayProduct.php?id=" . $row['product_id'] . "'\">View Product</button>
                        <button class='refill-stock' onclick=\"if(confirm('Delete this user?')) location.href='refillProduct.php?id=".$row['stock_id']."'\">Refill Stock</button>
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