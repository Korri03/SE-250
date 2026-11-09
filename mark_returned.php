<?php
// Load the database connection ($conn).
require "db.php";

// Read the id from the link (mark_returned.php?id=3) and force it to a whole number.
$id = (int)$_GET["id"];

// Update only the row with this id. The WHERE keeps other rows unchanged.
$stmt = mysqli_prepare($conn, "UPDATE loans SET returned = 1 WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Go back to the list.
header("Location: list.php");
exit;
