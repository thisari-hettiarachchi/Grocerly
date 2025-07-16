<?php 
include 'components/connect.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">
    <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    
    <?php include('components/user_header.php'); ?>

    <section class="order_banner">
        <div class="header">
            <p class="sub">🛒 My Orders</p>
            <p class="subtitle">Track, manage, and view all your recent purchases in one place.</p>
        </div>
    </section>

    <div class="order-container">
        <?php
        $select_order = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
        $select_order->execute([$user_id]);

        if ($select_order->rowCount() > 0) {
            while ($fetch_orders = $select_order->fetch(PDO::FETCH_ASSOC)) {
                $product_id = $fetch_orders['product_id'];
                $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $select_products->execute([$product_id]);

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <a href="view_order.php?get_id=<?= $fetch_orders['id']; ?>">
                <div class="icon">
                    <div class="icon-box">
                        <img src="uploaded_files/<?= $fetch_products['thumb_one']; ?>" class="img1">
                        <img src="uploaded_files/<?= $fetch_products['thumb_two']; ?>" class="img2">
                    </div>
                </div>
            </a>
        </div>
        <?php
                    }
                }
            }
        } else {
            echo "<p class='no-orders'>No orders found.</p>";
        }
        ?>
    </div>

    <video width="600" controls>
        <source src="your-video.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <script src="js/user_script.js"></script>
    <?php include('components/alert.php'); ?>
</body>
</html>
