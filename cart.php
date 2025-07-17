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

    if (isset($_POST['update_cart'])) {
        $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);

        $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE cart_id = ?");
        $update_qty->execute([$qty, $cart_id]);

        $success_msg[] = 'Cart Quantity Updated Successfully';
    }


    // Delete cart item
    if (isset($_POST['delete_item'])) {
        $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);

        $verify_delete_item = $conn->prepare("SELECT * FROM `cart` WHERE cart_id = ?");
        $verify_delete_item->execute([$cart_id]);

        if ($verify_delete_item->rowCount() > 0) {
            $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE cart_id = ?");
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

        <title>Cart Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">

        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>
    
    <body>

        <?php include('components/user_header.php'); ?>
        


        <div class="cart">
            <div class="heading">
                <h1>My Cart</h1>
            </div>
            <div class="cart-container">
                <?php
                    $grand_total = 0;

                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->execute([$user_id]);

                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            $select_products = $conn->prepare("SELECT * FROM `product` WHERE product_id = ?");
                            $select_products->execute([$fetch_cart['product_id']]);

                            if ($select_products->rowCount() > 0) {
                                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                ?>
                <form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) { echo "disabled"; } ?>">
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
                        <div class="flex-btn">
                            <h3 class="price">Rs. <?= $fetch_products['price']; ?></h3>
                            <input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" class="box qty"></input>
                            <button type="submit" class="bx bxs-edit fa-edit box" name="update_cart"></button>
                        </div>
                        <div class="flex-btn">
                            <p class="sub-total">Sub Total : <span>Rs. <?= $sub_total = (int)$fetch_cart['qty'] * (float)$fetch_cart['price']; ?></span></p>
                            <button type="submit" name="delete_item" class="btn" onclick="return confirm('Remove from cart?');">Delete</button>
                        </div>
                        <input type="hidden" name="cart_id" value="<?= $fetch_cart['cart_id']; ?>">
                    </div>
                </form>

                <?php
                    $grand_total += $sub_total;
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

            <?php if ($grand_total != 0) { ?>
                <div class="cart-total">
                    <p>Total Amount Payable : <span> Rs. <?= $grand_total; ?>/-</span></p>
                    <div class="button">
                        <a href="checkout.php" class="btn">Checkout</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="js/user_sript.js"></script>
        
        <?php include('components/alert.php'); ?>
    
    </body>
    
</html>
