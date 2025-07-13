

    <?php 
    include 'components/connect.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home Page</title>

        <link rel="stylesheet" type="text/css" href="css/user.css">
        
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>

    <body>
        <?php include('components/user_header.php'); ?>
        <!-- Section 1: Contact Us Text -->
    <section class="contact-section">
        <h1>Contact Us</h1>
        <p>
            Looking for farm-fresh produce, everyday essentials, <br>or gourmet goodies? We’re here to help! <br>
            At Grocery store, 
            your satisfaction is our top priority. <br>
            Whether you have a question about your order, <br>need product recommendations, or just want to say hello<br> we’d love to hear from you. <br>
            Reach out anytime and our friendly team will respond quickly <br>to make your shopping experience smooth, <br>fresh, and delightful!
        </p>

        <div class="nav-buttons">
            <a href="index.html">Home</a>
           
        </div>

       
    </section>

    <!-- Section 2: Our Services -->
    <section class="services-section" id="services">
        <h1>Our Services</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam accusamus qui expedita repellat commodi aperiam, libero rerum explicabo harum, exercitationem, at provident recusandae odit! Nulla ipsam quidem sequi corporis minima!</p>

        <img src="images/separator.png" alt="separator" class="separator">

        <div class="services">
            <div class="service-card">
                <img src="images/delivery.png" alt="Free Shipping">
                <h1><b>Free Shipping Fast</b></h1>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae officia quos delectus fugit quisquam rerum eaque eligendi aliquid, harum eos reprehenderit magni, repellat impedit, minima consectetur maiores sequi vitae omnis.</p>
            </div>

            <div class="service-card">
                <img src="images/return.png" alt="Money Back Guarantee">
                <h1><b>Money Back Guarantee</b></h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia, eveniet. Perspiciatis maiores voluptas recusandae laborum ratione. Sequi tempore excepturi similique ipsa, quod, laboriosam cum quasi corrupti sunt perferendis eaque deserunt!</p>
            </div>

            <div class="service-card">
                <img src="images/discount.png" alt="Online Support">
                <h1><b>Online Support 24/7</b></h1>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dolorum, modi. Deleniti, ipsam qui! Commodi iusto aspernatur doloribus quisquam nesciunt. Vel ex temporibus rem eaque est aliquam! Illum quos quod possimus?</p>
            </div>
        </div>

        <div class="contact-btn">
            <a href="#footer-content">Our Contact Details</a>
        </div>
    </section>

    <!-- Section 3: Contact Form -->
    <section class="form-section" id="contact-form">
        <div class="form-container">
            <h1>Get in Touch</h1>
            <form action="contact.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="number">Number</label>
                <input type="text" id="number" name="number" placeholder="Enter your number" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

        <script src="js/user_script.js"></script>
        <?php include('components/alert.php'); ?>
        <?php include('components/footer.php'); ?>

    </body>

</html>



