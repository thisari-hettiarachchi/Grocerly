<?php
// wishlist.php – session-based wishlist page
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();


/* --- WISHLIST SESSION --- */
if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
  $_SESSION['wishlist'] = [];
}
$wishlist = &$_SESSION['wishlist'];

/* --- REMOVE ITEM --- */
if (isset($_GET['remove'])) {
  $id = urldecode($_GET['remove']);
  if (($idx = array_search($id, $wishlist, true)) !== false) {
    unset($wishlist[$idx]);
    $wishlist = array_values($wishlist); // re-index array
  }
  header("Location: wishlist.php");
  exit;
}

/* --- CLEAR ALL --- */
if (isset($_GET['clear']) && $_GET['clear'] === 'true') {
  $wishlist = [];
  header("Location: wishlist.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Wishlist</title>
  <link rel="stylesheet" href="shop.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .header-bar { display:flex; justify-content:space-between; align-items:center; margin:2rem 0 }
    .header-bar a { color:#f44336; text-decoration:none; font-size:.9rem }
    .empty { padding:3rem 0; text-align:center; font-size:1.1rem; color:#555 }
    .container.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
    .card { border: 1px solid #ccc; border-radius: 10px; padding: 1rem; text-align: center; }
    .img-box img { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }
    .ribbon { background: orange; padding: 2px 6px; font-size: 0.8rem; border-radius: 4px; display: inline-block; margin-top: 5px; }
    .actions { list-style: none; padding: 0; display: flex; justify-content: center; gap: 1rem; margin: 1rem 0; }
    .btn-buy { background: green; color: white; border: none; padding: 5px 10px; border-radius: 4px; }
    .btn-like { color: red; font-size: 1.2rem; }
    .buy-line { font-weight: bold; margin-top: 5px; }
  </style>
</head>
<body>

<!-- Page Header -->
<div class="container header-bar">
  <h1>🧡 Your Wishlist</h1>
  <div>
    <a href="shop.php"><i class="fa-solid fa-arrow-left"></i> Back to Shop</a> |
    <a href="?clear=true"><i class="fa-solid fa-trash"></i> Clear All</a>
  </div>
</div>

<!-- Wishlist Items -->
<?php if (empty($wishlist)): ?>
  <p class="empty">Your wishlist is empty. Add items from the shop ❤️</p>
<?php else: ?>
  <div class="container grid">
    <?php foreach ($items as $item): ?>
      <?php if (in_array($item[0], $wishlist, true)): ?>
        <div class="card">
          <div class="img-box">
            <img src="<?= htmlspecialchars($item[3]) ?>" alt="<?= htmlspecialchars($item[0]) ?>">
            <span class="ribbon"><?= htmlspecialchars($item[4]) ?></span>
          </div>
          <h3 class="title"><?= htmlspecialchars($item[0]) ?></h3>
          <p class="category-label"><?= htmlspecialchars($item[5]) ?></p>
          <ul class="actions">
            <li><button class="btn-buy">Buy Now</button></li>
            <li>
              <a href="?remove=<?= urlencode($item[0]) ?>" class="btn-like" title="Remove from wishlist">
                <i class="fa-solid fa-heart-crack"></i>
              </a>
            </li>
          </ul>
          <div class="buy-line">
            Rs <?= number_format($item[1], 2) ?>/<?= htmlspecialchars($item[2]) ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</body>
</html>
