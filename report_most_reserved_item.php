<?php

// Connect to database
$mysqli = require __DIR__ . "/database.php";

?>
<!DOCTYPE html>
<html>

<body>
    <div>
        <a href="emain.php"><button>Home</button></a>
        <a href="report.php"><button>Report</button></a>
        <a href="logout.php"><button>Sign Out</button></a>
    </div>

    <head>
        <meta charset="UTF-8">
        <title>Most Reserved Item</title>
        <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    </head>

    <body>
        <?php

        // Query to get the most reserved item this month
        $mostReservedSql = "SELECT i.Name, i.SerialOrISBN, COUNT(*) as TotalReservations FROM item i 
                    INNER JOIN copy c ON i.ItemID = c.ItemID 
                    INNER JOIN reservation r ON c.CopyID = r.CopyID
                    GROUP BY i.ItemID 
                    ORDER BY TotalReservations DESC LIMIT 1";

        // Execute the query and get the results
        $mostReservedResult = $mysqli->query($mostReservedSql);

        // Check if there are any results
        if ($mostReservedResult->num_rows > 0) {
            // Fetch the first row of the results as an associative array
            $mostReservedRow = $mostReservedResult->fetch_assoc();

            // Get the name, serial/ISBN number, and total number of reservations for the most reserved item
            $mostReservedName = $mostReservedRow["Name"];
            $mostReservedSerialOrISBN = $mostReservedRow["SerialOrISBN"];
            $mostReservedCount = $mostReservedRow["TotalReservations"];

            // Print the most reserved item and its details
            echo "<h2>The most reserved item this month is:</h2>";
            echo "<p>" . $mostReservedName . " (" . $mostReservedSerialOrISBN . ") with a total of " . $mostReservedCount . " reservations.</p>";

            // Query to get all reservations for the most reserved item
            $reservationsSql = "SELECT r.ReservationID, r.MemberID, c.CopyID, r.ReservationDate FROM copy c
                        INNER JOIN reservation r ON c.CopyID = r.CopyID 
                        WHERE c.ItemID = (SELECT ItemID FROM item WHERE SerialOrISBN = '$mostReservedSerialOrISBN') 
                        ORDER BY r.ReservationDate DESC";

            // Execute the query and get the results
            $reservationsResult = $mysqli->query($reservationsSql);

            // Check if there are any results
            if ($reservationsResult->num_rows > 0) {
                // Print the reservations table
                echo "<table>";
                echo "<tr><th>Reservation ID</th><th>Member ID</th><th>Copy ID</th><th>Reservation Date</th></tr>";
                while ($row = $reservationsResult->fetch_assoc()) {
                    echo "<tr><td>" . $row["ReservationID"] . "</td><td>" . $row["MemberID"] . "</td><td>" . $row["CopyID"] . "</td><td>" . $row["ReservationDate"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                // If there are no results, print a message indicating that no reservations have been made for the most reserved item
                echo "<p>No reservations have been made for this item.</p>";
            }
        } else {
            // If there are no results, print a message indicating that no items have been reserved this month
            echo "<p>No items have been reserved this month.</p>";
        }

        // Close the database connection
