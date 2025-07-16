<?php 
    include 'components/connect.php';

    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    $user_id = null; 

    if (isset($_COOKIE['email'])) {
        $email = $_COOKIE['email'];

        $query = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $query->execute([$email]);

        if ($query->rowCount() > 0) {
            $fetch_user = $query->fetch(PDO::FETCH_ASSOC);
            $user_id = $fetch_user['user_id'];
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


    // Delete wishlist item
    if (isset($_POST['delete_item'])) {
        $cart_id = filter_var($_POST['wishlist_id'], FILTER_SANITIZE_NUMBER_INT);

        $verify_delete_item = $conn->prepare("SELECT * FROM `wishlist` WHERE id = ?");
        $verify_delete_item->execute([$cart_id]);

        if ($verify_delete_item->rowCount() > 0) {
            $delete_cart_id = $conn->prepare("DELETE FROM `wishlist` WHERE id  = ?");
            $delete_cart_id->execute([$cart_id]);

            $success_msg[] = 'Item Removed Successfully';
        } else {
            $warning_msg[] = 'Item Already Removed or Not Found';
        }
    }

?>


<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Wishlist Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">

        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>
    
    <body>

        <?php include('components/user_header.php'); ?>


        <div class="wishlist">
            <div class="heading">
                <h1>My Wishlist</h1>
            </div>
            <div class="wishlist-container">
                <?php
                    $grand_total = 0;

                    $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                    $select_wishlist->execute([$user_id]);

                    if ($select_wishlist->rowCount() > 0) {
                        while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                            $select_products = $conn->prepare("SELECT * FROM `product` WHERE product_id = ?");
                            $select_products->execute([$fetch_wishlist['product_id']]);

                            if ($select_products->rowCount() > 0) {
                                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                ?>
                <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0){echo"disabled";}?>">
                    <img src="uploaded_files/<?= $fetch_products['image']; ?>" class="image">
                    <input type="hidden" name="product_id" value="<?= $fetch_products['product_id']; ?>">
                    <input type="hidden" name="qty" value="1">
                    <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">

                    <?php  
                        if ($fetch_products['stock'] > 9) { ?>
                            <span class="stock" style="color: green;">In Stock</span>
                        <?php } elseif ($fetch_products['stock'] == 0) { ?>
                            <span class="stock" style="color: red;">Out Of Stock</span>
                        <?php } else { ?>
                            <span class="stock" style="color: red;">Only Few Left</span>
                        <?php } ?>

                    <div class="content">
                      <div class="name-price">
                          <h3 class="name"><?= $fetch_products['name']; ?></h3>
                          <h3 class="price">Rs. <?= $fetch_products['price']; ?></h3>
                      </div>
                      <div class="flex-btn">
                          <button type="submit" class="bx bx-cart" name="add_to_cart"></button>
                          <a href="view_page.php?product_id=<?= $fetch_products['product_id'] ?>" class="bx bxs-show"></a>
                          <button type="submit" class="remove" name="delete_item" onclick="return confirm('Remove from wishlist?');">Remove</button>
                      </div>
                  </div>
                </form>

                <?php

                            }
                        }
                    } else {
                        echo '
                        <div class="empty">
                            <p>No products added yet!</p>
                        </div>';
                    }
                ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="js/user_sript.js"></script>
        
        <?php include('components/alert.php'); ?>
    
    </body>
    
</html>
