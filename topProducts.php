<?php
$pageTitle = "Top Products";
$cssFile = "css/topProducts.css";
include 'adminHeader.php';
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
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql = "
        SELECT p.product_id, p.name, p.category, p.price, p.supplier_id, SUM(oi.quantity) AS total_sold
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        GROUP BY p.product_id
        ORDER BY total_sold DESC
        LIMIT 5
        ";

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
                <button class='view-product' onclick=\"location.href='displayProduct.php?id=" . $row['product_id'] . "'\">View Product</button>
                </div>
                </td>";
                echo "</tr>";
            }
        }
    ?>

    </table>
    </div>
</div>

<?php
include 'footer.php';
?>