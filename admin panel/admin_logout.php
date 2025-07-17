<?php 
  include '../components/connect.php';

  setcookie('seller_id','',time() -1, '/');
  header('location:login.php');
?>