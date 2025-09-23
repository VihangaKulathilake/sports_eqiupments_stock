<?php
$pageTitle = "Purchases";
$cssFile = "css/purchases.css";
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


<div class="purchases-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Supplier ID</th>
                <th>Order Date</th>
                <th>Order Status</th>
                <th>Managee Purchases</th>
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
                        <button class='edit' onclick=\"location.href='editPurchase.php?id=".$row['supplier_order_id']."'\"><img src='imgs/edit.png' class='btn-icon' alt='view-icon'>Edit</button>
                        <button class='delete' onclick=\"location.href='includes/deletePurchase.inc.php?id=".$row['supplier_order_id']."'\"><img src='imgs/trash.png' class='btn-icon' alt='view-icon'>Delete</button>
                    </div>
                </td>";
            echo"</tr>";
            }
        }
    ?>
    </table>
    </div>
    <a href="adminDashboard.php" class="btn-back">Back</a>
</div>

<!-- animate toast message -->
<script src="js/toast.js"></script>

<?php
include 'footer.php';
?>