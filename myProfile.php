<?php
    include 'adminHeader.php';
    require_once 'includes/myProfile.inc.php';
?>

<link rel="stylesheet" href="css/myprofile.css">

<div class="myProfileContainer">
    <div class="profileCard">
        <h1>My Profile</h1>
<img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture" class="profileCardImg"><br><br>
        <h2><?php echo htmlspecialchars($userData['name']); ?></h2>
        
        <div class="profileDetails">
            <p><strong>Username:</strong> <span><?php echo htmlspecialchars($userData['username']); ?></span></p>
            <p><strong>Email:</strong> <span><?php echo htmlspecialchars($userData['email']); ?></span></p>
            <p><strong>Phone:</strong> <span><?php echo htmlspecialchars($userData['phone']); ?></span></p>
            <p><strong>Address:</strong> <span><?php echo htmlspecialchars($userData['address']); ?></span></p>
            <p><strong>Role:</strong> <span><?php echo htmlspecialchars($userData['role']); ?></span></p>
        </div>
    </div>
</div>
    
<?php
    include 'footer.php';
?>