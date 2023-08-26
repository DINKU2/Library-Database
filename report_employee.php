<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// Get all libraries
$selectSql = "SELECT LibraryID, LibraryName FROM library ORDER BY LibraryName ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$libraries = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle search form submission
if (isset($_GET['submit'])) {
    // Get search parameters
    $libraryId = $_GET['library'];
    $active = $_GET['active'];

    // Build WHERE clause based on search parameters
    $whereClause = "";
    $queryParams = array();
    if (!empty($libraryId)) {
        $whereClause .= " AND library.LibraryID = ?";
        $queryParams[] = $libraryId;
    }
    if (!empty($active)) {
        $whereClause .= " AND employee.Active = ?";
        $queryParams[] = $active;
    }

    // Get employees based on search parameters
    $selectSql = "SELECT employee.*, library.LibraryName FROM employee JOIN library ON employee.LibraryID = library.LibraryID WHERE 1=1 " . $whereClause;

    $selectStmt = $mysqli->prepare($selectSql);
    if (!empty($queryParams)) {
        $selectStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
    }
    $selectStmt->execute();
    $employees = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Employee Report</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Employee Report</h1>

    <form method="GET">
        <label for="library">Library:</label>
        <select id="library" name="library">
            <option value="">All Libraries</option>
            <?php foreach ($libraries as $library): ?>
                <option value="<?= $library['LibraryID'] ?>" <?= (isset($_GET['submit']) && $_GET['library'] == $library['LibraryID']) ? "selected" : "" ?>>
                    <?= $library['LibraryName'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="active">Active:</label>
        <select id="active" name="active">
            <option value="">All Employees</option>
            <option value="1" <?= (isset($_GET['submit']) && $_GET['active'] == "1") ? "selected" : "" ?>>Active Employees
            </option>
            <option value="0" <?= (isset($_GET['submit']) && $_GET['active'] == "0") ? "selected" : "" ?>>Inactive
                Employees</option>
        </select>

        <input type="submit" name="submit" value="Search">
    </form>

    <?php if (isset($_GET['submit'])): ?>
        <?php if (empty($employees)): ?>
            <p>No employees found.</p>
        <?php else: ?>
            <h1>Showing
                <?= count($employees) ?> employees.
            </h1>
            <table <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Active</th>
                    <th>Library</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td>
                                <?= $employee['EmployeeID'] ?>
                            </td>
                            <td>
                                <?= $employee['Fname'] ?>
                            </td>
                            <td>
                                <?= $employee['Lname'] ?>
                            </td>
                            <td>
                                <?= $employee['Email'] ?>
                            </td>
                            <td>
                                <?= $employee['PhoneNum'] ?>
                            </td>
                            <td>
                                <?= $employee['Active'] ? "Yes" : "No" ?>
                            </td>
                            <td>
                                <?= $employee['LibraryName'] ?>
                            </td>
                            <td>
                                <a href="update_employee.php?id=<?= $employee['EmployeeID'] ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>