<?php
// Start session at the very top
session_start();

// Include database connection properly (remove @ to see potential errors)
@include 'connect.php';

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
<html>
<title>Contact Us</title>
<link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/user_styles.css"><!-- keep your site-wide CSS -->

<head>
<style>

:root {
  --brand: #ff6600;
  --brand-dark: #e65c00;
  --brand-accent: #00C896;
  --radius-sm: 5px;
  --radius-md: 10px;
  --radius-lg: 20px;
  --shadow-md: 0 4px 15px rgba(0,0,0,.15);
  --shadow-lg: 0 8px 30px rgba(0,0,0,.25);
  --max-content: 1200px;
}

body {
  margin: 0;
  font-family: 'Poppins', Arial, sans-serif;
  color: #2b2b2b;
  line-height: 1.5;
  background: #fff8f0; 
}

.contact-page-spacer {
  height: 70px; 
}


.contact-hero {
  position: relative;
  width: 100%;
  min-height: 50vh;              /* make it tall enough to look good */
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  isolation: isolate; /* allow blend */
}
.contact-hero img {
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover;              /* zoom & crop to fill */
  object-position: center bottom; /* push up so cart shows */
  
  
}
.contact-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,.35) 0%, rgba(0,0,0,.35) 60%, rgba(0,0,0,.55) 100%);
  pointer-events: none;
}
.contact-hero-content {
  position: relative;
  text-align: center;
  color: #fff;
  padding: 0 1rem;
  max-width: 90%;
  z-index: 1;
}
.contact-hero-content h1 {
  font-size: clamp(2rem, 5vw, 3.25rem);
  margin: 0 0 .5em;
  font-weight: 700;
}
.contact-hero-content p {
  font-size: clamp(1rem, 2.5vw, 1.25rem);
  margin: 0 auto 1.5em auto;
  max-width: 600px;
}
.contact-hero-content .scroll-btn {
  display: inline-block;
  padding: .75em 1.75em;
  background: var(--brand);
  color: #fff;
  font-weight: 600;
  border-radius: var(--radius-md);
  text-decoration: none;
  transition: background .25s;
}
.contact-hero-content .scroll-btn:hover {
  background: var(--brand-dark);
}

 
.quick-contact {
  max-width: var(--max-content);
  margin: 0 auto;
  padding: 3rem 1rem 1rem 1rem;
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fit, minmax(min(100%,250px),1fr));
}
.quick-card {
  background: #fff;
  padding: 2rem 1.5rem;
  text-align: center;
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-md);
  transition: transform .25s, box-shadow .25s;
}
.quick-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}
.quick-card i {
  font-size: 2rem;
  color: var(--brand);
  margin-bottom: .5rem;
}
.quick-card h3 {
  margin: 0 0 .25rem;
  font-size: 1.25rem;
}
.quick-card p {
  margin: 0;
  font-size: .95rem;
  color: #555;
}

.contact-wrapper {
  max-width: var(--max-content);
  margin: 0 auto;
  padding: 4rem 1rem 6rem 1rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: start;
}
@media (max-width:768px) {
  .contact-wrapper {
    grid-template-columns: 1fr;
    gap: 2.5rem;
    padding-bottom: 4rem;
  }
}


.contact-info-panel {
  text-align: left;
}
.contact-info-panel h2 {
  font-size: 2rem;
  margin: 0 0 .75em;
  font-weight: 700;
  color: var(--brand-dark);
}
.contact-info-panel p {
  margin: 0 0 1.25em;
  color: black;
}
.contact-badges {
  display: flex;
  flex-wrap: wrap;
  gap: .75rem;
  margin: 1.5rem 0 2.5rem 0;
}
.contact-badge {
  padding: .5em 1em;
  background: #fff;
  border: 1px solid var(--brand);
  color: var(--brand-dark);
  font-size: .9rem;
  border-radius: 999px;
}


