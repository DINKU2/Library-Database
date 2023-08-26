<?php
session_start();

if (isset($_SESSION["UserType"]) && $_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}


// Check connection
$mysqli = require __DIR__ . "/database.php";

// Pagination
$members_per_page = 30;
$current_page = isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) !== false ? (int) $_GET['page'] : 1;
$offset = ($current_page - 1) * $members_per_page;

// Get all members
$selectSql = "SELECT * FROM member LIMIT $offset, $members_per_page";
$result = $mysqli->query($selectSql);

// Count total members
$count_sql = "SELECT COUNT(*) FROM member";
$count_result = $mysqli->query($count_sql);
$row = $count_result->fetch_row();
$total_members = $row[0];
$total_pages = ceil($total_members / $members_per_page);

// Get members based on search parameters
if (!empty($queryParams)) {
    $selectSql = "SELECT * FROM member WHERE " . implode(" AND ", array_fill(0, count($queryParams), " ? ")) . " LIMIT $offset, $members_per_page";
    $selectStmt = $mysqli->prepare($selectSql);
    $selectStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
    $selectStmt->execute();
    $members = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $members = $result->fetch_all(MYSQLI_ASSOC);
}

$mysqli->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Change Member type</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Search for members</h1>

    <h2>Showing <?= count($members) ?> members.</h2>

    <table>
        <thead>
            <tr>
                <th>Member ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member) : ?>
                <tr>
                    <td><?= $member['MemberID'] ?></td>
                    <td><?= $member['Fname'] ?></td>
                    <td><?= $member['Lname'] ?></td>
                    <td><?= $member['Email'] ?></td>
                    <td><?= $member['PhoneNum'] ?></td>
                    <td><?= $member['Active'] === true ? "Yes" : "No" ?></td>
                    <td>
                        <a href="emp_update_member.php?id=<?= $member['MemberID'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="pagination">
        <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
            <?php if ($page == $current_page) : ?>
                <strong><?= $page ?></strong>
            <?php else : ?>
                <a href="?page=<?= $page ?>"><?= $page ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</body>

</html>