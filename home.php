<?php 
    include 'components/connect.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home Page</title>

        <link rel="stylesheet" type="text/css" href="css/user.css">
        
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>

    <body>
        <?php include('components/user_header.php'); ?>

        <section class="slide-container">
            <div class="slider">
                <div class="sliderBox active">
                    <div class="textBox">
                        <h1>Freshness Delivered <br> to Your Doorstep</h1>
                    </div>
                    <div class="imgBox">
                        <img src="images/hero2.png" alt="Slide 1">
                    </div>
                </div>
                <div class="sliderBox">
                    <div class="textBox">
                        <h1>The Grocery Store <br> That Comes to You</h1>
                    </div>
                    <div class="imgBox">
                        <img src="images/hero1.png" alt="Slide 2">
                    </div>
                </div>
            </div>
            <ul class="controls">
                <li class="slider-prev">
                    <i class="bx bxs-left-arrow-alt"></i>
                </li>
                <li class="slider-next">
                    <i class="bx bxs-right-arrow-alt"></i>
                </li>
            </ul>
        </section>


        <section class="categories">
            <div class="heading">
                <h1>Shop by Categories</h1>
            </div>
            <div class="box-container">
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/fruits.jpg">
                        <h3 class="btn">Fruits</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/vegetables.jpg">
                        <h3 class="btn">Vegitables</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/beverage.jpg">
                        <h3 class="btn">Dairy & Beverages</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/snacks.jpg">
                        <h3 class="btn">Snacks</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/bakery.jpg">
                        <h3 class="btn">Bakery</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/meat.jpg">
                        <h3 class="btn">Meat & Seafood</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/care.jpg">
                        <h3 class="btn">Personal Care</h3>
                    </a>
                </div>
                <div class="box">
                    <a href="shop.php" class="category-content">
                        <img src="images/household.jpg">
                        <h3 class="btn">Hosehold items</h3>
                    </a>
                </div>
            </div>
        </section>        
        

        <script src="js/user_script.js"></script>
        <?php include('components/alert.php'); ?>
        <?php include('components/footer.php'); ?>

    </body>

</html>



