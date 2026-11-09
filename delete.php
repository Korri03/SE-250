<?php
// pull in the database connection so I can use $conn
require "db.php";

// get the id from the link and make it a whole number
$id = (int)$_GET["id"];

// delete just this loan; without WHERE it would delete every row
$stmt = mysqli_prepare($conn, "DELETE FROM loans WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// back to the list page
header("Location: list.php");
exit;
