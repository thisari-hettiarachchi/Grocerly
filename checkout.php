<?php 
include 'components/connect.php';

session_start();
if (!isset($_SESSION['email'])) {
    header('location:login.php');
    exit;
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['user_id'] ?? '';

function generate_unique_id() {
    return uniqid('ord_');
}

if(isset($_POST['place_order']) && $user_id != ''){
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = htmlspecialchars(trim($_POST['number']));

    $address = htmlspecialchars($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ', ' . $_POST['pin']);
    $address_type = htmlspecialchars($_POST['address_type']);
    $method = htmlspecialchars($_POST['method']);

    $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $verify_cart->execute([$user_id]);

    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
        $get_product = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
        $get_product->execute([$get_id]);

        if ($get_product->rowCount() > 0) {
            $fetch_p = $get_product->fetch(PDO::FETCH_ASSOC);
            $seller_id = $fetch_p['seller_id'];

            $insert_order = $conn->prepare("INSERT INTO orders(order_id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty, dates, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', 'unpaid')");

            $insert_order->execute([
                generate_unique_id(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method,
                $fetch_p['product_id'], $fetch_p['price'], 1
            ]);

            header('location:order.php');
            exit;
        }
    } elseif ($verify_cart->rowCount() > 0) {
        while ($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
            $s_products = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
            $s_products->execute([$f_cart['product_id']]);
            $f_product = $s_products->fetch(PDO::FETCH_ASSOC);

            $seller_id = $f_product['seller_id'];

            $insert_order = $conn->prepare("INSERT INTO orders(order_id, user_id, seller_id, name, number, email, address, address_type, method, product_id, price, qty, dates, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', 'unpaid')");

            $insert_order->execute([
                generate_unique_id(), $user_id, $seller_id, $name, $number, $email, $address, $address_type, $method,
                $f_cart['product_id'], $f_cart['price'], $f_cart['qty']
            ]);
        }

        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $delete_cart_id->execute([$user_id]);

        header('location:order.php');
        exit;
    } else {
        $warning_msg[] = 'Your cart is empty!';
    }
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
            Please review your billing details carefully before placing the order.
        </p>
        <span><a href="home.php">home</a><i class="bx bxs-right-arrow-alt"></i><a href="checkout.php">checkout</a></span>
    </div>
</div>

<div class="checkout">
    <div class="heading">
        <h1>checkout summary</h1>
    </div>
</div>

<div class="row">
    <div class="form">
        <form action="" method="post" class="register">
            <input type="hidden" name="p_id" value="<?= $get_id; ?>">
            <h3>billing details</h3>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your name <span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="input-field">
                        <p>Your number <span>*</span></p>
                        <input type="text" name="number" placeholder="Enter your number" required>
                    </div>
                    <div class="input-field">
                        <p>Your email <span>*</span></p>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-field">
                        <p>Payment method <span>*</span></p>
                        <select name="method" required>
                            <option value="cash on delivery">Cash on Delivery</option>
                            <option value="credit or debit card">Credit or Debit Card</option>
                            <option value="net banking">Net Banking</option>
                            <option value="upi or rupay">UPI or Rupay</option>
                            <option value="paytm">Paytm</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <p>Address type <span>*</span></p>
                        <select name="address_type" required>
                            <option value="home">Home</option>
                            <option value="office">Office</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>Address Line 1 <span>*</span></p>
                        <input type="text" name="flat" placeholder="e.g. Flat & Building" required>
                    </div>
                    <div class="input-field">
                        <p>Address Line 2 <span>*</span></p>
                        <input type="text" name="street" placeholder="e.g. Street Name" required>
                    </div>
                    <div class="input-field">
                        <p>City <span>*</span></p>
                        <input type="text" name="city" placeholder="Enter City" required>
                    </div>
                    <div class="input-field">
                        <p>Country <span>*</span></p>
                        <input type="text" name="country" placeholder="Enter Country" required>
                    </div>
                    <div class="input-field">
                        <p>Pincode <span>*</span></p>
                        <input type="text" name="pin" placeholder="e.g. 110099" required>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" name="place_order" class="btn">Place Order</button>
            </div>
        </form>
    </div>

    <div class="summery">
        <div class="box-container">
            <?php
            $grand_total = 0;

            if (isset($_GET['get_id'])) {
                $select_get = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
                $select_get->execute([$_GET['get_id']]);

                while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                    $sub_total = $fetch_get['price'];
                    $grand_total += $sub_total;
                    ?>
                    <div class="flex">
                        <img src="uploaded_files/<?= $fetch_get['image'] ?>" class="img1" alt="Product Image">
                        <div>
                            <h3 class="name"><?= $fetch_get['name'] ?></h3>
                            <p class="price"><?= $fetch_get['price'] ?>/-</p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                $select_cart->execute([$user_id]);

                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $select_product = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
                        $select_product->execute([$fetch_cart['product_id']]);
                        $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

                        $sub_total = $fetch_product['price'] * $fetch_cart['qty'];
                        $grand_total += $sub_total;
                        ?>
                        <div class="flex">
                            <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="img1" alt="Product Image">
                            <div>
                                <h3 class="name"><?= $fetch_product['name']; ?></h3>
                                <p class="price"><?= $fetch_product['price']; ?> x <?= $fetch_cart['qty']; ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="empty"><p>No products in your cart!</p></div>';
                }
            }
            ?>
        </div>

        <div class="grant-total">
            <span>Total amount payable:</span> $<?= number_format($grand_total, 2); ?> /-
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="js/user_script.js"></script>
<?php include('components/alert.php'); ?>
</body>
</html>
