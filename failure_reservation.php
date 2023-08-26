<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Reservation Failure</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php
    include 'membernav.php';
    ?>
    <h1>Reservation Failure</h1>
    <p>Sorry, we were unable to reserve the item for you. Please try again later.</p>
</body>

</html>