<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit(); }

if(!isset($_GET['id'])){ header("Location: view_messages.php"); exit(); }

$id = $_GET['id'];

if(isset($_POST['reply'])){
    $reply = $_POST['reply'];
    mysqli_query($conn,"UPDATE contact_messages SET reply='$reply' WHERE id='$id'");
    header("Location: view_messages.php?replied=1");
    exit();
}

$message = mysqli_query($conn,"SELECT * FROM contact_messages WHERE id='$id'");
$m = mysqli_fetch_assoc($message);
?>

<!DOCTYPE html>
<html>
<head><title>Reply Message</title></head>
<body>
<h2>Reply to <?php echo $m['name']; ?></h2>

<form method="POST">
    Message:<br>
    <textarea readonly rows="5"><?php echo $m['message']; ?></textarea><br><br>
    Reply:<br>
    <textarea name="reply" rows="5" required><?php echo $m['reply']; ?></textarea><br><br>
    <button name="reply">Send Reply</button>
</form>

<a href="view_messages.php">Back</a>
</body>
</html>