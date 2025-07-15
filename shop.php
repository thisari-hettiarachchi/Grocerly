<?php
$items = [
  ["Carrots", 230, "250g", "images/carrots.jpg", "In Stock", "Fruits & Vegetables"],
  ["Bananas", 120, "250g", "images/bananas.jpg", "Fresh", "Fruits & Vegetables"],
  ["Red Apples", 650, "250g", "images/apples.jpg", "Only 5 Left", "Fruits & Vegetables"],
  ["Tomatoes", 400, "250g", "images/tomatoes.jpg", "Hot Deal", "Fruits & Vegetables"],
  ["Grapes", 300, "250g", "images/grapes.jpg", "Seasonal", "Fruits & Vegetables"],
  ["Potatoes", 60, "500g", "images/potatoes.jpg", "In Stock", "Fruits & Vegetables"],
  ["Mangoes", 280, "1kg", "images/mango.jpg", "Hurry 3 Only", "Fruits & Vegetables"],
  ["Broccoli", 190, "250g", "images/broccoli.jpg", "Organic", "Fruits & Vegetables"],
  ["Fresh Milk", 400, "1L", "images/milk.jpg", "Cold & Fresh", "Dairy"],
  ["Cheese", 550, "pack", "images/cheese.jpg", "New", "Dairy"],
  ["Bread", 140, "loaf", "images/bread.jpg", "Hot", "Bakery"],
  ["Buns", 100, "pack", "images/buns.jpg", "Best Seller", "Bakery"],
  ["Chicken Breast", 1200, "kg", "images/chicken.jpg", "Fresh Cut", "Meat & Seafood"],
  ["Prawns", 650, "kg", "images/prawns.jpg", "Frozen", "Meat & Seafood"],
  ["Tissue Roll", 90, "pack", "images/tissue.jpg", "Popular", "Household Items"],
  ["Dish Wash", 255, "bottle", "images/dishwash.jpg", "Clean Power", "Household Items"],
  ["Coca Cola", 400, "bottle", "images/coke.jpg", "Chilled", "Beverages"],
  ["Orange Juice", 160, "pack", "images/juice.jpg", "Natural", "Beverages"],
  ["Shampoo", 230, "bottle", "images/shampoo.jpg", "Silky Touch", "Personal Care"],
  ["Toothpaste", 200, "tube", "images/toothpaste.jpg", "Fresh Mint", "Personal Care"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grocery</title>
  <link rel="stylesheet" href="shop.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="navbar">
  <div class="container flex">
    <h1 class="logo">Gro<span>cery</span></h1>
    <nav>
      <a href="#">Home</a>
      <a href="#">About Us</a>
      <a href="#">Shop</a>               
      <a href="#">Order</a>
      <a href="#">Contact</a>
    </nav>

    //*
    
    <div class="icons">
      <i class="fa-solid fa-magnifying-glass"></i>
      <i class="fa-regular fa-heart"></i>
      <span id="cart-count">0</span>
      <i class="fa-solid fa-cart-shopping"></i>
      <i class="fa-solid fa-user"></i>
    </div>
  </div>
</header>

<!-- === FLAT PRODUCT GRID === -->
<main class="container grid">
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
</body>
</html>