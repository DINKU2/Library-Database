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
    $max_borrowing_days = (int)$_POST["max_borrowing_days"];
    $max_items = (int)$_POST["max_items"];

    if (!empty($name) && $max_borrowing_days > 0 && $max_items > 0) {
        // Check if $name is not empty and other fields are valid
        $existingSql = "SELECT Name FROM Membertype WHERE Name = '$name'";
        $existingResult = $mysqli->query($existingSql);
        if ($existingResult->num_rows > 0) { // Check if there are any existing records
            $message = "Error: The member type already exists.";
        } else {
            $sql = "INSERT INTO Membertype (Name, MaxBorrowingdays, MaxItems, ItemCount) 
                    VALUES ('$name', $max_borrowing_days, $max_items)";
            $result = $mysqli->query($sql);
            if ($result) {
                $newMemberTypeId = mysqli_insert_id($mysqli);
                header("Location: successful_create_new_membertype.php");
                exit;
            } else {
                $message = "Error: " . $mysqli->error;
            }
        }
    } else {
        $message = "Name, Max Borrowing Days and Max Items fields are required and Item Count field should be non-negative.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Member Type</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php include 'empnav.php'; ?>
    <h1>Add New Member Type</h1>
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
            <label for="max_borrowing_days">Max Borrowing Days:</label>
            <input type="number" name="max_borrowing_days" min="1" required>
        </div>
        <div>
            <label for="max_items">Max Items:</label>
            <input type="number" name="max_items" min="1" required>
        </div>
        <button type="submit">Add Member Type</button>
    </form>
</body>

</html>