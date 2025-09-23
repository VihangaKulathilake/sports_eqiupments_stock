<?php
$pageTitle = "Admin Products";
$cssFile = "css/products.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';

//toast messages
if (isset($_SESSION['success_msg'])) {
    echo "<div class='toast toast-success show'>".$_SESSION['success_msg']."</div>";
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    echo "<div class='toast toast-error show'>".$_SESSION['error_msg']."</div>";
    unset($_SESSION['error_msg']);
}
?>

<!-- products table -->
<div class="products-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price (LKR)</th>
                <th>Supplier ID</th>
                <th>Manage Products</th>
            </tr>
        </thead>
    <?php
        $sql="SELECT * FROM products;";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['product_id']."</td>";
            echo"<td>".$row['name']."</td>";
            echo"<td>".$row['category']."</td>";
            echo"<td>".$row['price']."</td>";
            echo"<td>".$row['supplier_id']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='view-product' onclick=\"location.href='viewProduct.php?id=".$row['product_id']."&from=products'\"><img src='imgs/eye.png' class='btn-icon' alt='view-icon'>View</button>
                        <button class='edit' onclick=\"location.href='editProduct.php?id=".$row['product_id']."&from=products'\"><img src='imgs/edit.png' class='btn-icon' alt='view-icon'>Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this product?')) location.href='includes/deleteProduct.inc.php?id=".$row['product_id']."&from=products'\"><img src='imgs/trash.png' class='btn-icon' alt='view-icon'>Delete</button>
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

<!-- toast Animation Script -->
<script src="js/toast.js"></script>

<?php
include 'footer.php';
?>
