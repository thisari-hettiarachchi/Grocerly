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
        } else {
            $seller_id = null;
            $seller_name = null;
        }
    } else {
        header("Location: login.php");
        exit();
    }
    
?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Unread Message</title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        <link rel="stylesheet" type="text/css" href="../css/account.css">
        
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
            <section class="messages-container">
                <div class="heading">
                    <h1>Registered User</h1>
                </div>
                <div class="box_container">
                  <?php
                     $select_users = $conn->prepare("SELECT * FROM `users`");
                     $select_users->execute();

                     if($select_users->rowCount() > 0){
                        while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                       
                  ?>
                  <div class="box">
                    <img src="../uploaded_files/<?= $fetch_users['image'] ?>">
                    <div class="detail">
                        <p>User Id : <?= $fetch_users['user_id'];?></p>
                        <p>User Name : <?= $fetch_users['name'];?></p>
                        <p>User Email : <?= $fetch_users['email'];?></p>
                    </div>
                  </div>
                  <?php
                   }
                    }else{
                         echo'

                        <div class="empty">
                             <p>no unread message yet!</p>
                         </div>

                         ';
                    }
                   ?>
                </div>
            </section>
        </div>

        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="../js/admin.js"></script>

        <?php include('../components/alert.php'); ?>
        
    </body>
    
</html>
