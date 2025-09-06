<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$profileImg = "imgs/user.png";
if (isset($_SESSION["profileImg"]) && !empty($_SESSION["profileImg"])) {
    $profileImg = "uploads/" . $_SESSION["profileImg"];
}
$pageTitle = "Shopping Cart";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <title><?php echo $pageTitle; ?></title>
</head>
<body>
<div class="page-container">

  <!-- HEADER -->
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
      <h1 class="mb-4">Shopping Cart</h1>
      <div class="row">
        <!-- Cart Items -->
        <div class="col-md-8">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="cart-items">
  <tr>
    <td>
      <div class="d-flex align-items-center">
        <img src="assets/sample-bat.jpg" alt="Product" width="60" class="me-3">
        <span>SS Toss Coin</span>
      </div>
    </td>
    <td class="item-price" data-price="25000">LKR 25,000.00</td>
    <td><input type="number" class="form-control form-control-sm qty-input" value="1" min="1" style="width:80px;"></td>
    <td class="line-total">LKR 25,000.00</td>
    <td><button class="btn btn-sm btn-danger remove-item">✕</button></td>
  </tr>
  <tr>
    <td>
      <div class="d-flex align-items-center">
        <img src="assets/sample-gloves.jpg" alt="Product" width="60" class="me-3">
        <span>Cricket Gloves</span>
      </div>
    </td>
    <td class="item-price" data-price="8000">LKR 8,000.00</td>
    <td><input type="number" class="form-control form-control-sm qty-input" value="2" min="1" style="width:80px;"></td>
    <td class="line-total">LKR 16,000.00</td>
    <td><button class="btn btn-sm btn-danger remove-item">✕</button></td>
  </tr>
</tbody>

          </table>

          <div class="d-flex justify-content-between mt-3">
            <a href="products.php" class="btn btn-outline-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-danger">Proceed to Checkout</a>
          </div>
        </div>

        <!-- Summary Section -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="mb-3">Apply Coupon Code</h5>
              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Enter coupon code">
                <button class="btn btn-danger">Apply</button>
              </div>

              <h5 class="mb-3">Summary</h5>
              <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between">
                  <span>Subtotal (Excl. Tax)</span><span id="subtotal-ex">LKR 25,000.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Subtotal (Incl. Tax)</span><span id="subtotal-in">LKR 28,000.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Shipping</span><span id="shipping">LKR 2,000.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Tax</span><span id="tax">LKR 3,000.00</span>
                </li>
              </ul>
              <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Order Total</span><span id="order-total">LKR 33,000.00</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer_box">
        <h3>About Sportivo</h3>
        <p>Sportivo is your one-stop destination for premium sports equipment and accessories. We bring quality, performance, and style together to help athletes and enthusiasts achieve their best every day.</p>
        <div class="social">
            <a href="#"><i class='bx bxl-facebook-circle'></i></a>
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-instagram-alt'></i></a>
            <a href="#"><i class='bx bxl-tiktok'></i></a>
        </div>
    </div>

    <div class="footer_box">
        <h3>Products</h3>
        <ul>
            <li><a href="#">All Sports Equipment</a></li>
            <li><a href="#">Team & Individual Gear</a></li>
            <li><a href="#">Fitness Accessories</a></li>
            <li><a href="#">Training & Coaching Tools</a></li>
            <li><a href="#">New Arrivals</a></li>
            <li><a href="#">Best Sellers</a></li>
            <li><a href="#">Sale Items</a></li>
        </ul>
    </div>

    <div class="footer_box">
        <h3>Support / Terms</h3>
        <ul>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Return & Refund Policy</a></li>
            <li><a href="#">Shipping & Delivery Policy</a></li>
            <li><a href="#">Help & Support</a></li>
            <li><a href="#">FAQs</a></li>
            <li><a href="#">Warranty Information</a></li>
        </ul>
    </div>

    <div class="footer_box">
        <h3>Contact</h3>
        <div class="Contact">
            <span><i class='bx bx-map'></i>Mahawaththa, Andaluwa, Gomila, Mawarala</span>
            <span><i class='bx bxs-phone-call'></i>0775227202</span>
            <span><i class='bx bxs-envelope'></i>sportivosports@gmail.com</span>
        </div>
    </div>
  </footer>
</div>

<div class="footer-bottom">
  <p>© 2025 Sportivo. All Rights Reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar toggle
const toggleBtn = document.querySelector(".menuToggle");
const sidebar = document.querySelector(".sidebar");
const overlay = document.querySelector(".overlay");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
});

overlay.addEventListener("click", () => {
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
});
// Auto update subtotal & summary
document.addEventListener("input", e => {
  if (e.target.classList.contains("qty-input")) {
    updateTotals();
  }
});

document.addEventListener("click", e => {
  if (e.target.classList.contains("remove-item")) {
    e.target.closest("tr").remove();
    updateTotals();
  }
});

function updateTotals() {
  let subtotalIncl = 0;

  document.querySelectorAll("#cart-items tr").forEach(row => {
    let price = parseFloat(row.querySelector(".item-price").dataset.price);
    let qty = parseInt(row.querySelector(".qty-input").value) || 1;
    let lineTotal = price * qty;

    // update line subtotal
    row.querySelector(".line-total").innerText = "LKR " + lineTotal.toLocaleString();

    subtotalIncl += lineTotal;
  });

  let tax = subtotalIncl * 0.12; // 12% tax
  let subtotalEx = subtotalIncl - tax;
  let shipping = subtotalIncl > 0 ? 2000 : 0;
  let orderTotal = subtotalIncl + shipping;

  document.getElementById('subtotal-ex').innerText = "LKR " + subtotalEx.toLocaleString();
  document.getElementById('subtotal-in').innerText = "LKR " + subtotalIncl.toLocaleString();
  document.getElementById('shipping').innerText = "LKR " + shipping.toLocaleString();
  document.getElementById('tax').innerText = "LKR " + tax.toLocaleString();
  document.getElementById('order-total').innerText = "LKR " + orderTotal.toLocaleString();
}

// run once on page load
updateTotals();

</script>
</body>
</html>


