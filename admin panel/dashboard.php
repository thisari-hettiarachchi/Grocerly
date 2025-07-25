<?php
    include '../components/connect.php';

    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("SELECT seller_id, name FROM sellers WHERE email = ?");
        $stmt->execute([$email]);
        $seller = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($seller) {
            $seller_id = $seller['seller_id'];
            $seller_name = $seller['name'];
        } else {
            $seller_id = null;
            $seller_name = null;
        }
    } else {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title> Admin Dashboard </title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        
        <link rel="shortcut icon" href="../images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
        
    </head>

    <body>
        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
        </div>

        <div class="main-container">
            <?php include '../components/admin_header.php'; ?>
            <section class="dashboard">
                <div class="heading">
                    <h1>Dashboard</h1>
                </div>
                <div class="box_container">
                    <div class="box">
                    <h3>Welcome!</h3>
                        <p><?= $seller_name; ?></p>
                        <a href="update_profile.php" class="btn">Update Profile</a>
                    </div>
                    <div class="box">
                        <?php 
                            $select_message = $conn->prepare("SELECT * FROM `message`");
                            $select_message->execute(); 
                            $number_of_msg = $select_message->rowCount();
                        ?>
                        <h3><?= $number_of_msg; ?></h3>
                        <p>Unread message</p>
                        <a href="admin_message.php" class="btn">See Message</a>
                    </div>
                </div>
                <div class="box_container">
                    <div class="box">
                        <?php 
                            $select_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ?");
                            $select_products->execute([$seller_id]); 
                            $number_of_products = $select_products->rowCount();
                        ?>
                        <h3><?= $number_of_products; ?></h3>
                        <p>Products Added</p>
                        <a href="add_product.php" class="btn">Add Product</a>
                    </div>
                    <div class="box">
                        <?php 
                            $select_active_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ? AND status = ?");
                            $select_active_products->execute([$seller_id, 'active']); 
                            $number_of_active_products = $select_active_products->rowCount();
                        ?>
                        <h3><?= $number_of_active_products; ?></h3>
                        <p>Total Active Products</p>
                        <a href="view_product.php?status=active" class="btn">Active Products</a>
                    </div>
                </div>
                <div class="box_container">
                    <div class="box">
                        <?php 
                            $select_deactive_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ? AND status = ?");
                            $select_deactive_products->execute([$seller_id, 'deactive']); 
                            $number_of_deactive_products = $select_deactive_products->rowCount();
                        ?>
                        <h3><?= $number_of_deactive_products; ?></h3>
                        <p>Total Deactive Products</p>
                        <a href="view_product.php?status=deactive" class="btn">Active Products</a>
                    </div>
                    <div class="box">
                        <?php 
                            $select_users = $conn->prepare("SELECT * FROM `users`");
                            $select_users->execute(); 
                            $number_of_users = $select_users->rowCount();
                        ?>
                        <h3><?= $number_of_users; ?></h3>
                        <p>User Account</p>
                        <a href="user_account.php" class="btn">See Users</a>
                    </div>
                </div>
                <div class="box_container">
                    <div class="box">
                        <?php 
                            $select_sellers = $conn->prepare("SELECT * FROM `sellers`");
                            $select_sellers->execute(); 
                            $number_of_sellers = $select_sellers->rowCount();
                        ?>
                        <h3><?= $number_of_sellers; ?></h3>
                        <p>Seller Account</p>
                        <a href="seller_account.php" class="btn">See Sellers</a>
                    </div>
                    <div class="box">
                        <?php 
                            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                            $select_orders->execute([$seller_id]); 
                            $number_of_orders = $select_orders->rowCount();
                        ?>
                        <h3><?= $number_of_orders; ?></h3>
                        <p>Total Orders Placed</p>
                        <a href="admin_order.php" class="btn">Total Orders</a>
                    </div>
                </div>
                <div class="box_container">
                    <div class="box">
                        <?php 
                            $select_confirm_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status = ?");
                            $select_confirm_orders->execute([$seller_id, 'in progress']); 
                            $number_of_confirm_orders = $select_confirm_orders->rowCount();
                        ?>
                        <h3><?= $number_of_confirm_orders; ?></h3>
                        <p>Total Confirm Orders</p>
                        <a href="admin_order.php" class="btn">Confirm Orders</a>
                    </div>
                    <div class="box">
                        <?php 
                            $select_canceled_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ? AND status = ?");
                            $select_canceled_orders->execute([$seller_id, 'canceled']); 
                            $number_of_canceled_orders = $select_canceled_orders->rowCount();
                        ?>
                        <h3><?= $number_of_canceled_orders; ?></h3>
                        <p>Total Canceled Orders</p>
                        <a href="admin_order.php" class="btn">Canceled Orders</a>
                    </div>
                </div>
            </section>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="../js/admin.js"></script>

        <?php include('../components/alert.php'); ?>
        
    </body>
    
</html>
