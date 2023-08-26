<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// Get checkout data
$checkoutId = $_GET['CheckoutID'];
$selectSql = "SELECT checkout.CheckoutID, member.Fname, member.Lname, item.Name AS Title, library.LibraryName, checkout.StartDate, checkout.EndDate, checkout.ReturnDate, checkout.TotalLateFee, checkout.Status, employee.Fname AS HandleEmployeeFname, employee.Lname AS HandleEmployeeLname, employee2.Fname AS ReturnEmployeeFname, employee2.Lname AS ReturnEmployeeLname, handle.EmployeeID AS HandleEmployeeID, `return`.EmployeeID AS ReturnEmployeeID
                FROM checkout
                JOIN member ON checkout.MemberID = member.MemberID
                JOIN copy ON checkout.CopyID = copy.CopyID
                JOIN item ON copy.ItemID = item.ItemID
                JOIN library ON copy.LibraryID = library.LibraryID
                LEFT JOIN handle ON checkout.CheckoutID = handle.CheckoutID
                LEFT JOIN employee ON handle.EmployeeID = employee.EmployeeID
                LEFT JOIN `return` ON checkout.CheckoutID = `return`.CheckoutID
                LEFT JOIN employee AS employee2 ON `return`.EmployeeID = employee2.EmployeeID
                WHERE checkout.CheckoutID = ?";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->bind_param("i", $checkoutId);
$selectStmt->execute();
$checkout = $selectStmt->get_result()->fetch_assoc();

// Redirect to checkout report page if checkout not found
if (!$checkout) {
    $_SESSION['error'] = "Checkout not found.";
    header("Location: checkout_report.php");
    exit();
}

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

?>
<!DOCTYPE html>
<html>

<head>
    <title>Checkout Details</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php
    include 'empnav.php';
    ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']) ?>
    <?php endif; ?>

    <div>
        <h2>Checkout Details</h2>
        <table>
            <tr>
                <td><strong>Checkout ID:</strong></td>
                <td>
                    <?= $checkout['CheckoutID'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Member:</strong></td>
                <td>
                    <?= $checkout['Lname'] . ", " . $checkout['Fname'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Title:</strong></td>
                <td>
                    <?= $checkout['Title'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Library:</strong></td>
                <td>
                    <?= $checkout['LibraryName'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Start Date:</strong></td>
                <td>
                    <?= $checkout['StartDate'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Due Date:</strong></td>
                <td>
                    <?= $checkout['EndDate'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Return Date:</strong></td>
                <td>
                    <?= ($checkout['ReturnDate']) ? $checkout['ReturnDate'] : "N/A" ?>
                </td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <?= getStatusText($checkout['Status']) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Total Late Fee:</strong></td>
                <td>
                    <?= $checkout['TotalLateFee'] ?>
                </td>
            </tr>
            <tr>
                <td><strong>Handling Employee:</strong></td>
                <td>
                    <?= ($checkout['HandleEmployeeFname'] && $checkout['HandleEmployeeLname']) ? $checkout['HandleEmployeeLname'] . ", " . $checkout['HandleEmployeeFname'] : "N/A" ?>
                </td>
            </tr>
            <tr>
                <td><strong>Return Employee:</strong></td>
                <td>
                    <?= ($checkout['ReturnEmployeeFname'] && $checkout['ReturnEmployeeLname']) ? $checkout['ReturnEmployeeLname'] . ", " . $checkout['ReturnEmployeeFname'] : "N/A" ?>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>