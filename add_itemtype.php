<?php
session_start();

if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}

// Check connection
$mysqli = require __DIR__ . "/database.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $mysqli->real_escape_string($_POST["name"]);
    $late_fee = (float)$_POST["late_fee"];

    if (!empty($name) && $late_fee >= 0) {
        // Check if $name is not empty and other fields are valid
        $existingSql = "SELECT Name FROM Itemtype WHERE Name = '$name'";
        $existingResult = $mysqli->query($existingSql);
        if ($existingResult->num_rows > 0) { // Check if there are any existing records
            $message = "Error: The item type already exists.";
        } else {
            $sql = "INSERT INTO Itemtype (Name, LateFee) VALUES ('$name', $late_fee)";
            $result = $mysqli->query($sql);
            if ($result) {
                $newItemTypeId = mysqli_insert_id($mysqli);
                header("Location: successful_create_itemtype.php");
                exit;
            } else {
                $message = "Error: " . $mysqli->error;
            }
        }
    } else {
        $message = "Name field is required and Late Fee field should be non-negative.";
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Item Type</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <?php include 'empnav.php'; ?>
    <h1>Add New Item Type</h1>
    <?php if (!empty($message)) : ?>
        <p>
            <?= $message ?>
        </p>
    <?php endif; ?>
    <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="late_fee">Late Fee:</label>
            <input type="number" name="late_fee" min="0" step="0.01" required>
        </div>
        <button type="submit">Add Item Type</button>
    </form>
</body>
</html>