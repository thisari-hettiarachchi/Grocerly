<?php 
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
    header('location:login.php');
    exit;
}

if(isset($_GET['pid'])){
    $pid = $_GET['pid'];
}else{
    $pid = '';
    header('location:home.php');
    exit;
}
if (isset($_POST['add_to_cart'])) {
    $qty = $_POST['qty'];
    // Optional: save to cart table here
    echo "<script>alert('Added $qty item(s) to cart!');</script>";
}

$select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
$select_product->execute([$pid]);

if($select_product->rowCount() > 0){
    $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
}else{
    echo "<script>alert('Product not found'); location.href='home.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Product</title>
    <link rel="stylesheet" href="css/view_page.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="view-container">
    <div class="view-image">
        <img id="mainImage" src="uploaded_files/<?= $fetch_product['mango']; ?>" alt="<?= $fetch_product['name']; ?>">
        <div class="thumbnails">
            <img onclick="changeImage(this)" src="uploaded_files/<?= $fetch_product['mango']; ?>" alt="">
        </div>
    </div>
    <div class="view-details">
        <h2><?= $fetch_product['name']; ?></h2>
        <p class="price">$<?= $fetch_product['price']; ?></p>
        <p class="desc"><?= $fetch_product['description']; ?></p>
        <p class="brand"><strong>Brand:</strong> <?= $fetch_product['brand']; ?></p>
        <p class="stock"><?= $fetch_product['stock'] > 0 ? 'In stock' : 'Out of stock'; ?></p>

        <form method="post">
            <input type="number" name="qty" min="1" value="1" class="qty">
            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<script src="js/view_page.js"></script>
<?php include 'components/footer.php'; ?>
</body>
</html>
