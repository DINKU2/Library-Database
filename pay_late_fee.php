<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
    header("Location: login.php");
    exit();
}

// Get checkout ID from GET request
$checkout_id = $_GET["id"];

// Update checkout record to set PayFlag to 1
$mysqli = require __DIR__ . "/database.php";
$updateSql = "UPDATE checkout SET PayFlag = 1 WHERE CheckoutID = ?";
$updateStmt = $mysqli->prepare($updateSql);
$updateStmt->bind_param("i", $checkout_id);
$updateStmt->execute();

// Retrieve checkout record with item name and late fee
$selectSql = "SELECT checkout.CheckoutID, item.Name, checkout.MemberID, checkout.TotalLateFee
               FROM checkout
               JOIN copy ON checkout.CopyID = copy.CopyID
               JOIN item ON copy.ItemID = item.ItemID
               WHERE checkout.CheckoutID = ?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $checkout_id);
$selectStmt->execute();
$result = $selectStmt->get_result();
$row = $result->fetch_assoc();
$item_name = $row["Name"];

// Create notification message
$message = "Late fee paid for item: " . $item_name;
$created_date = date("Y-m-d H:i:s");
$member_id = $_SESSION["MemberID"];

// Insert notification into database
$insertSql = "INSERT INTO notification (message, created_date, memberID) VALUES (?, ?, ?)";
$insertStmt = $mysqli->prepare($insertSql);
$insertStmt->bind_param("ssi", $message, $created_date, $member_id);
$insertStmt->execute();

// Redirect back to late fees page
header("Location: success_pay_latefee.php");
exit();
?>