<?php
$pageTitle = "Admin Products";
$cssFile = "css/products.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

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
                <th>Actions</th>
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
                        <button class='view-product' onclick=\"location.href='viewProduct.php?product_id=".$row['product_id']."'\">View</button>
                        <button class='edit' onclick=\"location.href='edit.php?id=".$row['product_id']."'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this product?')) location.href='delete.php?id=".$row['product_id']."'\">Delete</button>
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