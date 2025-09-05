<?php
$pageTitle = "Admin Dashboard";
$cssFile = "adminDashboard.css";
include 'adminHeader.php';
?>

<div class="dashboardcontainer">
    <div class="overviewcards">
        <div class="dashboard-card top-products">
            <p id="no-of-top-products">300</p>
            <div class="card-title">
                <img src="imgs/best-product.png" alt="Top products icon">
                <h2>Top Products</h2>
            </div>
        </div>

        <div class="dashboard-card low-stock-items">
            <p id="no-of-low-stock-items">50</p>
            <div class="card-title">
                <img src="imgs/graph.png" alt="Low stock items icon">
                <h2>Low Stock Items</h2>
            </div>
        </div>

        <div class="dashboard-card recent-orders">
            <p id="no-of-recent-orders">120</p>
            <div class="card-title">
                <img src="imgs/history.png" alt="Recent orders icon">
                <h2>Recent Orders</h2>
            </div>
        </div>

        <div class="dashboard-card top-customers">
            <p id="no-of-top-customers">80</p>
            <div class="card-title">
                <img src="imgs/best-customer-experience.png" alt="Top customers icon">
                <h2>Top Customers</h2>
            </div>
        </div>

        <div class="dashboard-card top-suppliers">
            <p id="no-of-top-suppliers">30</p>
            <div class="card-title">
                <img src="imgs/supplier.png" alt="Top suppliers icon">
                <h2>Top Suppliers</h2>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
