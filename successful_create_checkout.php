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
    <title>Checkout Successful</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php include 'membernav.php'; ?>

<body>
    <h1>Checkout Successful</h1>
    <p>Your checkout has been successful. Thank you for using our library!</p>
    <p>You can view your order on the <a href="myorders.php">My Order</a> page.</p>
</body>

</html>