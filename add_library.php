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
    $phoneNum = $mysqli->real_escape_string($_POST["phoneNum"]);
    $email = $mysqli->real_escape_string($_POST["email"]);
    $StreetAddress = $mysqli->real_escape_string($_POST["StreetAddress"]);
    $City = $mysqli->real_escape_string($_POST["City"]);
    $State = $mysqli->real_escape_string($_POST["State"]);
    $ZipCode = $mysqli->real_escape_string($_POST["ZipCode"]);

    if (!empty($name) && !empty($phoneNum) && !empty($email) && !empty($City) && !empty($City) && !empty($State) && !empty($ZipCode)) { // Check if all fields are not empty
        $sql = "INSERT INTO address (StreetAddress, City, State, ZipCode) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $StreetAddress, $City, $State, $ZipCode);

        $existingSql = "SELECT LibraryName FROM Library WHERE LibraryName = '$name'";
        $existingResult = $mysqli->query($existingSql);
        if ($existingResult->num_rows > 0) { // Check if there are any existing records
            $message = "Error: The library already exists.";
        } else {
            if ($stmt->execute()) {
                // Address record created successfully
                $addressID = $stmt->insert_id;
                $sql = "INSERT INTO Library (AddressID,LibraryName, PhoneNum, Email) VALUES ('$addressID', '$name', '$phoneNum', '$email')";
                $result = $mysqli->query($sql);
                if ($result) {
                    $newLibraryId = mysqli_insert_id($mysqli);
                    header("Location: successful_create_new_library.php");
                    exit;
                } else {
                    $message = "Error: " . $mysqli->error;
                }
            }
        }
    } else {
        $message = "All fields are required.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Library</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Add New Library</h1>
    <?php if (!empty($message)) : ?>
        <p>
            <?= $message ?>
        </p>
    <?php endif; ?>
    <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>">

        <div>
            <label for="name">Library Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="phoneNum">Phone Number:</label>
            <input type="text" name="phoneNum" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="StreetAddress">Street Address</label>
            <input type="text" id="StreetAddress" name="StreetAddress" required>
        </div>

        <div>
            <label for="City">City</label>
            <input type="text" id="City" name="City" required>
        </div>

        <div>
            <label for="State">State</label>
            <input type="text" id="State" name="State" required>
        </div>

        <div>
            <label for="ZipCode">Zip Code</label>
            <input type="text" id="ZipCode" name="ZipCode" required>
        </div>
        <button type="submit">Add Library</button>
    </form>
</body>

</html>