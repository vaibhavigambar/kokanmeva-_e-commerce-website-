<?php
session_start();
include("config/db.php");

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

/* ===== FETCH PRODUCT NAME ===== */
$product = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM products WHERE id=$product_id")
);

/* ===== FETCH SUB PRODUCTS ===== */
$subproducts = mysqli_query($conn,
    "SELECT * FROM sub_products WHERE product_id=$product_id"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Items - Kokan Meva</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(to right,#fff8e1,#e8f5e9);
}

.container{
    width:80%;
    margin:auto;
    text-align:center;
}

.card{
    background:white;
    padding:20px;
    margin:20px;
    border-radius:10px;
    box-shadow:0 0 10px #ccc;
}

button{
    background:#2e7d32;
    color:white;
    padding:8px 15px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#1b5e20;
}
</style>
</head>

<body>

<div class="container">

<h2>🌴 <?php echo $product['product_name']; ?> - Available Options</h2>

<?php if(mysqli_num_rows($subproducts) > 0){ ?>

    <?php while($sub = mysqli_fetch_assoc($subproducts)) { ?>

        <div class="card">
            <h3><?php echo $sub['sub_name']; ?></h3>
            <p><b>Price:</b> ₹<?php echo $sub['price']; ?></p>

            <form method="POST" action="products.php">
                <input type="hidden" name="product_id" value="<?php echo $sub['id']; ?>">
                <input type="hidden" name="type" value="sub">
                <button name="add_cart">🛒 Add to Cart</button>
            </form>
        </div>

    <?php } ?>

<?php } else { ?>

    <p>No options available for this product.</p>

<?php } ?>

<br>
<a href="products.php">
    <button>⬅ Back to Products</button>
</a>

</div>

</body>
</html>