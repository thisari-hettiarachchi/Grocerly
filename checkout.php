<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('location:login.php');
    exit;
}

if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $placed_on = date('Y-m-d');

    $cart_items = $conn->prepare("SELECT cart.*, products.price FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
    $cart_items->execute([$user_id]);

    if ($cart_items->rowCount() > 0) {
        while ($item = $cart_items->fetch(PDO::FETCH_ASSOC)) {
            $product_id = $item['product_id'];
            $qty = $item['qty'];
            $price = $item['price'];
            $status = 'pending';

            $insert_order = $conn->prepare("INSERT INTO orders (user_id, product_id, name, email, number, address, qty, price, status, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_order->execute([$user_id, $product_id, $name, $email, $number, $address, $qty, $price, $status, $placed_on]);
        }

        // Clear cart
        $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        $success_msg = "Order placed successfully!";
    } else {
        $warning_msg = "Your cart is empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Checkout</title>
    <link rel="stylesheet" href="css/user_styles.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="checkout">
    <div class="heading">
        <h1>Checkout</h1>
        <p>Review your items and fill in your billing information.</p>
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="alert success"><?= htmlspecialchars($success_msg); ?></div>
    <?php elseif (isset($warning_msg)): ?>
        <div class="alert warning"><?= htmlspecialchars($warning_msg); ?></div>
    <?php endif; ?>

    <div class="checkout-container">
        <form action="" method="post" class="checkout-form">
            <h3>Billing Details</h3>
            <input type="text" name="name" required placeholder="Full Name" />
            <input type="email" name="email" required placeholder="Email" />
            <input type="tel" name="number" required placeholder="Phone Number" />
            <textarea name="address" required placeholder="Shipping Address" rows="4"></textarea>
            <input type="submit" name="place_order" value="Place Order" class="btn" />
        </form>

        <div class="checkout-summary">
            <h3>Your Order</h3>
            <?php
            $grand_total = 0;
            $cart_query = $conn->prepare("SELECT cart.*, products.name, products.price FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
            $cart_query->execute([$user_id]);

            if ($cart_query->rowCount() > 0) {
                while ($item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
                    $total = $item['price'] * $item['qty'];
                    $grand_total += $total;
                    echo "<p>" . htmlspecialchars($item['name']) . " x " . (int)$item['qty'] . " - $" . number_format($total, 2) . "</p>";
                }
            } else {
                echo "<p>Your cart is empty.</p>";
            }
            ?>
            <hr />
            <p><strong>Total: $<?= number_format($grand_total, 2); ?>/-</strong></p>
        </div>
    </div>
</section>

<script src="js/user_script.js"></script>
<?php include 'components/footer.php'; ?>
</body>
</html>
