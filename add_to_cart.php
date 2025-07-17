<?php
session_start();
header('Content-Type: application/json');

require_once 'config/database.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}

$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

if ($product_id <= 0) {
    $response['message'] = 'Invalid product ID';
    echo json_encode($response);
    exit();
}

try {
    $database = new ZestyZone();
    $db = $database->getConnection();
    
    // Check if product exists and is available
    $query = "SELECT * FROM products WHERE id = ? AND status = 'active'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        $response['message'] = 'Product not found';
        echo json_encode($response);
        exit();
    }
    
    // Check stock availability
    $current_cart_quantity = $_SESSION['cart'][$product_id] ?? 0;
    $total_requested = $current_cart_quantity + $quantity;
    
    if ($total_requested > $product['stock_quantity']) {
        $available = $product['stock_quantity'] - $current_cart_quantity;
        $response['message'] = $available > 0 
            ? "Only $available more items available" 
            : 'Product is out of stock';
        echo json_encode($response);
        exit();
    }
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add to cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    $response['success'] = true;
    $response['message'] = 'Product added to cart successfully';
    $response['cart_count'] = array_sum($_SESSION['cart']);
    
} catch (Exception $e) {
    $response['message'] = 'Error adding product to cart: ' . $e->getMessage();
}

echo json_encode($response);
?>