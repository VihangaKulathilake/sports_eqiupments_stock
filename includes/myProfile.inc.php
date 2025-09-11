<?php
  

    // Include the database connection file
    require_once 'db.php';

    // Function to get a user's data from the database
    function getUserData($conn, $username) {
        $sql = "SELECT * FROM customers WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $row;
    }

    // Check if the user is logged in
    if (!isset($_SESSION["username"])) {
        header("Location: ../login.php");
        exit();
    }

    $username = $_SESSION["username"];
    $userData = getUserData($connect, $username);

    if (!$userData) {
        echo "User data not found.";
        exit();
    }

    // Check if the user has a profile photo. If not, set a default one.
    $imagePath = 'userImgs/default.jpg'; 
    if (!empty($userData['image_path'])) {
        $imagePath = 'userImgs/' . $userData['image_path'];
    }
?>