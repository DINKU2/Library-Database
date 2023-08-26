<?php
session_start();

if (!isset($_SESSION["MemberID"])) {
  header("Location: login.php");
  exit();
}

$mysqli = require __DIR__ . "/database.php";

// Check if reservation ID is set and valid
if (isset($_POST['ReservationID'])) {
  $reservationID = $_POST['ReservationID'];
  // Get reservation info
  $sql = "SELECT * FROM reservation WHERE ReservationID = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("i", $reservationID);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $reservation = $result->fetch_assoc();

    // Check if reservation belongs to the logged in user
    if ($reservation["MemberID"] == $_SESSION["MemberID"]) {
      // Delete the reservation
      $sql = "DELETE FROM reservation WHERE ReservationID = ?";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("i", $reservationID);
      $stmt->execute();

      // Redirect to the my_orders page
      header("Location: myreservation.php");
      exit();
    }
  }
}

// If reservation ID is not set or invalid, or if the reservation does not belong to the logged in user,
// show an error message and redirect to the my_orders page
$_SESSION["error_message"] = "Could not cancel reservation.";
header("Location: myorders.php");
exit();
?>