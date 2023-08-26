<?php
session_start();

if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}
// Get message count for current user
$mysqli = require __DIR__ . "/database.php";
// Query to retrieve list of libraries
$query = "SELECT LibraryID, LibraryName FROM library ORDER BY LibraryName";
$result = $mysqli->query($query);
$mysqli->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Employee Signup</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>

    <h1>Employee Signup</h1>

    <form action="process-emp-signup.php" method="post">
        <div>
            <label for="Fname">First Name</label>
            <input type="text" id="Fname" name="Fname" required>
        </div>

        <div>
            <label for="Lname">Last Name</label>
            <input type="text" id="Lname" name="Lname" required>
        </div>

        <div>
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>
        </div>
        <div>
            <label for="Password">Password</label>
            <input type="password" id="Password" name="Password" required>
        </div>

        <div>
            <label for="Password_confirmation">Repeat password</label>
            <input type="password" id="Password_confirmation" name="Password_confirmation" required>
        </div>
        <div>
            <label for="PhoneNum">Phone Number</label>
            <input type="tel" id="PhoneNum" name="PhoneNum" required>
        </div>

        <div>
            <label for="LibraryID">Library</label>
            <select id="LibraryID" name="LibraryID" required>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["LibraryID"] . "'>" . $row["LibraryName"] . "</option>";
                }
                ?>
            </select>
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



        <button type="submit">Submit</button>
    </form>

</body>

</html>