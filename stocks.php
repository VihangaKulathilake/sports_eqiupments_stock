<?php
$pageTitle = "Admin Stocks";
$cssFile = "css/stocks.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<div class="stocks-container">
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
        $sql="SELECT * FROM stocks;";
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
                        <button class='edit' onclick=\"location.href='edit.php?id=".$row['stock_id']."'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this user?')) location.href='delete.php?id=".$row['stock_id']."'\">Delete</button>
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