<?php
session_start();
require_once 'db.php';

if (isset($_POST['submit'])) {
    $customer_id = $_POST['customer_id'];
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    
    if (empty($name) || empty($username) || empty($email) || empty($phone) || empty($address)) {
        header("Location: ../editProfile.php?error=emptyfields");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../editProfile.php?error=invalidemail");
        exit();
    }

    
    $sql_fetch_img = "SELECT image_path FROM customers WHERE customer_id = ?";
    $stmt_fetch = mysqli_stmt_init($connect);
    if (!mysqli_stmt_prepare($stmt_fetch, $sql_fetch_img)) {
        header("Location: ../editProfile.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt_fetch, "i", $customer_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    $row = mysqli_fetch_assoc($result);
    $oldImagePath = $row['image_path'] ?? 'default.jpg';
    mysqli_stmt_close($stmt_fetch);

    $newImagePath = $oldImagePath;

    // Handle profile image upload
    if (isset($_FILES['profileImg']) && $_FILES['profileImg']['error'] === 0) {
        $file = $_FILES['profileImg'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        
       
        if (empty($fileName)) {
            header("Location: ../editProfile.php?error=nofile");
            exit();
        }
        
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed)) {
            header("Location: ../editProfile.php?error=invalidfiletype");
            exit();
        }

        if ($fileError !== 0) {
            header("Location: ../editProfile.php?error=uploaderror&code=" . $fileError);
            exit();
        }

        if ($fileSize > 5000000) { // Max 5MB
            header("Location: ../editProfile.php?error=filetoobig");
            exit();
        }

        // Verify it's actually an image
        $fileInfo = getimagesize($fileTmpName);
        if ($fileInfo === false) {
            header("Location: ../editProfile.php?error=notanimage");
            exit();
        }

        // Create uploads directory if it doesn't exist
        $uploadDir = '../userImgs/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                header("Location: ../editProfile.php?error=uploaddirfailed");
                exit();
            }
        }

        // Delete the old image if it exists and is not the default
        if (!empty($oldImagePath) && $oldImagePath !== 'default.jpg') {
            $oldImageFullPath = $uploadDir . $oldImagePath;
            if (file_exists($oldImageFullPath)) {
                unlink($oldImageFullPath);
            }
        }
        
        // Generate new unique filename
        $newFileName = 'profile-' . $customer_id . '-' . uniqid() . '.' . $fileExt;
        $uploadDestination = $uploadDir . $newFileName;
        
        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $uploadDestination)) {
            $newImagePath = $newFileName;
            
            // Set appropriate permissions
            chmod($uploadDestination, 0644);
        } else {
            header("Location: ../editProfile.php?error=movefailed");
            exit();
        }
    } else if (isset($_FILES['profileImg']) && $_FILES['profileImg']['error'] !== 4) {
        // Handle upload errors (error 4 means no file was uploaded, which is fine)
        $errorCode = $_FILES['profileImg']['error'];
        header("Location: ../editProfile.php?error=uploaderror&code=" . $errorCode);
        exit();
    }

    // Check if username or email already exists (excluding current user)
    $sql_check = "SELECT customer_id FROM customers WHERE (username = ? OR email = ?) AND customer_id != ?";
    $stmt_check = mysqli_stmt_init($connect);
    if (mysqli_stmt_prepare($stmt_check, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ssi", $username, $email, $customer_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        if (mysqli_num_rows($result_check) > 0) {
            mysqli_stmt_close($stmt_check);
            header("Location: ../editProfile.php?error=useroremailtaken");
            exit();
        }
        mysqli_stmt_close($stmt_check);
    }

    // Update user data in the database
    $sql = "UPDATE customers SET name = ?, username = ?, email = ?, phone = ?, address = ?, image_path = ? WHERE customer_id = ?";
    $stmt = mysqli_stmt_init($connect);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../editProfile.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $username, $email, $phone, $address, $newImagePath, $customer_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update the session username if it was changed
        $_SESSION['username'] = $username;
        mysqli_stmt_close($stmt);
        header("Location: ../myProfile.php?success=profileupdated");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        header("Location: ../editProfile.php?error=updatefailed");
        exit();
    }

} else {
    header("Location: ../myProfile.php");
    exit();
}
?>