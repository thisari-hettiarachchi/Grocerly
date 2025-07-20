<?php
    include 'components/connect.php';

    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; 
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $user_id = $user ? $user['user_id'] : '';
    } else {
        $user_id = '';
    }

    if (!function_exists('unique_id')) {
        function unique_id() {
            return uniqid();
        }
    }

    if (isset($_POST['add_to_cart'])) {
        if (!empty($user_id)) { 
            $id = unique_id(); 
            $product_id = $_POST['product_id'];
            $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

            $qty = $_POST['qty'];
            $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT); 

            if ($qty <= 0) {
                $warning_msg[] = 'Invalid quantity!';
            } else {
                $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
                $verify_cart->execute([$user_id, $product_id]);

                $max_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                $max_cart_items->execute([$user_id]);

                if ($verify_cart->rowCount() > 0) {
                    $warning_msg[] = 'Product already exists in your cart';
                } else if ($max_cart_items->rowCount() >= 20) {
                    $warning_msg[] = 'Cart is full!';
                } else {
                    $select_price = $conn->prepare("SELECT * FROM product WHERE product_id = ? LIMIT 1");
                    $select_price->execute([$product_id]);
                    $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                    if ($fetch_price) {
                        try {
                            $insert_cart = $conn->prepare("INSERT INTO cart (cart_id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)");
                            $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);

                            $success_msg[] = 'Product added to cart successfully';
                        } catch (PDOException $e) {
                            $error_msg[] = 'Error adding product to cart: ' . $e->getMessage();
                        }
                    } else {
                        $warning_msg[] = 'Product not found!';
                    }
                }
            }
        } else {
            $warning_msg[] = 'Please login to add items to your cart';
        }
    }

    if (isset($_POST['add_to_wishlist'])) {
        if (!empty($user_id)) { 
            $id = unique_id(); 
            $product_id = $_POST['product_id'];
            $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

            $verify_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
            $verify_wishlist->execute([$user_id, $product_id]);

            if ($verify_wishlist->rowCount() > 0) {
                $warning_msg[] = 'Product already exists in your wishlist';
            } else {
                $select_price = $conn->prepare("SELECT price FROM product WHERE product_id = ? LIMIT 1");
                $select_price->execute([$product_id]);
                $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

                if ($fetch_price) {
                    try {
                        $insert_wishlist = $conn->prepare("INSERT INTO wishlist (id, user_id, product_id, price) VALUES (?, ?, ?, ?)");
                        $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);

                        $success_msg[] = 'Product added to wishlist successfully';
                    } catch (PDOException $e) {
                        $error_msg[] = 'Error adding product to wishlist: ' . $e->getMessage();
                    }
                } else {
                    $warning_msg[] = 'Product not found!';
                }
            }
        } else {
            $warning_msg[] = 'Please login to add items to your wishlist';
        }
    } 

?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">
        <link rel="stylesheet" type="text/css" href="css/shop.css">
        
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
            
            <!--<ul class="controls">
                <li class="slider-prev">
                    <i class="bx bxs-left-arrow-alt"></i>
                </li>
                <li class="slider-next">
                    <i class="bx bxs-right-arrow-alt"></i>
                </li>
            </ul> -->
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
        
        <div class="shop-container">
            <div class="heading">
                <h1>Our Latest Products</h1>
            </div>
            <div class="box-container">
                <?php
                    $select_products = $conn->prepare("SELECT * FROM product WHERE status = ?");
                    $select_products->execute(['active']);

                    if ($select_products->rowCount() > 0) {
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                ?>

                <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo "disabled";}?>">
                    <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image">
                    <?php  
                        if ($fetch_products['stock'] > 9) { ?>
                            <span class="stock" style="color: green;">In Stock</span>
                        <?php } elseif ($fetch_products['stock'] == 0) { ?>
                            <span class="stock" style="color: red;">Out Of Stock</span>
                        <?php } else { ?>
                            <span class="stock" style="color: red;">Only Few Left</span>
                        <?php } ?>

                    <div class="content">
                        <h3 class="name"><?= $fetch_products['name']; ?></h3>
                        <div class="button">
                            <div>
                                <?php if ($fetch_products['stock'] > 0) { ?>
                                    <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                                    <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                                <?php } ?>
                                <a href="view_page.php?product_id=<?= $fetch_products['product_id'] ?>" class="bx bxs-show"></a>
                            </div>
                        </div>
                        <h3 class="price">Rs.<?=$fetch_products['price']; ?></h3>
                        <input type="hidden" name="product_id" value="<?= $fetch_products['product_id'] ?>">
                        <div class="flex-btn">
                            <?php if ($fetch_products['stock'] > 0) { ?>
                                <a href="checkout.php?get_id=<?= $fetch_products['product_id']?>" class="btn">Buy Now</a>
                                <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty btn">
                            <?php } else { ?>
                                <span class="btn disabled">Out of Stock</span>
                            <?php } ?>
                        </div>
                    </div>
                </form>

                <?php
                        }
                    }else{
                        echo '
                        <div class="empty">
                            <p>No products added yet!</p>
                        </div>';
                    }
                ?>
            </div>
        </div>

        <section class="deals-section">
            <h2 class="section-title">Current Deals</h2>
            <div class="deals-grid">
                <div class="deal-card">
                    <h3>Fresh Daily Picks</h3>
                    <p>Fresh vegetables and fruits at amazing prices</p>
                    <div class="deal-percentage">30% OFF</div>
                </div>
                
                <div class="deal-card">
                    <h3>Weekly Essentials</h3>
                    <p>Stock up on pantry and household items</p>
                    <div class="deal-percentage">25% OFF</div>
                </div>
                
                <div class="deal-card">
                    <h3>Seasonal Special</h3>
                    <p>Limited time offers on seasonal products</p>
                    <div class="deal-percentage">40% OFF</div>
                </div>
                
                <div class="deal-card">
                    <h3>Buy 1 Get 1</h3>
                    <p>Double the value on selected items</p>
                </div>
            </div>
        </section>


        <section class="features-section">
            <h2 class="section-title"> Why Choose Us</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>Fast Delivery</h3>
                    <p>Same-day delivery available. Get your groceries in 2 hours or less.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>Fresh Guarantee</h3>
                    <p>100% fresh guarantee on all produce. Not satisfied? Get a full refund.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>Secure Payments</h3>
                    <p>Multiple payment options with bank-level security for all transactions.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>24/7 Support</h3>
                    <p>Round-the-clock customer service to help with any questions or concerns.</p>
                </div>
            </div>
        </section>


        <section class="app-section">
            <h2>Download Our App</h2>
            <p>Shop on the go with our mobile app. Get exclusive deals and faster checkout!</p>
            <div class="app-buttons">
                <a href="#" class="app-button">
                    Download on Play Store
                </a>
                <a href="#" class="app-button">
                    Download on App Store
                </a>
            </div>
        </section>
        
        <script src="js/user_script.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php include('components/alert.php'); ?>
        <?php include('components/footer.php'); ?>

    </body>

</html>



