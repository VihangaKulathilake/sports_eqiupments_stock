<?php
session_start();
include_once 'includes/db.php';
$profileImg = "imgs/user.png";
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
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Sidebar Menu'; ?></title>
</head>
<body>
  <div class="page-container">
    <div class="headerbar">
      <div class="headerbartop">
        <div class="menuToggle" aria-label="Toggle Menu">
          <img src="imgs/png-transparent-hamburger-menu-more-navigation-basic-ui-jumpicon-glyph-icon-removebg-preview.png" alt="Menu Icon" class="icon">
        </div>
        <img src="imgs/E11.png" alt="headerLogo" class="headerLogo">

        <a href="cart.php" class="cartBtn">
            <img src="imgs/shopping-cart-black.png" alt="shopping cart" class="cart">
            <?php
            $cartCount = 0;
            if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                $cartCount = array_sum($_SESSION['cart']);
            }
            echo "<span class='cartCount'>{$cartCount}</span>";
            ?>
        </a>

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
                        <a href="displayCategory.php?category=<?= urlencode($category) ?>">
                            <?= htmlspecialchars($category) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>


        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <ul class="navbottom">
        <li><a href="includes/logout.inc.php">Logout</a></li>
      </ul>
    </nav>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.querySelector('.sidebar .navlinks li.dropdown');

        if(dropdown) {
            const dropBtn = dropdown.querySelector('.dropbtn');

            dropBtn.addEventListener('click', function(e) {
                e.preventDefault(); // prevent default link behavior
                dropdown.classList.toggle('active');
            });
        }
    });
    </script>

    <div class="overlay"></div>

    <main class="content">
