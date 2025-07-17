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

        <title>Checkout Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">

        <link rel="stylesheet" type="text/css" href="css/checkout.css">
        
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>

    <body>
        <?php include('components/user_header.php'); ?>
        <div class="check">
            <div class="details">
                <h1>Checkout</h1>
                <p class="checkout-note">
                    Please review your billing details carefully before placing the order. Your order will be processed securely, and you'll receive a confirmation via email shortly. Thank you for shopping with us!
                </p>
                <span><a href="home.php">home</a><i class="bx bxs-right-arrow-alt"><a href="checkout.php">checkout</a></i></span>
            </div>
        </div>

        <div class="checkout">
            <div class="heading">
                <h1>checkout summery</h1>
                
            </div>
        </div>
        <div class="row">
            <div class="form">
                <form action="" method= "post" class="register">
                    <input type="hidden" name="p_id" value="<?= $get_id; ?>">
                    <h3>billing details</h3>
                    <div class="flex">
                        <div class="col">
                            <div class="input-field">
                                <p>your name <span>*</span></p>
                                <input type="text" name="name" placeholder="enter your name" maxlength="50" required = "box">
                            </div>
                            <div class="input-field">
                                <p>your number <span>*</span></p>
                                <input type="text" name="number" placeholder="enter your number" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>your email <span>*</span></p>
                                <input type="text" name="email" placeholder="enter your email" class="box" required>
                            </div>
                            <div class="input-field">
                                <p>payment status <span>*</span></p>
                                <select name="method" class="box">
                                    <option value="cash on delivery">cash on delivery</option>
                                    <option value="credit or debit card">credit or debit card</option>
                                    <option value="net banking">net banking</option>
                                    <option value="upi or rupay">upi or rupay</option>
                                    <option value="paytm">paytm</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p>address type <span>*</span></p>
                                <select name="method" class="box">
                                    <option value="home">home </option>
                                    <option value="office">office  </option>
                                    
                                </select>
                            </div>
                            <div class="col">
                                <div class="input-field">
                                    <p>address line 01 <span>*</span></p>
                                    <input type="text" name="flat" placeholder="e.g flat & building" maxlength="50" required = "box">
                                </div>
                                <div class="input-field">
                                    <p>address line 02 <span>*</span></p>
                                    <input type="text" name="street" placeholder="e.g street name" maxlength="50" required = "box">
                                </div>
                                <div class="input-field">
                                    <p>city name <span>*</span></p>
                                    <input type="text" name="city" placeholder=" enter city name" maxlength="50" required = "box">
                                </div>
                                <div class="input-field">
                                    <p>country name <span>*</span></p>
                                    <input type="text" name="country" placeholder="enter country name" maxlength="50" required = "box">
                                </div>
                                <div class="input-field">
                                    <p>pincode <span>*</span></p>
                                    <input type="text" name="pin" placeholder="e.g 110099" maxlength="6" required = "box">
                                </div>

                            </div>
                            <div class="button-container">
                                <button type="submit" name="place_order" class="btn">Place Order</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="summery">
                
                <div class="box-container">
                    <?php
                        $grand_total=0;
                        if(isset($_GET['get_id'])){
                            $select_get = $conn->prepare("SELECT * FROM `products` WHERE id= 
                            ?");
                            $select_get->execute([$_GET['get_id']]);

                            while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
                                $sub_total = $fetch_get['price'];
                                $grand_total+= $sub_total;
                    ?>
                    <div class="flex">
                        <img src="uploaded_files/<?= $fetch_get['mango'];?>" class="image
                        ">
                        <div>
                            <h3 class="name"><?= $fetch_get['name'];?></h3>
                            <p class="price"><?= $fetch_get['price'];?>/-</p>
                        </div>
                    </div>
                    <?php
                            }
                        }else{
                            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                            $select_cart->execute([$user_id]);

                            if($select_cart->rowCount() > 0){
                                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                                    $select_get = $conn->prepare("SELECT * FROM `products` WHERE product_id = ?");
                                    $select_get->execute([$fetch_cart[`product_id`]]);
                                    $fetch_products = $select_get->fetch(PDO::FETCH_ASSOC);

                                $sub_total = ($fetch_cart['qty'] * $fetch_products['price']);
                                $grand_total += $sub_total;
                        ?>
                        <div class="flex">
                            <img src="uploaded_files/<?= $fetch_products['mango'] ;?>" class="image
                            ">
                            <div>
                                <h3 class="name"><?= $fetch_products['name'];?></h3>
                                <p class="price"><?= $fetch_products['price'];?> x  <?= $fetch_cart['qty'];?></p>
                                </p>
                            </div>
                        </div>
                        <?php

                            }
                            
                        }else{
                                echo'
                                    <div class="empty">
                                        <p>no products added yet!</p>
                                    </div>
            
                                ';
                            }
                        }
          
                    ?>
                </div>
                <div class="grant-total">
                    <span>total amount payable:</span>$<?$grand_total;?>/-
                </div>
            </div>
        </div>
</div>

            
        
        <script src="js/user_script.js"></script>
        <?php include('components/alert.php'); ?>
        

    </body>

</html>



