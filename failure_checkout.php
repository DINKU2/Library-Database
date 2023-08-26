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
    <title>Checkout Failed</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <?php include 'membernav.php'; ?>
    <h1>Checkout Failed</h1>
    <p><span style="font-size: 1.2em;"><strong>Sorry, your checkout has failed.</strong></span></p>
    <p><span style="font-size: 1.1em;">It's possible that you have exceeded your maximum number of items that can be
            borrowed or have unpaid late fees exceeding $50.</span></p>
    <p><span style="font-size: 1.1em;">Please consider returning some items or paying your late fees, and try again
            later.</span></p>
    <p><span style="font-size: 1.1em;">You can also check your <em>Messages</em> tab for more information.</span></p>


</body>

</html>