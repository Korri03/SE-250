<?php
// Connection settings. XAMPP default is root with an empty password.
// On MAMP the password is often "root", so change $pass if you use MAMP.
$host = "localhost";
$user = "root";
$pass = "";
$name = "costume_closet";

// Open the connection to MySQL. If it fails, stop and show the reason.
$conn = mysqli_connect($host, $user, $pass, $name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
