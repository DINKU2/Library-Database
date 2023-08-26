<?php
session_start();

if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}

// Check connection
$mysqli = require __DIR__ . "/database.php";

// Get list of item types
$sql = "SELECT ItemTypeID, Name FROM ItemType";
$result = $mysqli->query($sql);
$itemTypes = array();
while ($row = $result->fetch_assoc()) {
    $itemTypes[$row["ItemTypeID"]] = $row["Name"];
}

// Get list of subjects
$sql = "SELECT SubjectID, Name FROM Subject";
$result = $mysqli->query($sql);
$subjects = array();
while ($row = $result->fetch_assoc()) {
    $subjects[$row["SubjectID"]] = $row["Name"];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $typeID = $mysqli->real_escape_string($_POST["typeID"]);
    $name = $mysqli->real_escape_string($_POST["name"]);
    $description = $mysqli->real_escape_string($_POST["description"]);
    $serialOrISBN = $mysqli->real_escape_string($_POST["serialOrISBN"]);
    $publisher = $mysqli->real_escape_string($_POST["publisher"]);
    $replacementCost = $mysqli->real_escape_string($_POST["replacementCost"]);
    $creator = $mysqli->real_escape_string($_POST["creator"]);

    // Check if item type is selected
    if (empty($_POST["typeID"])) {
        $error = "Item type is required.";
    } else {
        // Check if subject is selected
        if (empty($_POST["subjectID"])) {
            $error = "Subject is required.";
        } else {
            $subjectID = $mysqli->real_escape_string($_POST["subjectID"]);
            $sql = "INSERT INTO Item (TypeID, Name, Description, SerialOrISBN, Publisher, ReplacementCost, Creator, Available, SubjectID) VALUES ('$typeID', '$name', '$description', '$serialOrISBN', '$publisher', '$replacementCost', '$creator', 0, '$subjectID')";
            $result = $mysqli->query($sql);
            if ($result) {
                $newItemId = mysqli_insert_id($mysqli);
                header("Location: item.php?id=" . $newItemId);
                exit;
            } else {
                $message = "Error: " . $mysqli->error;
            }
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Item</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Add New Item</h1>
    <?php if (!empty($message)): ?>
        <p>
            <?= $message ?>
        </p>
    <?php endif; ?>
    <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>">
        <div>
            <label for="typeID">Item Type:</label>
            <select name="typeID" required>
                <option value="">-- Select Item Type --</option>
                <?php foreach ($itemTypes as $id => $name): ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="subjectID">Subject:</label>
            <select name="subjectID" required>
                <option value="">-- Select Subject --</option>
                <?php foreach ($subjects as $id => $name): ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="description">Description:</label"> <textarea name="description"></textarea>
        </div>
        <div>
            <label for="serialOrISBN">Serial or ISBN:</label>
            <input type="text" name="serialOrISBN">
        </div>
        <div>
            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher">
        </div>
        <div>
            <label for="creator">Creator:</label>
            <input type="text" name="creator" required>
        </div>
        <div>
            <label for="replacementCost">Replacement Cost:</label>
            <input type="number" name="replacementCost" step="0.01" min="0">
        </div>
        <button type="submit">Add Item</button>
    </form>
</body>

</html>