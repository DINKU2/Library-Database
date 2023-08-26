<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Query to get total number of items
$totalItemsSql = "SELECT COUNT(*) FROM item";
$totalItemsResult = $mysqli->query($totalItemsSql);
$totalItems = $totalItemsResult->fetch_row()[0];

// Query to get total number of copies for each item
$totalCopiesSql = "SELECT i.Name, COUNT(*) as TotalCopies FROM item i 
                   INNER JOIN copy c ON i.ItemID = c.ItemID 
                   GROUP BY i.ItemID";
$totalCopiesResult = $mysqli->query($totalCopiesSql);

// Query to get total number of checkouts that are currently ongoing
$totalCheckoutsOngoingSql = "SELECT COUNT(*) FROM checkout WHERE ReturnDate IS NULL";
$totalCheckoutsOngoingResult = $mysqli->query($totalCheckoutsOngoingSql);
$totalCheckoutsOngoing = $totalCheckoutsOngoingResult->fetch_row()[0];

// Query to get total number of checkouts that are overdue
$totalCheckoutsOverdueSql = "SELECT COUNT(*) FROM checkout WHERE ReturnDate IS NULL";
$totalCheckoutsOverdueResult = $mysqli->query($totalCheckoutsOverdueSql);
$totalCheckoutsOverdue = $totalCheckoutsOverdueResult->fetch_row()[0];

// Query to get total checkouts for the month
$totalCheckoutsSql = "SELECT COUNT(*) FROM checkout WHERE MONTH(StartDate) = MONTH(CURRENT_DATE()) AND YEAR(StartDate) = YEAR(CURRENT_DATE())";
$totalCheckoutsResult = $mysqli->query($totalCheckoutsSql);
$totalCheckouts = $totalCheckoutsResult->fetch_row()[0];

// Query to get total reservations for the month
$totalReservationsSql = "SELECT COUNT(*) FROM reservation WHERE MONTH(ReservationDate) = MONTH(CURRENT_DATE()) AND YEAR(ReservationDate) = YEAR(CURRENT_DATE())";
$totalReservationsResult = $mysqli->query($totalReservationsSql);
$totalReservations = $totalReservationsResult->fetch_row()[0];

// Query to get member who made the most checkouts this month
$mostCheckoutsSql = "SELECT m.Fname, m.Lname, COUNT(*) as TotalCheckouts FROM member m 
                    INNER JOIN checkout c ON m.MemberID = c.MemberID
                    GROUP BY m.MemberID 
                    ORDER BY TotalCheckouts DESC LIMIT 1";
$mostCheckoutsResult = $mysqli->query($mostCheckoutsSql);
$mostCheckoutsRow = $mostCheckoutsResult->fetch_assoc();
$mostCheckoutsMember = $mostCheckoutsRow["Fname"] . " " . $mostCheckoutsRow["Lname"];
$mostCheckoutsCount = $mostCheckoutsRow["TotalCheckouts"];

// Query to get the most checked out item
$mostCheckedOutSql = "SELECT i.Name, i.SerialOrISBN, COUNT(*) as TotalCheckouts FROM item i 
                      INNER JOIN copy c ON i.ItemID = c.ItemID 
                      INNER JOIN checkout ch ON c.CopyID = ch.CopyID  
                      GROUP BY i.ItemID 
                      ORDER BY TotalCheckouts DESC LIMIT 1";
$mostCheckedOutResult = $mysqli->query($mostCheckedOutSql);
$mostCheckedOutRow = $mostCheckedOutResult->fetch_assoc();
$mostCheckedOutName = $mostCheckedOutRow["Name"];
$mostCheckedOutSerialOrISBN = $mostCheckedOutRow["SerialOrISBN"];
$mostCheckedOutCount = $mostCheckedOutRow["TotalCheckouts"];

// Query to get the most reserved item this month
$mostReservedSql = "SELECT i.Name, i.SerialOrISBN, COUNT(*) as TotalReservations FROM item i 
                    INNER JOIN copy c ON i.ItemID = c.ItemID 
                    INNER JOIN reservation r ON c.CopyID = r.CopyID
                    GROUP BY i.ItemID 
                    ORDER BY TotalReservations DESC LIMIT 1";
$mostReservedResult = $mysqli->query($mostReservedSql);
$mostReservedRow = $mostReservedResult->fetch_assoc();
$mostReservedName = $mostReservedRow["Name"];
$mostReservedSerialOrISBN = $mostReservedRow["SerialOrISBN"];
$mostReservedCount = $mostReservedRow["TotalReservations"];

// Close database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Library Statistics</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<div>
    <a href="emain.php"><button>Home</button></a>
    <a href="logout.php"><button>Sign Out</button></a>
</div>

<body>
    <h1>Library Statistics</h1>

    <h2>Overall Statistics</h2>
    <p><a href="report_total_items.php">Total Number of Items:</a>
        <?php echo $totalItems; ?>
    </p>
    <p><a href="report_total_checkouts.php">Total Number of Checkouts:</a>
        <?php echo $totalCheckouts; ?>
    </p>
    <p><a href="report_total_reservations.php">Total Number of Reservations:</a>
        <?php echo $totalReservations; ?>
    </p>
    <p><a href="report_total_ongoing_checkouts.php">Total Number of Ongoing Checkouts:</a>
        <?php echo $totalCheckoutsOngoing; ?>
    </p>
    <p>Total Number of Overdue Checkouts:</a>
        <?php echo $totalCheckoutsOverdue; ?>
    </p>

    <h2>Item Statistics</h2>
    <p><a href="report_most_checked_out_item.php">Most Checked Out Item:</a>
        <?php echo $mostCheckedOutName; ?> (
        <?php echo $mostCheckedOutSerialOrISBN; ?>) -
        <?php echo $mostCheckedOutCount; ?> checkouts
    </p>
    <p><a href="report_most_reserved_item.php">Most Reserved Item This Month:</a>
        <?php echo $mostReservedName; ?> (
        <?php echo $mostReservedSerialOrISBN; ?>) -
        <?php echo $mostReservedCount; ?> reservations
    </p>

    <h2>Member Statistics</h2>
    <p><a href="report_most_checkouts_member.php">Member with Most Checkouts This Month:</a>
        <?php echo $mostCheckoutsMember; ?> -
        <?php echo $mostCheckoutsCount; ?> checkouts
    </p>

</body>

</html>