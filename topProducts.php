<?php
$pageTitle = "Top Products";
$cssFile = "css/topProducts.css";
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

<div class="top-products-container">
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
        $sql = "SELECT p.product_id, p.name, p.category, p.price, p.supplier_id, SUM(oi.quantity) AS total_sold
        FROM order_items oi JOIN products p ON oi.product_id = p.product_id GROUP BY p.product_id
        ORDER BY total_sold DESC LIMIT 5";

        $result = mysqli_query($connect, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['product_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['supplier_id'] . "</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='view-product' onclick=\"location.href='viewProduct.php?product_id=".$row['product_id']."&from=topProducts'\"><img src='imgs/eye.png' class='btn-icon' alt='view-icon'>View</button>
                        <button class='edit' onclick=\"location.href='editProduct.php?id=".$row['product_id']."&from=topProducts'\"><img src='imgs/edit.png' class='btn-icon' alt='view-icon'>Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this product?')) location.href='includes/deleteProduct.inc.php?id=".$row['product_id']."&from=topProducts'\"><img src='imgs/trash.png' class='btn-icon' alt='view-icon'>Delete</button>
                    </div>
                </td>";
                echo "</tr>";
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