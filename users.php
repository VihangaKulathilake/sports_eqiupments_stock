<?php
$pageTitle = "Admin Users";
$cssFile = "users.css";
include 'includes/db.php';
include 'header.php';
?>

<div class="users-container">
    <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>User Contact No.</th>
                <th>User Address</th>
                <th>Actions</th>
            </tr>
        </thead>
    <?php
        $sql="SELECT * FROM customers;";
        $result=mysqli_query($connect,$sql);

        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
            echo"<tr>";
            echo"<td>".$row['customer_id']."</td>";
            echo"<td>".$row['name']."</td>";
            echo"<td>".$row['email']."</td>";
            echo"<td>".$row['phone']."</td>";
            echo"<td>".$row['address']."</td>";
            echo "<td>
                    <div class='actions-container'>
                        <button class='edit' onclick=\"location.href='edit.php?id=".$row['customer_id']."'\">Edit</button>
                        <button class='delete' onclick=\"if(confirm('Delete this user?')) location.href='delete.php?id=".$row['customer_id']."'\">Delete</button>
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