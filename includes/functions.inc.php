<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

//Check empty fields in User Login form
function inputsEmptyLogin($username,$password){
    $result=null;
    if(empty($username)||empty($password)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

//Login the User
function userLogin($connect,$username,$password){
    $userIDExists=userExists($connect,$username,$username,$username);
    if($userIDExists===false){
        $_SESSION['error_msg'] = "User not exists.";
        header('Location:../login.php?error=wronglogin');
        exit();
    }

    $pwdhashed=$userIDExists["password"];
    $pwdCheck=password_verify($password,$pwdhashed);
        
    if($pwdCheck===false){
        $_SESSION['error_msg'] = "Password incorrect.";
        header('Location:../login.php?error=wrongpassword');
        exit();
    }else{
        $_SESSION['customer_id']=$userIDExists['customer_id'];
        $_SESSION["username"]=$userIDExists["username"];
        $_SESSION["name"]=$userIDExists["name"];
        $_SESSION["role"]=$userIDExists["role"];
        $_SESSION["email"]=$userIDExists["email"];
        $_SESSION["image_path"]=$userIDExists["image_path"];
        $_SESSION["phone"]=$userIDExists["phone"];
        $_SESSION["address"]=$userIDExists["address"];

        if($userIDExists["role"]==="admin"){
            $_SESSION['success_msg'] = "Login successful.";
            header("Location:../adminDashboard.php");
        }else{
            $_SESSION['success_msg'] = "Login successful.";
            header("Location:../index.php");
        }
        exit();
    }
}

//Sign up functions

//Check for empty inputs
function inputsEmptySignup($name,$uName,$eMail,$phone,$address,$pwd,$confPwrd,$role){
    $result=null;
    if(empty($name)||empty($uName)||empty($eMail)||empty($phone)||empty($address)||empty($pwd)||empty($confPwrd)||empty($role)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

//Validate username
function invalidUid($username){
    $result=null;
    if(!preg_match("/^[a-zA-Z0-9_]*$/",$username)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

//Validate email
function invalidMail($eMail){
    $result=null;
    if(!filter_var($eMail,FILTER_VALIDATE_EMAIL)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

//Check password confirmation
function pwdMatch($pwd,$confPwrd){
    $result=null;
    if($pwd!==$confPwrd){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

//Check if username, email or phone already exists
function userExists($connect,$uName,$eMail,$phone){
    $sql="SELECT * FROM customers WHERE username=? OR email=? OR phone=?;";
    $stmt=mysqli_stmt_init($connect);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location:../signUp.php?error=statementfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"sss",$uName,$eMail,$phone);
    mysqli_stmt_execute($stmt);
    $resultData=mysqli_stmt_get_result($stmt);

    if($row=mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        return false;
    }
    mysqli_stmt_close($stmt);
}

//Insert new user
function userSignup($connect,$name,$uName,$eMail,$phone,$address,$pwd,$profileImgName,$role){
    $sql="INSERT INTO customers (name,username,email,phone,address,password,image_path,role) VALUES (?,?,?,?,?,?,?,?);";
    $stmt=mysqli_stmt_init($connect);

    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location:../signUp.php?error=statementfailed");
        exit();
    }

    $hashedpwd=password_hash($pwd,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"ssssssss",$name,$uName,$eMail,$phone,$address,$hashedpwd,$profileImgName,$role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location:../login.php?signup=success");
    exit();
}

//Check whether the input fields empty in the insert product form
function inputsEmptyProduct($pName,$pDes,$pCat,$pPrice,$pSup){
    if(empty($pName)||empty($pDes)||empty($pCat)||empty($pPrice)||empty($pSup)){
        return true;
    }
    return false;
}

//Insert a new product
function registerProduct($connect,$pName,$pDes,$pImg,$pCat,$pPrice,$pSup){
    $sql="INSERT INTO products (name,description,image_path,category,price,supplier_id) VALUES (?,?,?,?,?,?)";
    if($stmt=mysqli_prepare($connect,$sql)){
        mysqli_stmt_bind_param($stmt,"ssssds",$pName,$pDes,$pImg,$pCat,$pPrice,$pSup);
        if(!mysqli_stmt_execute($stmt)){
            die("Error inserting product: ".mysqli_error($connect));
        } else {
            $_SESSION['success_msg'] = "Product added successfully.";
            header("Location:../adminDashboard.php?insertProduct=success");
            exit();
        }
    }else{
        die("Error preparing statement: ".mysqli_error($connect));
    }
}
?>
