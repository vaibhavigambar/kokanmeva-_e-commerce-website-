<?php
session_start();
include("./config/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Kokan Meva - Taste of Konkan Nature</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins', sans-serif;
    background:#f3f8f4;
    display:flex;
    flex-direction:column;
    min-height:100vh;
}

/* ===== NAVBAR ===== */
header{
    background:linear-gradient(90deg,#1b5e20,#2e7d32);
    padding:15px 60px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.2);
}

.logo{
    display:flex;
    align-items:center;
    gap:12px;
}

.logo img{
    height:55px;
    border-radius:50%;
}

.logo h1{
    color:#fff;
    font-size:26px;
    letter-spacing:1px;
}

nav a{
    color:#fff;
    text-decoration:none;
    font-weight:500;
    margin-left:18px;
    padding:8px 16px;
    border-radius:20px;
    transition:0.3s;
}

nav a:hover{
    background:#dcedc8;
    color:#1b5e20;
}

/* ===== HERO SECTION ===== */
.hero{
    flex:1;
    background: linear-gradient(rgba(0,80,40,0.6), rgba(0,60,30,0.6)),
    url('images/konkan_nature.jpg');
    background-size:cover;
    background-position:center;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    color:white;
    padding:20px;
}

/* IMAGE ABOVE HEADING */
.hero-img{
    width:230px;
    margin-bottom:25px;
    border-radius:25px;
    box-shadow:0 15px 35px rgba(0,0,0,0.5);
    animation: float 3s ease-in-out infinite;
}

@keyframes float{
    0%{transform:translateY(0);}
    50%{transform:translateY(-12px);}
    100%{transform:translateY(0);}
}

.hero h2{
    font-size:50px;
    text-shadow:2px 2px 10px rgba(0,0,0,0.7);
    margin-bottom:15px;
}

.hero p{
    font-size:20px;
    margin-bottom:30px;
    color:#e8f5e9;
}

/* BUTTON */
.hero button{
    padding:15px 40px;
    font-size:18px;
    border:none;
    border-radius:30px;
    background:linear-gradient(45deg,#66bb6a,#2e7d32);
    color:white;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
    transition:0.3s;
}

.hero button:hover{
    background:linear-gradient(45deg,#43a047,#1b5e20);
    transform:scale(1.08);
}

/* FOOTER */
footer{
    text-align:center;
    padding:15px;
    background:#1b5e20;
    color:#fff;
    font-size:14px;
}

/* RESPONSIVE */
@media(max-width:768px){
    header{
        flex-direction:column;
        gap:10px;
        padding:20px;
    }

    nav{
        display:flex;
        flex-wrap:wrap;
        justify-content:center;
    }

    .hero h2{
        font-size:32px;
    }

    .hero-img{
        width:180px;
    }
}
</style>
</head>

<body>

<header>
    <div class="logo">
        <img src="images/logo.jpg" alt="Kokan Meva Logo">
        <h1>Kokan Meva</h1>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="my_orders.php">My Orders</a>
            <a href="#">👤 <?php echo $_SESSION['username']; ?></a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php } ?>
    </nav>
</header>

<section class="hero">

    <!-- IMAGE ABOVE HEADING -->
    <img src="images/logo.jpg" alt="Kokan Products" class="hero-img">

    <h2>Experience the Pure Taste of Konkan</h2>
    <p>Fresh • Natural • Direct from Konkan Farms</p>

    <button onclick="window.location.href='products.php'">
        🌿 Explore Products
    </button>

</section>

<footer>
    &copy; <?php echo date('Y'); ?> Kokan Meva | Inspired by Konkan Nature 🌴
</footer>

</body>
</html>