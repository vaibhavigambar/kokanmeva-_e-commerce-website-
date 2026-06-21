<?php
session_start();
include("../config/db.php");

// Admin login check
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

/* ================= DELETE PRODUCT ================= */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    // delete from order_items first (avoid FK issue)
    mysqli_query($conn,"DELETE FROM order_items WHERE product_id=$id");

    // delete product
    mysqli_query($conn,"DELETE FROM products WHERE id=$id");

    header("Location: manage_products.php");
    exit();
}

/* ================= FETCH PRODUCTS ================= */
$products = mysqli_query($conn,"SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Products</title>
<style>
body{font-family:Arial;background:#f4f6f8;padding:30px;}
h2{color:#2e7d32;margin-bottom:20px;}
.add-btn{display:inline-block;margin-bottom:15px;padding:10px 15px;background:#2e7d32;color:#fff;text-decoration:none;border-radius:6px;}
table{width:100%;border-collapse:collapse;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,.1);}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center;}
th{background:#2e7d32;color:#fff;}
img{width:80px;height:60px;object-fit:cover;border-radius:6px;}
.btn{padding:6px 10px;color:#fff;text-decoration:none;border-radius:5px;font-size:14px;}
.edit{background:#0277bd;}
.delete{background:#c62828;}
.manage-btn{background:#2e7d32;color:white;padding:12px 25px;font-size:18px;font-weight:600;border:none;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-block;transition:0.3s ease;}
.manage-btn:hover{background:#1b5e20;transform:scale(1.05);}
</style>
</head>

<body>

<h2>
<a href="dashboard.php" class="manage-btn">📦 Manage Products</a>
</h2>

<a href="./add_product.php" class="add-btn">➕ Add Product</a>

<table>
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Unit</th>
    <th>Price</th>
    <th>Action</th>
</tr>

<?php if($products && mysqli_num_rows($products)>0){ ?>
<?php while($row = mysqli_fetch_assoc($products)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td>
        <img src="../images/<?php echo $row['product_image']; ?>">
    </td>
    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
    <td><?php echo strtoupper($row['unit_type']); ?></td>
    <td>₹<?php echo $row['price']; ?> / <?php echo strtoupper($row['unit_type']); ?></td>
    <td>
        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
        <a href="manage_products.php?delete=<?php echo $row['id']; ?>" 
           class="btn delete"
           onclick="return confirm('Delete this product?')">Delete</a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
    <td colspan="6">No products found</td>
</tr>
<?php } ?>

</table>

</body>
</html>