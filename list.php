<?php
// Load the database connection ($conn).
require "db.php";

// Stretch goals: read the optional search text and "still out" filter from the address bar.
$search   = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$only_out = isset($_GET["only_out"]);

// Build the query. It must select id, or the links below have nothing to point to.
$sql    = "SELECT id, item, borrower, due_date, returned FROM loans WHERE borrower LIKE ?";
if ($only_out) {
    $sql .= " AND returned = 0";
}
$sql .= " ORDER BY due_date";

// Prepared statement again, because the search text comes from the user.
$like = "%" . $search . "%";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $like);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Today's date as YYYY-MM-DD, used to spot overdue loans.
$today = date("Y-m-d");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Costume Closet loans</title>
  <style>
    table { border-collapse: collapse; }
    th, td { border: 1px solid #999; padding: 6px 10px; }
    .overdue { color: red; font-weight: bold; }
  </style>
</head>
<body>
  <h1>Campus Costume Closet</h1>
  <p><a href="add.php">Add a loan</a></p>

  <!-- Stretch goals: search by borrower and show only items still out -->
  <form method="get" action="list.php">
    <input type="text" name="search" placeholder="Search borrower" value="<?= htmlspecialchars($search) ?>">
    <label><input type="checkbox" name="only_out" <?= $only_out ? "checked" : "" ?>> Only items still out</label>
    <button type="submit">Apply</button>
  </form>

<?php if (mysqli_num_rows($result) === 0): ?>
  <!-- Friendly message when there are no rows -->
  <p>No loans yet. The closet is empty!</p>
<?php else: ?>
  <table>
    <tr><th>Item</th><th>Borrower</th><th>Due date</th><th>Status</th><th>Actions</th></tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <?php
        // A loan is overdue if it is not returned and the due date has passed.
        $overdue = ($row["returned"] == 0 && $row["due_date"] < $today);
      ?>
      <tr class="<?= $overdue ? "overdue" : "" ?>">
        <td><?= htmlspecialchars($row["item"]) ?></td>
        <td><?= htmlspecialchars($row["borrower"]) ?></td>
        <td><?= htmlspecialchars($row["due_date"]) ?></td>
        <td><?= $row["returned"] ? "Returned" : ($overdue ? "OVERDUE" : "Out") ?></td>
        <td>
          <?php if (!$row["returned"]): ?>
            <a href="mark_returned.php?id=<?= (int)$row["id"] ?>">Mark returned</a> |
          <?php endif; ?>
          <a href="delete.php?id=<?= (int)$row["id"] ?>" onclick="return confirm('Delete this loan?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php endif; ?>
</body>
</html>
