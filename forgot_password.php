<?php
include("config/db.php");

$msg = "";

if(isset($_POST['reset'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($check) == 1){

        mysqli_query($conn,"UPDATE users SET password='$new_pass' WHERE username='$username'");
        $msg = "Password Updated Successfully!";

    }else{
        $msg = "User Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>

<style>
body{
    font-family:sans-serif;
    background:linear-gradient(135deg,#1b5e20,#4caf50);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.box{
    background:white;
    padding:30px;
    border-radius:15px;
    width:350px;
}
h2{text-align:center;color:#1b5e20;margin-bottom:20px;}
input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    width:100%;
    padding:10px;
    background:#2e7d32;
    color:white;
    border:none;
    border-radius:6px;
}
.msg{
    text-align:center;
    color:red;
    margin-bottom:10px;
}
a{
    display:block;
    text-align:center;
    margin-top:10px;
    color:#2e7d32;
    text-decoration:none;
}
</style>

</head>

<body>

<div class="box">

<h2>Reset Password</h2>

<?php if($msg!="") echo "<div class='msg'>$msg</div>"; ?>

<form method="POST">

<input type="text" name="username" placeholder="Enter Username" required>

<input type="password" name="new_password" placeholder="New Password" required>

<button name="reset">Reset Password</button>

</form>

<a href="login.php">← Back to Login</a>

</div>

</body>
</html>