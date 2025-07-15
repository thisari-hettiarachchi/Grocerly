<?php
$items = [
  ["Carrots", 230, "250g", "images/carrots.jpg", "In Stock", "Fruits & Vegetables"],
  ["Bananas", 120, "250g", "images/bananas.jpg", "Fresh", "Fruits & Vegetables"],
  ["Red Apples", 650, "250g", "images/apples.jpg", "Only 5 Left", "Fruits & Vegetables"],
  ["Tomatoes", 400, "250g", "images/tomatoes.jpg", "Hot Deal", "Fruits & Vegetables"]
];
?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shop Page</title>

        <link rel="stylesheet" type="text/css" href="css/user_styles.css">
        <link rel="stylesheet" type="text/css" href="css/shop.css">
        
        <link rel="shortcut icon" href="images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">

    </head>

    <body>

<?php include('components/user_header.php'); ?>

<!-- === FLAT PRODUCT GRID === -->
<main class="shop-container grid">
<?php foreach($items as $item): ?>
  <div class="card">
    <div class="img-box">
      <img src="<?= $item[3] ?>" alt="<?= $item[0] ?>">
      <span class="ribbon"><?= $item[4] ?></span>
    </div>
    <h3 class="title"><?= $item[0] ?></h3>
    <p class="category-label"><?= $item[5] ?></p>
    <ul class="actions">
      <li><button class="btn-cart"><i class="fa-solid fa-cart-plus"></i></button></li>
      <li><button class="btn-like"><i class="fa-regular fa-heart"></i></button></li>
      <li><button class="btn-view"><i class="fa-regular fa-eye"></i></button></li>
    </ul>
    <div class="buy-line">
      <span class="price">Rs:<?= number_format($item[1],2) ?>/<?= $item[2] ?></span>
      <button class="btn-buy">Buy Now</button>
    </div>
  </div>
<?php endforeach; ?>
</main>

<script src="shop.js"></script>
<?php include('components/footer.php'); ?>
</body>
</html>