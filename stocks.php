<?php
$pageTitle = "Admin Stocks";
$cssFile = "css/stocks.css";
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
//show stock deletion alert
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    echo "<script>alert('Stock deleted successfully!');</script>";
}

if (isset($_GET['error'])) {
    $errorMsg = '';
    if ($_GET['error'] == 'NoStockSelected') {
        $errorMsg = 'No stock selected to delete.';
    } elseif ($_GET['error'] == 'DeleteFailed') {
        $errorMsg = 'Failed to delete stock. Try again.';
    }
    if ($errorMsg) {
        echo "<script>alert('$errorMsg');</script>";
    }
}
?>

<!-- stocks table -->
<div class="stocks-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Stock ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Last Updated</th>
                <th>Manage Stocks</th>
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
                        <button class='edit' onclick=\"location.href='updateStock.php?id={$row['stock_id']}&from=stocks'\"><img src='imgs/edit.png' class='btn-icon' alt='view-icon'>Edit</button>
                        <button class='delete' onclick=\"location.href='includes/deleteStock.inc.php?id={$row['stock_id']}&from=stocks'\"><img src='imgs/trash.png' class='btn-icon' alt='view-icon'>Delete</button>
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

<script src="js/toast.js"></script>
<?php
include 'footer.php';
?>