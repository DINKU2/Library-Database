<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";
if (!$mysqli) {
    die("Failed to connect to database: " . mysqli_connect_error());
}

// Retrieve contact information for all libraries
$sql = "SELECT LibraryName, PhoneNum, Email, StreetAddress, City, State, ZipCode
        FROM library
        JOIN address ON library.AddressID = address.AddressID";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>University of Houston Library</title>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">

</head>

<body>

    <header>
        <div>
            <a href="login.php"><button>Log In</button></a>
            <a href="signup.php"><button>Sign Up</button></a>
        </div>
        <h1>University of Houston Library</h1>
    </header>

    <main>
        <section>
            <h2>About</h2>
            <p>The University of Houston Libraries serves the UH community by providing resources and services to
                support academic and research endeavors. The system consists of several libraries, including the MD
                Anderson Library, the William R. Jenkins Architecture, Design, & Art Library, the Health Sciences
                Library, the Music Library, the Medical Library, and the University of Houston Law Library. Combined,
                these locations provide access to millions of physical and digital resources, including books, journals,
                databases, and more.</p>
            <h3>Our Mission</h3>
            <p>Our mission is to enhance student learning, support scholarly research and creative output, and promote
                the discovery of diverse and relevant information and knowledge from our resources and services.</p>
            <h3>Our Vision</h3>
            <p>Our vision is to foster curiosity and creativity, and facilitate transformative learning and scholarship
                to help advance UH’s academic standing and impact.</p>
        </section>
        <section>
            <h2>Contact Information for All Libraries</h2>
            <table>
                <thead>
                    <tr>
                        <th>Library Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($row["LibraryName"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row["PhoneNum"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row["Email"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row["StreetAddress"]) ?><br>
                                <?= htmlspecialchars($row["City"]) ?>,
                                <?= htmlspecialchars($row["State"]) ?>
                                <?= htmlspecialchars($row["ZipCode"]) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>

</html>