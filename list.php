<?php
// pull in the database connection so I can use $conn
require "db.php";

// get the search text and checkbox from the URL (they're optional)
$search   = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$only_out = isset($_GET["only_out"]);

// start of the query; id is selected so the Mark returned / Delete links work
$sql = "SELECT id, item, borrower, due_date, returned FROM loans WHERE borrower LIKE ?";

// if the checkbox is ticked, only show loans that haven't been returned
if ($only_out) {
    $sql .= " AND returned = 0";
}
$sql .= " ORDER BY due_date";

// % on both sides means the search text can match anywhere in the name
$like = "%" . $search . "%";

// prepared statement because the search text comes from the user
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $like);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// today's date, used to check if a loan is overdue
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

  <!-- search by borrower and filter for items still out -->
  <form method="get" action="list.php">
    <input type="text" name="search" placeholder="Search borrower" value="<?= htmlspecialchars($search) ?>">
    <label><input type="checkbox" name="only_out" <?= $only_out ? "checked" : "" ?>> Only items still out</label>
    <button type="submit">Apply</button>
  </form>

<?php if (mysqli_num_rows($result) === 0): ?>
  <!-- no rows found, so show a message instead of an empty table -->
  <p>No loans yet. The closet is empty!</p>
<?php else: ?>
  <table>
    <tr><th>Item</th><th>Borrower</th><th>Due date</th><th>Status</th><th>Actions</th></tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <?php
        // overdue = not returned yet AND due date is before today
        $overdue = ($row["returned"] == 0 && $row["due_date"] < $today);
      ?>
      <!-- overdue rows get the red style -->
      <tr class="<?= $overdue ? "overdue" : "" ?>">
        <!-- htmlspecialchars so typed text can't inject HTML into the page -->
        <td><?= htmlspecialchars($row["item"]) ?></td>
        <td><?= htmlspecialchars($row["borrower"]) ?></td>
        <td><?= htmlspecialchars($row["due_date"]) ?></td>
        <td><?= $row["returned"] ? "Returned" : ($overdue ? "OVERDUE" : "Out") ?></td>
        <td>
          <!-- only show Mark returned if it's still out; (int) makes sure id is a number -->
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
