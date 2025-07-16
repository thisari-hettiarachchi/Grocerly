<?php
    include '../components/connect.php';
    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("SELECT seller_id, name FROM sellers WHERE email = ?");
        $stmt->execute([$email]);
        $seller = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($seller) {
            $seller_id = $seller['seller_id'];
            $seller_name = $seller['name'];
            $profile_image = $seller['profile_image'] ?? 'default_profile.png';
        } else {
            header("Location: login.php");
            exit();
        }
    } else {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Seller Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        
        <section class="dashboard">
            <div class="heading">
                <h1>Your Profile</h1>
            </div>

            <div class="box_container" style="justify-content: center;">
                <div class="box" style="text-align: center;">
                    <img src="../uploaded_files/<?= htmlspecialchars($profile_image); ?>" alt="Profile Image" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
                    <h3><?= htmlspecialchars($seller_name); ?></h3>
                    <a href="update_profile.php" class="btn">Edit Profile</a>
                </div>
            </div>
        </section>
    </div>

    <script src="../js/admin.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>
