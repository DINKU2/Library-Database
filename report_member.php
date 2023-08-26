<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// Get all distinct Zip codes
$selectSql = "SELECT DISTINCT(ZipCode) FROM address";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$zipCodes = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all distinct Member types
$selectSql = "SELECT MemberTypeID, Name FROM membertype";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$memberTypes = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Get all distinct Cities
$selectSql = "SELECT DISTINCT(City) FROM address";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$cities = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);





if (isset($_GET['submit'])) {
    $zipCode = $_GET['zipCode'];
    $memberType = $_GET['membertype'];
    $city = $_GET['city'];
    $Active = $_GET['Active_Status'];

    // Build the WHERE clause for the SQL query
    $whereClause = "1=1"; // Initialize with always true condition
    $params = array();
    if (!empty($zipCode)) {
        $whereClause .= " AND address.ZipCode = ?";
        $params[] = $zipCode;
    }
    if (!empty($memberType)) {
        $whereClause .= " AND membertype.MemberTypeID = ?";
        $params[] = $memberType;
    }
    if (!empty($city)) {
        $whereClause .= " AND address.City = ?";
        $params[] = $city;
    }
    if($Active === "1"){
        $whereClause .= " AND member.Active > 0";
    }
    else if($Active === "2"){
        $whereClause .= " AND member.Active < 1";
    }

    // Build the SQL query with the WHERE clause
    $selectSql = "SELECT member.MemberID, member.FName, member.LName, address.StreetAddress, address.City, address.State, address.ZipCode, membertype.Name AS membertypename, member.Active
                  FROM member 
                  JOIN address ON member.AddressID = address.AddressID
                  LEFT JOIN membertype ON member.MemberTypeID = membertype.MemberTypeID
                  WHERE " . $whereClause . "
                  ORDER BY member.LName, member.FName";
    $selectStmt = $mysqli->prepare($selectSql);
    if (!empty($params)) {
        $selectStmt->bind_param(str_repeat("s", count($params)), ...$params);
    }
    $selectStmt->execute();
    $members = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Member Report</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
    include 'empnav.php';
    ?>
<body>
    <h1>Member Report</h1>
    <form method="GET">
        <label for="zipCode">Zip Code:</label>
        <select id="zipCode" name="zipCode">
            <option value="">All Zip Codes</option>
            <?php foreach ($zipCodes as $zip): ?>
                <option value="<?= $zip['ZipCode'] ?>" <?php if (isset($_GET['zipCode']) && $_GET['zipCode'] == $zip['ZipCode']): ?> selected <?php endif; ?>>
                    <?= $zip['ZipCode'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="city">City:</label>
        <select id="city" name="city">
            <option value="">All Cities</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?= $city['City'] ?>" <?php if (isset($_GET['city']) && $_GET['city'] == $city['City']): ?>
                        selected <?php endif; ?>>
                    <?= $city['City'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="membertype">Member Type:</label>
        <select id="membertype" name="membertype">
            <option value="">All Member Types</option>
            <?php foreach ($memberTypes as $type): ?>
                <option value="<?= $type['MemberTypeID'] ?>" <?php if (isset($_GET['membertype']) && $_GET['membertype'] == $type['MemberTypeID']): ?> selected <?php endif; ?>>
                    <?= $type['Name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
         <br>
        <label for="Active_Status">Active Status</label>
        <select id="Active_Status" name="Active_Status">
            <option value="0">All</option>
            <option value="1">Active</option>
            <option value="2">Non-Active</option>
        </select>
        <input type="submit" name="submit" value="Search">
    </form>
    <?php if (isset($_GET['submit'])): ?>
        <?php if (empty($members)): ?>
            <p>No members found.</p>
        <?php else: ?>
            <h1>Showing
                <?= count($members) ?> Members.
            </h1>
            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Street Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip Code</th>
                        <th>Member Type</th>
                        <th>Active Status</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <?= $member['MemberID'] ?>
                            </td>
                            <td>
                                <?= $member['FName'] ?>
                            </td>
                            <td>
                                <?= $member['LName'] ?>
                            </td>
                            <td>
                                <?= $member['StreetAddress'] ?>
                            </td>
                            <td>
                                <?= $member['City'] ?>
                            </td>
                            <td>
                                <?= $member['State'] ?>
                            </td>
                            <td>
                                <?= $member['ZipCode'] ?>
                            </td>
                            <td>
                                <?= $member['membertypename'] ?>
                            </td>
                            <td>
                            <?= $member['Active'] ? 'Active' : 'Unactive' ?>
                             </td>
                            <td><a href="edit_member.php?id=<?= $member['MemberID'] ?>">Edit</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>