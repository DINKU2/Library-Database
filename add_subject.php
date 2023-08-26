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

    if (!empty($name)) { // Check if $name is not empty
        $existingSql = "SELECT Name FROM Subject WHERE Name = '$name'";
        $existingResult = $mysqli->query($existingSql);
        if ($existingResult->num_rows > 0) { // Check if there are any existing records
            $message = "Error: The subject already exists.";
        } else {
            $sql = "INSERT INTO Subject (Name) VALUES ('$name')";
            $result = $mysqli->query($sql);
            if ($result) {
                $newSubjectId = mysqli_insert_id($mysqli);
                header("Location: successful_create_new_subject.php");
                exit;
            } else {
                $message = "Error: " . $mysqli->error;
            }
        }
    } else {
        $message = "Name field is required.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Subject</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Add New Subject</h1>
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
        <button type="submit">Add Subject</button>
    </form>
</body>

</html>