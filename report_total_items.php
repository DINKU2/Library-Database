<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Retrieve all data from the "item" table
$sql = "SELECT * FROM item";
$result = $mysqli->query($sql);

// Get the total number of items
$totalItemsSql = "SELECT COUNT(*) FROM item";
$totalItemsResult = $mysqli->query($totalItemsSql);
$totalItems = $totalItemsResult->fetch_row()[0];

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Total Item Report</title>
	<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
	<div>
		<a href="emain.php"><button>Home</button></a>
		<a href="report.php"><button>Report</button></a>
		<a href="logout.php"><button>Sign Out</button></a>
	</div>
	<h1>Report</h1>
	<p>Total Number of Items:
		<?php echo $totalItems; ?>
	</p>

	<table>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Serial</th>
		</tr>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<td>
					<?php echo $row['ItemID']; ?>
				</td>
				<td>
					<?php echo $row['Name']; ?>
				</td>
				<td>
					<?php echo $row['SerialOrISBN']; ?>
				</td>
			</tr>
		<?php endwhile; ?>
	</table>
</body>

</html>