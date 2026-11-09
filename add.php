<?php
// Load the database connection ($conn).
require "db.php";

// Only save when the form was submitted (method="post").
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read the form fields. The names match the name="" attributes below.
    $item     = trim($_POST["item"]);
    $borrower = trim($_POST["borrower"]);
    $due_date = $_POST["due_date"];

    // Prepared statement: the ? marks are filled in separately, so user
    // input can never be run as SQL (this stops SQL injection).
    // "returned" is left out, so it uses the default 0 (still out).
    $stmt = mysqli_prepare($conn, "INSERT INTO loans (item, borrower, due_date) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $item, $borrower, $due_date);
    mysqli_stmt_execute($stmt);

    // Send the user back to the list, then stop running this file.
    header("Location: list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Add a loan</title></head>
<body>
  <h1>Add a loan</h1>
  <form method="post" action="add.php">
    <p><label>Item: <input type="text" name="item" maxlength="60" required></label></p>
    <p><label>Borrower: <input type="text" name="borrower" maxlength="60" required></label></p>
    <p><label>Due date: <input type="date" name="due_date" required></label></p>
    <p><button type="submit">Save loan</button></p>
  </form>
  <p><a href="list.php">Back to the list</a></p>
</body>
</html>
