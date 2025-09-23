<?php
session_start();
require_once 'db.php';
require_once 'functions.inc.php';

//determine redirect page
$redirectPage="users.php";
if (isset($_POST['from']) && $_POST['from'] === "topCustomers") {
    $redirectPage = "topCustomers.php";
}


if(!isset($_POST['submit'])){
    $_SESSION['error_msg']="No data submitted.";
    header("Location: ../$redirectPage");
    exit();
}

$userId=intval($_POST['userId']);
$name=trim($_POST['name']);
$email=trim($_POST['email']);
$phone=trim($_POST['phone']);
$address=trim($_POST['address']);
$username=trim($_POST['username']);
$profileImg=$_FILES['uImg'] ?? null;

//check for empty fields
if(empty($name) || empty($email) || empty($phone) || empty($address) || empty($username)){
    $_SESSION['error_msg']="Please fill in all required fields.";
    header("Location: ../editUser.php?id=$userId");
    exit();
}

//validate username
if(invalidUid($username)){
    $_SESSION['error_msg']="Invalid username. Only letters, numbers, and underscores allowed.";
    header("Location: ../editUser.php?id=$userId");
    exit();
}

//validate email
if(invalidMail($email)){
    $_SESSION['error_msg']="Invalid email format.";
    header("Location: ../editUser.php?id=$userId");
    exit();
}

//check if username, email or phone already exists
$sql = "SELECT * FROM customers WHERE (username=? OR email=? OR phone=?) AND customer_id!=?";
$stmt = mysqli_stmt_init($connect);
if(!mysqli_stmt_prepare($stmt, $sql)){
    $_SESSION['error_msg']="Database statement failed.";
    header("Location: ../editUser.php?id=$userId");
    exit();
}
mysqli_stmt_bind_param($stmt,"sssi",$username,$email,$phone,$userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if(mysqli_fetch_assoc($result)){
    $_SESSION['error_msg']="Username, email, or phone already exists.";
    header("Location: ../editUser.php?id=$userId");
    exit();
}

// Handle image upload
$imgPath = null;
if($profileImg && $profileImg['error'] === 0){
    $ext=pathinfo($profileImg['name'],PATHINFO_EXTENSION);
    $newName="user_".$userId."_".time().".".$ext;
    $target="userImgs/".$newName;
    if(move_uploaded_file($profileImg['tmp_name'],$target)){
        $imgPath=$newName;
    } else {
        $_SESSION['error_msg']="Image upload failed.";
        header("Location: ../editUser.php?id=$userId");
        exit();
    }
}

if($imgPath){
    $sql="UPDATE customers SET name=?,username=?,email=?,phone=?,address=?,image_path=? WHERE customer_id=?";
    $stmt=mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt,"ssssssi",$name,$username,$email,$phone,$address,$imgPath,$userId);
}else{
    $sql="UPDATE customers SET name=?,username=?,email=?,phone=?,address=? WHERE customer_id=?";
    $stmt=mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt,"sssssi",$name,$username,$email,$phone,$address,$userId);
}

if(mysqli_stmt_execute($stmt)){
    $_SESSION['success_msg']="User updated successfully.";
    header("Location:../$redirectPage");
    exit();
}else{
    $_SESSION['error_msg']="Failed to update user.";
    header("Location:../editUser.php?id=$userId");
    exit();
}
?>
