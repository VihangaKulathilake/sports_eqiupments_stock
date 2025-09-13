<?php
require_once 'includes/db.php';
$pageTitle="Thank You";
$cssFile="css/thankyou.css";
include 'userHeader.php';
?>
<div class="thankyou-container">
    <h2>🎉 Thank You for Your Order!</h2>
    <p>Your purchase was successful. We’ll send you an update once your order is processed.</p>
    <a href="displayProducts.php" class="btn">Continue Shopping</a>
</div>

<?php include 'footer.php'; ?>
