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
    <link rel="shortcut icon" href="images/fav.png" type="image/png">

    <!-- External CSS -->
    <link rel="stylesheet" href="css/user.css">
    

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <!-- About Banner -->
    
    <section class="banner">
        <div class="details">
            <h1>About Us</h1>
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
    
   <!-- Who We Are Section -->
    <section class="who-we-are">
        <div class="container">
            <div class="text-content">
                <h2>Who We Are</h2>
                <img src="image/about-banner.jpg" alt="About Grocerly">
                <p>
                    At <strong>Grocerly</strong>, we are a team of passionate individuals dedicated to transforming grocery shopping. 
                    By combining smart technology with efficient logistics, we deliver a smooth and delightful online shopping experience right to your doorstep.
                </p>
                <div class="btn-group">
                    <a href="shop.php" class="btn">Explore Our Shop</a>
                    <a href="shop.php" class="btn">Visit Our Store</a>
                </div>
            </div>
            <div class="image-gallery">
                <img src="image/banner.jpg" alt="Grocerly Banner" class="img">
                <img src="image/about-banner.jpeg" alt="About Banner" class="img">
            </div>
        </div>

        <!-- Exclusive Collection -->
        <div class="exclusive">
            <div class="details">
                <h1>Exclusive Collection<br>Summer Collection 2025</h1>
                <p>Discover the freshest picks and seasonal specials curated just for you.</p>
                <a href="shop.php" class="btn">Shop Now</a>
            </div>
        </div>
    </section>
    <!-- Custom JS -->
    <script src="js/user_script.js"></script>
        <?php include('components/alert.php'); ?>
        <?php include('components/footer.php'); ?>
</body>
</html>
