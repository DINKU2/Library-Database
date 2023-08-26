<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("America/Chicago");
// Check if the user clicked the "sign out" button
if (!isset($_SESSION["MemberID"]) && !isset($_SESSION["EmployeeID"])) {
    header("Location: login.php");
    exit();
}

$isEmployee = isset($_SESSION["EmployeeID"]);

// Check if the delete button was clicked for a notification
if (isset($_POST["delete_notification"]) && isset($_POST["notification_id"])) {

    $delete_id = $_POST["notification_id"];

    // Connect to database
    $mysqli = require __DIR__ . "/database.php";

    // Delete the notification with the specified ID
    $stmt = $mysqli->prepare("DELETE FROM notification WHERE notificationid = ?");
    $stmt->bind_param("s", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to this page after deleting the notification
    header("Location: messages.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>MainBoard</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="/js/validation.js" defer></script>
</head>

<body>
    <div>
        <?php
        if ($isEmployee) {
            echo '<a href="emain.php"><button>Employee Main Board</button></a>';
        }
        ?>
    </div>
    <?php
    include 'membernav.php';
    ?>
    <form action="search.php" method="POST">

        <h2>Messages</h2>

        <?php

        // Connect to database
        $mysqli = require __DIR__ . "/database.php";

        // Query to get total number of notifications
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM notification WHERE memberID = ?");

        // Bind the member ID parameter to the prepared statement
        $current_user_id = $_SESSION["MemberID"];
        $stmt->bind_param("i", $current_user_id);

        // Execute the prepared statement
        $stmt->execute();

        // Get the total number of notifications
        $stmt->bind_result($totalMessages);
        $stmt->fetch();

        $stmt->close();

        // Check if there are any notifications
        if ($totalMessages > 0) {
            // Select notifications for current user
            $sql = "SELECT * FROM notification WHERE memberID = $current_user_id ORDER BY notificationid DESC";
            $result = $mysqli->query($sql);

            // Output notifications as HTML table
            echo "<table>";
            echo "<thead><tr><th>Notification ID</th><th>Message</th><th>Date Created</th><th>Action</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["notificationid"] . "</td><td>" . $row["message"] . "</td> <td>" . $row["created_date"] . "</td>
    <td><form method='POST'>
    <input type='hidden' name='notification_id' value='" . $row["notificationid"] . "'>
    <button type='submit' name='delete_notification'>Delete</button>
</form>
</td></tr>";

            }
            echo "</tbody>";
            echo "</table>";
        } else {
            // Output message if there are no notifications
            echo "No notifications found.";
        }

        // Check if the delete button was clicked for a notification
        if (isset($_POST["delete_notification"]) && isset($_POST["notification_id"])) {

            $delete_id = $_POST["notification_id"];

            // Delete the notification with the specified ID
            $stmt = $mysqli->prepare("DELETE FROM notification WHERE notificationid = ?");
            $stmt->bind_param("s", $delete_id);
            $stmt->execute();
            $stmt->close();

            // Redirect back to this page after deleting the notification
            header("Location: messages.php");
            exit();
        }

        // Close the database connection
        $mysqli->close();

        ?>

</body>

</html>