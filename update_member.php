<?php
session_start();
require_once('database.php');

// Check if user is logged in
// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
    header("Location: login.php");
    exit();
}

// Get member information
$sql = "SELECT * FROM member WHERE MemberID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $_SESSION['MemberID']);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();

    // Get inputs
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $PhoneNum = $_POST['PhoneNum'] ?? '';

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

    if (!empty($PhoneNum) && !preg_match('/^\d{10}$/', $PhoneNum)) {
        $errors[] = 'Phone number must be 10 digits without any spaces or dashes.';
    }

    if (!empty($zipcode) && !preg_match('/^\d{5}$/', $zipcode)) {
        $errors[] = 'Zip code must be 5 digits.';
    }

    // Update member information if there are no errors
    if (empty($errors)) {
        $sql = "UPDATE member SET Fname = ?, Lname = ?, Email = ?, PhoneNum = ? WHERE MemberID = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssssi', $fname, $lname, $email, $PhoneNum, $_SESSION['MemberID']);
        if ($stmt->execute()) {
            $success = 'Your information was updated successfully.';
            $member['Fname'] = $fname;
            $member['Lname'] = $lname;
            $member['Email'] = $email;
            $member['PhoneNum'] = $PhoneNum;
        } else {
            $errors[] = 'There was an error updating your information. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Information</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<?php
include 'membernav.php';
?>

<body>
    <h1>Update Information</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;">
            <?php echo $success; ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
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

        <button type="submit">Update</button>
    </form>
</body>

</html>