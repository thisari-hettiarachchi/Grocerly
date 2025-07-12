<?php 
    include 'components/connect.php';
    session_start();
    
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    if (!isset($_SESSION['email'])) {
        header('location: login.php');
        exit();
    }

    $email = $_SESSION['email'];
    $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
    $select_users->execute([$email]);
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);

    if (!$fetch_users) {  
        header('location: login.php');  
        exit();
    }

    $user_id = $fetch_users['user_id'];
    $prev_pass = $fetch_users['password'];
    $prev_image = $fetch_users['image'];

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (!empty($name)) {
            $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE user_id = ?");
            $update_name->execute([$name, $user_id]);
            $success_msg[] = 'Username Updated Successfully';
        }

        if (!empty($email)) {
            $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND user_id != ?");
            $select_email->execute([$email, $user_id]);

            if ($select_email->rowCount() > 0) {
                $warning_msg[] = 'Email Already Exists';
            } else {
                $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE user_id = ?");
                $update_email->execute([$email, $user_id]);
                $success_msg[] = 'Email Updated Successfully';
            }
        }

        $image = $_FILES['image']['name'];
        $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); 
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_files/' . $image;

        if (!empty($image)) {
            if ($image_size > 2000000) {
                $warning_msg[] = 'Image Size Is Too Large';
            } else {
                $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE user_id = ?");
                $update_image->execute([$image, $user_id]);
                move_uploaded_file($image_tmp_name, $image_folder);

                if (!empty($prev_image) && $prev_image != $image) {
                    unlink('uploaded_files/' . $prev_image);
                }
                $success_msg[] = 'Image Updated Successfully';
            }
        }

        $old_pass = $_POST['old_pass'];
        $new_pass = $_POST['new_pass'];
        $cpass = $_POST['cpass'];

        if (!empty($old_pass)) {
            if ($old_pass != $prev_pass) {
                $warning_msg[] = 'Old Password Not Matched';
            } elseif ($new_pass != $cpass) {
                $warning_msg[] = 'Confirm Password Not Matched';
            } else {
                if (!empty($new_pass)) {
                    $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE user_id = ?");
                    $update_pass->execute([$new_pass, $user_id]);
                    $success_msg[] = 'Password Updated Successfully';
                } else {
                    $warning_msg[] = 'Please Enter A New Password';
                }
            }
        }

        header("Location: profile.php?updated=true");
        
        exit();

    }
?>


<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Update Profile Page</title>

        <link rel="stylesheet" type="text/css" href="css/user.css">
        
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>

    <body>

        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
        </div>

        <section class="form-container">
            <form action="" method="post" enctype="multipart/form-data" class="update">
                <h3>Update Profile Details</h3>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>Your Name</p>
                            <input type="text" name="name" placeholder="<?= $fetch_users['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Your Email </p>
                            <input type="text" name="email" placeholder="<?= $fetch_users['email']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Select Pic</p>
                            <input type="file" name="image" accept="image/*" class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>Old Password</p>
                            <input type="password" id="old_password" name="old_pass" placeholder="Enter Your Old Password" class="box">
                            <button type="button" class="password-toggle" onclick="togglePassword('old_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="input-field">
                            <p>New Password</p>
                            <input type="password" id="new_password" name="new_pass" placeholder="Enter Your New Password" class="box">
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="input-field">
                            <p>Confirm Password</p>
                            <input type="password" id="c_password" name="cpass" placeholder="Confirm Your Password" class="box">
                            <button type="button" class="password-toggle" onclick="togglePassword('c_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                    </div>
                </div>
                <input type="submit" name="submit" value="Update Profile" class="btn">
            </form>
        </section>
                

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        

        <?php include('components/alert.php'); ?>

        <script src="js/user_script.js"></script>

    </body>
    
</html>
