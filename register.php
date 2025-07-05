<?php
    include 'components/connect.php';

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $user_id = unique_id();
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $warning_msg[] = "Email is already registered.";
        } else {
            if ($pass !== $cpass) {
                $warning_msg[] = "Passwords do not match.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (user_id, name, email, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $name, $email, $pass]);
                $success_msg[] = "Successfully registered! Please login.";
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Register</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <link rel="stylesheet" href="css/user_styles.css">
    </head>

    <body style="background-color: #f3842a;">
        <section class="form-container">
            <form action="" method="post" enctype="multipart/form-data" class="register">
                <h3>Register Now</h3>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>Your Name</p>
                            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                        </div>
                        <div class="input-field">
                            <p>Your Email </p>
                            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>Your Password</p>
                            <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box" id="password1">
                            <button type="button" class="password-toggle" onclick="togglePassword('password1', this)">
                                <i class="fas fa-eye" id="password1-icon"></i>
                            </button>
                        </div>

                        <div class="input-field">
                            <p>Confirm Password</p>
                            <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" required class="box" id="password2">
                            <button type="button" class="password-toggle" onclick="togglePassword('password2', this)">
                                <i class="fas fa-eye" id="password2-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                    <p class="link">Already have an account? <a href="login.php">Login</a></p>
                    <input type="submit" name="submit" value="register now" class="btn">
            </form>
        </section>


        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php include 'components/alert.php'; ?>

        <script src="js/user_script.js"></script>
    </body>

</html>