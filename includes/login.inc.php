<?php
session_start();
if(isset($_POST["submit"])){
    $username=$_POST["uname"];
    $password=$_POST["pwd"];

    require_once 'db.php';
    require_once 'functions.inc.php';

    if(inputsEmptyLogin($username,$password)!==false){
        $_SESSION['error_msg'] = "All fields required.";
        header('Location:../login.php');
        exit();
    }

    userLogin($connect,$username,$password);

}else{
    $_SESSION['error_msg'] = "login unsuccessfull.";
    header('Location:../login.php');
}