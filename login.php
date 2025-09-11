<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/auth.css"> <title>Login</title>
</head>
<body class="auth-container">
    <form class="auth-form" action="login.inc.php" method="post">
        <div class="logo-container">
            <img src="imgs/E11.png" alt="logo">
        </div>
        <h1>Login </h1>
        <input type="text" name="uname" id="uname" placeholder="Username/Email"><br><br>
        <input type="password" name="pwd" id="pwd" placeholder="Password"><br><br>
        <input class="auth-btn" type="submit" name="submit" value="Login"><br><br>
        <p>Don't have an account?,<a href="signUp.php">Sign up</a></p>
    </form>
</body>
</html>