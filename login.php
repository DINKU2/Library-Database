<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require __DIR__ . "/database.php";

    if ($_POST["user_type"] === "member") {
        $table = "member";
        $id_column = "MemberID";
        $redirect_url = "main.php";
    } elseif ($_POST["user_type"] === "employee") {
        $table = "employee";
        $id_column = "EmployeeID";
        $redirect_url = "emain.php";
    } else {
        $is_invalid = true;
    }

    if (!$is_invalid) {
        $sql = sprintf("SELECT * FROM %s
                        WHERE Email = '%s'",
            $table,
            $mysqli->real_escape_string($_POST["email"])
        );

        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($_POST["password"], $user["Password"])) {

                // Check if the member or employee is active before allowing them to log in
                if ($table === "member" && $user["Active"] == 0) {
                    $is_invalid = true;
                } elseif ($table === "employee" && $user["Active"] == 0) {
                    $is_invalid = true;
                } else {
                    session_start();

                    session_regenerate_id();
                    if ($table === "member") {
                        $_SESSION["MemberID"] = $user[$id_column];
                    } else {
                        $_SESSION["EmployeeID"] = $user[$id_column];
                    }
                    $_SESSION["Username"] = $user["Email"];
                    $_SESSION["Fname"] = $user["Fname"];
                    $_SESSION["MemberTypeID"] = $user["MemberTypeID"];
                    $_SESSION["UserType"] = $table;
                    header("Location: " . $redirect_url);
                    exit;
                }
            }
        }

        $is_invalid = true;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<div>
    <a href="index.php"><button>Home</button></a>
    <a href="signup.php"><button>Signup</button></a>
</div>

<body>

    <h1>Login</h1>

    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>

    <form method="post">
        <input type="radio" name="user_type" value="member" id="member">
        <label for="member">Member</label>

        <input type="radio" name="user_type" value="employee" id="employee">
        <label for="employee">Employee</label>

        <br><br>

        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <button>Log in</button>
    </form>

</body>

</html>