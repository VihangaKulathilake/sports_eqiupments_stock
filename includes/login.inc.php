<?php
if(isset($_POST["submit"])){
    $username = $_POST["uname"];
    $password = $_POST["pwd"];

    require_once 'db.php';
    require_once 'functions.inc.php';

    // Simple validation to check for empty inputs
    if(empty($username) || empty($password)){
        header("Location: ../login.php?error=emptyinputs");
        exit();
    }
    
    // Attempt to log the user in
    userLogin($connect, $username, $password);

}else{
    header('Location:../login.php');
    exit();
}