<?php
// pull in the database connection so I can use $conn
require "db.php";

// this only runs after the form is submitted, not when the page first loads
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // grab what the user typed (trim removes extra spaces)
    $item     = trim($_POST["item"]);
    $borrower = trim($_POST["borrower"]);
    $due_date = $_POST["due_date"];

    // prepared statement: the ?s get filled in separately so typed text
    // can't be run as SQL (protects against SQL injection)
    // returned isn't listed, so it defaults to 0 = still out
    $stmt = mysqli_prepare($conn, "INSERT INTO loans (item, borrower, due_date) VALUES (?, ?, ?)");

    // "sss" = all three values are strings
    mysqli_stmt_bind_param($stmt, "sss", $item, $borrower, $due_date);
    mysqli_stmt_execute($stmt);

    // go back to the list page and stop running this file
    header("Location: list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Add a loan</title></head>
<body>
  <h1>Add a loan</h1>
  <!-- form sends its fields to this same file using POST -->
  <form method="post" action="add.php">
    <p><label>Item: <input type="text" name="item" maxlength="60" required></label></p>
    <p><label>Borrower: <input type="text" name="borrower" maxlength="60" required></label></p>
    <p><label>Due date: <input type="date" name="due_date" required></label></p>
    <p><button type="submit">Save loan</button></p>
  </form>
  <p><a href="list.php">Back to the list</a></p>
</body>
</html>
