<?php
session_start();
include("./config/db.php");

/* ================= LOGIN CHECK ================= */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php?message=login_required");
    exit();
}

$user_id   = $_SESSION['user_id'];
$session_id = session_id();

/* ================= FETCH CART ================= */
$cart_query = mysqli_query($conn,"SELECT * FROM cart WHERE session_id='$session_id'");

if(mysqli_num_rows($cart_query) == 0){
    echo "<h2 style='color:red;text-align:center;'>Your cart is empty!</h2>";
    exit();
}

$total = 0;
$cart_data = [];

while($item = mysqli_fetch_assoc($cart_query)){

    if($item['type'] == 'product'){
        $product = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT product_name AS name, price FROM products WHERE id=".$item['product_id']
        ));
    }else{
        $product = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT sub_name AS name, price FROM sub_products WHERE id=".$item['product_id']
        ));
    }

    $item['name']  = $product['name'];
    $item['price'] = $product['price'];
    $item['subtotal'] = $product['price'] * $item['quantity'];

    $total += $item['subtotal'];
    $cart_data[] = $item;
}

/* ================= PLACE ORDER ================= */
if(isset($_POST['place_order'])){

    $name     = mysqli_real_escape_string($conn,$_POST['name']);
    $email    = mysqli_real_escape_string($conn,$_POST['email']);
    $address  = mysqli_real_escape_string($conn,$_POST['address']);
    $district = mysqli_real_escape_string($conn,$_POST['district']);
    $taluka   = mysqli_real_escape_string($conn,$_POST['taluka']);
    $delivery = floatval($_POST['delivery_charges']);
    $payment  = mysqli_real_escape_string($conn,$_POST['payment_method']);

    $final_total = $total + $delivery;
    $status = "Order Placed";

    $stmt = $conn->prepare("INSERT INTO orders
        (user_id,name,email,address,district,taluka,delivery_charges,final_total,payment_method,delivery_status,order_date)
        VALUES (?,?,?,?,?,?,?,?,?,?,NOW())");

    $stmt->bind_param("isssssddss",
        $user_id,
        $name,
        $email,
        $address,
        $district,
        $taluka,
        $delivery,
        $final_total,
        $payment,
        $status
    );

    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach($cart_data as $item){

        $stmt2 = $conn->prepare("INSERT INTO order_items
            (order_id,product_id,quantity,price,date_created)
            VALUES (?,?,?,?,NOW())");

        $stmt2->bind_param("iiid",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );

        $stmt2->execute();
    }

    mysqli_query($conn,"DELETE FROM cart WHERE session_id='$session_id'");

    echo "<script>
        alert('Order Placed Successfully!');
        window.location='my_orders.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout - Kokan Meva</title>

<style>
body{font-family:Segoe UI;background:#f4f6f9;padding:20px;}
h2,h3{text-align:center;color:#2e7d32;}

table{
    width:85%;
    margin:20px auto;
    border-collapse:collapse;
    background:#fff;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
th,td{padding:12px;text-align:center;border:1px solid #ddd;}
th{background:#2e7d32;color:#fff;}

form{
    width:420px;
    margin:30px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

input,textarea,select{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    width:100%;
    padding:12px;
    background:#2e7d32;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{background:#1b5e20;}
</style>
</head>
<body>

<h2>🛒 Checkout</h2>

<h3>Your Cart</h3>
<table>
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>

<?php foreach($cart_data as $item){ ?>
<tr>
    <td><?php echo $item['name']; ?></td>
    <td><?php echo $item['quantity']; ?></td>
    <td>₹<?php echo $item['price']; ?></td>
    <td>₹<?php echo $item['subtotal']; ?></td>
</tr>
<?php } ?>

<tr>
    <td colspan="3"><b>Cart Total</b></td>
    <td><b>₹<?php echo $total; ?></b></td>
</tr>
</table>

<h3>Billing Details</h3>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email Address" required>
<textarea name="address" placeholder="Full Address" required></textarea>

<select name="district" id="district" onchange="updateTaluka()" required>
    <option value="">Select District</option>
    <option value="Ratnagiri">Ratnagiri</option>
    <option value="Sindhudurg">Sindhudurg</option>
    <option value="Pune">Pune</option>
    <option value="Thane">Thane</option>
     <option value="Mumbai">Mumbai</option>


    

</select>

<select name="taluka" id="taluka" onchange="updateDelivery()" required>
    <option value="">Select area</option>
</select>

<input type="text" name="delivery_charges" id="delivery_charges" placeholder="Delivery Charges" readonly>
<input type="text" id="final_total_box" placeholder="Final Total" readonly>

<select name="payment_method" required>
    <option value="">Select Payment Method</option>
    <option value="Cash on Delivery">Cash on Delivery</option>
</select>

<button type="submit" name="place_order">Place Order</button>

</form>

<script>
function updateTaluka(){

    var district = document.getElementById("district").value;
    var taluka = document.getElementById("taluka");

    taluka.innerHTML = "<option value=''>Select Area</option>";
    document.getElementById("delivery_charges").value = "";
    document.getElementById("final_total_box").value = "";

    if(district == "Ratnagiri"){
        taluka.innerHTML += "<option value='Mandangad'>Mandangad</option>";
        taluka.innerHTML += "<option value='Dapoli'>Dapoli</option>";
        taluka.innerHTML += "<option value='Khed'>Khed</option>";
    }

    else if(district == "Sindhudurg"){
        taluka.innerHTML += "<option value='Kankavli'>Kankavli</option>";
        taluka.innerHTML += "<option value='Sawantwadi'>Sawantwadi</option>";
    }

    else if(district == "Pune"){
        taluka.innerHTML += "<option value='Shirur'>Shirur</option>";
        taluka.innerHTML += "<option value='Bhor'>Bhor</option>";
        taluka.innerHTML += "<option value='Mulshi'>Mulshi</option>";
        taluka.innerHTML += "<option value='Maval'>Maval</option>";
        taluka.innerHTML += "<option value='velhe'>Velhe</option>";
        taluka.innerHTML += "<option value='Ambegaon'>Ambegaon</option>";

    }

    else if(district == "Thane"){
        taluka.innerHTML += "<option value='Thane'>Thane</option>";
        taluka.innerHTML += "<option value='Kalyan'>Kalyan</option>";
        taluka.innerHTML += "<option value='Bhiwandi'>Bhiwandi</option>";
        taluka.innerHTML += "<option value='shahapur'>Shahapur</option>";
        taluka.innerHTML += "<option value='Murbad'>Murbad</option>";
        taluka.innerHTML += "<option value='Ambernath'>Ambernath</option>";
        taluka.innerHTML += "<option value='Ulhasnagar'>Ulhasnagar</option>";
        
    }

    else if(district == "Mumbai"){
        taluka.innerHTML += "<option value='Andheri'>Andheri</option>";
        taluka.innerHTML += "<option value='Bandra'>Bandra</option>";
        taluka.innerHTML += "<option value='Borivali'>Borivali</option>";
        taluka.innerHTML += "<option value='Kandivali'>Kandivali</option>";
        taluka.innerHTML += "<option value='Malad'>Malad</option>";
        taluka.innerHTML += "<option value='Goregaon'>Goregaon</option>";
        taluka.innerHTML += "<option value='Jogeshwari'>Jogeshwari</option>";
        taluka.innerHTML += "<option value='Santacruz'>Santacruz</option>";
        taluka.innerHTML += "<option value='Dadar'>Dadar</option>";
        taluka.innerHTML += "<option value='Kurla'>Kurla</option>";
        taluka.innerHTML += "<option value='Ghatkopar'>Ghatkopar</option>";
        taluka.innerHTML += "<option value='Vashi'>Vashi</option>";
        taluka.innerHTML += "<option value='Nerul'>Nerul</option>";
        taluka.innerHTML += "<option value='Churchgate'>Churchgate</option>";
        
    }

}

function updateDelivery(){

    var taluka = document.getElementById("taluka").value;
    var delivery = 0;

    if(taluka == "") return;

    if(taluka == "Mandangad") delivery = 50;
    else if(taluka == "Dapoli") delivery = 70;
    else if(taluka == "Khed") delivery = 80;
    else if(taluka == "Kankavli") delivery = 60;
    else if(taluka == "Sawantwadi") delivery = 90;
    else delivery = 100;

    document.getElementById("delivery_charges").value = delivery;

    var total = <?php echo $total; ?>;
    document.getElementById("final_total_box").value = total + delivery;
}
</script>

</body>
</html>