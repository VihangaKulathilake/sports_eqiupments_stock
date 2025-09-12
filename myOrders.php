<?php
$pageTitle = "My Orders";
$cssFile = "css/myorders.css";
include 'adminHeader.php';
require_once 'includes/myOrders.inc.php';
?>


<div class="orders-container">
    <h1>My Orders</h1>
    <div class="orders-list">
        <?php if (count($userOrders) > 0) : ?>
            <?php foreach ($userOrders as $order) : ?>
                <div class="order-card">
                    <div class="order-summary">
                        <div class="summary-item">
                            <strong>Order ID:</strong>
                            <span><?php echo htmlspecialchars($order['order_id']); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Date:</strong>
                            <span><?php echo htmlspecialchars(date('M d, Y', strtotime($order['order_date']))); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Status:</strong>
                            <span class="status-badge status-<?php echo htmlspecialchars(strtolower($order['order_status'])); ?>"><?php echo htmlspecialchars($order['order_status']); ?></span>
                        </div>
                    </div>

                    <div class="order-items">
                        <?php foreach ($order['items'] as $item) : ?>
                            <div class="order-item">
                                <img src="product_images/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-image">
                                <div class="product-details">
                                    <span class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                                    <span class="product-quantity">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></span>
                                    <span class="product-price">$<?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="no-orders-message">You have no orders yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php
    include 'footer.php';
?>