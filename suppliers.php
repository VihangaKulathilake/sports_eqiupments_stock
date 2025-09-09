<?php
$pageTitle = "Admin Suppliers";
$cssFile = "css/suppliers.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<div class="supplier-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>Supplier ID</th>
                <th>Supplier Name</th>
                <th>Supplier Email</th>
                <th>Supplier Contact No.</th>
                <th>Supplier Address</th>
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql="SELECT * FROM suppliers;";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['supplier_id']."</td>";
            echo"<td>".$row['name']."</td>";
            echo"<td>".$row['email']."</td>";
            echo"<td>".$row['phone']."</td>";
            echo"<td>".$row['address']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='edit' onclick=\"location.href='edit.php?id=".$row['supplier_id']."'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this user?')) location.href='delete.php?id=".$row['supplier_id']."'\">Delete</button>
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