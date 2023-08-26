<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// Get all item types
$selectSql = "SELECT ItemTypeID, Name FROM itemtype ORDER BY Name ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$itemTypes = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all subjects
$selectSql = "SELECT SubjectID, Name FROM subject ORDER BY Name ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$subjects = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get the item to edit
$itemId = $_GET['itemid'];
$selectSql = "SELECT * FROM item WHERE ItemID = ?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param('i', $itemId);
$selectStmt->execute();
$item = $selectStmt->get_result()->fetch_assoc();

// Handle form submission
if (isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $typeId = $_POST['type'];
    $serialOrIsbn = $_POST['serial_or_isbn'];
    $publisher = $_POST['publisher'];
    $creator = $_POST['creator'];
    $subjectId = $_POST['subject'];
    $available = isset($_POST['available']) ? 1 : 0;

// Update the item
$updateSql = "UPDATE item SET Name = ?, TypeID = ?, SerialOrISBN = ?, Publisher = ?, Creator = ?, SubjectID = ?, Available = ? WHERE ItemID = ?";
$updateStmt = $mysqli->prepare($updateSql);
$updateStmt->bind_param('sisssiii', $name, $typeId, $serialOrIsbn, $publisher, $creator, $subjectId, $available, $itemId);
$updateStmt->execute();




    // Redirect back to the item report page
    header("Location: success_edit_item.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Item</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php include 'empnav.php'; ?>
    <h1>Edit Item</h1>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $item['Name'] ?>" required>
        <br>

        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="">Select a Type</option>
            <?php foreach ($itemTypes as $itemType): ?>
                <option value="<?= $itemType['ItemTypeID'] ?>" <?= $itemType['ItemTypeID'] == $item['TypeID'] ? 'selected' : '' ?>>
                    <?= $itemType['Name'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="serial_or_isbn">Serial/ISBN:</label>
        <input type="text" id="serial_or_isbn" name="serial_or_isbn" value="<?= $item['SerialOrISBN'] ?>" required>
        <br>
        <label for="publisher">Publisher:</label>
        <input type="text" id="publisher" name="publisher" value="<?= $item['Publisher'] ?>" required>
        <br>

        <label for="creator">Creator:</label>
        <input type="text" id="creator" name="creator" value="<?= $item['Creator'] ?>" required>
        <br>

        <label for="subject">Subject:</label>
        <select id="subject" name="subject" required>
            <option value="">Select a Subject</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?= $subject['SubjectID'] ?>" <?= $subject['SubjectID'] == $item['SubjectID'] ? 'selected' : '' ?>>
                    <?= $subject['Name'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="quantity">Quantity:</label>
<input type="text" id="quantity" name="quantity" value="<?= $item['Quantity'] ?>" readonly>
<br>


        <button type="submit" name="submit">Update Item</button>
    </form>
</body>

</html>