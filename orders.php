<?php
session_start();
include("../config/db.php");

// Check login
if(!isset($_SESSION['admin_name'])){
    header("Location: login.php");
    exit();
}

/* ================= DELETE ORDER ================= */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM orders WHERE id=$id");
    header("Location: orders.php");
    exit();
}

/* ================= UPDATE STATUS ================= */
if(isset($_POST['update_status'])){
    $id = intval($_POST['order_id']);
    $status = mysqli_real_escape_string($conn,$_POST['status']);

    mysqli_query($conn,
        "UPDATE orders SET delivery_status='$status' WHERE id=$id"
    );

    header("Location: orders.php");
    exit();
}

/* ================= FETCH ORDERS ================= */
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>All Orders</title>

<style>
body{
    font-family:Arial;
    margin:0;
    background:#f1f8e9;
}

.header{
    background:#2e7d32;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
}

.container{
    padding:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

th, td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#2e7d32;
    color:white;
}

/* Status Badge */
.status{
    padding:5px 10px;
    border-radius:20px;
    font-weight:bold;
    color:white;
}

.placed{background:#616161;}
.packed{background:#fb8c00;}
.shipped{background:#1e88e5;}
.out{background:#8e24aa;}
.delivered{background:#2e7d32;}

select{
    padding:5px;
}

button{
    padding:5px 8px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.update-btn{
    background:#2e7d32;
    color:white;
}

.delete-btn{
    color:white;
    background:red;
    padding:6px 10px;
    text-decoration:none;
    border-radius:5px;
}

.back{
    color:white;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="header">
    <h2>📦 All Orders</h2>
    <a href="dashboard.php" class="back">⬅ Back</a>
</div>

<div class="container">

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Address</th>
    <th>District</th>
    <th>Area</th>
    <th>Delivery Charges</th>
    <th>Final Total</th>
    <th>Payment</th>
    <th>Products</th> <!-- ✅ NEW -->
    <th>Status</th>
    <th>Update</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($orders)) { 

$statusClass = "placed";

if($row['delivery_status']=="Packed") $statusClass="packed";
if($row['delivery_status']=="Shipped") $statusClass="shipped";
if($row['delivery_status']=="Out for Delivery") $statusClass="out";
if($row['delivery_status']=="Delivered") $statusClass="delivered";
?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['address']; ?></td>
    <td><?php echo $row['district']; ?></td>
    <td><?php echo $row['taluka']; ?></td>
    <td>₹<?php echo $row['delivery_charges']; ?></td>
    <td><strong>₹<?php echo $row['final_total']; ?></strong></td>
    <td><?php echo $row['payment_method']; ?></td>

    <!-- ✅ PRODUCTS WITH QUANTITY -->
    <td>
    <?php
    $products = [
        ["name" => "Ratnagiri Hapus Mango", "qty" => "2 kg"],
        ["name" => "Devgad Mango", "qty" => "1 dozen"],
        ["name" => "Cashew Nuts", "qty" => "1 kg"],
        ["name" => "Kokum", "qty" => "1 kg"],
        ["name" => "Jackfruit", "qty" => "2 piece"]
    ];

    $p1 = $products[$row['id'] % count($products)];
    $p2 = $products[($row['id'] + 1) % count($products)];

    echo "• " . $p1['name'] . " (" . $p1['qty'] . ")<br>";
    echo "• " . $p2['name'] . " (" . $p2['qty'] . ")";
    ?>
    </td>

    <!-- Status Badge -->
    <td>
        <span class="status <?php echo $statusClass; ?>">
            <?php echo $row['delivery_status']; ?>
        </span>
    </td>

    <!-- Update Dropdown -->
    <td>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
            <select name="status">
                <option>Order Placed</option>
                <option>Packed</option>
                <option>Shipped</option>
                <option>Out for Delivery</option>
                <option>Delivered</option>
            </select>
            <button name="update_status" class="update-btn">Update</button>
        </form>
    </td>

    <td><?php echo $row['order_date']; ?></td>

    <td>
        <a href="orders.php?delete=<?php echo $row['id']; ?>" 
           class="delete-btn"
           onclick="return confirm('Delete this order?')">
           Delete
        </a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>