<?php
include '../components/connect.php';
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '1');

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT seller_id, name, email, password FROM sellers WHERE email = ?");
    $stmt->execute([$email]);
    $seller = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($seller) {
        $seller_id = $seller['seller_id'];
        $seller_name = $seller['name'];
        $seller_email = $seller['email'];
        $prev_pass = $seller['password'];
    } else {
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

if (isset($_POST['update'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $new_email = htmlspecialchars(trim($_POST['email']));

    // Update name
    if (!empty($name) && $name != $seller_name) {
        $update_name = $conn->prepare("UPDATE sellers SET name = ? WHERE seller_id = ?");
        $update_name->execute([$name, $seller_id]);
        $success_msg[] = 'Username updated successfully';
    }

    // Update email
    if (!empty($new_email) && $new_email != $seller_email) {
        $check_email = $conn->prepare("SELECT * FROM sellers WHERE email = ? AND seller_id != ?");
        $check_email->execute([$new_email, $seller_id]);
        if ($check_email->rowCount() > 0) {
            $warning_msg[] = 'Email already exists';
        } else {
            $update_email = $conn->prepare("UPDATE sellers SET email = ? WHERE seller_id = ?");
            $update_email->execute([$new_email, $seller_id]);
            $success_msg[] = 'Email updated successfully';
        }
    }

    // Password update
    $old_pass = sha1($_POST['old_pass']);
    $new_pass = sha1($_POST['new_pass']);
    $cpass = sha1($_POST['cpass']);

    if (!empty($_POST['old_pass'])) {
        if ($old_pass !== $prev_pass) {
            $warning_msg[] = 'Old password not matched';
        } elseif ($new_pass !== $cpass) {
            $warning_msg[] = 'Confirm password does not match';
        } elseif (!empty($_POST['new_pass'])) {
            $update_pass = $conn->prepare("UPDATE sellers SET password = ? WHERE seller_id = ?");
            $update_pass->execute([$cpass, $seller_id]);
            $success_msg[] = 'Password updated successfully';
        } else {
            $warning_msg[] = 'Please enter a new password';
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" type="text/css" href="../css/update_profile.css">
    <link rel="shortcut icon" href="../images/fav.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
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
                <h1>Profile Update</h1>
            </div>
        </section>

        <section class="Form-container">
            <form action="" method="post" enctype="multipart/form-data" class="Register">
                <h3>Update Profile</h3>
                <div class="Flex">
                    <div class="Col">
                        <div class="Input-field">
                            <p>Your Name</p>
                            <input type="text" name="name" value="<?= htmlspecialchars($seller_name); ?>" maxlength="50" class="Box">
                        </div>
                        <div class="Input-field">
                            <p>Your Email</p>
                            <input type="email" name="email" value="<?= htmlspecialchars($seller_email); ?>" maxlength="50" class="Box">
                        </div>
                    </div>

                    <div class="Col">
                        <div class="Input-field">
                            <p>Old Password</p>
                            <input type="password" name="old_pass" placeholder="Enter old password" maxlength="50" class="Box" id="old_pass">
                            <button type="button" class="password-toggle" onclick="togglePassword('old_pass', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="Input-field">
                            <p>New Password</p>
                            <input type="password" name="new_pass" placeholder="Enter new password" maxlength="50" class="Box" id="new_pass">
                            <button type="button" class="password-toggle" onclick="togglePassword('new_pass', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="Input-field">
                            <p>Confirm Password</p>
                            <input type="password" name="cpass" placeholder="Confirm password" maxlength="50" class="Box" id="cpass">
                            <button type="button" class="password-toggle" onclick="togglePassword('cpass', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <input type="submit" name="update" value="Update Profile" class="Btn">
            </form>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin.js"></script>
    <?php include('../components/alert.php'); ?>
</body>
</html>
