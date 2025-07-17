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

    if(isset($_POST['update_order'])){
        $order_id = $_POST['update_order'];
        $order_id = filter_var($order_id, FILTER_SANITIZE_NUMBER_INT);

        $update_payment = $_POST['update_payment'];
        $update_payment = filter_var($update_payment, FILTER_SANITIZE_NUMBER_INT);

        $update_pay = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE order_id = ?");

        $success_message[] = 'order payment_status update';
    }

    if(isset($_POST['delete_id'])){
        $delete_id = $_POST['order_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_NUMBER_INT);

        $verify_delete = $conn->prepare("SELECT * FROM `message` WHERE message_id = ?");
        $verify_delete->execute([$delete_id]);

        if($verify_delete->rowCount()>0){
            $delete_msg = $conn->prepare("DELETE FROM `message` WHERE message_id = ?");
            $delete_msg->execute([$delete_id]);
            $success_message[] = 'Order delete';
        }else{
            $warning_msg[] = 'Order already delete';
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
            <section class="order-container">
                <div class="heading">
                    <h1>My Orders</h1>
                </div>
                <div class="Box_container">
                  <?php
                     $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
                     $select_orders->execute([$seller_id]);

                     if($select_orders->rowCount() > 0){
                        while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                       
                  ?>
                  <div class="Box">
                    <div class="status" style="color: <?php if($fetch_orders['status']=='in progress'){echo "limegreen";}else{echo "red";}?>"><?= $fetch_orders['status'];?></div>
                  </div>
                  <div class="detail">
                    <p>User name : <span><?= $fetch_orders['name'];?></span></p>
                    <p>User id : <span><?= $fetch_orders['user_id'];?></span></p>
                    <p>Placed On : <span><?= $fetch_orders['date'];?></span></p>
                    <p>Number : <span><?= $fetch_orders['number'];?></span></p>
                    <p>Total Price : <span><?= $fetch_orders['price'];?></span></p>
                    <p>Payment Method : <span><?= $fetch_orders['method'];?></span></p>
                    <p>Adress : <span><?= $fetch_orders['adress'];?></span></p>
                  </div>
                  <form action="" method="post">
                    <input type="hidden" name="order_id" value="<?= $fetch_orders['order_id']; ?>">
                    <select name="update_payment" class="Box"style="width:90%;">
                    <option selected disabled><?= $fetch_orders['payment_status'];?></option>
                    <option value="pending">Pending</option>
                    <option value="pending">Confirm</option>
                    </select>
                    <div class="Flex-btn">
                    <button type="submit" name="update_order" class="Btn">Update Order</button>
                    <button type="submit" name="delete" onclick="return confirm('want to delete thi Order');" class="Btn">Delete Order</button>
                  </div>
                  </form>
                  
                  <?php
                   }
                    }else{
                         echo'

                        <div class="empty">
                             <p>no orders take placed yet!</p>
                         </div>

                         ';
                    }
                   ?>
                </div>
            </section>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

        <script src="../js/admin.js"></script>

        <?php include('../components/alert.php'); ?>
        
    </body>
    
</html>
