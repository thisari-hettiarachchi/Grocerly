<?php
    include '../components/connect.php';

    session_start();

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    ini_set('display_errors', '1');

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("SELECT seller_id, name FROM sellers WHERE email = ?");
        $stmt->execute([$email]);
        $seller = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($seller) {
            $seller_id = $seller['seller_id'];
            $seller_name = $seller['name'];
        } else {
            $seller_id = null;
            $seller_name = null;
        }
    } else {
        header("Location: login.php");
        exit();
    }
    //save as in database
$success_msg[] = '';

if (isset($_POST['publish'])) {
    $status = 'active';
    $success_msg[] = 'Product published successfully!';
} elseif (isset($_POST['draft'])) {
    $status = 'deactive';
    $success_msg[] = 'Product saved as draft!';
}


if (isset($_POST['publish']) || isset($_POST['draft'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $price = htmlspecialchars(trim($_POST['price']));
    $stock = htmlspecialchars(trim($_POST['stock']));
    $category = htmlspecialchars(trim($_POST['category']));
    $description = htmlspecialchars(trim($_POST['content']));
    $brand = htmlspecialchars(trim($_POST['brand']));
    $sizes = htmlspecialchars(trim($_POST['sizes']));

    // Image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $image;

    if (!empty($image)) {
        move_uploaded_file($image_tmp, $image_folder);
    } else {
        $image = '';
    }

   $stmt = $conn->prepare("INSERT INTO product
(seller_id, name, price, stock, image, status, category, description)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $seller_id, $name, $price, $stock, $image,
    $status, $category, $description
]);

}

?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title> Add Product </title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        <link rel="stylesheet" type="text/css" href="../css/add_product.css">
        
        <link rel="shortcut icon" href="../images/fav.png" type="image/svg+xml">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <link href="https://unpkg.com/boxicons@2.1/css/boxicons.min.css" rel="stylesheet">
        
    </head>

    <body>
        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
        </div>

        <div class="main-container">
            <?php include '../components/admin_header.php'; ?>
            <section class="add_products">
                <div class="heading">
                    <h1>Add Product</h1>
                </div>
                <div class="form-add-container">
                    <form action="" method="post" enctype="multipart/form-data" class="Register">
                        <div class="Flex">
                            <div class="Col">
                                <div class="Input-field">
                                    <p>Product name<span>*</span></p>
                                    <input type="text" name="name" maxlength="100" placeholder="add product name" required class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Product price<span>*</span></p>
                                    <input type="number" name="price" maxlength="100" placeholder="add product price" required class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Total stock<span>*</span></p>
                                    <input type="number" name="stock" maxlength="10" min="0" max="9999999999" placeholder="add product stock" required class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Product Image<span>*</span></p>
                                    <input type="file" name="image" accept="image/*" class="Box">
                                </div>
                            </div>
                        </div>
                        <div class="Input-field">
                            <p>Product category<span>*</span></p>
                            <select name="category" required class="Box">
                                <option disabled selected>select product category</option>
                                <option value="Fruits items">Fruits</option>
                                <option value="Vegitable items">Vegitables</option>
                                <option value="Dairy & Beverage items">Dairy and Beverage</option>
                                <option value="Snack items">Snacks</option>
                                <option value="Bakery items">Bakery Items</option>
                                <option value="Meat & SeaFood items">Meat & SeaFood</option>
                                <option value="Personal Care items">Personal Care</option>
                                <option value="Household items ">House Hold</option>
                            </select>
                        </div>

                        <div class="Input-field">
                                     <p>Brand</p>
                                     <input type="text" name="brand" maxlength="100" placeholder="e.g. Nestle, Kellogg’s" class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Available Sizes</p>
                                    <input type="text" name="sizes" maxlength="255" placeholder="e.g. S, M, L or 500ml, 1L" class="Box">
                                </div>

                        <div class="Input-field">
                            <p>Product Description<span>*</span></p>
                            <textarea class="Box" name="content" placeholder="Product Description" required></textarea>
                        </div>
                        <div class="Flex-btn">
                            <button type="submit" name="publish" class="Btn">Publish</button>
                            <button type="submit" name="draft" class="Btn">Save as draft</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <script src="../js/admin.js"></script>

        <?php include('../components/alert.php'); ?>
        
    </body>
    
</html>
