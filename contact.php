<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Contact Us - Kokan Meva</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    font-family:Arial;
    background:linear-gradient(to right,#dcedc8,#ffffff);
}

/* ===== NAVBAR ===== */
header{
    background: linear-gradient(90deg,#1b5e20,#2e7d32);
    padding:15px 60px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}

header h1{
    color:white;
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

/* Contact Info Section */
.contact-section{
    width:60%;
    margin:80px auto;
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    text-align:center;
}

.contact-section h2{
    color:#2e7d32;
    margin-bottom:25px;
}

.contact-info{
    font-size:18px;
    line-height:32px;
    color:#444;
}

.contact-info strong{
    color:#1b5e20;
}

/* Footer */
footer{
    text-align:center;
    padding:15px;
    background:#2e7d32;
    color:white;
    margin-top:60px;
}

/* Responsive */
@media(max-width:768px){
    .contact-section{
        width:90%;
    }
}
</style>
</head>

<body>

<header>
    <h1>Kokan Meva</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="contact.php">Contact</a>
        <a href="about.php">About</a>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="my_orders.php">My Orders</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php } ?>
    </nav>
</header>

<div class="contact-section">
    <h2>🌿 Contact Kokan Meva</h2>

    <div class="contact-info">
        <p><strong>📍 Address:</strong> Ratnagiri, Konkan, Maharashtra, India</p>
        <p><strong>📞 Phone:</strong> +91 98765 43210</p>
        <p><strong>📧 Email:</strong> info@kokanmeva.com</p>
        <p><strong>🌐 Website:</strong> www.kokanmeva.com</p>
        <p><strong>🌴 Our Promise:</strong> Fresh, Natural & Authentic Konkan Products</p>
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Kokan Meva | Taste of Konkan 🌴
</footer>

</body>
</html>