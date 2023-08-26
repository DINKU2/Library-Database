<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
  header("Location: login.php");
  exit();
}

// Get database connection
$mysqli = require __DIR__ . "/database.php";
date_default_timezone_set('America/Chicago');
// Add reservation if copy ID is provided
if (isset($_POST["copy_id"])) {
  $copyID = $_POST["copy_id"];
  $memberID = $_SESSION["MemberID"];
  $reservationDate = date("Y-m-d H:i:s");
  $expirationDate = date('Y-m-d 23:59:59', strtotime('+10 days'));

  // Check if member has already reserved the copy
  $checkSql = "SELECT * FROM reservation WHERE CopyID = ? AND MemberID = ? AND Status = 0";
  $checkStmt = $mysqli->prepare($checkSql);
  $checkStmt->bind_param("ii", $copyID, $memberID);
  $checkStmt->execute();
  $result = $checkStmt->get_result();

  if ($result->num_rows > 0) {
    // Member has already reserved the copy, show message and redirect to main page
    header("Location: failure_reservation.php");
    exit();
  } else {
    // Insert reservation into database
    $insertSql = "INSERT INTO reservation (ReservationID, MemberID, CopyID, ReservationDate, ExpirationDate) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $mysqli->prepare($insertSql);
    $insertStmt->bind_param("iiiss", $reservationId, $memberID, $copyID, $reservationDate, $expirationDate);
    $insertStmt->execute();

    //-- Mauricio 
    // Set notification message and member ID
    // Get the item details for the reserved copy
    $itemQuery = "SELECT i.Name, i.Description, i.SerialOrISBN, i.Publisher, i.Creator
    FROM item AS i
    JOIN copy AS c ON i.ItemID = c.ItemID
    WHERE c.CopyID = " . $copyID;
    $itemResult = mysqli_query($mysqli, $itemQuery);
    $item = mysqli_fetch_assoc($itemResult);

    // Create the message
    $message = "Your reservation has been successfully created. ";
    $message = "You have reserved the item \"" . $item['Name'] . "\" by " . $item['Creator'] . ".\n\n";
    $message .= "Item details:\n";
    $message .= "Serial or ISBN: " . $item['SerialOrISBN'] . "\n";
    $message .= "Publisher: " . $item['Publisher'] . "\n\n";
    $message .= "Your reservation is valid from " . $reservationDate . " to " . $expirationDate . ".\n";
    $memberID = $_SESSION["MemberID"];

    // Insert notification into database
    $insertNotification = "INSERT INTO notification (message, created_date, memberID) VALUES (?, NOW(), ?)";

    $insertNot = $mysqli->prepare($insertNotification);
    $insertNot->bind_param("si", $message, $memberID);
    $insertNot->execute();

    header("Location: successful_create_reservation.php");
    exit();

  }
}

// Redirect to item details page
header("Location: item.php?id=" . $_POST["item_id"]);
exit();
?>