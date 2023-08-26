<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Retrieve all data from the "item" table
$sql = "SELECT * FROM item";
$result = $mysqli->query($sql);

// Retrieve the most checked out item for this month and year, including checkout dates
$mostCheckedOutSql = "SELECT i.Name, i.SerialOrISBN, ch.StartDate FROM item i 
                      INNER JOIN copy c ON i.ItemID = c.ItemID 
                      INNER JOIN checkout ch ON c.CopyID = ch.CopyID 
                      WHERE MONTH(ch.StartDate) = MONTH(CURRENT_DATE()) AND YEAR(ch.StartDate) = YEAR(CURRENT_DATE()) AND i.ItemID = (
                        SELECT i2.ItemID FROM item i2 
                        INNER JOIN copy c2 ON i2.ItemID = c2.ItemID 
                        INNER JOIN checkout ch2 ON c2.CopyID = ch2.CopyID 
                        WHERE MONTH(ch2.StartDate) = MONTH(CURRENT_DATE()) AND YEAR(ch2.StartDate) = YEAR(CURRENT_DATE()) 
                        GROUP BY i2.ItemID 
                        ORDER BY COUNT(*) DESC LIMIT 1
                      )";
$mostCheckedOutResult = $mysqli->query($mostCheckedOutSql);




// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Total Item Report</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <div>
        <a href="emain.php"><button>Home</button></a>
        <a href="report.php"><button>Report</button></a>
        <a href="logout.php"><button>Sign Out</button></a>
    </div>
    <h1>Report</h1>

    <h2>Most Checked Out Item:
        <?php echo $mostCheckedOutResult->num_rows > 0 ? $mostCheckedOutResult->fetch_assoc()['Name'] : "N/A"; ?>
    </h2>
    <?php if ($mostCheckedOutResult->num_rows > 0): ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Serial or ISBN</th>
                <th>Checkout Date</th>
            </tr>
            <?php while ($row = $mostCheckedOutResult->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php echo $row['Name']; ?>
                    </td>
                    <td>
                        <?php echo $row['SerialOrISBN']; ?>
                    </td>
                    <td>
                        <?php echo $row['StartDate']; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No checkouts found for this month.</p>
    <?php endif; ?>

</body>

</html>