<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_date DESC");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
    font-family:Segoe UI;
    background:#f1f8e9;
    margin:0;
    padding:20px;
}
h2{
    text-align:center;
    color:#1b5e20;
}
.order-card{
    background:white;
    padding:25px;
    margin:25px auto;
    max-width:650px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}
.status-badge{
    padding:6px 15px;
    border-radius:20px;
    color:white;
    font-weight:bold;
}
.print-btn{
    margin-top:15px;
    padding:10px 18px;
    background:black;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.back-btn{
    padding:8px 15px;
    margin-bottom:20px;
    background:#1b5e20;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
</style>
</head>
<body>

<h2>📦 My Orders</h2>

<!-- Back Button -->
<button class="back-btn" onclick="window.location.href='index.php'">← Back</button>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){

        $status = $row['delivery_status'] ?? 'Order Placed';
        $color = "#616161";
        if($status=="Packed") $color="#fb8c00";
        elseif($status=="Shipped") $color="#1e88e5";
        elseif($status=="Delivered") $color="#2e7d32";

        // Use demo products (quick solution)
        $products_demo = "Cashew 1kg x2, Mango x1"; 
?>

<div class="order-card">
<p><strong>Order ID:</strong> <?php echo $row['id']; ?></p>
<p><strong>Products:</strong> <?php echo $products_demo; ?></p>
<p><strong>Total Amount:</strong> ₹<?php echo $row['final_total']; ?></p>
<p>
<strong>Status:</strong>
<span class="status-badge" style="background:<?php echo $color; ?>">
<?php echo $status; ?>
</span>
</p>
<p><strong>Date:</strong> <?php echo $row['order_date']; ?></p>

<a href="print_invoice.php?order_id=<?php echo $row['id']; ?>&products=<?php echo urlencode($products_demo); ?>" target="_blank">
<button class="print-btn">🖨 Print Invoice</button>
</a>
</div>

<?php
    }
}else{
    echo "<p>No Orders Found</p>";
}
?>

</body>
</html>