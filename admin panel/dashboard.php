<?php
   include '../components/connect.php';

   if(isset($_COOKIE['seller_id'])){
    $seller_id=$_COOKIE['seller_id'];
   }else{
    $seller_id='';
    header('location:login.php');
   }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <link rel="stylesheet" type="text/css" href="../css/adminHeader.css">
        <link rel="stylesheet" type="text/css" href="../css/sidebar.css">
</head>
<body>
    <?php include '../components/admin_header.php';?>

    <?php include '../components/footer.php';?>

    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
    
</body>
</html>