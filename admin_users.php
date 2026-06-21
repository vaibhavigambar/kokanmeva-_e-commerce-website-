<?php
session_start();
include("config/db.php");

/* Optional: Protect page (only admin can access)
   Uncomment if you have admin session system

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
*/

// Delete user
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM users WHERE id=$id");
    header("Location: admin_users.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - View Users</title>

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:#2e7d32;
    margin:0;
    padding:20px;
}

h2{
    color:white;
    text-align:center;
}

.table-box{
    background:white;
    padding:20px;
    border-radius:8px;
    width:80%;
    margin:20px auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th, table td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

table th{
    background:#1b5e20;
    color:white;
}

.delete-btn{
    text-decoration:none;
    background:#c62828;
    color:white;
    padding:5px 10px;
    border-radius:4px;
    font-size:13px;
}

.delete-btn:hover{
    background:#b71c1c;
}
</style>
</head>

<body>

<h2>🌿 Registered Users - Admin Panel</h2>

<div class="table-box">

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['username']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td>
        <a class="delete-btn"
           href="admin_users.php?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>