.contact-form-card {
  position: relative;
  background: rgba(255,255,255,.98);
  padding: 2.5rem 2rem 3rem 2rem;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  width: 100%;
  max-width: 480px;
  margin: 0 auto;
}
.contact-form-card h3 {
  margin: 0 0 1.25rem;
  text-align: center;
  font-size: 1.75rem;
  font-weight: 600;
}
.contact-form-card form {
  display: grid;
  gap: 1.25rem;
}
.contact-form-card label {
  font-size: .95rem;
  font-weight: 600;
  display: block;
  margin-bottom: .25rem;
}
.contact-form-card input,
.contact-form-card textarea {
  width: 100%;
  padding: .9rem 1rem;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: var(--radius-sm);
  transition: border-color .2s, box-shadow .2s;
  font-family: inherit;
}
.contact-form-card input:focus,
.contact-form-card textarea:focus {
  outline: none;
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgb(255 102 0 / .25);
}
.contact-form-card button {
  padding: 1rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  border: none;
  border-radius: var(--radius-md);
  background: var(--brand);
  color: #fff;
  cursor: pointer;
  transition: background .25s, transform .1s;
}
.contact-form-card button:hover {
  background: var(--brand-dark);
}
.contact-form-card button:active {
  transform: scale(.97);
}


.form-feedback {
  margin-bottom: 1rem;
  padding: 1rem 1.25rem;
  border-radius: var(--radius-sm);
  font-size: .95rem;
  line-height: 1.3;
}
.form-feedback.success {
  background: #e6fff5;
  border-left: 4px solid var(--brand-accent);
  color: #006a4e;
}
.form-feedback.error {
  background: #ffeaea;
  border-left: 4px solid #ff3b3b;
  color: #b00000;
}
.form-feedback.info {
  background: #fffbe6;
  border-left: 4px solid #ffcc00;
  color: #6b5500;
}
#contact-main
{
  background-color:#00C896;
  border-radius: 10px;
}
.contact-map-wrapper {
  width: 100%;
  max-width: var(--max-content);
  margin: 0 auto 6rem auto;
  padding: 0 1rem;
  
}
.contact-map-wrapper iframe {
  width: 50%;
  height: 500px;
  border: 1;
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-md);
 margin: 20px;
 
}
.contact-footer-spacer {
  height: 4rem;
}
footer{
    margin-top:5%
}

</style>
</head>
<body>
 <?php include('components/user_header.php'); ?>

<div class="contact-page-spacer" aria-hidden="true"></div>


<section class="contact-hero" id="top">
  <img src="images/contact.jpg" alt="Grocery banner"> <!-- use your bottom banner image -->
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
  <!-- Left info -->
  <div class="contact-info-panel">
    <h2>We'd Love to Hear From You</h2>
    <p>Whether you're looking for a special ingredient, tracking an order, or just have feedback, drop us a note. We respond quickly—usually within 24 hours.</p>
    <div class="contact-badges">
      <span class="contact-badge">Farm Fresh</span>
      <span class="contact-badge">Fast Delivery</span>
      <span class="contact-badge">Support 24/7</span>
    </div>
    <p><i class="fa-solid fa-headset"></i> Live chat support available during business hours.</p>
    <p><i class="fa-solid fa-truck"></i> Island-wide delivery in 24–48 hrs.</p>
  </div>

  <!-- Form card -->
  <div class="contact-form-card">
    <h3>Get in Touch</h3>

    

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#contact-main" method="POST" novalidate>
      <div>
        <label for="cf-name">Name *</label>
        <input type="text" id="cf-name" name="name" placeholder="Enter your name" required value="<?php echo isset($_POST['name'])?htmlspecialchars($_POST['name']):''; ?>">
      </div>
      <div>
        <label for="cf-email">Email *</label>
        <input type="email" id="cf-email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):''; ?>">
      </div>
      <div>
        <label for="cf-subject">Subject</label>
        <input type="text" id="cf-subject" name="subject" placeholder="Order #, product, etc." value="<?php echo isset($_POST['subject'])?htmlspecialchars($_POST['subject']):''; ?>">
      </div>
      <div>
        <label for="cf-message">Message *</label>
        <textarea id="cf-message" name="message" rows="5" placeholder="How can we help?" required><?php echo isset($_POST['message'])?htmlspecialchars($_POST['message']):''; ?></textarea>
      </div>

      <input type="text" name="number" style="display:none" tabindex="-1" autocomplete="off">

      <button type="submit" name="send">Send Message</button>
    </form>
  </div>
</section>


<div class="contact-map-wrapper">
  <iframe loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.60460364028!2d79.76361403496394!3d6.921837955355837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2594f38263b0b%3A0x5d09852ffb62a1c4!2sColombo!5e0!3m2!1sen!2slk!4v0000000000000"></iframe>
</div>

<div class="contact-footer-spacer" aria-hidden="true"></div>

<?php include('components/footer.php'); ?>
<?php include('components/alert.php'); ?>
<script src="js/user_script.js"></script>

    </body>
</html>
