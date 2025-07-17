<?php 
    include_once '../components/connect.php';

    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    $fetch_profile = null;

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; 

        $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE email = ?");
        $select_profile->execute([$email]);

        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        }
    }
?>

<header>
    <div class="logo">
        <img src="../images/logo.png" >
    </div>
    <div class="right">
        <div class="bx bxs-user" id="user-btn" style="cursor: pointer;"></div>
    </div>

    <div class="profile-detail">
        <?php 
            if ($fetch_profile) {
        ?>
            <img src="<?= !empty($fetch_profile['image']) ? '../uploaded_files/' . htmlspecialchars($fetch_profile['image']) : '../images/default.jpg'; ?>" class="logo-img" width="100">
            <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
                <a href="profile.php" class="btn">Profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="btn">Logout</a>
            </div>
        <?php } else { ?>
            <p>Profile not found.</p>
        <?php } ?>
    </div>

</header>


<div class="sidebar-container">
    <div class="sidebar">
        <?php if ($fetch_profile): ?>
            <div class="profile">
                <img src="<?= !empty($fetch_profile['image']) ? '../uploaded_files/' . htmlspecialchars($fetch_profile['image']) : '../images/default.jpg'; ?>" class="logo-img" width="100">
                <p><?= $fetch_profile['name']; ?></p>
            </div>
        <?php else: ?>
            <p>Profile not found.</p>
        <?php endif; ?>
        <h5>Menu</h5>
        <div class="navbar">
            <ul>
                <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>Dashboard</a></li>
                <li><a href="add_product.php"><i class="bx bxs-shopping-bags"></i>Add Products</a></li>
                <li><a href="view_product.php"><i class="bx bxs-food-menu"></i>View Products</a></li>
                <li><a href="user_account.php"><i class="bx bxs-user-detail"></i>Accounts</a></li>
                <li><a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');"><i class="bx bx-log-out"></i>Logout</a></li>
            </ul>
        </div>
        <h5>Find Us</h5>
        <div class="social-links">
            <i class="bx bxl-facebook"></i>
            <i class="bx bxl-instagram-alt"></i>
            <i class="bx bxl-linkedin"></i>
            <i class="bx bxl-twitter"></i>
        </div>
    </div>
</div>
