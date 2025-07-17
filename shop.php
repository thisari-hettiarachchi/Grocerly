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
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop Page</title>

    <link rel="stylesheet" type="text/css" href="css/user_styles.css">
    <link rel="stylesheet" type="text/css" href="css/shop.css">
    <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <?php include('components/user_header.php'); ?>

    <div class="shop">
        <div class="detail">
            <h1>Our Shop</h1>
            <p>Find everything you need - all in one place <br>
            Shop smart, eat fresh, and enjoy fast delivery.
            </p>
        </div>
    </div>

    <?php
    //  Fetch distinct categories from active products
    $categories_stmt = $conn->prepare("SELECT DISTINCT category FROM product WHERE status = 'active' ORDER BY category ASC");
    $categories_stmt->execute();
    $categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);

    if ($categories) {
        foreach ($categories as $category) {
            echo '<div class="shop-container">';
            echo '<div class="heading"><h1>' . htmlspecialchars($category) . '</h1></div>';
            echo '<div class="box-container">';

            // Fetch products of this category
            $products_stmt = $conn->prepare("SELECT * FROM product WHERE category = ? AND status = 'active' ORDER BY name ASC");
            $products_stmt->execute([$category]);

            if ($products_stmt->rowCount() > 0) {
                while ($product = $products_stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <form action="" method="post" class="box <?php if($product['stock'] == 0){echo 'disabled';} ?>">
                        <img src="uploaded_files/<?= htmlspecialchars($product['image']) ?>" class="image">
                        <?php  
                            if ($product['stock'] > 9) { ?>
                                <span class="stock" style="color: green;">In Stock</span>
                            <?php } elseif ($product['stock'] == 0) { ?>
                                <span class="stock" style="color: red;">Out Of Stock</span>
                            <?php } else { ?>
                                <span class="stock" style="color: red;">Only Few Left</span>
                            <?php } ?>

                        <div class="content">
                            <h3 class="name"><?= htmlspecialchars($product['name']) ?></h3>
                            <div class="button">
                                <div>
                                    <?php if ($product['stock'] > 0) { ?>
                                        <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                                        <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                                    <?php } ?>
                                    <a href="view_page.php?product_id=<?= $product['product_id'] ?>" class="bx bxs-show"></a>
                                </div>
                            </div>
                            <h3 class="price">Rs.<?= htmlspecialchars($product['price']) ?></h3>
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <div class="flex-btn">
                                <?php if ($product['stock'] > 0) { ?>
                                    <a href="checkout.php?get_id=<?= $product['product_id']?>" class="btn">Buy Now</a>
                                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty btn">
                                <?php } else { ?>
                                    <span class="btn disabled">Out of Stock</span>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                    <?php
                }
            } else {
                echo '<div class="empty"><p>No products added yet in this category!</p></div>';
            }

            echo '</div>'; 
            echo '</div>'; 
        }
    } else {
        echo '<div class="empty"><p>No product categories found!</p></div>';
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/user_script.js"></script>

    <?php include('components/footer.php'); ?>
    <?php include('components/alert.php'); ?>

    </body>
</html>
