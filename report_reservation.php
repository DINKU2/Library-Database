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
    // Build WHERE clause based on search parameters
    $whereClause = "";
    $queryParams = array();
    if (!empty($memberId)) {
        $whereClause .= " AND reservation.MemberID = ?";
        $queryParams[] = $memberId;
    }
    if (!empty($libraryId)) {
        $whereClause .= " AND library.LibraryID = ?";
        $queryParams[] = $libraryId;
    }
    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND reservation.ReservationDate BETWEEN ? AND ?";
        $queryParams[] = $startDate;
        $queryParams[] = $endDate;
    } elseif (!empty($startDate)) {
        $whereClause .= " AND reservation.ReservationDate >= ?";
        $queryParams[] = $startDate;
    }
    $status = $_GET['status'];
    if ($status !== "") {
        $whereClause .= " AND reservation.status = ?";
        $queryParams[] = $status;
    }

    // Get reservations based on search parameters
    $selectSql = "SELECT reservation.ReservationID, member.Fname, member.Lname, item.Name AS Title, library.LibraryName, reservation.ReservationDate, reservation.status
                    FROM reservation
                    JOIN member ON reservation.MemberID = member.MemberID
                    JOIN copy ON reservation.CopyID = copy.CopyID
                    JOIN item ON copy.ItemID = item.ItemID
                    JOIN library ON copy.LibraryID = library.LibraryID
                    WHERE 1" . $whereClause . "
                    ORDER BY reservation.ReservationID DESC";
    $selectStmt = $mysqli->prepare($selectSql);
    $selectStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
    $selectStmt->execute();
    $reservations = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    function getStatusText($status)
    {
        switch ($status) {
            case 0:
                return "Open";
            case 1:
                return "Closed";
            default:
                return "Unknown";
        }
    }
}

?>



<!DOCTYPE html>
<html>

<head>
    <title>Reservation Report</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Reservation Report</h1>

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
            <option value="0">Open</option>
            <option value="1">Closed</option>
        </select>

        <input type="submit" name="submit" value="Search">
    </form>

    <?php if (isset($_GET['submit'])): ?>
        <?php if (empty($reservations)): ?>
            <p>No reservations found.</p>
        <?php else: ?>
            <h1>Showing
                <?= count($reservations) ?> reservations.
            </h1>
            <table>
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Member Name</th>
                        <th>Item</th>
                        <th>Library Name</th>
                        <th>Reserved Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td>
                                <?= $reservation['ReservationID'] ?>
                            </td>
                            <td>
                                <?= $reservation['Lname'] . ", " . $reservation['Fname'] ?>
                            </td>
                            <td>
                                <?= $reservation['Title'] ?>
                            </td>
                            <td>
                                <?= $reservation['LibraryName'] ?>
                            </td>
                            <td>
                                <?= $reservation['ReservationDate'] ?>
                            </td>
                            <td>
                                <?= getStatusText($reservation['status']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>