<?php
session_start();

if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}

// Get database connection
$mysqli = require __DIR__ . "/database.php";

// Get form data
$fname = $_POST["Fname"];
$lname = $_POST["Lname"];
$email = $_POST["Email"];
$password = password_hash($_POST["Password"], PASSWORD_DEFAULT);
$phoneNum = $_POST["PhoneNum"];
$libraryID = $_POST["LibraryID"];
$streetAddress = $_POST["StreetAddress"];
$city = $_POST["City"];
$state = $_POST["State"];
$zipCode = $_POST["ZipCode"];

// Insert new employee record
$insertSql = "INSERT INTO employee (LibraryID, AddressID, Fname, Lname, Email, PhoneNum, Password)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
$insertStmt = $mysqli->prepare($insertSql);
$insertStmt->bind_param("iisssis", $libraryID, $addressID, $fname, $lname, $email, $phoneNum, $password);

// Insert new address record
$insertAddressSql = "INSERT INTO address (StreetAddress, City, State, ZipCode)
                        VALUES (?, ?, ?, ?)";
$insertAddressStmt = $mysqli->prepare($insertAddressSql);
$insertAddressStmt->bind_param("ssss", $streetAddress, $city, $state, $zipCode);

// Execute queries within a transaction to ensure atomicity
$mysqli->begin_transaction();
$insertAddressStmt->execute();
$addressID = $mysqli->insert_id;
$insertStmt->execute();
$employeeID = $mysqli->insert_id;

// Commit transaction and close database connection
$mysqli->commit();
$mysqli->close();

// Redirect to success page
header("Location: successful_signup_emp.php");
exit;
?>
