<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// Get all members
$selectSql = "SELECT MemberID, Fname, Lname FROM member ORDER BY Lname ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$members = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all libraries
$selectSql = "SELECT LibraryID, LibraryName FROM library ORDER BY LibraryName ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$libraries = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle search form submission
if (isset($_GET['submit'])) {
    // Get search parameters
    $memberId = $_GET['member'];
    $libraryId = $_GET['library'];
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $status = $_GET['status'];
    $lateFee = $_GET['late_fee'];
    // Build WHERE clause based on search parameters
    $whereClause = "";
    $queryParams = array();
    if (!empty($memberId)) {
        $whereClause .= " AND checkout.MemberID = ?";
        $queryParams[] = $memberId;
    }
    if (!empty($libraryId)) {
        $whereClause .= " AND library.LibraryID = ?";
        $queryParams[] = $libraryId;
    }
    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND checkout.StartDate BETWEEN ? AND ?";
        $queryParams[] = $startDate;
        $queryParams[] = $endDate;
    } elseif (!empty($startDate)) {
        $whereClause .= " AND checkout.StartDate >= ?";
        $queryParams[] = $startDate;
    }
    $status = $_GET['status'];
    if ($status !== "") {
        $whereClause .= " AND checkout.Status = ?";
        $queryParams[] = $status;
    }
    if ($lateFee === "1") {
        $whereClause .= " AND checkout.TotalLateFee > 0";
    }
    // Get checkouts based on search parameters
    $selectSql = "SELECT checkout.CheckoutID, member.Fname, member.Lname, item.Name AS Title, library.LibraryName, checkout.StartDate, checkout.EndDate, checkout.ReturnDate, checkout.TotalLateFee, checkout.Status, employee.Fname AS HandleEmployeeFname, employee.Lname AS HandleEmployeeLname, employee2.Fname AS ReturnEmployeeFname, employee2.Lname AS ReturnEmployeeLname
                    FROM checkout
                    JOIN member ON checkout.MemberID = member.MemberID
                    JOIN copy ON checkout.CopyID = copy.CopyID
                    JOIN item ON copy.ItemID = item.ItemID
                    JOIN library ON copy.LibraryID = library.LibraryID
                    LEFT JOIN handle ON checkout.CheckoutID = handle.CheckoutID
                    LEFT JOIN employee ON handle.EmployeeID = employee.EmployeeID
                    LEFT JOIN `return` ON checkout.CheckoutID = `return`.CheckoutID
                    LEFT JOIN employee AS employee2 ON `return`.EmployeeID = employee2.EmployeeID
                    WHERE 1" . $whereClause . "
                    ORDER BY checkout.CheckoutID DESC";
    $selectStmt = $mysqli->prepare($selectSql);
    $selectStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
    $selectStmt->execute();
    $checkouts = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    function getStatusText($status)
    {
        switch ($status) {
            case 0:
                return "Ordered";
            case 1:
                return "Handling";
            case 2:
                return "Returned";
            case 3:
                return "Lost";
            default:
                return "Unknown";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Checkout Report</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php
    include 'empnav.php';
    ?>

    <h1>Checkout Report</h1>

    <form method="GET">
        <label for="member">Member:</label>
        <select id="member" name="member">
            <option value="">All Members</option>
            <?php foreach ($members as $member): ?>
                <option value="<?= $member['MemberID'] ?>"><?= $member['Lname'] . ", " . $member['Fname'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="library">Library:</label>
        <select id="library" name="library">
            <option value="">All Libraries</option>
            <?php foreach ($libraries as $library): ?>
                <option value="<?= $library['LibraryID'] ?>"><?= $library['LibraryName'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="start_date">From:</label>
        <input type="date" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>">

        <label for="end_date">To:</label>
        <input type="date" id="end_date" name="end_date"
            value="<?= date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')))) ?>">

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="">All Statuses</option>
            <option value="0">Ordered</option>
            <option value="1">Handling</option>
            <option value="2">Returned</option>
            <option value="3">Lost</option>
        </select>

        <label for="late_fee">Late Fee:</label>
        <select id="late_fee" name="late_fee">
            <option value="0">All</option>
            <option value="1">With Late Fee</option>
        </select>

        <input type="submit" name="submit" value="Search">
    </form>

    <?php if (isset($_GET['submit'])): ?>
        <?php if (empty($checkouts)): ?>
            <p>No checkouts found.</p>
        <?php else: ?>
            <h1>Showing
                <?= count($checkouts) ?> checkouts.
            </h1>
            <table>
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Item</th>
                        <th>Library Name</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Late Fee</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($checkouts as $checkout): ?>
                        <tr>
                            <td>
                                <?= $checkout['Lname'] . ", " . $checkout['Fname'] ?>
                            </td>
                            <td>
                                <?= $checkout['Title'] ?>
                            </td>
                            <td>
                                <?= $checkout['LibraryName'] ?>
                            </td>
                            <td>
                                <?= $checkout['StartDate'] ?>
                            </td>
                            <td>
                                <?= $checkout['EndDate'] ?>
                            </td>
                            <td>
                                <?= $checkout['ReturnDate'] ?>
                            </td>
                            <td>
                                <?= getStatusText($checkout['Status']) ?>
                            </td>
                            <td>
                                <?= $checkout['TotalLateFee'] . '$' ?>
                            </td>
                            <td>
                                <a href='info_checkout.php?CheckoutID=<?= $checkout['CheckoutID'] ?>'>View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    <?php endif; ?>
</body>

</html>