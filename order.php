<?php
    include 'components/connect.php';

    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';
        $error_msg = [];

        if (empty($email) || empty($pass)) {
            $error_msg[] = "Please fill both email and password.";
        } else {
            $sql = "SELECT email, password FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($pass === $row['password']) {
                    $_SESSION['email'] = $row['email'];

                    setcookie('email', $row['email'], time() + (86400 * 30), "/"); 
                    setcookie('user_id', $row['user_id'], time() + (86400 * 30), "/"); 
                    
                    header("Location: home.php");
                    exit();
                } else {
                    $error_msg[] = "Invalid password. Please try again.";
                }
            } else {
                $warning_msg[] = "Email not found. Please register.";
            }
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>User Login</title>

        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <link rel="stylesheet" href="css/user_styles.css">
        <link rel="stylesheet" href="css/order.css">
    </head>

    <body>
        <?php include('components/user_header.php'); ?>

        
        <div class="banner1">
            <div class="details">
                <h1>My Orders</h1>
                <p>

                </p>
                <span><a href="home.php"></a><i class=""bx bx-right-arrow-alt></i>My Orders</span>
            </div>
        </div>
        <div class="line2"></div>

        <section class="order-video">
            <h2>📦 Watch How We Process Your Orders</h2>
            <video controls width="100%" poster="images/video-thumbnail.jpg">
                <source src="videos/order_process.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>


        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php include 'components/alert.php'; ?>

        <script src="js/user_script.js"></script>
        <?php include('components/alert.php'); ?>
        <?php include('components/footer.php'); ?>
    </body>

</html>