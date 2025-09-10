<?php
session_start();

// Get order summary from session
$summary = $_SESSION["checkout_summary"] ?? [
    "subtotal_ex" => 0,
    "subtotal_in" => 0,
    "shipping"   => 0,
    "tax"        => 0,
    "order_total"=> 0,
    "payment"     => "Not Selected"
];

// Get or generate a unique order ID
if (!isset($_SESSION["order_id"])) {
    $_SESSION["order_id"] = "ORD-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -6));
}
$orderId = $_SESSION["order_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order Successful</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f0f4ff;
        font-family: Arial, sans-serif;
    }
    .success-container {
        max-width: 650px;
        margin: 50px auto;
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
        border-top: 6px solid #007bff;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        background: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .success-icon span {
        font-size: 45px;
        color: #fff;
    }
    h2 {
        color: #004aad;
        font-weight: bold;
        margin-bottom: 15px;
    }
    p {
        color: #333;
        font-size: 16px;
    }
    .order-summary {
        margin-top: 30px;
        text-align: left;
    }
    .order-summary h5 {
        font-weight: bold;
        margin-bottom: 15px;
        color: #004aad;
    }
    .list-group-item {
        border-radius: 6px;
        border: 1px solid #d0d8f0;
        margin-bottom: 8px;
    }
    .fw-bold.fs-5 {
        color: #007bff;
    }
    .back-btn {
        margin-top: 25px;
    }
    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>
</head>
<body>
  <!-- Back Button -->
<div class="back-btn" style="margin: 20px 0;">
    <button onclick="goBack()" class="btn btn-outline-primary">← Back</button>
</div>

<script>
function goBack() {
    window.history.back();
}
</script>


<div class="success-container">
    <!-- Blue Tick Icon -->
    <div class="success-icon">
        <span>✓</span>
    </div>

    <!-- Thank You Message -->
    <h2>Thank you for your purchase!</h2>
    <p>We've received your order. It will ship in 5–7 business days.</p>
    <p>Your order number is <strong><?php echo $orderId; ?></strong></p>

    <!-- Order Summary -->
    <div class="order-summary">
        <h5>Order Summary</h5>
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between">
                <span>Subtotal (Excl. Tax)</span>
                <span>LKR <?php echo number_format($summary["subtotal_ex"], 2); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Subtotal (Incl. Tax)</span>
                <span>LKR <?php echo number_format($summary["subtotal_in"], 2); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Shipping</span>
                <span><?php echo $summary["shipping"] > 0 ? "LKR " . number_format($summary["shipping"], 2) : "Free"; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Tax</span>
                <span>LKR <?php echo number_format($summary["tax"], 2); ?></span>
            </li>
        </ul>
        <div class="d-flex justify-content-between fw-bold fs-5">
            <span>Order Total</span>
            <span>LKR <?php echo number_format($summary["order_total"], 2); ?></span>
        </div>
        <div class="mt-3">
            <span>Payment Method: </span>
            <strong>
                <?php 
                  echo ($summary["payment"] === "cod") ? "Cash on Delivery" : "Credit/Debit Card";
                ?>
            </strong>
        </div>
    </div>

    <!-- Back Button -->
    <div class="back-btn">
        <a href="index.php" class="btn btn-outline-primary">Back to Home</a>
    </div>
</div>
<script>
function goBack() {
    window.history.back();
}
</script>

</body>
</html>
