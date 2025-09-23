<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = intval($_POST['customer_id']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($password) || empty($confirm_password)) {
        $_SESSION['error_msg'] = "Password cannot be empty.";
        $_SESSION['customer_id'] = $customer_id;
        header("Location: ../resetPassword.php?step=2");
        exit();
    } elseif ($password !== $confirm_password) {
        $_SESSION['error_msg'] = "Passwords do not match!";
        $_SESSION['customer_id'] = $customer_id;
        header("Location: ../resetPassword.php?step=2");
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($connect, "UPDATE customers SET password=? WHERE customer_id=?");
        mysqli_stmt_bind_param($stmt, "si", $hashed_password, $customer_id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_msg'] = "Password updated successfully! You can now login.";
            unset($_SESSION['customer_id']);
        } else {
            $_SESSION['error_msg'] = "Failed to update password. Try again later.";
            $_SESSION['customer_id'] = $customer_id;
            header("Location: ../resetPassword.php?step=2");
            exit();
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($connect);
    header("Location: ../resetPassword.php");
    exit();
}
