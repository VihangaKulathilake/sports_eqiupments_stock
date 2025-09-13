<?php
$pageTitle = "Top Customers";
$cssFile = "css/topCustomers.css?v=" . time(); // Dynamic version number to force a refresh
include 'adminHeader.php';
include 'includes/db.php';
?>

<div class="main-content">
    <div class="top-customers-container">
        <h1 class="header">Top Customers</h1>
        <div class="customers-grid">
            <?php
            $sql = "
                SELECT 
                    c.customer_id, 
                    c.name,
                    c.email,
                    c.phone,
                    c.image_path,
                    SUM(oi.quantity * p.price) AS total_spent
                FROM customers c
                JOIN orders o ON c.customer_id = o.customer_id
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                GROUP BY c.customer_id
                ORDER BY total_spent DESC
                LIMIT 8
            ";

            $result = mysqli_query($connect, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Check if an image path exists and if the file actually exists on the server
                    $profileImgPath = "userImgs/" . htmlspecialchars($row['image_path']);
                    if (!empty($row['image_path']) && file_exists($profileImgPath)) {
                        $profileImg = $profileImgPath;
                    } else {
                        $profileImg = "userImgs/default.jpg";
                    }

                    echo "
                    <div class='customer-card'>
                        <div class='img-container'>
                            <img src='" . $profileImg . "' alt='" . htmlspecialchars($row['name']) . "' class='customer-profile-img'>
                        </div>
                        <div class='customer-details'>
                            <h3 class='customer-name'>" . htmlspecialchars($row['name']) . "</h3>
                            <p class='customer-phone'>Phone: " . htmlspecialchars($row['phone']) . "</p>
                            <p class='total-spent'>Total Spent: LKR " . number_format($row['total_spent'], 2) . "</p>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='no-data'>No customer data found.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>