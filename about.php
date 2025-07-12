<?php 
    include 'components/connect.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About | Grocerly</title>
    
    <!-- Favicon -->
    <link rel="stylesheet" type="text/css" href="css/user.css">
        
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <!-- About Banner -->
    
    <section class="banner">
        <div class="details">
            <h1>About Us</h1>
             <h2>💚 Welcome to Grocerly</h2>
            <p>
                At <strong>Grocerly</strong>, we believe grocery shopping should be simple, convenient, and enjoyable. That’s why we’ve built a one-stop online destination where you can find
                fresh produce, pantry staples, and everyday essentials—all delivered right to your door.
                <br><br>
                Whether you're stocking up for the week or grabbing last-minute items, Grocerly is here to make your life easier. We’re committed to quality, affordability, and exceptional service,
                so you can spend less time shopping and more time enjoying what matters most.
            </p>
            <span><a href="dashboard.php">Home</a> <i class="bx bx-right-arrow-alt"></i> About Us</span>
        </div>
    </section>

    <section class="row-container">
        <!-- Left Side: About Grocerly -->
        <div class="box-left">
            <h2>🏬 About Grocerly - Who We Are</h2>
            <p><strong>Grocerly</strong> is proudly Sri Lankan — locally owned and operated, with a mission to redefine everyday grocery shopping through .</p>
            <p>We're committed to delivering exceptional value and service to thousands of households across the island, every single day.</p>
            <p>Grocerly operates a growing network of outlets and an advanced online shopping platform. Our purpose is to improve the quality of life
                 for every Sri Lankan by making groceries accessible, affordable, and enjoyable<.</p>
            <p>With a dedicated team of over 2,000+ passionate individuals, we source fresh produce from local farmers and deliver to stores within
                24 hours. Our in-house certified bakeries and hot kitchens serve oven-fresh meals daily.</p>
        </div>

        <!-- Right Side: Awards -->
        <div class="box-right">
            <h2>🏆 Recognized for Our Commitment to Quality and Innovation</h2>
            <ul class="award-list">
                <br>
            <li><strong>Best Retail Innovation 2024</strong> - Sri Lanka Retail Excellence Awards</li>
            <li><strong>National Excellence Award</strong> - Fresh Produce Supply Chain 2023</li>
            <li><strong>Green Retailer of the Year 2023</strong> - Sustainability Forum Sri Lanka</li>
            <li><strong>Top 10 Most Trusted Grocery Brands</strong> - Lanka Trust Index 2024</li>
            <li><strong>Digital Grocery Leader Award</strong> - Sri Lanka E-Commerce Awards 2023</li>
            <li><strong>Winner</strong> - Best Customer Loyalty Program 2024</li>
            <li><strong>Best Use of Technology in Retail</strong> - GrocerTech Asia 2024</li>
            </ul>
        </div>
    </section>

    <section class="grocerly-intro">
        <div class="grocerly-content">
            
            <p>
            At Grocerly, we're more than just a supermarket — we're a part of your home, your health, and your community.
            From farm-fresh produce to daily essentials, from oven-warm baked goods to ready-made meals, we bring quality and convenience to your doorstep.
            <br>
            <br>
            Our mission is to make your grocery shopping experience simple, affordable, and enjoyable. Whether online or in-store, we are committed to making sure you get the best — every single day.
            
            As a proudly Sri Lankan brand, we support local farmers and producers while embracing sustainable, future-friendly practices that benefit your family and the planet.
            
            Grocerly is not just a store — it's a service, a smile, and a promise to grow with you.
            </p>
            <br>
            <br>
             <p class="closing-message"><strong>
                Thank you for choosing Grocerly — have a great day! 🌿</strong>
            </p>

        </div>
    </section>



    
   <!-- Who We Are Section -->
    <section class="who-we-are">
  <div class="container">
    <img src="image/grocerly.webp" alt="Grocerly Team" class="top-image">
    <h2>Who We Are</h2>
    <p>
      At <strong>Grocerly</strong>, we are a team of passionate individuals dedicated to transforming grocery shopping. 
      We combine smart technology with efficient logistics to deliver a smooth and reliable experience.
    </p>
    <div class="btn-group">
      <a href="shop.php" class="btn">Explore Our Shop</a>
      <a href="shop.php" class="btn">Visit Our Store</a>
    </div>
  </div>
</section>

        <!-- Exclusive Collection 
        <div class="exclusive">
            <div class="details">
                <h1>Exclusive Collection<br>Summer Collection 2025</h1>
                <p>Discover the freshest picks and seasonal specials curated just for you.</p>
                <a href="shop.php" class="btn">Shop Now</a>
            </div>
        </div>
    </section>
    -->

    <!-- Custom JS -->
    <script src="js/user_script.js"></script>
        <?php include('components/alert.php'); ?>
        <?php include('components/footer.php'); ?>
</body>
</html>
