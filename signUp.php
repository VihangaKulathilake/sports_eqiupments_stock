<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/auth.css">
    <title>Sign Up</title>
</head>
<body class="auth-container">
    <form class="auth-form" action="includes/signUp.inc.php" method="post" enctype="multipart/form-data">
        <div class="logo-container">
            <img src="imgs/E11.png" alt="logo">
        </div>
        <h1>Sign Up</h1>
        <input type="text" name="name" id="name" placeholder="Full Name" required>
        <input type="text" name="email" id="email" placeholder="Email Address" required>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="text" name="phone" id="phone" placeholder="Phone Number" required>
        <input type="text" name="address" id="address" placeholder="Address" required>
        <input type="password" name="pwrd" id="pwrd" placeholder="Password" required>
        <input type="password" name="confPwrd" id="confPwd" placeholder="Confirm Password" required>

        <label class="file-label" for="profileImg">Profile Photo (Optional):</label>
        <input type="file" name="profileImg" id="profileImg">
        
        <input class="auth-btn" type="submit" name="submit" value="Sign Up">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>