<?php
session_start();

// Redirect to login page if user is not logged in
if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Subject Creation Successful</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php include 'empnav.php'; ?>

<body>
    <h1>New Subject Creation Successful</h1>
    <p>Subject Creation successful.</p>
</body>

</html>