<?php
session_start();
include("config/db.php");

$error = "";

if(isset($_POST['login'])){

    $role     = $_POST['role'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    /* ================= USER LOGIN ================= */
    if($role == "user"){

        $query = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' LIMIT 1");

        if(mysqli_num_rows($query) == 1){

            $row = mysqli_fetch_assoc($query);

            if(password_verify($password,$row['password'])){

                $_SESSION['user_id']   = $row['id'];
                $_SESSION['username']  = $row['username'];

                header("Location: index.php");
                exit();

            }else{
                $error = "Wrong User Password!";
            }

        }else{
            $error = "User Not Found!";
        }
    }

    /* ================= ADMIN LOGIN ================= */
    if($role == "admin"){

        $query = mysqli_query($conn,"SELECT * FROM admins WHERE username='$username' LIMIT 1");

        if(mysqli_num_rows($query) == 1){

            $row = mysqli_fetch_assoc($query);

            if(password_verify($password,$row['password'])){

                $_SESSION['admin_id']   = $row['id'];
                $_SESSION['admin_name'] = $row['username'];

                header("Location: admin/dashboard.php");
                exit();

            }else{
                $error = "Wrong Admin Password!";
            }

        }else{
            $error = "Admin Not Found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Kokan Meva</title>

<style>
/* SAME YOUR CSS */
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:'Segoe UI',sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#1b5e20,#4caf50);
}
.login-box{
    background:white;
    padding:40px;
    width:400px;
    border-radius:18px;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
}
.login-box h2{text-align:center;margin-bottom:25px;color:#1b5e20;}
.role-title{font-weight:600;margin-bottom:10px;color:#333;}
.role{display:flex;gap:15px;margin-bottom:20px;}
.role input{display:none;}
.role label{
    flex:1;padding:12px;text-align:center;border:2px solid #ccc;
    border-radius:10px;cursor:pointer;font-weight:600;transition:0.3s;background:#f9f9f9;
}
.role input:checked + label{
    border-color:#2e7d32;background:#e8f5e9;color:#1b5e20;
}
.input-group{margin-bottom:15px;}
.input-group label{font-size:14px;font-weight:600;display:block;margin-bottom:5px;color:#333;}
.input-group input{
    width:100%;padding:12px;border-radius:8px;border:1px solid #ccc;font-size:14px;
}
button{
    width:100%;padding:12px;background:#2e7d32;color:white;border:none;
    border-radius:8px;font-size:15px;font-weight:bold;cursor:pointer;
}
.error{
    background:#ffebee;color:#c62828;padding:10px;border-radius:6px;
    margin-bottom:15px;text-align:center;
}
a{text-decoration:none;color:#2e7d32;font-size:14px;}
</style>
</head>

<body>

<div class="login-box">

<h2>Kokan Meva Login</h2>

<?php
if(isset($_GET['message']) && $_GET['message']=="login_required"){
    echo "<div class='error'>Please login to place order!</div>";
}
?>

<?php if($error!="") echo "<div class='error'>$error</div>"; ?>

<form method="POST">

<div class="role-title">Login As:</div>

<div class="role">
    <input type="radio" id="user" name="role" value="user" checked>
    <label for="user">User</label>

    <input type="radio" id="admin" name="role" value="admin">
    <label for="admin">Admin</label>
</div>

<div class="input-group">
    <label>Username</label>
    <input type="text" name="username" required>
</div>

<div class="input-group">
    <label>Password</label>
    <input type="password" name="password" required>
</div>

<button name="login">Login</button>

<!-- ✅ FORGOT PASSWORD LINK -->
<p style="text-align:center; margin-top:10px;">
    <a href="forgot_password.php">Forgot Password?</a>
</p>

<p style="text-align:center; margin-top:10px;">
    <a href="index.php">← Back</a>
</p>

</form>
</div>

</body>
</html>