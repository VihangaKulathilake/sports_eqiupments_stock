<?php
    include 'userHeader.php';
    require_once 'includes/myProfile.inc.php';
?>

<link rel="stylesheet" href="css/editProfile.css">

<div class="editProfileContainer">
    <h1>Edit Profile</h1>
    
    <?php
    
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        echo '<div class="alert alert-error">';
        switch($error) {
            case 'filetoobig':
                echo 'File size is too large. Maximum 5MB allowed.';
                break;
            case 'invalidfiletype':
                echo 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
                break;
            case 'uploaderror':
                echo 'An error occurred during file upload.';
                break;
            case 'stmtfailed':
                echo 'Database error occurred.';
                break;
            default:
                echo 'An unknown error occurred.';
        }
        echo '</div>';
    }
    
    // Display success message
    if (isset($_GET['success']) && $_GET['success'] == 'profileupdated') {
        echo '<div class="alert alert-success">Profile updated successfully!</div>';
    }
    ?>
    
    <div class="editFormCard">
        <form action="includes/editProfile.inc.php" method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($userData['customer_id']); ?>">

            <div class="profile-image-section">
                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture" class="editProfileImg" id="profilePreview">
                <label for="profileImg" class="file-upload-label">
                    <span> Change Photo</span>
                </label>
                <input type="file" name="profileImg" id="profileImg" accept="image/*">
                <div class="file-info" id="fileInfo"></div>
            </div>

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($userData['address']); ?>" required>
            </div>
            
            <div class="button-container">
                <button type="submit" name="submit" class="save-btn">Save Changes</button>
                <a href="myProfile.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>

document.getElementById('profileImg').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileInfo = document.getElementById('fileInfo');
    const profilePreview = document.getElementById('profilePreview');
    
    if (file) {
        // Validate file size (5MB max)
        if (file.size > 5000000) {
            fileInfo.textContent = 'File too large. Maximum 5MB allowed.';
            fileInfo.style.color = 'red';
            this.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            fileInfo.textContent = 'Invalid file type. Only JPG, PNG, and GIF allowed.';
            fileInfo.style.color = 'red';
            this.value = '';
            return;
        }
        
        // Show file info
        fileInfo.textContent = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        fileInfo.style.color = 'green';
        
        // Preview the image
        const reader = new FileReader();
        reader.onload = function(e) {
            profilePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Trigger file input when label is clicked
document.querySelector('.file-upload-label').addEventListener('click', function() {
    document.getElementById('profileImg').click();
});
</script>
    
<?php
    include 'footer.php';
?>