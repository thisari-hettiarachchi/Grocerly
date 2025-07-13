<?php
@include 'config.php';
session_start();

// Dummy fallback if user is not logged in
$user_id = $_SESSION['user_id'] ?? 0;

if (isset($_POST['send'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

    // Check if same message already exists
    $stmt = $conn->prepare("SELECT * FROM message WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $stmt->execute([$name, $email, $number, $msg]);

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('You have already sent this message.');</script>";
    } else {
        // Insert new message
        $stmt = $conn->prepare("INSERT INTO message(user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $number, $msg]);
        echo "<script>alert('Message sent successfully!');</script>";
    }
}
?>