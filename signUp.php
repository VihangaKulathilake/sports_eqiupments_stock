<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signUp.css"> <title>Sign up</title>
</head>
<body class="auth-container">
    <form class="auth-form" action="includes/signUp.inc.php" method="post" enctype="multipart/form-data">
        <div class="logo-container">
            <img src="imgs/E11.png" alt="logo">
        </div>
        <h1>Sign Up </h1>
        <input type="text" name="name" id="name" placeholder="Name"><br><br>
        <input type="text" name="email" id="email" placeholder="Email"><br><br>
        <input type="text" name="username" id="username" placeholder="Username"><br><br>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <input type="text" name="phone" id="phone" placeholder="Phone"><br><br>
        <input type="text" name="address" id="address" placeholder="Address"><br><br>
        <input type="password" name="pwrd" id="pwrd" placeholder="Password"><br><br>
        <input type="password" name="confPwrd" id="confPwd" placeholder="Confirm Password"><br><br>
        <label for="profileImg" class="file-label" >Upload Profile Photo</label>
        <input type="file" name="profileImg" id="profileImg" accept="image/*"><br><br>
        <input class="auth-btn" type="submit" name="submit" value="Sign Up">
    </form>
    
</body>
</html>