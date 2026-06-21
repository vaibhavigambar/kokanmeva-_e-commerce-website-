<?php
session_start();
include("./config/db.php");

$session_id = session_id();
$success_msg = "";
$msg_type = "";

/* ================= UPDATE QUANTITY (STOCK SAFE) ================= */
if(isset($_POST['update_qty'])){

    $pid  = intval($_POST['product_id']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $requested_qty  = intval($_POST['quantity']);

    // Get real stock and product name
    if($type == 'product'){
        $stock_query = mysqli_query($conn, "SELECT stock, product_name FROM products WHERE id=$pid");
    } else {
        $stock_query = mysqli_query($conn, "SELECT stock, sub_name AS product_name FROM sub_products WHERE id=$pid");
    }

    $stock_data = mysqli_fetch_assoc($stock_query);
    $stock = $stock_data['stock'] ?? 0;
    $product_name = $stock_data['product_name'] ?? "Product";

    // Limit requested quantity to available stock
    if($requested_qty > $stock){
        $requested_qty = $stock;
        $success_msg = "⚠ Only $stock units available for <b>$product_name</b>!";
        $msg_type = "error";
    } else {
        $success_msg = "✅ Cart updated successfully!";
        $msg_type = "success";
    }

    // Update cart safely
    if($requested_qty > 0){
        mysqli_query($conn,
            "UPDATE cart SET quantity=$requested_qty 
             WHERE product_id=$pid 
             AND type='$type' 
             AND session_id='$session_id'"
        );
    }
}

/* ================= REMOVE ITEM ================= */
if(isset($_GET['remove']) && isset($_GET['type'])){

    $rid  = intval($_GET['remove']);
    $type = mysqli_real_escape_string($conn, $_GET['type']);

    mysqli_query($conn,
        "DELETE FROM cart 
         WHERE product_id=$rid 
         AND type='$type' 
         AND session_id='$session_id'"
    );

    $success_msg = "❌ Item removed from cart!";
    $msg_type = "error";
}

/* ================= FETCH CART WITH JOIN ================= */
$cart_items = mysqli_query($conn,
"
SELECT 
    cart.id AS cart_id,
    cart.product_id,
    cart.quantity,
    cart.type,

    CASE 
        WHEN cart.type='product' THEN products.product_name
        ELSE sub_products.sub_name
    END AS name,

    CASE 
        WHEN cart.type='product' THEN products.price
        ELSE sub_products.price
    END AS price,

    CASE 
        WHEN cart.type='product' THEN products.stock
        ELSE sub_products.stock
    END AS stock

FROM cart

LEFT JOIN products 
    ON cart.product_id = products.id 
    AND cart.type='product'

LEFT JOIN sub_products 
    ON cart.product_id = sub_products.id 
    AND cart.type='sub'

WHERE cart.session_id='$session_id'
");

$total = 0;

/* ================= SAFE CART COUNT ================= */
$count_query = mysqli_query($conn,
    "SELECT SUM(quantity) AS total 
     FROM cart WHERE session_id='$session_id'"
);

$count_data = mysqli_fetch_assoc($count_query);
$cart_count = isset($count_data['total']) ? $count_data['total'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Cart - Kokan Meva</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    padding:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

th{
    background:#2e7d32;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
}

tr:hover{
    background:#f9fff9;
}

.btn{
    padding:8px 15px;
    background:#2e7d32;
    color:white;
    text-decoration:none;
    border-radius:6px;
    border:none;
    cursor:pointer;
}

.btn:hover{
    background:#1b5e20;
}

.remove{
    color:red;
    font-weight:bold;
    text-decoration:none;
}

.qty-box{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:5px;
}

.qty-box input{
    width:50px;
    text-align:center;
    padding:5px;
}

.qty-btn{
    padding:5px 10px;
    background:#2e7d32;
    color:white;
    border:none;
    cursor:pointer;
}

.message{
    padding:12px;
    margin-bottom:15px;
    border-radius:6px;
    font-weight:bold;
}

.success{
    background:#d4edda;
    color:#155724;
}

.error{
    background:#fdecea;
    color:#b71c1c;
}
</style>

<script>
setTimeout(function(){
    var msg = document.getElementById("msgBox");
    if(msg){
        msg.style.display="none";
    }
},3000);
</script>

</head>
<body>

<h2>🛒 Your Cart (<?php echo $cart_count; ?> items)</h2>

<?php if($success_msg != ""){ ?>
<div id="msgBox" class="message <?php echo $msg_type; ?>">
    <?php echo $success_msg; ?>
</div>
<?php } ?>

<a href="products.php" class="btn">⬅ Continue Shopping</a>

<br><br>

<table border="1">
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Available Stock</th>
    <th>Total</th>
    <th>Remove</th>
</tr>

<?php if(mysqli_num_rows($cart_items) > 0){ ?>

<?php while($item = mysqli_fetch_assoc($cart_items)) {

    // Auto delete broken items
    if($item['price'] == NULL){
        mysqli_query($conn, "DELETE FROM cart WHERE id=".$item['cart_id']);
        continue;
    }

    $price = floatval($item['price']);
    $qty   = intval($item['quantity']);
    $available_stock = intval($item['stock']);
    $subtotal = $price * $qty;
    $total += $subtotal;
?>

<tr>
    <td><?php echo $item['name']; ?></td>
    <td>₹<?php echo number_format($price,2); ?></td>
    <td>
        <form method="POST" class="qty-box">
            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
            <input type="hidden" name="type" value="<?php echo $item['type']; ?>">
            <button class="qty-btn" type="button" onclick="this.nextElementSibling.stepDown()">−</button>
            <input type="number" name="quantity" value="<?php echo $qty; ?>" min="1" max="<?php echo $available_stock; ?>">
            <button class="qty-btn" type="button" onclick="this.previousElementSibling.stepUp()">+</button>
            <button class="btn" name="update_qty">Update</button>
        </form>
    </td>
    <td><?php echo $available_stock; ?></td>
    <td>₹<?php echo number_format($subtotal,2); ?></td>
    <td>
        <a class="remove" 
           href="cart.php?remove=<?php echo $item['product_id']; ?>&type=<?php echo $item['type']; ?>">
           ❌
        </a>
    </td>
</tr>

<?php } ?>

<tr>
    <td colspan="4"><b>Grand Total</b></td>
    <td colspan="2"><b>₹<?php echo number_format($total,2); ?></b></td>
</tr>

<?php } else { ?>

<tr>
    <td colspan="6"><b>Your cart is empty 😔</b></td>
</tr>

<?php } ?>

</table>

<?php if($total > 0){ ?>
<br>
<a href="checkout.php" class="btn">✅ Proceed to Checkout</a>
<?php } ?>

</body>
</html>