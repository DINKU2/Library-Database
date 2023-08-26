<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"]) && !isset($_SESSION["EmployeeID"])) {
  header("Location: login.php");
  exit();
}

$isEmployee = isset($_SESSION["EmployeeID"]);

$mysqli = require __DIR__ . "/database.php";

// Get the item ID from the URL parameter
if (isset($_GET["id"])) {
  $itemID = $_GET["id"];
} else {
  // Redirect to main page if item ID is not provided
  header("Location: index.php");
  exit();
}

// Get item information
$itemSql = "SELECT * FROM item WHERE ItemID = ?";
$itemStmt = $mysqli->prepare($itemSql);
$itemStmt->bind_param("i", $itemID);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();

if ($itemResult->num_rows == 0) {
  // Redirect to main page if item does not exist
  header("Location: index.php");
  exit();
}

$item = $itemResult->fetch_assoc();

// Get copy information
$copySql = "SELECT c.CopyID, c.Status, l.LibraryName
            FROM copy c
            JOIN library l ON c.LibraryID = l.LibraryID
            WHERE c.ItemID = ?";

$copyStmt = $mysqli->prepare($copySql);
$copyStmt->bind_param("i", $itemID);
$copyStmt->execute();
$copyResult = $copyStmt->get_result();
?>


<!DOCTYPE html>
<html>

<head>
  <title>Item Details</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
  <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
  <script src="/js/validation.js" defer></script>
  <style>
    /* Style the "sign out" button */
    #signout-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #ff6347;
      border: none;
      color: white;
    }
  </style>
</head>

<div>
  <?php
  if ($isEmployee) {
    include 'empnav.php';
  }
  ?>
  <a href="main.php"><button>Home</button></a>
  <a href="logout.php"><button>Sign Out</button></a>
</div>

<body>
  <h1>Item Details</h1>
  <table>
    <tr>
      <th>Title</th>
      <td>
        <?php echo $item["Name"]; ?>
      </td>
    </tr>
    <tr>
      <th>Description</th>
      <td>
        <?php echo $item["Description"]; ?>
      </td>
    </tr>
    <tr>
      <th>ISBN</th>
      <td>
        <?php echo $item["SerialOrISBN"]; ?>
      </td>
    </tr>
    <tr>
      <th>Publisher</th>
      <td>
        <?php echo $item["Publisher"] ? $item["Publisher"] : "Not available"; ?>
      </td>
    </tr>
  </table>
  <br>
  <h2>Copy Information</h2>
  <table>
    <thead>
      <tr>
        <th>Copy ID</th>
        <th>Library Name</th>
        <th>Available</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($copy = $copyResult->fetch_assoc()) { ?>
        <tr>
          <td>
            <?php echo $copy["CopyID"]; ?>
          </td>
          <td>
            <?php echo $copy["LibraryName"]; ?>
          </td>
          <td>
            <?php echo ($copy["Status"] == 0) ? "Yes" : "No"; ?>
          </td>
          <td>
            <?php if (!$isEmployee) { // Check if the user is NOT an employee ?>
              <?php if ($copy["Status"] == 0) { ?>
                <form action="add_to_cart.php" method="POST">
                  <input type="hidden" name="copy_id" value="<?php echo $copy["CopyID"]; ?>">
                  <input type="hidden" name="item_id" value="<?php echo $item["ItemID"]; ?>">
                  <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
              <?php } else { ?>
                <form action="add_to_reservation.php" method="POST">
                  <input type="hidden" name="copy_id" value="<?php echo $copy["CopyID"]; ?>">
                  <input type="hidden" name="item_id" value="<?php echo $item["ItemID"]; ?>">
                  <button type="submit" name="add_to_reservation">Reserve</button>
                </form>
              <?php } ?>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
  </table>
  <?php if ($isEmployee) { ?>
    <h2>Add Copy</h2>
    <form method="post" action="add_copy.php">
      <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
      <label for="libraryID">Library:</label>
      <select id="libraryID" name="libraryID" required>
        <option value="" selected disabled hidden>Select library</option>
        <?php
        // Get list of libraries
        $librarySql = "SELECT * FROM library";
        $libraryResult = $mysqli->query($librarySql);

        // Loop through libraries and add to select options
        while ($library = $libraryResult->fetch_assoc()) {
          echo "<option value='" . $library["LibraryID"] . "'>" . $library["LibraryName"] . "</option>";
        }
        ?>
      </select>
      <br>
      <label for="quantity">Quantity:</label>
      <input type="number" id="quantity" name="quantity" required min="1">
      <br>
      <button type="submit" name="add_copy">Add Copy</button>
    </form>
  <?php } ?>

  </tbody>
  </table>

</body>

</html>