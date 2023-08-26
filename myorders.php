<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["MemberID"])) {
    // Redirect the user to the login page
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";
// Get all checkouts and reservations made by the user
$sql = "SELECT co.CheckoutID, co.CopyID, co.StartDate, co.EndDate, co.ReturnDate, co.TotalLateFee, co.Status, i.Name, i.SerialOrISBN, library.LibraryName
        FROM checkout co
        LEFT JOIN copy c ON co.CopyID = c.CopyID
        LEFT JOIN item i ON c.ItemID = i.ItemID
        LEFT JOIN library ON c.LibraryID = library.LibraryID
        WHERE co.MemberID = ?
        ORDER BY co.CheckoutID DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_SESSION["MemberID"]);
$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT co.CheckoutID, co.CopyID, co.StartDate, co.EndDate, co.ReturnDate, co.TotalLateFee, co.Status, i.Name, i.SerialOrISBN, library.LibraryName
        FROM checkout co
        LEFT JOIN copy c ON co.CopyID = c.CopyID
        LEFT JOIN item i ON c.ItemID = i.ItemID
        LEFT JOIN library ON c.LibraryID = library.LibraryID
        WHERE co.MemberID = ? AND co.Status != 2
        ORDER BY co.CheckoutID DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_SESSION["MemberID"]);
$stmt->execute();
$resultongoing = $stmt->get_result();

$count = mysqli_num_rows($resultongoing);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Orders</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'membernav.php';
?>

<body>

    <h1>My Orders</h1>
    <h3><?php echo "You have $count ongoing orders."; ?></h3>
    <table>
        <thead>
            <tr>
                <th>OrderID</th>
                <th>Item Name</th>
                <th>Library</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Return Date</th>
                <th>Late Fee</th>

            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["CheckoutID"] . "</td>";
                    echo "<td>" . $row["Name"] . "</td>";
                    echo "<td>" . $row["LibraryName"] . "</td>";
                    echo "<td>" . $row["StartDate"] . "</td>";
                    echo "<td>" . $row["EndDate"] . "</td>";
                    echo "<td>";
                    switch ($row["Status"]) {
                        case 0:
                            echo "Ordered";
                            break;
                        case 1:
                            echo "Handling";
                            break;
                        case 2:
                            echo "Returned";
                            break;
                        case 3:
                            echo "Lost";
                            break;
                        default:
                            echo "Unknown";
                    }
                    echo "</td>";
                    echo "<td>" . $row["ReturnDate"] . "</td>";
                    echo "<td>" . $row["TotalLateFee"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No orders found.</td></tr>";
            }
            ?>

        </tbody>
    </table>
</body>

</html>