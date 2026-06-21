<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// ID check
if(!isset($_GET['id'])){
    header("Location: manage_products.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch ONLY ONE product
$result = mysqli_query($conn,"SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);

if(!$product){
    echo "Product not found!";
    exit();
}

// Update logic
if(isset($_POST['update'])){

    $name = mysqli_real_escape_string($conn,$_POST['product_name']);
    $price = mysqli_real_escape_string($conn,$_POST['price']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $availability = mysqli_real_escape_string($conn,$_POST['availability']);
    $stock = intval($_POST['stock']);   // ✅ STOCK ADDED

    $update = "UPDATE products 
               SET product_name='$name',
                   description='$description',
                   price='$price',
                   availability='$availability',
                   stock='$stock'      -- ✅ STOCK UPDATE
               WHERE id=$id";

    if(mysqli_query($conn,$update)){
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Update failed: ".mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<style>
body{
    font-family: Arial, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 0;
}

h2{
    text-align: center;
    margin-top: 30px;
    color: #2e7d32;
}

form{
    width: 400px;
    margin: 30px auto;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

input[type="text"],
input[type="number"],
textarea,
select{
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
select:focus{
    border-color: #2e7d32;
    box-shadow: 0 0 5px rgba(46,125,50,0.3);
    outline: none;
}

textarea{
    resize: none;
    height: 80px;
}

input[type="submit"]{
    width: 100%;
    padding: 12px;
    background-color: #2e7d32;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

input[type="submit"]:hover{
    background-color: #1b5e20;
}

a{
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    color: #555;
    font-weight: 500;
}

a:hover{
    color: #2e7d32;
}
</style>
</head>

<body>

<h2>Edit Product</h2>

<form method="POST">

Name:<br>
<input type="text" name="product_name" 
value="<?php echo $product['product_name']; ?>"><br><br>

Description:<br>
<textarea name="description"><?php echo $product['description']; ?></textarea><br><br>

Price:<br>
<input type="text" name="price" 
value="<?php echo $product['price']; ?>"><br><br>

Availability:<br>
<select name="availability">
    <option value="Available"
        <?php if($product['availability']=="Available") echo "selected"; ?>>
        Available
    </option>
    <option value="Out of Stock"
        <?php if($product['availability']=="Out of Stock") echo "selected"; ?>>
        Out of Stock
    </option>
</select><br><br>

<!-- ✅ NEW STOCK FIELD -->
Stock:<br>
<input type="number" name="stock" min="0"
value="<?php echo $product['stock']; ?>"><br><br>

<input type="submit" name="update" value="Update Product">

</form>

<br>
<a href="manage_products.php">Back</a>

</body>
</html>