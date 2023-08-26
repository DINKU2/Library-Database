<?php
session_start();

if ($_SESSION["UserType"] !== "employee" || empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}


// Check connection
$mysqli = require __DIR__ . "/database.php";

// Get employee information
$sql = "SELECT E.Fname, E.Lname, E.Email, L.LibraryName  AS LibraryName FROM Employee E JOIN Library L ON E.LibraryID = L.LibraryID WHERE E.EmployeeID = " . $_SESSION["EmployeeID"];
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Output employee information
    $row = $result->fetch_assoc();
    $fname = $row["Fname"];
    $lname = $row["Lname"];
    $email = $row["Email"];
    $libraryName = $row["LibraryName"];
} else {
    echo "Error: Employee not found.";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee Main</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <div>
        <h2>Employee Tasks</h2>
        <div class="button-list">
            <?php
            include 'empnav.php';
            ?>
            <form action="search.php" method="POST">
                <div>
                    <h1></h1>
                    <label for="search-field">Search Item by:</label>
                    <select id="search-field" name="search-field">
                        <option value="name">Name</option>
                        <option value="isbn">Serial</option>
                        <option value="creator">Creator</option>
                    </select>
                </div>
                <div>
                    <input type="text" id="search" name="search">
                </div>
                <button type="submit">Search</button>
        </div>
    </div>
    <div>
        <h2>Employee Information</h2>
        <p><strong>Email:</strong>
            <?= $email ?>
        </p>
        <p><strong>First Name:</strong>
            <?= $fname ?>
        </p>
        <p><strong>Last Name:</strong>
            <?= $lname ?>
        </p>
        <p><strong>Work at:</strong>
            <?= $libraryName ?>
        </p>
    </div>

    </form>

    <div>
        <form method="post" action="logout.php">
            <button class="water-btn" type="submit">Sign out</button>
        </form>
    </div>
</body>

</html>