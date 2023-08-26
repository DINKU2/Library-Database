<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
    header("Location: login.php");
    exit();
}

// Retrieve reservations for the member
$mysqli = require __DIR__ . "/database.php";
$selectSql = "SELECT r.ReservationID, r.CopyID, r.ReservationDate, r.ExpirationDate, r.Status, i.Name
              FROM reservation r
              LEFT JOIN copy c ON r.CopyID = c.CopyID
              LEFT JOIN item i ON c.ItemID = i.ItemID
              WHERE r.MemberID = ?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $_SESSION['MemberID']);
$selectStmt->execute();
$result = $selectStmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Reservations</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php
    include 'membernav.php';
    ?>
    <h2>Reservations</h2>
    <?php if ($result->num_rows === 0): ?>
        <p>You have no reservations.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Reservation Date</th>
                    <th>Expiration Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= $row["Name"] ?>
                        </td>
                        <td>
                            <?= $row["ReservationDate"] ?>
                        </td>
                        <td>
                            <?= $row["ExpirationDate"] ?>
                        </td>
                        <td>
                            <?php switch ($row["Status"]):
                                case 0: ?>
                                    <form action="cancel_reservation.php" method="post">
                                        <input type="hidden" name="ReservationID" value="<?= $row["ReservationID"] ?>">
                                        <button type="submit">Cancel</button>
                                    </form>
                                <?php break;
                                case 1: ?>
                                    Closed
                                <?php break;
                                default: ?>
                                    Unknown
                            <?php endswitch; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>
