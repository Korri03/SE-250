<?php
// Load the database connection ($conn).
require "db.php";

// Read the id from the link and force it to a whole number.
$id = (int)$_GET["id"];

// Delete only the row with this id. Without WHERE this would delete every row.
$stmt = mysqli_prepare($conn, "DELETE FROM loans WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Go back to the list.
header("Location: list.php");
exit;
