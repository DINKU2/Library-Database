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
    <title>Member Type Creation Successful</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php include 'empnav.php'; ?>

<body>
    <h1>Member Type Creation Successful</h1>
    <p>Member Type successful.</p>
</body>

</html>