<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Member</title>
    <metacharset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
    include 'empnav.php';
    ?>
<body>
    <h1>Member Updated Successfully</h1>
    <a href="report_member.php">Back to Member Report</a>
</body>

</html>
