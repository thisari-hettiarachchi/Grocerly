<?php 
    include 'components/connect.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>About Page</title>
        <link rel="stylesheet" type="text/css" href="css/user.css">
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
  
    </head>

    <body>
        <?php include 'components/user_header.php'; ?>
        <div class="banner">
            <div class="details">
                <h1>About Us</h1>
                <p>At Grocerly, we believe grocery shopping should be simple, convenient, and enjoyable. That’s why we’ve built a one-stop online destination where you can find<br> 
                fresh produce, pantry staples, and everyday essentials—all delivered right to your door. Whether you're stocking up for the week or grabbing last-minute items, <br>
                Grocerly is here to make your life easier. We’re committed to quality, affordability, and exceptional service,<br>
                 so you can spend less time shopping and more time enjoying what matters most.

</p>
                <span><a href="dashboard.php">About Us</a><i class="bx bx-right-arrow-alt"></i>About Us</span>
            </div>
        </div>
        <div class="line2"></div>

        <?php include 'components/user_footer.php'; ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetlert/2.1.2/sweetalert.min.js"></script>

        <script src="js/user_script.js"></script>
        <?php include 'components/alert.php'; ?>
    </body>

</html>