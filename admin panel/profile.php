<?php
    include '../components/connect.php';
    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    if (isset($_SESSION['email'])) {
        $seller_email = $_SESSION['email'];
    } else {
        header('location:login.php'); 
        exit;
    }

    $fetch_user = $conn->prepare("SELECT * FROM `sellers` WHERE email = ?");
    $fetch_user->execute([$seller_email]);

    if ($fetch_user->rowCount() > 0) {
        $fetch_profile = $fetch_user->fetch(PDO::FETCH_ASSOC);
        $user_id = $fetch_profile['id']; 
    } else {
        header('location:login.php'); 
        exit;
    }
?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title> Seller Profile </title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        
        <link rel="shortcut icon" href="../images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
        
    </head>
    
    <body>

        <div class="main-container">
            <?php include '../components/admin_header.php'; ?>

            <div class="profile-container">
                <div class="user-profile">
                    <div class="heading">
                        <h1>Profile Details</h1>
                    </div>
                    <div class="user">
                        <div class="image-container">
                            <img src="<?= !empty($fetch_profile['image']) ? '../uploaded_files/' . htmlspecialchars($fetch_profile['image']) : '../images/default.jpg'; ?>" class="logo-img" width="100">
                            <div class="status-indicator"></div>
                        </div>
                        <h3 class="name"><?= htmlspecialchars($fetch_profile['name']); ?></h3>
                        <a href="update_profile.php" class="btn"><i class='bx bx-pencil'></i> Update Profile</a>
                    </div>
                    <div class="more-info">
                        <div class="title">
                            <span>Personal Information</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class='bx bx-envelope'></i>Full Name</span>
                            <span class="detail-value" id="fullName"><?= htmlspecialchars($fetch_profile['name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class='bx bx-pencil'></i>Email Address</span>
                            <span class="detail-value" id="userEmail"><?= htmlspecialchars($fetch_profile['email']); ?></span>
                        </div> 
                    </div>   
                </div>
            </div>
        </div>


        <?php include('../components/alert.php'); ?>
        
        <script src="../js/admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </body>

</html>