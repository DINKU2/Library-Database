<?php
session_start();

if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}
// Connect to the database
$mysqli = require __DIR__ . "/database.php";

// Check if the user submitted the form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['member_to_delete'])) {
    // Set the member as inactive by updating the `Active` field to 0
    $sql = "UPDATE member SET Active = 0 WHERE MemberID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_POST['member_to_delete']);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Member successfully deleted.";
    } else {
        echo "Error deleting member: " . $stmt->error;
    }
}

// Pagination
$members_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($current_page - 1) * $members_per_page;

// Get all members and their addresses
$sql = "SELECT member.*, address.StreetAddress, address.City, address.State, address.ZipCode
        FROM member
        JOIN address ON member.AddressID = address.AddressID
        LIMIT $offset, $members_per_page";
$result = $mysqli->query($sql);

// Count total members
$count_sql = "SELECT COUNT(*) FROM member";
$count_result = $mysqli->query($count_sql);
$row = $count_result->fetch_row();
$total_members = $row[0];
$total_pages = ceil($total_members / $members_per_page);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Delete Member</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Delete Member</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="member_to_delete">Member ID to delete:</label>
        <input type="number" id="member_to_delete" name="member_to_delete" required>
        <button type="submit">Delete Member</button>
    </form>

    <h2>All Members</h2>
    <table>
        <thead>
            <tr>
                <th>MemberID</th>
                <th>MemberTypeID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Email</th>
                <th>PhoneNum</th>
                <th>Active</th>
                <th>Street Address</th>
                <th>City</th>
                <th>State</th>
                <th>ZipCode</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= $row["MemberID"] ?>
                    </td>
                    <td>
                        <?= $row["MemberTypeID"] ?>
                    </td>
                    <td>
                        <?= $row["Lname"] ?>
                    </td>
                    <td>
                        <?= $row["Fname"] ?>
                    </td>
                    <td>
                        <?= $row["Email"] ?>
                    </td>
                    <td>
                        <?= $row["PhoneNum"] ?>
                    </td>
                    <td>
                        <?= $row["Active"] ? "Yes" : "No" ?>
                    </td>
                    <td>
                        <?= $row["StreetAddress"] ?>
                    </td>
                    <td>
                        <?= $row["City"] ?>
                    </td>
                    <td>
                        <?= $row["State"] ?>
                    </td>
                    <td>
                        <?= $row["ZipCode"] ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="pagination">
        <?php for ($page = 1; $page <= $total_pages; $page++): ?>
            <?php if ($page == $current_page): ?>
                <strong>
                    <?= $page ?>
                </strong>
            <?php else: ?>
                <a href="?page=<?= $page ?>"><?= $page ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</body>

</html>