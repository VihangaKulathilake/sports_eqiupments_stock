<?php
    $pageTitle=" My Profile ";
    include 'adminHeader.php';
    require_once 'includes/myProfile.inc.php';
?>

<link rel="stylesheet" href="css/myProfileAdmin.css">

<div class="myProfileContainer">
    <h1>My Profile</h1>
    <div class="profileCard">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture" class="profileCardImg"><br>
        
        <div class="profileDetails">
            <h2><?php echo htmlspecialchars($userData['name']); ?></h2>
            <p><strong>Username:</strong> <span><?php echo htmlspecialchars($userData['username']); ?></span></p>
            <p><strong>Role:</strong> <span><?php echo htmlspecialchars($userData['role']); ?></span></p>
            <p><strong>Email:</strong> <span><?php echo htmlspecialchars($userData['email']); ?></span></p>
            <p><strong>Phone:</strong> <span><?php echo htmlspecialchars($userData['phone']); ?></span></p>
            <p><strong>Address:</strong> <span><?php echo htmlspecialchars($userData['address']); ?></span></p><br>
            <a href="editProfileAdmin.php" class="edit-btn">Edit Profile</a>
        </div>
    </div>   
</div>
    
<?php
    include 'footer.php';
?>