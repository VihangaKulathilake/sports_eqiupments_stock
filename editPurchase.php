<?php
$pageTitle = "Edit Purchase";
$cssFile = "css/editPurchase.css";
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
    $_SESSION['error_msg'] = "No Purchase Selected.";
    header("Location: orders.php?error=NoPurchaseSelected");
    exit();
}

//get purchase with it's supplier
$purchaseId = intval($_GET['id']);
$sql = "SELECT so.supplier_order_id, so.supplier_id, so.order_status, s.name AS supplier_name 
        FROM supplier_orders so
        JOIN suppliers s ON so.supplier_id = s.supplier_id 
        WHERE so.supplier_order_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $purchaseId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$purchase = mysqli_fetch_assoc($result);

if (!$purchase) {
    $_SESSION['error_msg'] = "No Purchase Found.";
    header("Location:purchases.php?error=PurchaseNotFound");
    exit();
}

//Prevent editing completed purchases
if ($purchase['order_status']==='completed') {
    $_SESSION['error_msg']="Cannot edit a completed purchase.";
    header("Location:purchases.php?error=CannotEditCompleted");
    exit();
}

//get order items
$sqlItems = "SELECT soi.supplier_order_item_id, p.product_id, p.name, soi.quantity, soi.unit_price FROM supplier_order_items soi
             JOIN products p ON soi.product_id = p.product_id WHERE soi.supplier_order_id=?";
$stmtItems = mysqli_prepare($connect, $sqlItems);
mysqli_stmt_bind_param($stmtItems, "i", $purchaseId);
mysqli_stmt_execute($stmtItems);
$resultItems = mysqli_stmt_get_result($stmtItems);

$purchaseItems = [];
while ($row = mysqli_fetch_assoc($resultItems)) {
    $purchaseItems[] = $row;
}
?>

<!-- Edit order form -->
 <div class="edit-purchase-container">
    <h1>Edit Pusrchase #<?= $purchase['supplier_order_id'] ?></h1>
    <p><strong>Supplier: <?= htmlspecialchars($purchase['supplier_name']) ?> (Supplier ID: <?= $purchase['supplier_id'] ?>)</strong></p>

    <form action="includes/editPurchase.inc.php" method="post">
        <input type="hidden" name="orderId" value="<?= $purchase['supplier_order_id'] ?>">

        <label for="orderStatus">Purchase Status: </label>
        <select name="orderStatus" id="orderStatus">
            <option value="pending" <?= $purchase['order_status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="completed" <?= $purchase['order_status']=='completed'?'selected':'' ?>>Completed</option>
        </select>

        <h3>Purchase Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (LKR.)</th>
                    <th>Total (LRK.)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($purchaseItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>
                            <input type="number" name="quantities[<?= $item['supplier_order_item_id'] ?>]" 
                                   value="<?= $item['quantity'] ?>" min="1">
                        </td>
                        <td><?= number_format($item['unit_price'],2) ?></td>
                        <td><?= number_format($item['unit_price']*$item['quantity'],2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" name="submit">Save Changes</button>
        <a href="purchases.php" class="cancel-btn">Cancel</a>
    </form>
</div>

<?php 
include 'footer.php'; 
?>

<!-- animate toast message -->
<script src="js/toast.js"></script>