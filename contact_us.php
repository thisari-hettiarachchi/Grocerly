<?php
    include 'components/connect.php';

    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

// Initialize variables with default values
$user_id = $_SESSION['user_id'] ?? 0;
$name = $email = $number = $msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send'])) {
    // Check if database connection exists
    if (!isset($conn)) {
        die("<script>alert('Database connection failed. Please try again later.');</script>");
    }

    // Sanitize inputs with null checks
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'] ?? '', FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'] ?? '', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($name) || empty($email) || empty($number) || empty($msg)) {
        echo "<script>alert('Please fill all required fields!');</script>";
    } else {
        try {
            // Check for duplicate messages
            $stmt = $conn->prepare("SELECT * FROM message WHERE name = ? AND email = ? AND number = ? AND message = ?");
            $stmt->execute([$name, $email, $number, $msg]);

            if ($stmt->rowCount() > 0) {
                echo "<script>alert('You have already sent this message.');</script>";
            } else {
                // Insert new message
                $stmt = $conn->prepare("INSERT INTO message (user_id, name, email, number, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$user_id, $name, $email, $number, $msg]);
                echo "<script>alert('Message sent successfully!'); window.location.href='contact_us.php';</script>";
                exit();
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo "<script>alert('An error occurred. Please try again.');</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Contac Us Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">
        <link rel="stylesheet" type="text/css" href="css/contact.css">

        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">


    </head>
<body>
 <?php include('components/user_header.php'); ?>

<div class="contact-page-spacer" aria-hidden="true"></div>


<section class="contact-hero" id="top">
  <img src="images/contact.jpg" alt="Grocery banner"> 
  <div class="contact-hero-content">
    <h1>Contact Us</h1>
    <p>Questions about fresh produce, delivery, or your order? Our friendly team is ready to help you 24/7.</p>
    <a href="#contact-main" class="scroll-btn">Get in Touch</a>
  </div>
</section>


<div class="quick-contact">
  <div class="quick-card">
    <i class="fa-solid fa-phone"></i>
    <h3>Call Us</h3>
    <p>+94 11 234 5678</p>
  </div>
  <div class="quick-card">
    <i class="fa-solid fa-envelope"></i>
    <h3>Email</h3>
    <p>support@grocerystore.lk</p>
  </div>
  <div class="quick-card">
    <i class="fa-solid fa-location-dot"></i>
    <h3>Visit</h3>
    <p>123 Farm Lane, Colombo</p>
  </div>
</div>


<section class="contact-wrapper" id="contact-main">
 

  <!-- Form card -->
  <div class="contact-form-card">
    <h3>Get in Touch</h3>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#contact-main" method="POST" novalidate>
      <div>
        <label for="cf-name">Name *</label>
        <input type="text" id="cf-name" name="name" placeholder="Enter your name" value="<?php echo isset($_POST['name'])?htmlspecialchars($_POST['name']):''; ?>">
      </div>
      <div>
        <label for="cf-email">Email *</label>
        <input type="email" id="cf-email" name="email" placeholder="Enter your email"  value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):''; ?>">
      </div>
      <div>
        <label for="cf-subject">Subject</label>
        <input type="text" id="cf-subject" name="subject" placeholder="Order #, product, etc." value="<?php echo isset($_POST['subject'])?htmlspecialchars($_POST['subject']):''; ?>">
      </div>
      <div>
        <label for="cf-message">Message *</label>
        <textarea id="cf-message" name="message" rows="5" placeholder="How can we help?"><?php echo isset($_POST['message'])?htmlspecialchars($_POST['message']):''; ?></textarea>
      </div>

      <input type="text" name="number" style="display:none" tabindex="-1" autocomplete="off">

      <button type="submit" name="send">Send Message</button>
    </form>
  </div>

  <div class="contact-map-wrapper">
  <iframe loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.60460364028!2d79.76361403496394!3d6.921837955355837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2594f38263b0b%3A0x5d09852ffb62a1c4!2sColombo!5e0!3m2!1sen!2slk!4v0000000000000"></iframe>
</div>
</section>



<?php include('components/footer.php'); ?>
<?php include('components/alert.php'); ?>
<script src="js/user_script.js"></script>

    </body>
</html>
