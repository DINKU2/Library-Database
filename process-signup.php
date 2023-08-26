<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StreetAddress = $_POST["StreetAddress"];
    $City = $_POST["City"];
    $State = $_POST["State"];
    $ZipCode = $_POST["ZipCode"];
    $Fname = $_POST["Fname"];
    $Lname = $_POST["Lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $PhoneNum = $_POST["PhoneNum"];
    if (isset($_POST["MemberType"])) {
        if ($_POST["MemberType"] == "student") {
            $MemberType = 1;
        } elseif ($_POST["MemberType"] == "teacher") {
            $MemberType = 2;
        } else {
            // handle error if option value is not valid
        }
    }

    $errors = [];

    if (empty($_POST["Fname"])) {
        $errors["Fname"] = "First name is required";
    }

    if (empty($_POST["Lname"])) {
        $errors["Lname"] = "Last name is required";
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Valid email is required";
    }

    if (strlen($_POST["password"]) < 8) {
        $errors["password"] = "Password must be at least 8 characters";
    }

    if (!preg_match("/[a-z]/i", $_POST["password"])) {
        $errors["password"] = "Password must contain at least one letter";
    }

    if (!preg_match("/[0-9]/", $_POST["password"])) {
        $errors["password"] = "Password must contain at least one number";
    }

    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        $errors["password_confirmation"] = "Passwords must match";
    }

    if (empty($_POST["PhoneNum"])) {
        $errors["PhoneNum"] = "Phone number is required";
    }

    if (empty($_POST["StreetAddress"])) {
        $errors["StreetAddress"] = "Street address is required";
    }

    if (empty($_POST["City"])) {
        $errors["City"] = "City is required";
    }

    if (empty($_POST["State"])) {
        $errors["State"] = "State is required";
    }

    if (empty($_POST["ZipCode"])) {
        $errors["ZipCode"] = "Zip code is required";
    }

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM member WHERE Email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors["email"] = "Email already taken";
    }

    if (!empty($errors)) {
        echo '<ul>';
        foreach ($errors as $field => $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
    } else {
        $sql = "INSERT INTO address (StreetAddress, City, State, ZipCode) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $StreetAddress, $City, $State, $ZipCode);

        if ($stmt->execute()) {
            // Address record created successfully
            $addressID = $stmt->insert_id;

            $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $sql = "INSERT INTO member (MemberTypeID, AddressID, Lname, Active, Fname, Email, Password, PhoneNum)
                    VALUES ('$MemberType', '$addressID', '$Lname', '1', '$Fname', '$email', '$password_hash', '$PhoneNum')";

            if ($mysqli->query($sql) === TRUE) {
                // Member record created successfully
                header("Location: index.php");
                exit;
            } else {
                // Error creating member record
                echo "Error creating member record: " . $mysqli->error;
            }
        } else {
            // Error creating address record
            echo "Error creating address record: " . $stmt->error;
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>