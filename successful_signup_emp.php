<?php
session_start();

if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}

include 'empnav.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Signup Successful</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <h1>Employee Signup Successful</h1>
    <p>Account has been created successfully.</p>
</body>
</html>