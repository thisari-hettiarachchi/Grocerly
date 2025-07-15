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
        
        <title> View Product </title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        <link rel="stylesheet" type="text/css" href="../css/view_product.css">
        
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
            <section class="show_products">
                <div class="heading">
                    <h1>All Products</h1>
                </div>
                <div class="box-container">
                    <?php 
                      $select_products = $conn->prepare("SELECT * FROM `product` WHERE seller_id = ?");
                      $select_products ->execute([$seller_id]);

                      if($select_products->rowCount()>0){
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                      
                    ?>

                    <form action="" method="post" class="box">
                    <div class="icon">
                        <div class="icon-box">
                            <img src="../uploaded_files/<?= $fetch_products['image']?>" class="img1">
                        </div>
                    </div>  
                    <p class="status" data-status="<?= $fetch_products['status']; ?>"><?= $fetch_products['status']; ?></p>
                    <p class="price">$<?=$fetch_products['price'];?>/-</p>
                    <div class="content">
                        <div class="title"><?= $fetch_products['name'];?></div>
                        <div class="flex-btn">
                            <a href="edit_product.php?id=<?=$fetch_products['id'];?>" class="btn">edit</a>
                            <button type="submit" name="delet" class="btn">delet</button>
                            <a href="read_product.php?post_id=<?=$fetch_products['id'];?>" class="btn">read</a>
                        </div>

                    </div>
                    </form>


                   <?php
                     }
                    }else{
                        echo'

                        <div class="empty">
                             <p>no products added yet! <br> <a href="add_product.php" class="btn" style="margin-topL: 1rem;">add product</a></p>
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
