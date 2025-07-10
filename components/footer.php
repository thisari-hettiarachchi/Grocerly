<?php
    include 'components/connect.php';

    $user_id = null;
    $email = '';

    if (isset($_COOKIE['email'])) {
        $email = $_COOKIE['email'];

        $query = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $query->execute([$email]);

        if ($query->rowCount() > 0) {
            $fetch_user = $query->fetch(PDO::FETCH_ASSOC);
            $user_id = $fetch_user['user_id'];
        }
    }

    
    // Handle subscription
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = $_POST['email'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $check_subscription = $conn->prepare("SELECT * FROM newsletter WHERE email = ?");
            $check_subscription->execute([$email]);

            if ($check_subscription->rowCount() > 0) {
                $update_subscription = $conn->prepare("UPDATE newsletter SET status = 'subscribed' WHERE email = ?");
                $update_subscription->execute([$email]);

                $success_msg[] = "Thank you for subscribing!";
            } else {
                $insert_subscription = $conn->prepare("INSERT INTO newsletter (user_id, email, status) VALUES (?, ?, 'subscribed')");
                $insert_subscription->execute([$user_id, $email]);

                $success_msg[] = "Thank you for subscribing!";
            }
        } else {
            $warning_msg[] = "Invalid email address.";
        }
    }

    if (isset($_POST['unsubscribe'])) {
        $update_newsletter = $conn->prepare("UPDATE `newsletter` SET status = 'unsubscribed' WHERE email = ?");
        $update_newsletter->execute([$email]);

        $success_msg[] = "You have been unsubscribed.";
    }
?>

<div class="newsletter">
    <div class="content">
        <span>Get Latest Grocerlt Update</span>
        <h1>Subscribe Our Newsletter</h1>
        <p>Subscribe to receive the latest updates, exclusive offers, and exciting news delivered straight to your inbox.<br>
        Be the first to know about new products, special promotions, and much more. Don't miss out. <br>
        Subscribe Today and Join Our Community</p>

        <?php 
        $query = $conn->prepare("SELECT * FROM newsletter WHERE email = ? AND status = 'subscribed'");
        $query->execute([$email]);

        if ($query->rowCount() > 0) { 
            ?>
            <p class="success"><?php echo implode('<br>', $success_msg); ?></p>
            <a href="#" class="btn" style="line-height: 3"><i class='bx bxs-bell-ring' ></i> Already Subscribed</a>
            <form method="POST" action="">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit" name="unsubscribe" class="btn">Unsubscribe</button>
            </form>
        <?php 
        } else { 
            ?>
            <form method="POST" action="">
                <div class="input-field">
                    <input type="email" name="email" placeholder="Enter your E-mail" required>
                    <button class="btn" type="submit"><i class='bx bxs-bell bx-tada' ></i> Subscribe</button>
                </div>
            </form>
        <?php } ?>

        <p>No ads, No trials, No commitment</p>
    </div>
</div>


