<?php
$pageTitle = "Top Suppliers";
$cssFile = "css/topSuppliers.css";
$extraCss="css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<?php
// toast messages
if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<div class="top-supplier-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Supplier ID</th>
                <th>Supplier Name</th>
                <th>Supplier Email</th>
                <th>Supplier Contact No.</th>
                <th>Supplier Address</th>
                <th>Total Quantity</th>
                <th>Manage Suppliers</th>
            </tr>
        </thead>
    <?php
        $sql="SELECT s.supplier_id, s.name, s.email, s.phone, s.address, 
       SUM(st.quantity) AS total_quantity
FROM suppliers s
JOIN products p ON s.supplier_id = p.supplier_id
JOIN stocks st ON p.product_id = st.product_id
GROUP BY s.supplier_id, s.name, s.email, s.phone, s.address
ORDER BY total_quantity DESC
LIMIT 5;
";

        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['supplier_id']."</td>";
            echo"<td>".$row['name']."</td>";
            echo"<td>".$row['email']."</td>";
            echo"<td>".$row['phone']."</td>";
            echo"<td>".$row['address']."</td>";
            echo"<td>".$row['total_quantity']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='edit' onclick=\"location.href='editSupplier.php?id={$row['supplier_id']}&from=topSuppliers'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this user?')) location.href='includes/deleteSupplier.php?id={$row['supplier_id']}'\">Delete</button>
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