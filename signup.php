<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<div>
    <a href="index.php"><button>Home</button></a>
    <a href="login.php"><button>Log In</button></a>
</div>

<body>

    <h1>Signup</h1>
    <form action="process-signup.php" method="post">
        <div>
            <label for="Fname">First Name</label>
            <input type="text" id="Fname" name="Fname" required>
        </div>

        <div>
            <label for="Lname">Last Name</label>
            <input type="text" id="Lname" name="Lname" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <p>Password must be at least 8 characters, contain at least one letter, and at least one number.</p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

        </div>

        <div>
            <label for="password_confirmation">Repeat password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div>
            <label for="PhoneNum">Phone Number</label>
            <input type="tel" id="PhoneNum" name="PhoneNum" required>
        </div>

        <div>
            <label for="MemberType">Member Type</label>
            <select id="MemberType" name="MemberType" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>

        <div>
            <label for="StreetAddress">Street Address</label>
            <input type="text" id="StreetAddress" name="StreetAddress" required>
        </div>

        <div>
            <label for="City">City</label>
            <input type="text" id="City" name="City" required>
        </div>

        <div>
            <label for="State">State</label>
            <input type="text" id="State" name="State" required>
        </div>

        <div>
            <label for="ZipCode">Zip Code</label>
            <input type="text" id="ZipCode" name="ZipCode" required>
        </div>

        <button type="submit">Submit</button>
    </form>

</body>

</html>