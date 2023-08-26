<?php
session_start();

// Check if the user clicked the "sign out" button
if (!isset($_SESSION["MemberID"]) && !isset($_SESSION["EmployeeID"])) {
  header("Location: login.php");
  exit();
}

$isEmployee = isset($_SESSION["EmployeeID"]);

// Get message count for current user
$mysqli = require __DIR__ . "/database.php";
// Get member type for current user
$selectSql = "SELECT member.*, membertype.Name AS MemberTypeName, membertype.MaxItems, membertype.MaxBorrowingDays
FROM member
JOIN membertype ON member.MemberTypeID = membertype.MemberTypeID
WHERE member.MemberID = ?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $_SESSION["MemberID"]);
$selectStmt->execute();
$result = $selectStmt->get_result();
$member = $result->fetch_assoc();

$sql = "SELECT COUNT(*) as count FROM notification WHERE MemberID = ?";
$stmt = $mysqli->prepare($sql);
if ($isEmployee) {
  $stmt->bind_param("i", $_SESSION["EmployeeID"]);
} else {
  $stmt->bind_param("i", $_SESSION["MemberID"]);
}
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $numNotifications = $row["count"];
} else {
  $numNotifications = 0;
}
$_SESSION["numNotifications"] = $numNotifications;


// Pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;
?>
<!DOCTYPE html>
<html>

<head>
  <title>MainBoard</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
  <script src="/js/validation.js" defer></script>
</head>
<body>
  <div>
    <?php
    if ($isEmployee) {
      echo '<a href="emain.php"><button>Employee Main Board</button></a>';
    }
    ?>
  </div>
  <?php if (!$isEmployee): ?>
    <div style="display: inline-block;">
      <a href="add_to_cart.php"><button>Cart</button></a>
      <a href="myorders.php"><button>My Order</button></a>
      <a href="myreservation.php"><button>My Reservation</button></a>
      <a href="update_member.php"><button>Change Information</button></a>
      <a href="latefee.php"><button>Late Fee</button></a>
      <a href="messages.php"><button>Messages
          <?php echo $numNotifications > 0 ? " (" . $numNotifications . ")" : ""; ?>
        </button></a>
      <a href="logout.php"><button>Sign Out</button></a>
    </div>
    <div>
      <h2>Member Details</h2>
      <?php if ($member) { ?>
        <p>Name:
          <?= $member['Fname'] . ' ' . $member['Lname'] ?>
        </p>
        <p>Email:
          <?= $member['Email'] ?>
        </p>
        <p>Phone Number:
          <?= $member['PhoneNum'] ?>
        </p>
        <p>Member Type:
          <?= $member["MemberTypeName"] ?> | Max Items:
          <?= $member["MaxItems"] ?> | Max Borrowing Days:
          <?= $member["MaxBorrowingDays"] ?>
        </p>
      <?php } else { ?>
        <p>Member not found.</p>
      <?php } ?>
    </div>
  <?php endif; ?>

  <form action="search.php" method="POST">
    <div>
      <label for="search-field">Search by:</label>
      <select id="search-field" name="search-field">
        <option value="name">Name</option>
        <option value="isbn">Serial</option>
        <option value="creator">Creator</option>
      </select>
    </div>
    <div>
      <input type="text" id="search" name="search">
    </div>
    <button type="submit">Search</button>
  </form>
  <h2>List of Items</h2>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Quantity</th>
        <th>Creator</th>
        <th>ISBN</th>
        <th>Subject</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $mysqli = require __DIR__ . "/database.php";
      $sql = "SELECT item.ItemID, item.Name, item.Creator, item.SerialOrISBN, itemtype.Name AS TypeName, subject.Name AS SubjectName
                FROM item 
                JOIN itemtype ON item.TypeID = itemtype.ItemTypeID
                JOIN subject ON item.SubjectID = subject.SubjectID
                ORDER BY subject.Name, itemtype.Name, item.Name
                LIMIT $offset, $items_per_page";

      $result = $mysqli->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["Name"] . "</td>";
          echo "<td>" . $row["TypeName"] . "</td>";
          echo "<td>" . getQuantity($row["ItemID"]) . "</td>";
          echo "<td>" . $row["Creator"] . "</td>";
          echo "<td>" . $row["SerialOrISBN"] . "</td>";
          echo "<td>" . $row["SubjectName"] . "</td>"; // Display the subject name
          echo "<td><a href='item.php?id=" . $row["ItemID"] . "'>View Details</a></td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>No items found.</td></tr>";
      }
      function getQuantity($itemID)
      {
        $mysqli = require __DIR__ . "/database.php";
        $sql = "SELECT COUNT(*) as count FROM copy WHERE ItemID = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          return $row["count"];
        } else {
          return 0;
        }
      }

      // Get the total number of items for pagination
      $sql = "SELECT COUNT(*) as total_items FROM item WHERE Available = 1";
      $result = $mysqli->query($sql);
      $total_items = $result->fetch_assoc()['total_items'];
      $total_pages = ceil($total_items / $items_per_page);
      ?>
    </tbody>
  </table>

  <nav aria-label="Page navigation">
    <ul class="pagination">
      <?php if ($current_page > 1): ?>
        <li class="page-item"><a class="page-link" href="main.php?page=<?php echo $current_page - 1; ?>">Previous</a></li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item<?php if ($i == $current_page)
          echo ' active'; ?>"><a class="page-link" href="main.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="main.php?page=<?php echo $current_page + 1; ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>


</body>

</html>