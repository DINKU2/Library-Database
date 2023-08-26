<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$mysqli = require __DIR__ . "/database.php";

// Get all item types
$selectSql = "SELECT ItemTypeID, Name FROM itemtype ORDER BY Name ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$itemTypes = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all publishers
$selectSql = "SELECT DISTINCT Publisher FROM item ORDER BY Publisher ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$publishers = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all creators
$selectSql = "SELECT DISTINCT Creator FROM item ORDER BY Creator ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$creators = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$selectSql = "SELECT SubjectID, Name FROM subject ORDER BY Name ASC";
$selectStmt = $mysqli->prepare($selectSql);
$selectStmt->execute();
$subjects = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);



// Handle search form submission
if (isset($_GET['submit'])) {
    // Get search parameters
    $typeId = $_GET['type'];
    $available = $_GET['available'];
    $publisher = $_GET['publisher'];
    $creator = $_GET['creator'];
    $subject = $_GET['subject'];

    // Build WHERE clause based on search parameters
    $whereClause = "1=1";
    $queryParams = array();
    if (!empty($typeId)) {
        $whereClause .= " AND item.TypeID = ?";
        $queryParams[] = $typeId;
    }
    if ($available !== "") {
        $whereClause .= " AND item.Available = ?";
        $queryParams[] = $available;
    }
    if (!empty($publisher)) {
        $whereClause .= " AND item.Publisher = ?";
        $queryParams[] = $publisher;
    }
    if (!empty($creator)) {
        $whereClause .= " AND item.Creator = ?";
        $queryParams[] = $creator;
    }
    if (!empty($subject)) {
        $whereClause .= " AND item.SubjectID = ?";
        $queryParams[] = $subject;
    }
    // Get items based on search parameters
    $selectSql = "SELECT item.*, subject.Name AS SubjectName, itemtype.Name AS TypeName FROM item JOIN itemtype ON item.TypeID = itemtype.ItemTypeID  JOIN subject ON item.SubjectID = subject.SubjectID WHERE $whereClause";

    $selectStmt = $mysqli->prepare($selectSql);
    if (!empty($queryParams)) {
        $selectStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
    }
    $selectStmt->execute();
    $items = $selectStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Item Report</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php include 'empnav.php'; ?>
    <h1>Item Report</h1>

    <form method="GET">
        <label for="type">Type:</label>
        <select id="type" name="type">
            <option value="">All Types</option>
            <?php foreach ($itemTypes as $itemType): ?>
                <option value="<?= $itemType['ItemTypeID'] ?>" <?= (isset($_GET['submit']) && $_GET['type'] == $itemType['ItemTypeID'] ? 'selected' : '') ?>><?= $itemType['Name'] ?></option>
                $itemType['ItemTypeID'] ? 'selected' : '') ?>>
                <?= $itemType['Name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="available">Availability:</label>
        <select id="available" name="available">
            <option value="">All Items</option>
            <option value="1" <?= (isset($_GET['submit']) && $_GET['available'] == "1") ? 'selected' : '' ?>>Available
            </option>
            <option value="0" <?= (isset($_GET['submit']) && $_GET['available'] == "0") ? 'selected' : '' ?>>Not Available
            </option>
        </select>
        <br>

        <label for="publisher">Publisher:</label>
        <select id="publisher" name="publisher">
            <option value="">All Publishers</option>
            <?php foreach ($publishers as $publisher): ?>
                <option value="<?= $publisher['Publisher'] ?>" <?= (isset($_GET['submit']) && $_GET['publisher'] == $
                      $publisher['Publisher'] ? 'selected' : '') ?>><?= $publisher['Publisher'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="creator">Creator:</label>
        <select id="creator" name="creator">
            <option value="">All Creators</option>
            <?php foreach ($creators as $creator): ?>
                <option value="<?= $creator['Creator'] ?>" <?= (isset($_GET['submit']) && $_GET['creator'] == $creator['Creator']) ? 'selected' : '' ?>><?= $creator['Creator'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="subject">Subject:</label>
        <select id="subject" name="subject">
            <option value="">All Subjects</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?= $subject['SubjectID'] ?>" <?= (isset($_GET['submit']) && $_GET['subject'] == $subject['SubjectID'] ? 'selected' : '') ?>><?= $subject['Name'] ?></option>
                $subject['SubjectID'] ? 'selected' : '') ?>>
                <?= $subject['Name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="submit">Search</button>
    </form>

    <?php if (isset($_GET['submit'])): ?>
        <?php if (empty($items)): ?>
            <p>No items found.</p>
        <?php else: ?>
            <h3>Showing
                <?= count($items) ?> items.
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Available</th>
                        <th>Publisher</th>
                        <th>Creator</th>
                        <th>Subject ID</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?= $item['ItemID'] ?>
                            </td>
                            <td>
                                <?= $item['TypeName'] ?>
                            </td>
                            <td>
                                <?= $item['Name'] ?>
                            </td>
                            <td>
                                <?= $item['Available'] ? "Yes" : "No" ?>
                            </td>
                            <td>
                                <?= $item['Publisher'] ?>
                            </td>
                            <td>
                                <?= $item['Creator'] ?>
                            </td>
                            <td>
                                <?= $item['SubjectName'] ?>
                            </td>
                            <td>
                                <?= $item['Quantity'] ?>
                            </td>
                            <td>
                                <a href="edit_item.php?itemid=<?= $item['ItemID'] ?>">Edit</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>