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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subscribe'])) {
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


<div class="bottom-section">
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
                        <button class="btn" type="submit" name="subscribe"><i class='bx bxs-bell bx-tada' ></i> Subscribe</button>
                    </div>
                </form>
            <?php } ?>

            <p>No ads, No trials, No commitment</p>
        </div>
    </div>




    <footer>
        <div class="content">
            <div class="box">
                <h3>My Account</h3>
                <a href=""><i class="bx bx-chevron-right"></i>My Account</a>
                <a href=""><i class="bx bx-chevron-right"></i>Order History</a>
                <a href=""><i class="bx bx-chevron-right"></i>Wishlist</a>
                <a href=""><i class="bx bx-chevron-right"></i>Newsletter</a>
            </div>
            <div class="box">
                <h3>Information</h3>
                <a href=""><i class="bx bx-chevron-right"></i>About Us</a>
                <a href=""><i class="bx bx-chevron-right"></i>Deliver Information</a>
                <a href=""><i class="bx bx-chevron-right"></i>Privacy Policy</a>
                <a href=""><i class="bx bx-chevron-right"></i>Terms & Conditions</a>
            </div>
            <div class="box">
                <h3>Extras</h3>
                <a href=""><i class="bx bx-chevron-right"></i>Brands</a>
                <a href=""><i class="bx bx-chevron-right"></i>Gift Certification</a>
                <a href=""><i class="bx bx-chevron-right"></i>Affiliate</a>
                <a href=""><i class="bx bx-chevron-right"></i>Specials</a>
            </div>
            <div class="box">
                <h3>Contact Us</h3>
                <p><i class="bx bxs-phone"></i>0756984236</p>
                <p><i class="bx bxs-envelope"></i>grocerly@gmail.com</p>
                <p><i class="bx bxs-location-plus"></i>Colombo, Sri Lanka</p>
                <div class="icons">
                    <i class="bx bxl-facebook"></i>
                    <i class="bx bxl-twitter"></i>
                    <i class="bx bxl-instagram"></i>
                    <i class="bx bxl-linkedin"></i>
                </div>
            </div>
        </div>

        <div class="bottom">
            <p>&copy; 2025 Grocerly | All Rights Reserved</p>
            <a href="admin panel/login.php">Become A Seller</a>
        </div>
    </footer>
</div>