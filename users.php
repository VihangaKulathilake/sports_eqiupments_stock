<?php
$pageTitle = "Admin Users";
$cssFile = "css/users.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';
?>

<!-- Toast messages -->
<?php if (isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success show"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error show"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<!-- Users Table -->
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
                    <th>Manage Users</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM customers ORDER BY customer_id DESC";
                $result = mysqli_query($connect, $sql);

                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['customer_id'] ?></td>
                            <td><?php echo htmlspecialchars($row['name']) ?></td>
                            <td><?php echo htmlspecialchars($row['email']) ?></td>
                            <td><?php echo htmlspecialchars($row['phone']) ?></td>
                            <td><?php echo htmlspecialchars($row['address']) ?></td>
                            <td>
                                <div class="actions-container">
                                    <button class="edit" onclick="location.href='editUser.php?id=<?= $row['customer_id'] ?>&from=customers'"><img src='imgs/edit.png' class='btn-icon' alt='view-icon'>Edit</button>
                                    <button class='delete' onclick="location.href='includes/deleteUser.inc.php?id=<?= $row['customer_id'] ?>&from=customers'"><img src='imgs/trash.png' class='btn-icon' alt='delete-icon'>Delete</button>
                                </div>
                            </td>

                        </tr>
                    <?php endwhile; 
                else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <a href="adminDashboard.php" class="btn-back">Back</a>
</div>

<!-- Toast animation -->
<script src="js/toast,js"></script>

<?php include 'footer.php'; ?>
