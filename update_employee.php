<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

if (isset($_POST['submit'])) {
    // Get form data
    $employeeId = $_POST['employee_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $active = isset($_POST['active']) ? 1 : 0;

    // Update employee in database
    $updateSql = "UPDATE employee SET Fname=?, Lname=?, PhoneNum=?, Email=?, Active=? WHERE EmployeeID=?";
    $updateStmt = $mysqli->prepare($updateSql);
    $updateStmt->bind_param("ssssii", $firstName, $lastName, $phone, $email, $active, $employeeId);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect to success page
    header("Location: success_update_employee.php");
    exit();
}

// Get employee information from database
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // If ID is not provided or is not a number, redirect to employee report page
    header("Location: report_employee.php");
    exit();
}
$employeeId = $_GET['id'];
$selectSql = "SELECT * FROM employee WHERE EmployeeID=?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $employeeId);
$selectStmt->execute();
$employee = $selectStmt->get_result()->fetch_assoc();
$selectStmt->close();

// Get all libraries
$selectSql = "SELECT LibraryID, LibraryName FROM library ORDER BY LibraryName ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$libraries = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Employee</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>
<body>
    <h1>Update Employee</h1>

    <form method="POST">
        <input type="hidden" name="employee_id" value="<?= $employee['EmployeeID'] ?>">

        <label for="employee_id">Employee ID:</label>
        <input type="number" id="employee_id" name="employee_id" value="<?= $employee['EmployeeID'] ?>" readonly>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?= $employee['Fname'] ?>">

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?= $employee['Lname'] ?>">

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?= $employee['PhoneNum'] ?>">

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?= $employee['Email'] ?>">

        <label for="library">Library:</label </label>
        <select id="library" name="library">
            <?php foreach ($libraries as $library): ?>
                <option value="<?= $library['LibraryID'] ?>" <?= ($employee['LibraryID'] == $library['LibraryID']) ? "selected" : "" ?>>
                    <?= $library['LibraryName'] ?>
                </option>
            <?php endforeach; ?>
        </select> <label for="active">Active:</label>
        <input type="checkbox" id="active" name="active" value="1" <?= ($employee['Active'] == 1) ? "checked" : "" ?>>
        <input type="submit" name="submit" value="Update">
    </form>
</body>

</html>