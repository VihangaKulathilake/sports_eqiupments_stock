<?php
session_start();
$extraCss = "css/toast.css";
?>

<!-- toast messages -->
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error">
        <?= htmlspecialchars($_SESSION['error_msg']); ?>
        <span class="toast-close">&times;</span>
    </div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success">
        <?= htmlspecialchars($_SESSION['success_msg']); ?>
        <span class="toast-close">&times;</span>
    </div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/auth.css">
    <title>Login</title>
    <?php if(isset($extraCss)) { ?>
      <link rel="stylesheet" href="<?php echo $extraCss; ?>">
    <?php } ?>
</head>
<body class="auth-container">
    <form class="auth-form" action="includes/login.inc.php" method="post">
        <div class="logo-container">
            <img src="imgs/E11.png" alt="logo">
        </div>

        <h1>Login</h1>
        <input type="text" name="uname" id="uname" placeholder="Username / Email"><br><br>
        <input type="password" name="pwd" id="pwd" placeholder="Password"><br><br>
        <input class="auth-btn" type="submit" name="submit" value="Login"><br><br>
        <p>Don't have an account? <a href="signUp.php">Sign up</a></p>
        <p>Forgot password? <a href="resetPassword.php">Reset password</a></p>
    </form>

<script src="js/toast.js"></script>
</body>
</html>
