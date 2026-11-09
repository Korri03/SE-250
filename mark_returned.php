<?php
// pull in the database connection so I can use $conn
require "db.php";

// get the id from the link (mark_returned.php?id=3) and make it a whole number
$id = (int)$_GET["id"];

// set returned to 1 for just this loan; WHERE stops it from changing every row
$stmt = mysqli_prepare($conn, "UPDATE loans SET returned = 1 WHERE id = ?");

// "i" = the value is an integer
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// back to the list page
header("Location: list.php");
exit;
