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
    if(isset($_POST['delete_id'])){
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_NUMBER_INT);

        $verify_delete = $conn->prepare("SELECT * FROM `message` WHERE message_id = ?");
        $verify_delete->execute([$delete_id]);

        if($verify_delete->rowCount()>0){
            $delete_msg = $conn->prepare("DELETE FROM `message` WHERE message_id = ?");
            $delete_msg->execute([$delete_id]);
            $success_message[] = 'message delete';
        }else{
            $warning_msg[] = 'message already delete';
        }
    }
?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Unread Message</title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        <link rel="stylesheet" type="text/css" href="../css/admin_msg.css">
        
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
                    <h1>Unread Messages</h1>
                </div>
                <div class="Box_container">
                  <?php
                     $select_msg = $conn->prepare("SELECT * FROM `message`");
                     $select_msg->execute();

                     if($select_msg->rowCount() > 0){
                        while($fetch_msg = $select_msg->fetch(PDO::FETCH_ASSOC)){
                       
                  ?>
                  <div class="Box">
                    <h3 class="Name">Name: <?= $fetch_msg['name'];?></h3>
                    <h4>Subject: <?= $fetch_msg['subject'];?></h4>
                    <p>Message: <?= $fetch_msg['message'];?></p>
                    <form action="" method="post">
                        <input type="hidden" name="delete_id" value="<?= $fetch_msg['message_id'];?>">
                        <button type="submit" name="delete" onclick="return confirm('want to delete thi message');" class="Btn">Delete message</button>
                    </form>
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
