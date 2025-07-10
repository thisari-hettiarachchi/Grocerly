<?php
    session_start();

    $_SESSION = [];

    session_unset();
    session_destroy();

    setcookie('user_id', '', time() - 3600, '/');

    header('Location: ../home.php'); 
    exit();
?>
