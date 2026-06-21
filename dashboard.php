<?php
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Kokan Meva</title>
    <meta charset="UTF-8">

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #e8f5e9, #ffffff);
}

/* Header */
.header{
    background:#2e7d32;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h2{
    margin:0;
}

.logout{
    background:white;
    color:#2e7d32;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
    font-weight:bold;
}

.logout:hover{
    background:#c8e6c9;
}

/* Container */
.container{
    padding:40px;
    text-align:center;
}

/* Cards */
.card{
    display:inline-block;
    width:260px;
    background:white;
    padding:25px;
    margin:20px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
}

.card h3{
    color:#2e7d32;
}

.card a{
    display:inline-block;
    margin-top:15px;
    padding:10px 18px;
    background:#2e7d32;
    color:white;
    border-radius:6px;
    text-decoration:none;
}

.card a:hover{
    background:#1b5e20;
}
</style>
</head>

<body>

<div class="header">
    <h2>Welcome <?php echo htmlspecialchars($_SESSION['admin_name']); ?> 👋</h2>
    <a href="../index.php" class="logout">Logout</a>
</div>

<div class="container">

    <div class="card">
        <h3>Manage Products</h3>
        <a href="manage_products.php">Go</a>
    </div>

    

    <div class="card">
        <h3>View Orders</h3>
        <a href="orders.php">Go</a>
    </div>

    <div class="card">
        <h3>Manage Users</h3>
        <a href="manage_users.php">Go</a>
    </div>

</div>

</body>
</html>