<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/db.php';
$profileImg = "imgs/default.png";
if (isset($_SESSION["profileImg"]) && !empty($_SESSION["profileImg"])) {
    $profileImg = "uploads/" . $_SESSION["profileImg"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <?php if(isset($cssFile)) { ?>
      <link rel="stylesheet" href="<?php echo $cssFile; ?>">
  <?php } ?>
  <?php if(isset($extraCss)) { ?>
      <link rel="stylesheet" href="<?php echo $extraCss; ?>">
  <?php } ?>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Sidebar Menu'; ?></title>
</head>
<body>

<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="toast toast-error">
        <?= htmlspecialchars($_SESSION['error_msg']); ?>
        <span class="toast-close">&times;</span>
    </div>
<?php unset($_SESSION['error_msg']); endif; ?>

<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="toast toast-success">
        <?= htmlspecialchars($_SESSION['success_msg']); ?>
        <span class="toast-close">&times;</span>
    </div>
<?php unset($_SESSION['success_msg']); endif; ?>

  <div class="page-container">
    <div class="headerbar">
      <div class="headerbartop">
        <div class="menuToggle" aria-label="Toggle Menu">
          <img src="imgs/png-transparent-hamburger-menu-more-navigation-basic-ui-jumpicon-glyph-icon-removebg-preview.png" alt="Menu Icon" class="icon">
        </div>
        <img src="imgs/E11.png" alt="headerLogo" class="headerLogo">
        <a href="myProfileAdmin.php" class="profileImgLink">
          <img src="<?php echo $profileImg; ?>" alt="profilePhoto" class="profilePhoto">
        </a>
      </div>

      <div class="searchbarcontainer">
        <form class="searchbarhome" action="searchResultsAdmin.php" method="get">
          <input type="search" class="searchbar" name="query" placeholder="Search products, users,..." style="font-size:18px; padding-left:10px">
          <button type="submit" class="searchbtn">Search</button>
        </form>
      </div>
    </div>

    <nav class="sidebar" aria-label="Main Navigation">
      <img src="imgs/E2-removebg-preview-2.png" alt="sidebarLogo" class="sidebarLogo">
      <img src="imgs/cancel-button.png" alt="Close Sidebar" class="sidebarCancelBtn">
      <ul class="navlinks">
        <li><a href="adminDashboard.php">Dashboard</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="stocks.php">Stock</a></li>
        <li><a href="orders.php">Orders</a></li>
        <li><a href="purchases.php">Purchases</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="suppliers.php">Suppliers</a></li>
      </ul>
      <ul class="navbottom">
        <li><a href="includes/logout.inc.php">Logout</a></li>
      </ul>
    </nav>

    <script src="js/header.js"></script>
    <script src="js/toast.js"></script>

    <div class="overlay"></div>

    <main class="content">



