<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["submit"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $pwd = $_POST["pwrd"];
    $confPwrd = $_POST["confPwrd"];

    require_once 'db.php';
    require_once 'functions.inc.php';

    // Validation checks
    if(empty($name) || empty($email) || empty($username) || empty($phone) || empty($address) || empty($pwd) || empty($confPwrd)){
        header("Location: ../signUp.php?error=emptyinputs");
        exit();
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../signUp.php?error=invalidemail");
        exit();
    }
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        header("Location: ../signUp.php?error=invalidusername");
        exit();
    }
    if($pwd !== $confPwrd){
        header("Location: ../signUp.php?error=passwordmismatch");
        exit();
    }
    if(userExists($connect, $username, $email, $phone)){
        header("Location: ../signUp.php?error=userexists");
        exit();
    }

    $newFileName = "default.png";
    if (isset($_FILES["profileImg"]) && $_FILES["profileImg"]["error"] === 0) {
        $profileImg = $_FILES["profileImg"];
        $fileName = $profileImg["name"];
        $fileTmpName = $profileImg["tmp_name"];
        $fileSize = $profileImg["size"];
        $fileType = $profileImg["type"];
        
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (in_array($fileExt, $allowed)) {
            if ($fileSize <= 5*1024*1024) {
                $newFileName = uniqid("user", true) . "." . $fileExt;
                $uploadPath = "../userImgs/";
                $fileDestination = $uploadPath . $newFileName;

                if(!is_dir($uploadPath)){
                    mkdir($uploadPath, 0777, true);
                }

                move_uploaded_file($fileTmpName, $fileDestination);
            } else {
                header("Location:../signUp.php?error=filetoobig");
                exit();
            }
        } else {
            header("Location:../signUp.php?error=invalidfiletype");
            exit();
        }
    }

    // Hash the password for security
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    // Call the final signup function
    userSignup($connect, $name, $username, $email, $phone, $address, $hashedPwd, $newFileName, date('Y-m-d H:i:s'));

} else {
    header("Location: ../signUp.php");
    exit();
}