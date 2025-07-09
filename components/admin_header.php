<div class="admin-container">
  <!-- HEADER -->
  <header class="admin-header">
    <div class="admin-logo">
      <img src="../images/logo.png" alt="Grocerly Logo">
    </div>

    <div class="admin-header-right">
      <i class='bx bxs-user' id="user-btn"></i>
      <i class='bx bx-menu' id="menu-btn"></i> <!-- Toggle sidebar -->
    </div>

    <!-- Profile Dropdown --> 
    <div class="profile-detail" id="profile-detail">
      <?php 
        if (isset($_SESSION['email']) && $fetch_profile) { 
          $profile_image = !empty($fetch_profile['image']) ? '../uploaded_files/' . htmlspecialchars($fetch_profile['image']) : 'images/default.jpg';
      ?>
      <div class="profile">
        <img src="<?= $profile_image; ?>" class="logo-img" width="100">
        <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
      </div>
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
  </header>

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <?php 
      if (isset($_SESSION['email']) && $fetch_profile) { 
        $profile_image = !empty($fetch_profile['image']) ? '../uploaded_files/' . htmlspecialchars($fetch_profile['image']) : 'images/default.jpg';
    ?>
      <div class="profile">
        <img src="<?= $profile_image; ?>" class="logo-img" alt="Profile Image">
        <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
      </div>
    <?php } ?>

    <h5>Menu</h5>
    <div class="navbar">
      <ul>
        <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>Dashboard</a></li>
        <li><a href="add_product.php"><i class="bx bxs-shopping-bags"></i>Add Product</a></li>
        <li><a href="view_product.php"><i class="bx bxs-food-menu"></i>View Product</a></li>
        <li><a href="user_account.php"><i class="bx bxs-user"></i>Accounts</a></li>
        <li><a href="../components/admin_logout.php" onclick="return confirm('Logout from this website');"><i class="bx bxs-log-out"></i>Logout</a></li>
      </ul>
    </div>

    <h5>Find Us</h5>
    <div class="social-links">
      <i class="bx bxl-facebook"></i>
      <i class="bx bxl-instagram-alt"></i>
      <i class="bx bxl-linkedin"></i>
      <i class="bx bxl-twitter"></i>
      <i class="bx bxl-pinterest-alt"></i>
    </div>
  </div>
</div>
