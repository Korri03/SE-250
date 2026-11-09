<?php
// login info for the local MySQL server
// (XAMPP default is root with no password, MAMP usually uses "root")
$host = "localhost";
$user = "root";
$pass = "";
$name = "costume_closet";

// try to connect to the database
$conn = mysqli_connect($host, $user, $pass, $name);

// if it didn't connect, stop here and print why
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
