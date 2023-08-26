<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
    header("Location: login.php");
    exit();
}

// Get member ID
$member_id = $_SESSION["MemberID"];

// Retrieve checkouts and their total late fees for the member
$mysqli = require __DIR__ . "/database.php";
$selectSql = "SELECT checkout.PayFlag, checkout.CheckoutID, item.Name, checkout.StartDate, checkout.EndDate, checkout.ReturnDate, checkout.TotalLateFee
               FROM checkout
               JOIN copy ON checkout.CopyID = copy.CopyID
               JOIN item ON copy.ItemID = item.ItemID
               WHERE checkout.MemberID = ? AND (checkout.TotalLateFee > 0) AND checkout.PayFlag IS NULL";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $member_id);
$selectStmt->execute();
$result = $selectStmt->get_result();

// Calculate sum of total late fees
$selectSql = "SELECT SUM(TotalLateFee) AS sum_late_fees FROM checkout WHERE MemberID = ? AND (TotalLateFee > 0) AND PayFlag IS NULL";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $member_id);
$selectStmt->execute();
$resultSum = $selectStmt->get_result();
$rowSum = $resultSum->fetch_assoc();
$sum_late_fees = $rowSum["sum_late_fees"];

?>
<!DOCTYPE html>
<html>

<head>
    <title>Late Fees</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php
    include 'membernav.php';
    ?>
    <h1>Late Fees</h1>
    <?php if ($result->num_rows === 0): ?>
        <p>You have no late fees.</p>
    <?php else: ?>
        <p>Total Late Fees:
            <?= $sum_late_fees ?>
        </p>
        <table>
            <thead>
                <tr>
                    <th>Checkout ID</th>
                    <th>Item Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Return Date</th>
                    <th>Late Fee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= $row["CheckoutID"] ?>
                        </td>
                        <td>
                            <?= $row["Name"] ?>
                        </td>
                        <td>
                            <?= $row["StartDate"] ?>
                        </td>
                        <td>
                            <?= $row["EndDate"] ?>
                        </td>
                        <td>
                            <?= $row["ReturnDate"] ?>
                        </td>
                        <td>
                            <?= $row["TotalLateFee"] ?>
                        </td>
                        <td>
                            <?php if ($row["TotalLateFee"] > 0 && $row["PayFlag"] === null): ?>
                                <a href="pay_late_fee.php?id=<?= $row["CheckoutID"] ?>" class="button">Pay</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>