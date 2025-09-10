<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Initialize summary
$summary = $_SESSION["checkout_summary"] ?? [
    "subtotal_ex" => 0,
    "subtotal_in" => 0,
    "shipping"    => 0,
    "tax"         => 0,
    "order_total" => 0,
    "payment"     => "Not Selected"
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $paymentMethod = $_POST["payment_method"] ?? "Not Selected";

    $_SESSION["checkout_summary"] = [
        "subtotal_ex" => $_POST["subtotal_ex"] ?? 0,
        "subtotal_in" => $_POST["subtotal_in"] ?? 0,
        "shipping"    => $_POST["shipping"] ?? 0,
        "tax"         => $_POST["tax"] ?? 0,
        "order_total" => $_POST["order_total"] ?? 0,
        "payment"     => $paymentMethod
    ];

    // Redirect to order success only if payment method selected
    if ($paymentMethod !== "Not Selected") {
        header("Location: order_success.php");
        exit;
    }
}

// Profile image setup
$profileImg = "imgs/user.png";
if (isset($_SESSION["profileImg"]) && !empty($_SESSION["profileImg"])) {
    $profileImg = "uploads/" . $_SESSION["profileImg"];
}

$pageTitle = "Checkout - Sportivo";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $pageTitle; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <style>
    body { background: #f8faff; font-family: Arial, sans-serif; }
    h1,h4,h5 { color: #004aad; }
    .btn-confirm, .btn-primary { background: #007bff; color: #fff; border-radius: 6px; border:none; padding:10px 20px; }
    .btn-confirm:hover, .btn-primary:hover { background: #0056b3; }
    .payment-btn { padding: 10px 20px; border-radius:6px; cursor:pointer; border:1px solid #007bff; background:#eaf2ff; color:#004aad; margin-right:10px; }
    .payment-btn:hover { background:#d0e4ff; }
    .payment-box { border:1px solid #007bff; border-radius:6px; padding:15px; background:#fff; margin-top:10px; }
    .form-control, .form-select { border-radius:6px; border:1px solid #ccc; }
    .card { border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05); }
  </style>
</head>
<body>
<div class="page-container">

  <!-- HEADERBAR -->
  <div class="headerbar">
    <div class="headerbartop">
      <div class="menuToggle" aria-label="Toggle Menu">
        <img src="imgs/png-transparent-hamburger-menu-more-navigation-basic-ui-jumpicon-glyph-icon-removebg-preview.png" alt="Menu Icon" class="icon">
      </div>
      <img src="imgs/E11.png" alt="headerLogo" class="headerLogo">
      <a href="myProfile.php" class="profileImgLink">
        <img src="<?php echo $profileImg; ?>" alt="profilePhoto" class="profilePhoto">
      </a>
    </div>
    <div class="searchbarcontainer">
      <form class="searchbarhome" action="searchResults.php" method="get">
        <input type="search" class="searchbar" name="query" placeholder="Search products, users,..." style="font-size:18px; padding-left:10px">
        <button type="submit" class="searchbtn">Search</button>
      </form>
    </div>
  </div>

  <!-- SIDEBAR -->
  <nav class="sidebar" aria-label="Main Navigation">
    <img src="imgs/E2-removebg-preview-2.png" alt="sidebarLogo" class="sidebarLogo">
    <ul class="navlinks">
      <li><a href="adminDashboard.php">Dashboard</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="categories.php">Categories</a></li>
      <li><a href="stock.php">Stock</a></li>
      <li><a href="orders.php">Orders</a></li>
      <li><a href="users.php">Users</a></li>
      <li><a href="suppliers.php">Suppliers</a></li>
    </ul>
    <ul class="navbottom">
      <li><a href="includes/logout.inc.php">Logout</a></li>
    </ul>
  </nav>
  <div class="overlay"></div>

  <!-- MAIN CONTENT -->
  <main class="content">
    <div class="container py-4">
      <h1 class="mb-4">Checkout</h1>
      <div class="row">

        <!-- Billing Form -->
        <div class="col-md-7">
          <form id="checkout-form" class="card p-4" method="post" action="checkout.php">
            <h4 class="mb-3">Billing Details</h4>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">First Name *</label>
                <input type="text" name="first_name" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Last Name *</label>
                <input type="text" name="last_name" class="form-control">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Phone *</label>
              <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Street Address *</label>
              <input type="text" name="address" class="form-control">
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Town / City *</label>
                <input type="text" name="city" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Country *</label>
                <select name="state" class="form-select">
                  <option value="">Select a country...</option>
                  <option>Sri Lanka</option>
                  <option>India</option>
                  <option>United States</option>
                  <option>United Kingdom</option>
                  <option>Australia</option>
                  <option>Canada</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">PIN Code *</label>
              <input type="text" name="pin" class="form-control">
            </div>

            <!-- Shipping -->
            <div class="form-check mt-3 mb-3">
              <input class="form-check-input" type="checkbox" id="shipDiff" name="shipDiff">
              <label class="form-check-label fw-bold" for="shipDiff">Ship to a different address?</label>
            </div>

            <div id="shipping-address" class="border rounded p-3 mb-3" style="display:none;">
              <h5 class="mb-3">Shipping Address</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <input type="text" name="ship_first_name" class="form-control" placeholder="First Name">
                </div>
                <div class="col-md-6 mb-3">
                  <input type="text" name="ship_last_name" class="form-control" placeholder="Last Name">
                </div>
              </div>
              <input type="text" name="ship_address" class="form-control mb-2" placeholder="Street Address">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <input type="text" name="ship_city" class="form-control" placeholder="Town / City">
                </div>
                <div class="col-md-6 mb-3">
                  <select name="ship_state" class="form-select">
                    <option value="">Select a country...</option>
                    <option>Sri Lanka</option>
                    <option>India</option>
                    <option>United States</option>
                    <option>United Kingdom</option>
                    <option>Australia</option>
                  </select>
                </div>
              </div>
              <input type="text" name="ship_pin" class="form-control" placeholder="PIN Code">
            </div>

            <!-- Payment Method -->
            <h4 class="mb-3">Payment Method</h4>
            <input type="hidden" name="payment_method" id="payment_method">
            <button type="button" class="payment-btn" onclick="showPayment('card')">💳 Credit/Debit Card</button>
            <button type="button" class="payment-btn" onclick="showPayment('cod')">💵 Cash on Delivery</button>

            <div id="card-payment" class="payment-box" style="display:none;">
              <h5>Credit / Debit Card</h5>
              <input type="text" class="form-control mb-2" placeholder="Card Number" name="card_number">
              <input type="text" class="form-control mb-2" placeholder="Name on Card" name="card_name">
              <div style="display:flex; gap:10px;">
                <input type="text" class="form-control" placeholder="MM/YY" name="card_expiry">
                <input type="text" class="form-control" placeholder="CVV" name="card_cvv">
              </div>
              <button type="submit" class="btn-confirm mt-2">Pay Now</button>
            </div>

            <div id="cod-payment" class="payment-box" style="display:none;">
              <h5>Cash on Delivery</h5>
              <p>Pay with cash upon delivery.</p>
              <button type="submit" class="btn-confirm mt-2">Confirm Order</button>
            </div>

            <!-- Hidden inputs for order summary -->
            <input type="hidden" name="subtotal_ex" value="<?php echo $summary['subtotal_ex']; ?>">
            <input type="hidden" name="subtotal_in" value="<?php echo $summary['subtotal_in']; ?>">
            <input type="hidden" name="shipping" value="<?php echo $summary['shipping']; ?>">
            <input type="hidden" name="tax" value="<?php echo $summary['tax']; ?>">
            <input type="hidden" name="order_total" value="<?php echo $summary['order_total']; ?>">

          </form>
        </div>

        <!-- Order Summary -->
        <div class="col-md-5">
          <div class="card p-4">
            <h4 class="mb-3">Order Summary</h4>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between"><span>Subtotal (Excl. Tax)</span><strong>LKR <?php echo number_format($summary['subtotal_ex'],2); ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Subtotal (Incl. Tax)</span><strong>LKR <?php echo number_format($summary['subtotal_in'],2); ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Shipping</span><strong><?php echo ($summary['shipping']>0)?"LKR ".number_format($summary['shipping'],2):"Free"; ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Tax</span><strong>LKR <?php echo number_format($summary['tax'],2); ?></strong></li>
              <li class="list-group-item d-flex justify-content-between fw-bold"><span>Total</span><strong>LKR <?php echo number_format($summary['order_total'],2); ?></strong></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </main>
</div>

<script>
function showPayment(type) {
  document.getElementById('card-payment').style.display='none';
  document.getElementById('cod-payment').style.display='none';
  document.getElementById('payment_method').value = type;
  if(type==='card') document.getElementById('card-payment').style.display='block';
  if(type==='cod') document.getElementById('cod-payment').style.display='block';
}

document.getElementById('shipDiff')?.addEventListener('change', function(){
  document.getElementById('shipping-address').style.display = this.checked ? 'block' : 'none';
});

// Sidebar toggle
const toggleBtn = document.querySelector(".menuToggle");
const sidebar = document.querySelector(".sidebar");
const overlay = document.querySelector(".overlay");
toggleBtn.addEventListener("click", ()=>{sidebar.classList.toggle("active"); overlay.classList.toggle("active");});
overlay.addEventListener("click", ()=>{sidebar.classList.remove("active"); overlay.classList.remove("active");});
</script>
</body>
</html>
