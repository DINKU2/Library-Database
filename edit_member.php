<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// If the form has been submitted, update the member record in the database
if (isset($_POST['submit'])) {
    $memberId = $_POST['memberId'];
    $firstName = $_POST['Fname'];
    $lastName = $_POST['Lname'];
    $addressId = $_POST['addressId'];
    $memberTypeId = $_POST['memberTypeId'];
    $email = $_POST['email'];
    $phoneNum = $_POST['PhoneNum'];
    $active = isset($_POST['active']) ? 1 : 0;

    $updateSql = "UPDATE member SET Fname = ?, Lname = ?, AddressID = ?, MemberTypeID = ?, email = ?, PhoneNum = ?, Active = ? WHERE MemberID = ?";
    $updateStmt = $mysqli->prepare($updateSql);
    $updateStmt->bind_param("ssiissii", $firstName, $lastName, $addressId, $memberTypeId, $email, $phoneNum, $active, $memberId);
    $updateStmt->execute();
    $updateStmt->close();

    header("Location: success_edit_member.php");
    exit();
}

// If the member ID is not set, redirect to the member list page
if (!isset($_GET['id'])) {
    header("Location: report_member.php");
    exit();
}

// Get the member record from the database
$memberId = $_GET['id'];
$selectSql = "SELECT member.MemberID, member.Fname, member.Lname, member.AddressID, member.MemberTypeID, member.Email, member.Password, member.PhoneNum, member.Active, address.StreetAddress, address.City, address.State, address.ZipCode FROM member JOIN address ON member.AddressID = address.AddressID WHERE member.MemberID = ?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $memberId);
$selectStmt->execute();
$member = $selectStmt->get_result()->fetch_assoc();
$selectStmt->close();

// Get all member types
$selectSql = "SELECT MemberTypeID, Name FROM membertype";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$memberTypes = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$selectStmt->close();

// Get all addresses
$selectSql = "SELECT AddressID, CONCAT(StreetAddress, ', ', City, ', ', State, ' ', ZipCode) AS fullAddress FROM address";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$addresses = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$selectStmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <?php include 'empnav.php'; ?>

    <h1>Edit Member</h1>
    <form method="POST">
        <input type="hidden" name="memberId" value="<?= $member['MemberID'] ?>">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="Fname" value="<?= $member['Fname'] ?>" required>
<br>
<label for="lastName">Last Name:</label>
<input type="text" id="lastName" name="Lname" value="<?= $member['Lname'] ?>" required>
<br>
<label for="addressId">Address:</label>
<select id="addressId" name="addressId">
<?php foreach ($addresses as $address): ?>
<option value="<?= $address['AddressID'] ?>" <?php if ($address['AddressID'] == $member['AddressID'])
                   echo "selected" ?>>
<?= $address['fullAddress'] ?>
</option>
<?php endforeach; ?>
</select>
<br>
<label for="memberTypeId">Member Type:</label>
<select id="memberTypeId" name="memberTypeId">
<?php foreach ($memberTypes as $memberType): ?>
<option value="<?= $memberType['MemberTypeID'] ?>" <?php if ($memberType['MemberTypeID'] == $member['MemberTypeID'])
                   echo "selected" ?>>
<?= $memberType['Name'] ?>
</option>
<?php endforeach; ?>
</select>
<br>
<label for="email">Email:</label>
<input type="email" id="email" name="email" value="<?= $member['Email'] ?>" required>
<br>
<label for="phoneNum">Phone Number:</label>
<input type="tel" id="phoneNum" name="PhoneNum" value="<?= $member['PhoneNum'] ?>" required>
<br>
<label for="active">Active:</label>
<input type="checkbox" id="active" name="active" <?php if ($member['Active'])
         echo "checked" ?>>
<br>
<input type="submit" name="submit" value="Save">
<a href="report_member.php">Cancel</a>
</form>
</body>
</html>
