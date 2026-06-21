<?php
session_start();
include("./config/db.php");

$session_id = session_id();
$success_msg = "";

/* ================= ADD TO CART ================= */
if(isset($_POST['add_cart'])){

    $id   = intval($_POST['product_id']);
    $type = "product";

    // 🔍 Check if already in cart
    $check = mysqli_query($conn,
        "SELECT * FROM cart 
         WHERE product_id=$id 
         AND session_id='$session_id'"
    );

    if($check && mysqli_num_rows($check) > 0){
        // Update quantity in cart table
        mysqli_query($conn,
            "UPDATE cart 
             SET quantity = quantity + 1 
             WHERE product_id=$id 
             AND session_id='$session_id'"
        );
    } else {
        // Insert into cart table
        mysqli_query($conn,
            "INSERT INTO cart (session_id, product_id, type, quantity) 
             VALUES ('$session_id', $id, '$type', 1)"
        );
    }

    $success_msg = "✅ Added to Cart!";
}

/* ================= FETCH PRODUCTS ================= */
$products = mysqli_query($conn,
    "SELECT * FROM products ORDER BY product_name DESC"
);

/* ================= CART COUNT ================= */
$cart_count = 0;

$count_query = mysqli_query($conn,
    "SELECT SUM(quantity) AS total FROM cart WHERE session_id='$session_id'"
);

if($count_query){
    $count_data = mysqli_fetch_assoc($count_query);
    $cart_count = $count_data['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Our Products - Kokan Meva</title>

<style>
/* Your existing CSS here */
body{ font-family:Segoe UI; background:linear-gradient(to right,#f1f8e9,#ffffff); margin:0; }
header{ background:linear-gradient(90deg,#1b5e20,#2e7d32); padding:12px 60px; display:flex; justify-content:space-between; align-items:center; }
header h2{color:white;}
nav a{ color:white; text-decoration:none; margin-left:18px; font-weight:bold; }
.page-title{ padding:25px 60px; font-size:24px; display:flex; justify-content:space-between; align-items:center; }
.success-msg{ margin:0 60px 20px; padding:12px; background:#d4edda; color:#155724; border-radius:8px; font-weight:bold; }
.view-cart-btn{ background:#2e7d32; color:white; padding:8px 18px; border-radius:25px; text-decoration:none; font-weight:bold; }
.cart-badge{ background:red; padding:4px 8px; border-radius:50%; font-size:12px; margin-left:5px; }
.products-container{ display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:30px; padding:0 60px 60px; }
.product-box{ background:white; padding:20px; border-radius:15px; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
.product-box img{ width:100%; height:200px; object-fit:cover; border-radius:12px; }
button{ width:100%; padding:10px; border:none; border-radius:8px; margin-top:10px; cursor:pointer; font-weight:bold; }
button[name="add_cart"]{ background:#2e7d32; color:white; }
button[name="add_cart"]:hover{ background:#1b5e20; }
button:disabled{ background:gray; cursor:not-allowed; }
</style>

<script>
setTimeout(function(){
    var msg = document.getElementById("msgBox");
    if(msg){ msg.style.display="none"; }
},3000);
</script>

</head>
<body>

<header>
    <h2>🥭 Kokan Meva</h2>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="products.php">Products</a>
        <a href="contact.php">Contact</a>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="my_orders.php">My Orders</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php } ?>
    </nav>
</header>

<div class="page-title">
    🌴 Our Fresh Konkan Products
    <a href="cart.php" class="view-cart-btn">
        🛒 View Cart
        <span class="cart-badge"><?php echo $cart_count; ?></span>
    </a>
</div>

<?php if($success_msg != "") { ?>
    <div id="msgBox" class="success-msg">
        <?php echo $success_msg; ?>
    </div>
<?php } ?>

<div class="products-container">

<?php while($product = mysqli_fetch_assoc($products)) { 

    // ✅ Calculate available stock from cart table
    $cart_query = mysqli_query($conn, "SELECT SUM(quantity) AS inCart FROM cart WHERE product_id={$product['id']}");
    $cart_data = mysqli_fetch_assoc($cart_query);
    $inCart = $cart_data['inCart'] ?? 0;

    $availableStock = $product['stock'] - $inCart;

?>

<div class="product-box">

    <h3><?php echo $product['product_name']; ?></h3>

    <?php if(!empty($product['product_image'])) { ?>
        <img src="images/<?php echo $product['product_image']; ?>">
    <?php } else { ?>
        <img src="images/no-image.png">
    <?php } ?>

    <p><b>Price:</b> ₹<?php echo $product['price']; ?> / <?php echo strtoupper($product['unit_type']); ?></p>
    <p><?php echo $product['description']; ?></p>

    <p><b>Available Stock:</b> <?php echo $availableStock; ?></p>

    <?php if($availableStock > 0) { ?>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button name="add_cart">🛒 Add to Cart</button>
        </form>
    <?php } else { ?>
        <button disabled>Out of Stock</button>
    <?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>