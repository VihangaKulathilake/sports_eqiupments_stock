<?php
session_start();
include 'includes/db.php';

$step = isset($_GET['step']) ? intval($_GET['step']) : 1;

if ($step === 2) {
    $customer_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : (isset($_GET['customer_id']) ? intval($_GET['customer_id']) : null);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = mysqli_prepare($connect, "SELECT * FROM customers WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Move to step 2
        $customer_id = $user['customer_id'];
        $_SESSION['customer_id'] = $customer_id;
        header("Location: resetPassword.php?step=2");
        exit();
    } else {
        $_SESSION['error_msg'] = "Email not found!";
        header("Location: resetPassword.php");
        exit();
    }
}
?>

<link rel="stylesheet" href="css/resetPassword.css">
<link rel="stylesheet" href="css/toast.css">

<!-- Toast Messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
<div class="toast toast-error"><?= $_SESSION['error_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
<div class="toast toast-success"><?= $_SESSION['success_msg']; ?> <span class="toast-close">&times;</span></div>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php if ($step === 1): ?>
<form method="POST">
    <h3>Enter your email to reset password</h3>
    <input type="email" name="email" placeholder="Your registered email" required>
    <button type="submit">Next</button>
    <a href="login.php" class="btn-back">Back</a>
</form>
<?php endif; ?>

<?php if ($step === 2 && $customer_id): ?>
<form method="POST" action="includes/updatePassword.inc.php">
    <h3>Enter new password</h3>
    <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
    <input type="password" name="password" placeholder="New password" required>
    <input type="password" name="confirm_password" placeholder="Confirm password" required>
    <button type="submit">Reset Password</button>
    <a href="resetPassword.php?step=1" class="btn-back">Back</a>
</form>
<?php endif; ?>

<script src="js/toast.js"></script>
