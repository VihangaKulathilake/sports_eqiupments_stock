<?php
    

    // Include the database connection
    require_once 'db.php';

    // Check if the user is logged in
    if (!isset($_SESSION["username"])) {
        header("Location: ../login.php");
        exit();
    }

    $username = $_SESSION["username"];
    $userOrders = [];

    // SQL query to get all order items for the logged-in user
    // We join multiple tables to get product details for each item
    $sql = "SELECT
                o.order_id,
                o.order_date,
                o.order_status,
                oi.quantity,
                p.product_id,
                p.name AS product_name,
                p.price,
                p.image_path
            FROM
                customers c
            JOIN
                orders o ON c.customer_id = o.customer_id
            JOIN
                order_items oi ON o.order_id = oi.order_id
            JOIN
                products p ON oi.product_id = p.product_id
            WHERE
                c.username = ?
            ORDER BY
                o.order_id DESC;";

    $stmt = mysqli_stmt_init($connect);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Group the results by order_id to create a structured array
    while ($row = mysqli_fetch_assoc($result)) {
        $orderId = $row['order_id'];
        if (!isset($userOrders[$orderId])) {
            $userOrders[$orderId] = [
                'order_id' => $orderId,
                'order_date' => $row['order_date'],
                'order_status' => $row['order_status'],
                'items' => []
            ];
        }
        $userOrders[$orderId]['items'][] = [
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'image_path' => $row['image_path']
        ];
    }

    mysqli_stmt_close($stmt);
?>