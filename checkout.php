<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
  header("Location: login.php");
  exit();
}

// Check if cart is empty
if (!isset($_SESSION["cart"]) || count($_SESSION["cart"]) == 0) {
  echo "Your cart is empty.";
  exit();
}

try {
  // Insert checkout into database
  $mysqli = require __DIR__ . "/database.php";

  $member_id = $_SESSION["MemberID"];
  $member_type_id = $_SESSION["MemberTypeID"];
  $sql = "SELECT * FROM membertype WHERE MemberTypeID = $member_type_id";
  $result = $mysqli->query($sql);
  if ($result->num_rows === 1) {
    // Member type found, fetch data
    $row = $result->fetch_assoc();
    $max_borrowing_days = $row["MaxBorrowingDays"];
    $max_items = $row["MaxItems"];
  }
  date_default_timezone_set('America/Chicago');
  $start_date = date('Y-m-d H:i:s'); // Current datetime in 'Y-m-d H:i:s' format
  $end_date = date('Y-m-d 23:59:59', strtotime("+$max_borrowing_days days", strtotime($start_date))); // End date = start date + max borrowing days, set time to end of day (23:59:59)
  $employee_id = NULL; // Assuming employee is not needed anymore

  $insertSql = "INSERT INTO checkout (MemberID, CopyID, StartDate, EndDate) 
              VALUES (?, ?, ?, ?)";
  $insertStmt = $mysqli->prepare($insertSql);

  $updateSql = "UPDATE copy SET Status = '1' WHERE CopyID = ?";
  $updateStmt = $mysqli->prepare($updateSql);

  // Create the message
  $x = 0;
  foreach ($_SESSION["cart"] as $copy_id) {
    $insertStmt->bind_param("iiss", $member_id, $copy_id, $start_date, $end_date);
    $insertStmt->execute();
    $updateStmt->bind_param("i", $copy_id);
    $updateStmt->execute();
    $x++;

  }
  $message = "Thank you for coming to the library.\n\n";
  foreach ($_SESSION["cart"] as $copy_id) {
    $sql = "SELECT i.Name FROM item i JOIN copy c ON i.ItemID = c.ItemID WHERE c.CopyID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $copy_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
      $row = $result->fetch_assoc();
      $item_name = $row["Name"];
      $message .= "- " . $item_name . "\n";
    }
  }
  $message .= "\nYour item(s) are due back by " . $end_date . ".";
  $message .= "\nThank you for using our library services. Please return your items on time to avoid late fees.\n";

  // Insert notification into database
  $insertNotification = "INSERT INTO notification (message, created_date, memberID) VALUES (?, NOW(), ?)";
  $insertNot = $mysqli->prepare($insertNotification);
  $insertNot->bind_param("si", $message, $member_id);
  $insertNot->execute();

  // Clear cart
  $_SESSION["cart"] = array();

  // Redirect to main page
  header("Location: successful_create_checkout.php");
  exit();

} catch (Exception $e) {
  // Insert notification into database
  $insertNotification = "INSERT INTO notification (message, created_date, memberID) VALUES (?, NOW(), ?)";
  $insertStmt = $mysqli->prepare($insertNotification);
  $message = "Error: Failed to create checkout.\n" .
    $e->getMessage() . "\n" .
    "Member ID: " . $member_id . "\n" .
    "Max borrowing days: " . $max_borrowing_days . "\n" .
    "Max items: " . $max_items;
  $insertStmt->bind_param("si", $message, $member_id);
  $insertStmt->execute();

  // Redirect to main page
  header("Location: failure_checkout.php");
  exit();
}
?>