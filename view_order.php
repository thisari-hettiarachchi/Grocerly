<?php 
    include 'components/connect.php';
session_start();

// Use session to verify login (same as order.php)
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['user_id'];
    } else {
        header('location:login.php');
        exit;
    }
} else {
    header('location:login.php');
    exit;
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    header('location:order.php');
    exit;
}

if (isset($_POST['canceled'])) {
    $update_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE order_id = ?");
    $update_order->execute(['canceled', $get_id]);
    header('location:order.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Order Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">
        <link rel="stylesheet" type="text/css" href="css/order.css">

        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">


    </head>

<body>
    
    <?php include('components/user_header.php'); ?>

    <div class="order_banner">
        <div class="header">
            <h1> Order Details <h1>
            <p class="subtitle">Track, manage, and view all your recent purchases in one place.</p>
            <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>order details</span>        
        </div>
    </div>

    <div class="line2"></div>
    <div class="heading">
    </div>
    <div class="view_order">
        
        <div class="box-container">
        <?php 
            $grand_total= 0;

            $select_order = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ? LIMIT 1");
            $select_order->execute([$get_id]);

            if ($select_order->rowCount() > 0){
                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                    $select_product = $conn->prepare("SELECT * FROM `product` WHERE product_id = ?");
                    $select_product->execute([$fetch_order['product_id']]);

                    if($select_product->rowCount() > 0){
                        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                            $sub_total = ($fetch_order['price']*$fetch_order['qty']);
                            $grand_total = ($fetch_order['price']*$fetch_order['qty']);
     
        ?>
        <div class="box">
            <div class="col">
                <div class="product_slider">
                    <img src="uploaded_files/<?= $fetch_product['image'] ?>" class="img1" alt="Product Image">
                </div>
                <p class="date">
                <i class="bx bxs-calender"></i><span><?= $fetch_order['dates']; ?></span></p>
                
                <div class="details">
                    <p class="price">$<?= $fetch_product['price']; ?> x <?= $fetch_order['qty'];?></p>
                    <p class="name"><?= $fetch_product['name'];?></p>
                    <p class="grad_total">total amount payable : <span>$<?= $grand_total?>/-</span></p>

                </div>
            </div>
        </div>
        <div class="col">
            <p class="title">billing address</p>
            <p class="user"><i class="bx bxs-user-rectangle"></i><?= $fetch_order['name']; ?></p>
            <p class="user"><i class="bx bxs-phone-outgoing"></i><?= $fetch_order['number']; ?></p>
            <p class="user"><i class="bx bxs-envelope"></i><?= $fetch_order['email']; ?></p>
            <p class="user"><i class="bx bxs-map-alt"></i><?= $fetch_order['address']; ?></p>
            <p class="title">status</p>
            <p class="status" style="color:<?php if($fetch_order['status']=='delivered'){echo "green";}elseif($fetch_order['status']=='canceled'){echo "red";}else{echo "orange";}?>"><?= $fetch_order['status']; ?></p>
            
            <?php if($fetch_order['status'] == 'canceled'): ?>
                <a href="checkout.php?get_id=<?= $fetch_product['product_id'] ?>" class="btn">order again</a>
            <?php else: ?>
                <form action="" method="post">
                    <button type="submit" name="canceled" class="btn" onclick="return confirm('Do you want to canceled this order?');">canceled</button>
                </form>
            <?php endif;?>
                    
        </div>
        <?php
                        

                        }
                    }
                }
            }else {
            echo '
                <div class="empty">
                    <p>no order take placed yet! </p>
                </div>
                ';
        }
        ?>
    </div>

</div>





    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="js/user_script.js"></script>
    <?php include('components/alert.php'); ?>
    <?php include('components/footer.php'); ?>

    </body>
</html>
