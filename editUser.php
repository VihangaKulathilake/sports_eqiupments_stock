<?php
session_start();
$pageTitle = "Edit User";
$cssFile = "css/editUser.css";
$extraCss = "css/toast.css";
include 'includes/db.php';
include 'adminHeader.php';

//determine redirect page
$redirectPage="users.php";
if (isset($_GET['from']) && $_GET['from'] === "topCustomers") {
    $redirectPage="topCustomers.php";
}

//toast messages
if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php

//fetch user from database
if (!isset($_GET['id'])) {
    header("Location: $redirectPage");
    exit();
}

$userId=intval($_GET['id']);
$sql="SELECT * FROM customers WHERE customer_id=?";
$stmt=mysqli_prepare($connect,$sql);
mysqli_stmt_bind_param($stmt,"i",$userId);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
$user=mysqli_fetch_assoc($result);

if (!$user) {
    header("Location:$redirectPage");
    exit();
}
?>

<!-- Edit User Form -->
<div class="edit-user-container">
    <form class="edit-user-form" action="includes/editUser.inc.php" method="post" enctype="multipart/form-data">
        <h1>Edit User</h1>
        <input type="hidden" name="userId" value="<?= $user['customer_id'] ?>">
        <input type="hidden" name="from" value="<?= isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '' ?>">

        <label for="name" class="user-label">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']); ?>">

        <label for="email" class="user-label">E-mail:</label>
        <input type="text" name="email" id="email" value="<?= htmlspecialchars($user['email']); ?>">

        <label for="phone" class="user-label">Phone:</label>
        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']); ?>">

        <label for="address" class="user-label">Address:</label>
        <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']); ?>">

        <label for="username" class="user-label">Username:</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']); ?>">

        <label class="user-image-upload">Current Image</label>
        <?php 
        $currentImg = !empty($user['image_path']) ? htmlspecialchars($user['image_path']) : null;
        if ($currentImg && file_exists("userImgs/" . $currentImg)) { ?>
            <div class="current-image-container">
                <img src="userImgs/<?= $currentImg ?>" alt="Current User Image" class="user-preview">
                <span class="image-filename"><?= $currentImg ?></span>
            </div>
        <?php } else { ?>
            <p>No image uploaded.</p>
        <?php } ?>

        <label for="uImg" class="user-image-upload" style="margin-top:15px;">Upload New Image (optional)</label>
        <input type="file" name="uImg" id="uImg" accept="image/*"><br>

        <input type="hidden" name="from" value="<?= isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '' ?>">
        <input class="edit-user" type="submit" name="submit" value="Save Changes">
        <a href="<?= $redirectPage ?>" class="cancel-user">Cancel</a>
    </form>
</div>

<?php 
include_once 'footer.php'; 
?>

<!-- toast Animation Script -->
<script src="js/toast.js"></script>
