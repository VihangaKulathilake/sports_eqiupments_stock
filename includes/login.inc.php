<?php
if(isset($_POST["submit"])){
    $username=$_POST["uname"];
    $password=$_POST["pwd"];

    require_once 'db.php';
    require_once 'functions.inc.php';

    if(inputsEmptyLogin($username,$password)!==false){
        exit();
    }

    userLogin($connect,$username,$password);

}else{
    header('Location:../login.php');
}