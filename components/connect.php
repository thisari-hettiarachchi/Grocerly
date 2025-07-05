<?php
    $db_name = 'mysql:host=localhost;dbname=zestyzone';
    $user_name = 'root';
    $user_password = '';

    try {
        $conn = new PDO($db_name, $user_name, $user_password);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
