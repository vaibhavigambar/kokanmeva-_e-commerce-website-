<?php
session_start();
include("config/db.php");

if(!isset($_GET['order_id'])){
    die("Invalid Order");
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'] ?? 0;

/* Security: User can print only their order */
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stmt->bind_param("ii",$order_id,$user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if(!$order){
    die("Order Not Found");
}

/* ✅ DEMO PRODUCTS — updated as requested */
$products = [
    ['name'=>'Raw Cashew Nut (Kaju Biya)','qty'=>2,'price'=>700],
    ['name'=>'Raigad Alphonso (Hapus)','qty'=>1,'price'=>1500],
];

/* Delivery Charges */
$delivery_charges = 100;

/* Calculate total for demo products */
$total_products = 0;
foreach($products as $item){
    $total_products += $item['qty'] * $item['price'];
}
$final_total = $total_products + $delivery_charges;
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice #<?php echo $order['id']; ?></title>
<style>
body{ font-family:Arial; background:#f5f5f5; padding:40px; }
.invoice-box{ max-width:850px; margin:auto; background:white; padding:40px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
h1{ text-align:center; margin-bottom:30px; }
.info{ margin-bottom:20px; }
table{ width:100%; border-collapse:collapse; margin-top:20px; }
th, td{ padding:12px; border:1px solid #ddd; text-align:center; }
th{ background:#2e7d32; color:white; }
.button-group{ margin-top:30px; text-align:center; }
.print-btn{ padding:12px 25px; background:#2e7d32; color:white; border:none; border-radius:8px; cursor:pointer; margin-right:10px;}
.back-btn{ padding:12px 25px; background:#616161; color:white; border:none; border-radius:8px; cursor:pointer;}
@media print{ .button-group{ display:none; } body{ background:white; } }
</style>
</head>

<body>
<div class="invoice-box">

<h1>🧾 Order Invoice</h1>

<div class="info">
<p><strong>Invoice No:</strong> #INV-<?php echo $order['id']; ?></p>
<p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
<p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
</div>

<div class="info">
<p><strong>Customer Name:</strong> <?php echo $order['name']; ?></p>
<p><strong>Email:</strong> <?php echo $order['email']; ?></p>
<p><strong>Address:</strong> 
<?php echo $order['address']; ?>,
<?php echo $order['taluka']; ?>,
<?php echo $order['district']; ?>
</p>
</div>

<!-- PRODUCT TABLE -->
<table>
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>

<?php
foreach($products as $item){
    $pname = $item['name'];
    $qty   = $item['qty'];
    $price = $item['price'];
    $total = $qty * $price;
?>
<tr>
    <td><?php echo $pname; ?></td>
    <td><?php echo $qty; ?></td>
    <td>₹<?php echo $price; ?></td>
    <td>₹<?php echo $total; ?></td>
</tr>
<?php
}
?>

<tr>
    <td colspan="3"><strong>Delivery Charges</strong></td>
    <td>₹<?php echo $delivery_charges; ?></td>
</tr>

<tr>
    <td colspan="3"><strong>Final Total</strong></td>
    <td>₹<?php echo $final_total; ?></td>
</tr>
</table>

<div class="button-group">
    <button onclick="window.print()" class="print-btn">🖨 Print / Save as PDF</button>
    <button onclick="window.location.href='my_orders.php'" class="back-btn">⬅ Back to My Orders</button>
</div>

</div>
</body>
</html>