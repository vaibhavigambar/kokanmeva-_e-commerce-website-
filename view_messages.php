<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit(); }

// Fetch messages
$messages = mysqli_query($conn,"SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head><title>Customer Messages</title></head>
<body>
<h2>Customer Messages</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
    <th>Reply</th>
    <th>Actions</th>
</tr>

<?php while($m = mysqli_fetch_assoc($messages)){ ?>
<tr>
    <td><?php echo $m['id']; ?></td>
    <td><?php echo $m['name']; ?></td>
    <td><?php echo $m['email']; ?></td>
    <td><?php echo $m['message']; ?></td>
    <td><?php echo $m['reply']; ?></td>
    <td><a href="reply_message.php?id=<?php echo $m['id']; ?>">Reply</a></td>
</tr>
<?php } ?>
</table>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>