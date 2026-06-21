<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit(); }

// Update subproduct
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $sub_name = $_POST['sub_name'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    mysqli_query($conn,"UPDATE sub_products SET sub_name='$sub_name', price='$price', availability='$availability' WHERE id='$id'");
    header("Location: edit_subproduct.php?success=1");
    exit();
}

// Delete subproduct
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM sub_products WHERE id='$id'");
    header("Location: edit_subproduct.php?deleted=1");
    exit();
}

// Fetch subproducts
$subs = mysqli_query($conn,"SELECT s.*, p.product_name FROM sub_products s JOIN products p ON s.product_id=p.id");
?>

<!DOCTYPE html>
<html>
<head><title>Manage Sub-Products</title></head>
<body>
<h2>Manage Sub-Products</h2>

<?php
if(isset($_GET['success'])) echo "<p style='color:green'>Sub-Product Updated!</p>";
if(isset($_GET['deleted'])) echo "<p style='color:red'>Sub-Product Deleted!</p>";
?>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Product</th>
    <th>Sub-Product</th>
    <th>Price</th>
    <th>Availability</th>
    <th>Actions</th>
</tr>

<?php while($s = mysqli_fetch_assoc($subs)){ ?>
<tr>
    <form method="POST">
        <td><?php echo $s['id']; ?><input type="hidden" name="id" value="<?php echo $s['id']; ?>"></td>
        <td><?php echo $s['product_name']; ?></td>
        <td><input type="text" name="sub_name" value="<?php echo $s['sub_name']; ?>"></td>
        <td><input type="text" name="price" value="<?php echo $s['price']; ?>"></td>
        <td>
            <select name="availability">
                <option value="Available" <?php if($s['availability']=='Available') echo "selected"; ?>>Available</option>
                <option value="Not Available" <?php if($s['availability']=='Not Available') echo "selected"; ?>>Not Available</option>
            </select>
        </td>
        <td>
            <button type="submit" name="update">Update</button>
            <a href="edit_subproduct.php?delete=<?php echo $s['id']; ?>" onclick="return confirm('Delete this sub-product?')">Delete</a>
        </td>
    </form>
</tr>
<?php } ?>
</table>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>