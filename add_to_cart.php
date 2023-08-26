<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
  header("Location: login.php");
  exit();
}
// Add item to cart if copy ID is provided
if (isset($_POST["copy_id"])) {
  // Initialize cart session variable if it does not exist
  if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
  }



  // Check if copy ID is already in cart
  $copyId = $_POST["copy_id"];
  $inCart = false;
  foreach ($_SESSION["cart"] as $cartItem) {
    if ($cartItem == $copyId) {
      $inCart = true;
      break;
    }
  }

  // Add copy ID to cart if not already in cart
  if (!$inCart) {
    array_push($_SESSION["cart"], $copyId);
  }

  // Redirect to item details page
  // header("Location: item.php?id=" . $_POST["item_id"]);
  // exit();
}

// Clear cart if "clear cart" button is clicked
if (isset($_POST["clear_cart"])) {
  $_SESSION["cart"] = array();
}

// Delete item from cart if "delete" button is clicked
if (isset($_POST["delete_item"])) {
  $copyId = $_POST["copy_id"];
  $key = array_search($copyId, $_SESSION["cart"]);
  if ($key !== false) {
    unset($_SESSION["cart"][$key]);
  }
}
// Print out all items currently in the cart
$cartContent = "";
if (isset($_SESSION["cart"])) {
  $mysqli = require __DIR__ . "/database.php";

  $cartContent .= "<table>";
  $cartContent .= "<tr><th>Item</th><th>Copy ID</th><th>Library ID</th><th>Status</th><th>Action</th></tr>";

  foreach ($_SESSION["cart"] as $copyID) {
    // Get copy information
    $copySql = "SELECT copy.*, library.LibraryName FROM copy JOIN library ON copy.LibraryID = library.LibraryID WHERE CopyID = ?";
    $copyStmt = $mysqli->prepare($copySql);
    $copyStmt->bind_param("i", $copyID);
    $copyStmt->execute();
    $copyResult = $copyStmt->get_result();

    if ($copyResult->num_rows > 0) {
      $copy = $copyResult->fetch_assoc();

      // Get item information
      $itemSql = "SELECT * FROM item WHERE ItemID = ?";
      $itemStmt = $mysqli->prepare($itemSql);
      $itemStmt->bind_param("i", $copy["ItemID"]);
      $itemStmt->execute();
      $itemResult = $itemStmt->get_result();

      if ($itemResult->num_rows > 0) {
        $item = $itemResult->fetch_assoc();

        $cartContent .= "<tr><td>" . $item["Name"] . "</td><td>" . $copy["CopyID"] . "</td><td>" . $copy["LibraryID"] . "</td><td>" . (($copy["Status"] == 0) ? "Available" : "Not available") . "</td><td><form action='' method='post'><input type='hidden' name='copy_id' value='" . $copy["CopyID"] . "'><input type='submit' name='delete_item' value='Delete'></form></td></tr>";
      }
    }
  }

  $cartContent .= "</table>";
} else {
  $cartContent .= "Your cart is empty.";
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Cart</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
  <?php
  include 'membernav.php';
  ?>
  <h1>Cart</h1>
  <div class="cart-content">
    <?php echo $cartContent; ?>
  </div>
  <form action="checkout.php" method="post">
    <input type="submit" name="checkout" value="Checkout">
  </form>
  <form action="" method="post">
    <input type="submit" name="clear_cart" value="Clear Cart">
  </form>
  </div>
</body>

</html>
<?php
// Process checkout if "checkout" button is clicked
if (isset($_POST["checkout"])) {
  // Check if cart is empty
  if (empty($_SESSION["cart"])) {
    echo "<script>alert('Your cart is esadasdmpty.');</script>";
  } else {
    // Redirect to checkout page



    header("Location: checkout.php");
    exit();
  }
}
?>