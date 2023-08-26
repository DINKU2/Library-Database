<?php
session_start();


if ($_SESSION["UserType"] !== "employee" && empty($_SESSION["EmployeeID"])) {
    header("Location: main.php");
    exit;
}

// Check connection
$mysqli = require __DIR__ . "/database.php";

$memberId = 0;
if (isset($_GET["id"])) {
    $memberId = $_GET["id"];
    //echo "Received member ID: " . $memberId;
} else {
    echo "No member ID received.";
}
//Get the member that is being updated
$sql = "SELECT * FROM member WHERE MemberID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $memberId);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

// Get list of member types
$sql = "SELECT MemberTypeID, Name FROM MemberType";
$result = $mysqli->query($sql);
$memberTypes = array();
while ($row = $result->fetch_assoc()) {
    $memberTypes[$row["MemberTypeID"]] = $row["Name"];
}
// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();

    // Get inputs
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $PhoneNum = $_POST['PhoneNum'] ?? '';
    $MemberTypeID = (int)$_POST['MemberTypeID'] ?? '';
    $active = (int)$_POST['active'] ?? '';

    // Validate inputs
    if (!empty($fname) && !preg_match('/^[a-zA-Z ]*$/', $fname)) {
        $errors[] = 'First name must only contain letters and spaces.';
    }

    if (!empty($lname) && !preg_match('/^[a-zA-Z ]*$/', $lname)) {
        $errors[] = 'Last name must only contain letters and spaces.';
    }

    if (!empty($email)) {
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is not valid.';
        } else {
            if ($email !== $member['Email']) {
                // Check if email is already in use
                $sql = "SELECT * FROM member WHERE Email = ? AND MemberID != ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $email, $_SESSION['MemberID']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $errors[] = 'Email is already in use.';
                }
            }
        }
    }

    if (!empty($PhoneNum) && !preg_match('/^\d{10}$/', $PhoneNum)) {
        if ($PhoneNum !== $member['PhoneNum']) {
            $errors[] = 'Phone number must be 10 digits without any spaces or dashes.';
        }
    }

    if (!empty($zipcode) && !preg_match('/^\d{5}$/', $zipcode)) {
        $errors[] = 'Zip code must be 5 digits.';
    }

    // Update member information if there are no errors
    if (empty($errors)) {
        $sql = "UPDATE member SET Fname = '$fname', Lname = '$lname', Email = '$email', PhoneNum = '$PhoneNum', MemberTypeID = $MemberTypeID, Active = $active WHERE MemberID = $memberId";
        $result = $mysqli->query($sql);
        if ($result) {
            $success = 'Member information was updated successfully. You can view the list of member types on <a href="change_membertype.php">List of Members</a> page.</p>';
        } else {
            $errors[] = 'There was an error updating your information. Please try again.';
        }
    }
}



// Pagination
$membertypes_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($current_page - 1) * $membertypes_per_page;

// Get all membertypes
$sql = "SELECT * FROM membertype LIMIT $offset, $membertypes_per_page";
$result = $mysqli->query($sql);

// Count total membertypes
$count_sql = "SELECT COUNT(*) FROM membertype";
$count_result = $mysqli->query($count_sql);
$row = $count_result->fetch_row();
$total_membertypes = $row[0];
$total_pages = ceil($total_membertypes / $membertypes_per_page);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Employee Update Member Information</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'empnav.php';
?>

<body>
    <h1>Update Member Information</h1>
    <?php if (isset($success)) : ?>
        <p style="color: green;">
            <?php echo $success; ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error) : ?>
                <li>
                    <?php echo $error; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label for="fname">First Name:</label>
        <input type="text" name="fname" value="<?php echo $member['Fname']; ?>"><br>

        <label for="lname">Last Name:</label>
        <input type="text" name="lname" value="<?php echo $member['Lname']; ?>"><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $member['Email']; ?>"><br>

        <label for="PhoneNum">Phone Number:</label>
        <input type="tel" name="PhoneNum" value="<?php echo $member['PhoneNum']; ?>"><br>

        <label for="MemberTypeID">Member Type:</label>
        <select name="MemberTypeID" required>
            <option value="">-- Select Member Type --</option>
            <?php foreach ($memberTypes as $id => $name) : ?>
                <option value="<?= $id ?>"><?= $name ?></option>
            <?php endforeach; ?>
        </select>

        <label for="Active">Active:</label> <input type="checkbox" id="active" name="active" value="1" <?= ($member['Active'] == 1) ? "checked" : "" ?>><br>

        <button type="submit">Update</button>
    </form>

    <h2>All Member types</h2>
    <table>
        <thead>
            <tr>
                <th>MemberTypeID</th>
                <th>Name</th>
                <th>MaxBorrowingDays</th>
                <th>MaxItems</th>
            </tr>
        </thead>
        <tbody> 
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row["MemberTypeID"] ?></td>
                    <td><?= $row["Name"] ?></td>
                    <td><?= $row["MaxBorrowingDays"] ?></td>
                    <td><?= $row["MaxItems"] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>