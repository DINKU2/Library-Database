<head>
    <title>Search</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="/js/validation.js" defer></script>
</head>
<div>
    <a href="main.php"><button>Home</button></a>
    <a href="logout.php"><button>Sign Out</button></a>
</div>
<?php
$mysqli = require __DIR__ . "/database.php";

// Check if the search query and search field are set
if (isset($_POST["search"]) && isset($_POST["search-field"])) {
    // Get the search query and search field selected by the user
    $search = $_POST["search"];
    $searchField = $_POST["search-field"];

    // Build the SQL query based on the search field selected by the user
    switch ($searchField) {
        case "name":
            $sql = "SELECT ItemID, Name, SerialOrISBN FROM item WHERE Name LIKE '%" . $search . "%' LIMIT 20";
            break;
        case "isbn":
            $sql = "SELECT ItemID, Name, SerialOrISBN FROM item WHERE SerialOrISBN LIKE '%" . $search . "%' LIMIT 20";
            break;
        case "creator":
            $sql = "SELECT DISTINCT ItemID, Name, SerialOrISBN FROM item WHERE (Name LIKE '%" . $search . "%' OR SerialOrISBN LIKE '%" . $search . "%' OR Creator LIKE '%" . $search . "%') LIMIT 20";
            break;
        default:
            $sql = "SELECT ItemID, Name, SerialOrISBN FROM item WHERE Name LIKE '%" . $search . "%' LIMIT 20";
    }

    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if there are any search results
    if ($result->num_rows > 0) {
        // Display the search results in a table
        echo "<h2>Search Results:</h2>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Name</th>";
        echo "<th>ISBN</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Name"] . "</td>";
            echo "<td>" . $row["SerialOrISBN"] . "</td>";
            echo "<td><a href='item.php?id=" . $row["ItemID"] . "'>View Details</a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        // Display a message if no search results were found
        echo "<h2>No results found for your search query.</h2>";
    }
} else {
    // Redirect the user to the main page if the search query and search field are not set
    header("Location: main.php");
    exit();
}
?>