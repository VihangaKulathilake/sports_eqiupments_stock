<?php
$pageTitle = "Categories";
$cssFile = "css/categories.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<div class="categories-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Number of Products</th>
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql = "SELECT category, COUNT(*) AS product_count FROM products GROUP BY category;";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['category']."</td>";
            echo"<td>".$row['product_count']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='view-products' onclick=\"location.href='viewCatProducts.php?id=".$row['category']."'\">View Products</button>
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