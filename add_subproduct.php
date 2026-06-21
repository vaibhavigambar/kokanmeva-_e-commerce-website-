<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit(); }

// Add subproduct
if(isset($_POST['add'])){
    $product_id = $_POST['product_id'];
    $sub_name = $_POST['sub_name'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    mysqli_query($conn,"INSERT INTO sub_products (product_id, sub_name, price, availability) VALUES('$product_id','$sub_name','$price','$availability')");
    header("Location:add_subproduct.php?success=1");
    exit();
}

// Fetch products for dropdown
$products = mysqli_query($conn,"SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head><title>Add Sub-Product</title></head>
<body>
<h2>Add Sub-Product</h2>
<?php if(isset($_GET['success'])) echo "<p style='color:green'>Sub-Product Added Successfully!</p>"; ?>

<form method="POST">
    Select Product:<br>
    <select name="product_id" required>
        <option value="">-- Select Product --</option>
        <?php while($p = mysqli_fetch_assoc($products)){ ?>
            <option value="<?php echo $p['id']; ?>"><?php echo $p['product_name']; ?></option>
        <?php } ?>
    </select><br><br>

    Sub-Product Name:<br>
    <input type="text" name="sub_name" required><br><br>

    Price:<br>
    <input type="text" name="price" required><br><br>

    Availability:<br>
    <select name="availability">
        <option value="Available">Available</option>
        <option value="Not Available">Not Available</option>
    </select><br><br>

    <button name="add">Add Sub-Product</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>