<?php 
include '../components/connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';
    $error_msg = [];

    if (empty($email) || empty($pass)) {
        $error_msg[] = "Please fill both email and password.";
    } else {
        $sql = "SELECT email, password FROM sellers WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($pass === $row['password']) {
                $_SESSION['email'] = $row['email'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error_msg[] = "Invalid password. Please try again.";
            }
        } else {
            $warning_msg[] = "Email not found. Please register.";
        }
    }

    if (isset($_COOKIE['email'])) {
        echo "Cookie value: " . $_COOKIE['email']; 
    } else {
        echo "Cookie not set";
    }
    
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Admin Login</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <link rel="stylesheet" href="../css/admin_style.css">
    </head>

    <body>
        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
        </div>


        <section class="form-container">
            <form action="" method="post" enctype="multipart/form-data" class="login">
                <h3>Login Now</h3>

                    <div class="input-field">
                        <p>Your Email </p>
                        <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                    </div>    
                    <div class="input-field">
                        <p>Your Password</p>
                        <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box" id="password1">
                        <button type="button" class="password-toggle" onclick="togglePassword('password1', this)">
                            <i class="fas fa-eye" id="password1-icon"></i>
                        </button>
                    </div>
              
                    <p class="link">Don't have an account? <a href="register.php">Register</a></p>
                    <input type="submit" name="submit" value="login now" class="btn">
            </form>
        </section>


        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php include '../components/alert.php'; ?>

        <script src="../js/admin_script.js"></script>
    </body>

</html>