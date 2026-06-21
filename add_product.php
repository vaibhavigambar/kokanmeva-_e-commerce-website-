<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['submit'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = intval($_POST['stock']);
    $unit_type = mysqli_real_escape_string($conn, $_POST['unit_type']);

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

        $imageName = $_FILES['image']['name'];
        $tempName  = $_FILES['image']['tmp_name'];
        $fileSize  = $_FILES['image']['size'];

        $allowedTypes = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        if(!in_array($ext, $allowedTypes)){
            $message = "Only JPG, JPEG, PNG, WEBP files allowed!";
        }
        elseif($fileSize > 2*1024*1024){
            $message = "File size must be less than 2MB!";
        }
        else{

            $uploadFolder = "../images/";
            if(!is_dir($uploadFolder)){
                mkdir($uploadFolder,0777,true);
            }

            $newImageName = time() . "_" . $imageName;

            if(move_uploaded_file($tempName, $uploadFolder . $newImageName)){

                // ✅ status removed
                $query = "INSERT INTO products 
                (product_name, description, price, stock, unit_type, product_image) 
                VALUES 
                ('$name','$description','$price','$stock','$unit_type','$newImageName')";

                if(mysqli_query($conn, $query)){
                    header("Location:add_product.php?success=1");
                    exit();
                }else{
                    $message = "Database Error: " . mysqli_error($conn);
                }

            }else{
                $message = "Image upload failed!";
            }
        }

    }else{
        $message = "Please select an image!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<style>
    body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #fdf6e3, #e6ffe6);
}

/* HEADER */
header {
    background: linear-gradient(to right, #006400, #228b22);
    padding: 15px 50px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: white;
    font-size: 24px;
    font-weight: bold;
}

nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 500;
}

nav a:hover {
    color: #ffd700;
}

/* HERO SECTION */
.hero {
    text-align: center;
    padding: 100px 20px;
    background: url('../images/kokan.jpg') no-repeat center center/cover;
    color: white;
}

.hero h1 {
    font-size: 40px;
}

.hero p {
    font-size: 18px;
    margin: 15px 0;
}

.btn {
    padding: 10px 20px;
    background: #ff8c00;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.btn:hover {
    background: #ff4500;
}

/* FEATURES SECTION */
.features {
    display: flex;
    justify-content: center;
    padding: 50px;
    gap: 30px;
}

.card {
    background: white;
    padding: 25px;
    width: 250px;
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-10px);
}

/* ABOUT SECTION */
.about {
    padding: 80px;
    text-align: center;
}

/* FOOTER */
footer {
    background: #006400;
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: 50px;
}
</style>
</head>
<body>

<h2>Add Product</h2>

<?php 
if(isset($_GET['success'])){
    echo "<p style='color:green;'>Product Added Successfully!</p>";
}
if(!empty($message)){
    echo "<p style='color:red;'>$message</p>";
}
?>

<form action="" method="POST" enctype="multipart/form-data">

Name:<br>
<input type="text" name="name" required><br><br>

Description:<br>
<textarea name="description" required></textarea><br><br>

Price:<br>
<input type="number" name="price" required><br><br>

Stock:<br>
<input type="number" name="stock" min="0" required><br><br>

Unit Type:<br>
<select name="unit_type" required>
    <option value="">Select Unit</option>
    <option value="kg">KG</option>
    <option value="dozen">Dozen</option>
    <option value="piece">Piece</option>
    <option value="bottle">Bottle</option>
</select><br><br>

Image:<br>
<input type="file" name="image" required><br><br>

<input type="submit" name="submit" value="Add Product">

</form>

<a href="manage_products.php">Back</a>

</body>
</html>