<?php
$pageTitle = "Edit Supplier";
$cssFile = "css/editSupplier.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<!-- toast messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<?php
if(!isset($_GET['id'])){
    $_SESSION['error_msg'] = "No supplier selected.";
    header("Location: suppliers.php?error=NoSupplierSelected");
    exit();
}

//determine redirect page
$redirectPage = "suppliers.php";
if (isset($_GET['from']) && $_GET['from'] === "topSuppliers") {
    $redirectPage = "topSuppliers.php";
}

$supplierId = intval($_GET['id']);
$sql = "SELECT * FROM suppliers WHERE supplier_id=?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $supplierId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$supplier = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if(!$supplier){
    $_SESSION['error_msg'] = "Supplier not found.";
    header("Location: suppliers.php?error=SupplierNotFound");
    exit();
}
?>

<div class="edit-supplier-container">
    <h1>Edit Supplier</h1>
    <form action="includes/editSupplier.inc.php" method="post">
        <input type="hidden" name="supplier_id" value="<?= $supplier['supplier_id'] ?>">
        <input type="hidden" name="from" value="<?= isset($_GET['from']) ? $_GET['from'] : '' ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($supplier['name']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($supplier['email']) ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($supplier['phone']) ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($supplier['address']) ?>" required>

        <button type="submit" name="submit">Save Changes</button>
        <a href="<?= $redirectPage ?>" class="cancel-btn">Cancel</a>
    </form>
</div>

<?php include 'footer.php'; ?>
<script src="js/toast.js"></script>
