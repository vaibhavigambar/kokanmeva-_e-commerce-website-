<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us - Kokan Meva</title>
    <style>
        body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #e3fde6, #e6ffe6);
}

/* ===== NAVBAR ===== */
header{
    background: linear-gradient(90deg,#1b5e20,#2e7d32);
    padding:10px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}

header h2{
    color:white;
    font-size:28px;
    letter-spacing:2px;
}

nav a{
    color:white;
    text-decoration:none;
    font-weight:600;
    margin-left:20px;
    padding:8px 14px;
    border-radius:8px;
    transition:0.3s;
}

nav a:hover{
    background:white;
    color:#2e7d32;
}

/* HERO SECTION */
.hero {
    text-align: center;
    padding: 100px 20px;
    background: url('../images/kokan.jpg') no-repeat center center/cover;
    color: white;
}

.hero h1 {
    font-size: 40px;
}

.hero p {
    font-size: 18px;
    margin: 15px 0;
}

.btn {
    padding: 10px 20px;
    background: #ff8c00;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.btn:hover {
    background: #ff4500;
}

/* FEATURES SECTION */
.features {
    display: flex;
    justify-content: center;
    padding: 50px;
    gap: 30px;
}

.card {
    background: white;
    padding: 25px;
    width: 250px;
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-10px);
}

/* ABOUT SECTION */
.about {
    padding: 80px;
    text-align: center;
}

/* FOOTER */
footer {
    background: #006400;
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: 50px;
}
    </style>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="logo">🥭 Kokan Meva</div>
   <nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="products.php">Products</a>
    <a href="contact.php">Contact</a>

    <?php if(isset($_SESSION['user_id'])) { ?>
        
        <!-- ✅ Login झाल्यावर -->
        <a href="my_orders.php">My Orders</a>
        <a href="logout.php">Logout</a>

    <?php } else { ?>

        <!-- ❌ Login नसेल तर -->
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>

    <?php } ?>
</nav>
</header>

<section class="about">
    <h1>🌴 About Kokan Meva</h1>
    <p>
        Kokan Meva is dedicated to delivering fresh and authentic 
        Konkan products directly from farmers to customers.
        Our mission is to provide pure Alphonso mangoes, cashew,
        kokum, jackfruit and other traditional products.
    </p>
    <p>
        We believe in supporting local farmers and maintaining
        natural farming practices.
    </p>
</section>

<footer>
    © 2026 Kokan Meva | Pure Konkan Taste 🥭
</footer>

</body>
</html>