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

    
     $get_id = $_GET['post_id'];
     $get_id = filter_var($get_id, FILTER_SANITIZE_NUMBER_INT);

     $select_product = $conn->prepare("SELECT * FROM `product` WHERE product_id = ?");
     $select_product->execute([$get_id]);


?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title> View Product </title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        <link rel="stylesheet" type="text/css" href="../css/read_product.css">
        
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
            <section class="read_products">
                <div class="heading">
                    <h1>Read Product</h1>
                </div>
                <div class="container">
                    <?php

                    $select_product = $conn->prepare("SELECT * FROM `product` WHERE product_id=?");
                    $select_product ->execute([$get_id]);
                    
                    if($select_product->rowCount()>0){
                        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){

                        
                     ?>
                     
                     <form action="" method="post" class="box">
                        <input type="hidden" name="product_id" value="<?= $fetch_product['product_id'];?>">
                        </div>
                        <div class="product-slider">
                            <div class="slider-images">
                            <a href="../uploaded_files/<?= $fetch_product['image'];?>" class="slider-image active">
                            <img src="../uploaded_files/<?= $fetch_product['image'];?>" alt="">
                            </a>
                            </div>

                            <div class="status" style="color: <?= $fetch_product['status'] === 'active' ? 'limegreen' : 'red'; ?>">
                               <?= ucfirst($fetch_product['status']); ?>
                            </div>

                            <p class="price">$<?=$fetch_product['price'];?>/-</p>
                            <div class="title"><?= $fetch_product['name'];?></div>
                            <div class="content"><?= $fetch_product['description'];?></div>
                            <div class="flex-btn">
                                <a href="edit_product.php?product_id=<?= $fetch_product['product_id'];?>" class="btn">edit</a>
                                <a href="view_product.php?post_id=<?= $fetch_product['id'];?>" class="btn">go back</a>
                            </div>
                        </div>

                     </form>
 

                     <?PHP 
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
