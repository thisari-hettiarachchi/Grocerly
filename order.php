<?php 
include 'components/connect.php';
session_start();

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

<section class="order_banner">
    <div class="header">
        <p class="sub">🛒 My Orders</p>
        <p class="subtitle">Track, manage, and view all your recent purchases in one place.</p>
    </div>
</section>

<div class="order-container">
    <?php
    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
    $select_order->execute([$user_id]);

    if ($select_order->rowCount() > 0) {
        while ($fetch_orders = $select_order->fetch(PDO::FETCH_ASSOC)) {
            $product_id = $fetch_orders['product_id'];
            $select_products = $conn->prepare("SELECT * FROM `product` WHERE product_id = ?");
            $select_products->execute([$product_id]);

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="order-box">
        <a href="view_order.php?get_id=<?= $fetch_orders['order_id']; ?>">
            <div class="icon">
                <div class="icon-box">
                    <img src="uploaded_files/<?= $fetch_products['image'] ?>" class="img1" alt="Product Image">
                   
                </div>
            </div>
        </a>

        <div class="content">
            <p class="date"><i class="bx bxs-calendar"></i> <span><?= htmlspecialchars($fetch_orders['dates']); ?></span></p>
            <div class="row">
                <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                <?php
                    $total_price = $fetch_orders['price'] * $fetch_orders['qty'];
                    $status = $fetch_orders['status'];
                    $color = ($status === 'delivered') ? 'orange' : (($status === 'canceled') ? 'red' : 'orange');
                ?>
                <p><strong>Total: Rs.<?= number_format($total_price, 2); ?>/-</strong></p>
                <p class="status" style="color: <?= $color ?>;"><?= htmlspecialchars($status); ?></p>
            </div>
        </div>

    </div>
    <?php
                }
            }
        }
    } else {
        echo '
        <div class="empty">
            <p>No orders placed yet!</p>
        </div>
        ';
    }
    ?>
</div>

<!-- Optional video section -->
<video width="600" controls>
    <source src="./videos/order_process.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="js/user_script.js"></script>
<?php include('components/alert.php'); ?>

</body>
</html>
