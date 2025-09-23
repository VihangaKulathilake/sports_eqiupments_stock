<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/db.php';
$profileImg = "imgs/default.png";
if (isset($_SESSION["image_path"]) && !empty($_SESSION["image_path"])) {
    $profileImg = "userImgs/".$_SESSION["image_path"];
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
      
      <?php
        if(isset($_SESSION['customer_id'])) {

            echo '<a href="myFavourites.php?customer_id='.$_SESSION['customer_id'].'" class="fav-btn">';
            echo '<img src="imgs/like.png" alt="like button" class="like">';
            echo '</a>';

            echo '<a href="cart.php" class="cartBtn">';
            echo '<img src="imgs/shopping-cart-black.png" alt="shopping cart" class="cart">';

            $cartCount = 0;
            if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                $cartCount = array_sum($_SESSION['cart']);
            }
            echo "<span class='cartCount'>{$cartCount}</span>";
            echo '</a>';

            echo '<a href="myProfile.php" class="profileImgLink">
                    <img src="'.$profileImg.'" alt="profilePhoto" class="profilePhoto">
                  </a>';
        } else {
            echo '<a href="login.php" class="loginBtn">Login</a>';
        }
        ?>
      </div>

      <div class="searchbarcontainer">
        <form class="searchbarhome" action="searchResults.php" method="get">
          <input type="search" class="searchbar" name="query" placeholder="Search products, users,..." style="font-size:18px; padding-left:10px">
          <button type="submit" class="searchbtn">Search</button>
        </form>
      </div>
    </div>

    <?php
    $sql="SELECT DISTINCT category FROM products WHERE category IS NOT NULL";
    $result=mysqli_query($connect, $sql);
    $categories=[];
    if($result) {
        while($row=mysqli_fetch_assoc($result)) {
            $categories[]=$row['category'];
        }
    }
    ?>

    <nav class="sidebar" aria-label="Main Navigation">
      <img src="imgs/E2-removebg-preview-2.png" alt="sidebarLogo" class="sidebarLogo">
      <ul class="navlinks">
        <li><a href="index.php">Home</a></li>
        <li><a href="displayProducts.php">Products</a></li>

        <li class="dropdown">
          <a href="javascript:void(0);" class="dropbtn">Categories</a>
            <ul class="dropdown-content">
                <?php foreach($categories as $category): ?>
                    <li>
                        <a href="displayProductsCategory.php?category=<?= urlencode($category) ?>">
                            <?= htmlspecialchars($category) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>


        <li><a href="myOrders.php">My Orders</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <ul class="navbottom">
        <li><a href="includes/logout.inc.php">Logout</a></li>
      </ul>
    </nav>

    <script src="js/header.js"></script>
    <script src="js/toast.js"></script>


    <div class="overlay"></div>

    <main class="content">
