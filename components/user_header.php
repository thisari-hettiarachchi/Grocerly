<?php 
    include_once 'components/connect.php';

    session_start();

    $fetch_profile = null;

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; 

        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_profile->execute([$email]);

        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        }
    }
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; 
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['user_id']; 
        } else {
            $user_id = ''; 
        }
    } else {
        $user_id = ''; 
    }
?>

<header>
    <section class="flex">
        <a href="home.php" class="logo">
            <img src="images/Logo.png" width="130px">
        </a>

        <?php $page = basename($_SERVER['PHP_SELF']); ?>
        <nav class="navbar">
            <a href="home.php" class="<?= $page == 'home.php' ? 'active' : '' ?>">Home</a>
            <a href="about.php" class="<?= $page == 'about.php' ? 'active' : '' ?>">About Us</a>
            <a href="shop.php" class="<?= $page == 'shop.php' ? 'active' : '' ?>">Shop</a>
            <a href="order.php" class="<?= $page == 'order.php' ? 'active' : '' ?>">Order</a>
            <a href="contact_us.php" class="<?= $page == 'contact-us.php' ? 'active' : '' ?>">Contact Us</a>
        </nav>
        
        <form action="search_product.php" method="post" class="search-form">
            <input type="text" class="search-input" name="search-product" placeholder="Search product..." required maxlength="100">
            <button type="submit" class="search-btn bx bx-search-alt-2"></button>
        </form>

        <div class="icons">
            <?php
                $count_wishlist_item = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $count_wishlist_item->execute([$user_id]);
                $total_wishlist_items = $count_wishlist_item->rowCount();
            ?>
            <a href="wishlist.php"  class="wishlist-btn">
                <i class="bx bx-heart"></i><sup><?= $total_wishlist_items; ?></sup>
            </a>

            <?php
                $count_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $count_cart_item->execute([$user_id]);
                $total_cart_items = $count_cart_item->rowCount();
            ?>
            <a href="cart.php"  class="cart-btn">
                <i class="bx bx-cart"></i><sup><?= $total_cart_items; ?></sup>
            </a>
            <div class="profile-btn bx bx-user" id="user-btn" style="cursor: pointer;"></div>
        </div>

        <div class="profile-detail" id="profile-detail">
            <?php 
            if (isset($_SESSION['email']) && $fetch_profile) { 
                $profile_image = !empty($fetch_profile['image']) ? 'uploaded_files/' . htmlspecialchars($fetch_profile['image']) : 'images/default.jpg';
            ?>
                <img src="<?= $profile_image; ?>" class="logo-img" width="100">
                <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
                <div class="flex-btn">
                    <a href="profile.php?email=<?= urlencode($_SESSION['email']); ?>" class="btn">View Profile</a>
                    <a href="components/user_logout.php" onclick="return confirm('Logout from this website');" class="btn">Logout</a>
                </div>
            <?php } else { ?>
                <h3 style="margin-bottom: 1rem;">Please Login or Register</h3>
                <div class="flex-btn">
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn">Register</a>
                </div>
            <?php } ?>

        </div>


    </section>
</header>