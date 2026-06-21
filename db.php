<?php
$servername = "localhost";
$username = "root"; // default XAMPP MySQL user
$password = "";     // default XAMPP MySQL password is empty
$database = "kokanmeva"; // replace with your DB name

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>