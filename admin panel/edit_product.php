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
$success_message = '';

if (isset($_POST['update']) || isset($_POST['draft'])) {
    $product_id = $_POST['product_id'];
    $status = isset($_POST['update']) ? 'active' : 'deactive';

    $name = htmlspecialchars(trim($_POST['name']));
    $price = htmlspecialchars(trim($_POST['price']));
    $stock = htmlspecialchars(trim($_POST['stock']));
    $category = htmlspecialchars(trim($_POST['category']));
    $description = htmlspecialchars(trim($_POST['content']));
    $brand = htmlspecialchars(trim($_POST['brand']));
    $sizes = htmlspecialchars(trim($_POST['sizes']));

    $update_product = $conn->prepare("UPDATE `product` SET name=?, price=?, category=?, description=?, stock=?, status=?, brand=?, sizes=? WHERE product_id = ?");
    $update_product->execute([$name, $price, $category, $description, $stock, $status, $brand, $sizes, $product_id]);

    // Image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $image;

    if (!empty($image)) {
        move_uploaded_file($image_tmp, $image_folder);
        $update_image = $conn->prepare("UPDATE `product` SET image=? WHERE product_id=?");
        $update_image->execute([$image, $product_id]);
    }

    $success_message = ($status == 'active') ? 'Product updated successfully!' : 'Product saved as draft!';
}


?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title> Edit Product </title>

        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
        <link rel="stylesheet" type="text/css" href="../css/add_product.css">
        <link rel="stylesheet" type="text/css" href="../css/edit_product.css">

        
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
            <section class="edit_products">
                <div class="heading">
                    <h1>Edit Product</h1>
                </div>
                <div class="container">
                    <?php 
                    $product_id=$_GET['product_id'];
                    $select_product = $conn->prepare("SELECT * FROM `product` WHERE product_id =?");
                    $select_product->execute([$product_id]);

                    if ($select_product->rowCount()>0){
                        $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

                     ?>
                    <div class="form-add-container">
                    <form action="" method="post" enctype="multipart/form-data" class="Register">
                        <input type="hidden" name="product_id" value="<?= $fetch_product['product_id'];?>">
                        <div class="Flex">
                            <div class="Col">
                                <div class="Input-field">
                                    <p>Product name<span>*</span></p>
                                    <input type="text" name="name" maxlength="100" value="<?= $fetch_product['name'];?>" class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Product price<span>*</span></p>
                                    <input type="number" name="price" maxlength="100" value="<?= $fetch_product['price'];?>" class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Total stock<span>*</span></p>
                                    <input type="number" name="stock" maxlength="10" min="0" max="9999999999" value="<?= $fetch_product['stock'];?>" class="Box">
                                </div>
                                <div class="Input-field">
                                <p>Product category<span>*</span></p>                                      
                                 <select name="category" required class="Box">
                                      <option selected value="<?= $fetch_product['category'];?>"><?= $fetch_product['category'];?></option>
                                      <option value="Fruits items">Fruits</option>
                                      <option value="Vegitable items">Vegitables</option>
                                      <option value="Dairy & Beverage items">Dairy & Beverage</option>
                                      <option value="Snack items">Snacks</option>
                                      <option value="Bakery items">Bakery Items</option>
                                      <option value="Meat & SeaFood items">Meat & SeaFood</option>
                                      <option value="Personal Care items">Personal Care</option>
                                      <option value="Household items ">House Hold</option>
                                 </select>
                               </div>
                                <div class="Input-field">
                                    <p>Product Image<span>*</span></p>
                                    <input type="file" name="image" accept="image/*" class="Box">
                                </div>
                                <div class="Input-field">
                                <p>Product status<span>*</span></p>                                      
                                 <select name="status" required class="Box">
                                      <option selected value="<?= $fetch_product['status'];?>"><?= $fetch_product['status'];?></option>
                                      <option value="Active">Active</option>
                                      <option value="Deactive">Deactive</option>
                                 </select>
                               </div>
                               <div class="Input-field">
                                     <p>Brand</p>
                                     <input type="text" name="brand" maxlength="100" value="<?= $fetch_product['brand'];?>"  class="Box">
                                </div>
                                <div class="Input-field">
                                    <p>Available Sizes</p>
                                    <input type="text" name="sizes" maxlength="255"value="<?= $fetch_product['sizes'];?>" class="Box">
                                </div>
                            </div>
                        </div>

                        <div class="Input-field">
                            <p>Product Description<span>*</span></p>
                            <textarea class="Box" name="content" ><?= $fetch_product['description'];?></textarea>
                        </div>
                        <div class="Flex-btn">
                            <button type="submit" name="update" class="Btn">Ubdate</button>
                            <button type="submit" name="draft" class="Btn">Save as draft</button>
                            <a href="view_product.php?post_id=<?= $fetch_product['id'];?>" class="Btn">Go back</a>
                        </div>
                    </form>
                </div>
                     <?php
                        }else{
                      echo'                       
                      <div class="empty">
                             <p>no products added yet! <br> <a href="add_product.php" class="btn" style="margin-topL: 1rem;">add product</a></p>
                         </div>                 
                      ';
                    }
                      
                      ?>

                </div>
            </section>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

        <script src="../js/admin.js"></script>

        <?php include('../components/alert.php'); ?>
        
    </body>
    
</html>
