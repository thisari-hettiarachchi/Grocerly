<?php
include '../components/connect.php';

session_start();

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', '1');

// Check if seller/admin logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Get seller info (if needed for future use)
    $stmt = $conn->prepare("SELECT seller_id, name FROM sellers WHERE email = ?");
    $stmt->execute([$email]);
    $seller = $stmt->fetch(PDO::FETCH_ASSOC);

    // If not found seller, redirect to login
    if (!$seller) {
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

// Update order payment status and order status
if (isset($_POST['update_order'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);
    $update_payment = filter_var($_POST['update_payment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $update_status = filter_var($_POST['update_status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $update_order = $conn->prepare("UPDATE `orders` SET payment_status = ?, status = ? WHERE order_id = ?");
    $update_order->execute([$update_payment, $update_status, $order_id]);

    // Redirect to refresh the page and prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete order
if (isset($_POST['delete'])) {
    $delete_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);

    $verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE order_id = ?");
        $delete_order->execute([$delete_id]);
        $success_message[] = 'Order deleted';

        // Redirect after deletion as well
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $warning_msg[] = 'Order already deleted';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Orders - Admin</title>

    <link rel="stylesheet" type="text/css" href="../css/admin_style.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin_order.css" />
    <link rel="shortcut icon" href="../images/fav.png" type="image/svg+xml" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
    <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="order-container">
            <div class="heading">
                <h1>All Orders</h1>
            </div>
            <div class="Box_container">
                <?php
                // Select all orders with seller name joined
                $select_orders = $conn->prepare("
                    SELECT o.*, s.name AS seller_name
                    FROM orders o
                    LEFT JOIN sellers s ON o.seller_id = s.seller_id
                    ORDER BY o.dates DESC
                ");
                $select_orders->execute();

                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {

                        // Determine status color
                        $status_color = 'red';
                        switch ($fetch_orders['status']) {
                            case 'pending':
                                $status_color = 'orange';
                                break;
                            case 'in progress':
                                $status_color = 'limegreen';
                                break;
                            case 'delivered':
                                $status_color = 'green';
                                break;
                            case 'canceled':
                                $status_color = 'red';
                                break;
                        }

                        // Determine payment status color
                        $payment_color = ($fetch_orders['payment_status'] == 'confirmed') ? 'green' : 'orange';
                        ?>
                        <div class="Box">
                            <div class="status" style="color: <?= $status_color; ?>">
                                Status: <?= htmlspecialchars($fetch_orders['status']); ?>
                            </div>
                            <div class="payment_status" style="color: <?= $payment_color; ?>">
                                Payment: <?= htmlspecialchars($fetch_orders['payment_status']); ?>
                            </div>
                            <div class="detail">
                                <p>Seller: <span><?= htmlspecialchars($fetch_orders['seller_name'] ?? 'N/A'); ?></span></p>
                                <p>User name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
                                <p>User ID: <span><?= htmlspecialchars($fetch_orders['user_id']); ?></span></p>
                                <p>Placed On: <span><?= htmlspecialchars($fetch_orders['dates']); ?></span></p>
                                <p>Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
                                <p>Total Price: <span><?= htmlspecialchars($fetch_orders['price']); ?></span></p>
                                <p>Payment Method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
                                <p>Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
                            </div>
                            <form action="" method="post">
                                <input type="hidden" name="order_id" value="<?= (int)$fetch_orders['order_id']; ?>" />

                                <label for="update_status_<?= $fetch_orders['order_id']; ?>">Order Status:</label>
                                <select name="update_status" id="update_status_<?= $fetch_orders['order_id']; ?>" class="Box" style="width: 90%; margin-bottom: 10px;">
                                    <option value="pending" <?= ($fetch_orders['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="in progress" <?= ($fetch_orders['status'] == 'in progress') ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="delivered" <?= ($fetch_orders['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="canceled" <?= ($fetch_orders['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                                </select>

                                <label for="update_payment_<?= $fetch_orders['order_id']; ?>">Payment Status:</label>
                                <select name="update_payment" id="update_payment_<?= $fetch_orders['order_id']; ?>" class="Box" style="width: 90%; margin-bottom: 10px;">
                                    <option value="pending" <?= ($fetch_orders['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?= ($fetch_orders['payment_status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                </select>

                                <div class="Flex-btn">
                                    <button type="submit" name="update_order" class="Btn">Update Order</button>
                                    <button type="submit" name="delete" onclick="return confirm('Want to delete this order?');" class="Btn">Delete Order</button>
                                </div>
                            </form>
                        </div>
                        <?php
                    }
                } else {
                    echo '
                    <div class="empty">
                        <p>No orders have been placed yet!</p>
                    </div>
                    ';
                }
                ?>
            </div>
        </section>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/admin.js"></script>
    <?php include('../components/alert.php'); ?>
</body>
</html>
