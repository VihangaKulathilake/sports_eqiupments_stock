<?php
    $pageTitle = "About Us";
    $cssFile = "css/about.css";
    include 'userHeader.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Sportivo</title>
  <link rel="stylesheet" href="about.css">
</head>
<body>

  <!-- Hero -->
  <section class="about-hero">
    <div class="overlay"></div>
    <div class="hero-content">
      <h1>About <span>Sportivo</span></h1>
      <p>Your trusted partner for high-quality sports gear and equipment</p>
    </div>
  </section>

  <!-- About -->
  <section class="about">
    <div class="about-text">
      <h2>Who We Are</h2>
      <p>
        At Sportivo, we are passionate about empowering athletes and fitness enthusiasts 
        by providing top-quality sports equipment, apparel, and accessories. 
        Our mission is to inspire a healthy lifestyle through innovation, 
        durability, and performance-driven products.
      </p>
    </div>
  </section>

  <!-- Mission and Vision -->
  <section class="mission-vision">
    <div class="card">
      <h3>Our Mission</h3>
      <p>
        To deliver world-class sporting goods that boost performance 
        and help athletes achieve their full potential.
      </p>
    </div>
    <div class="card">
      <h3>Our Vision</h3>
      <p>
        To become the leading sports brand recognized globally for 
        quality, innovation, and sustainability.
      </p>
    </div>
  </section>

  
  <section class="call-to-action">
        <h2>Ready to Gear Up?</h2>
        <p>Explore our wide range of products and find the perfect gear for your sport.</p>
        <a href="index.php" class="cta-btn">Start Shopping</a>
    </section>
</body>
</html>
<?php
    include 'footer.php';
?>
