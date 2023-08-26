<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Retrieve all ongoing checkouts data from the "checkout" table
$ongoingCheckoutsSql = "SELECT * FROM checkout WHERE ReturnDate IS NULL";
$ongoingCheckoutsResult = $mysqli->query($ongoingCheckoutsSql);

// Get the total number of ongoing checkouts
$totalCheckoutsOngoingSql = "SELECT COUNT(*) FROM checkout WHERE ReturnDate IS NULL";
$totalCheckoutsOngoingResult = $mysqli->query($totalCheckoutsOngoingSql);
$totalCheckoutsOngoing = $totalCheckoutsOngoingResult->fetch_row()[0];

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Ongoing Checkouts Report</title>
	<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
	<div>
		<a href="emain.php"><button>Home</button></a>
		<a href="report.php"><button>Report</button></a>
		<a href="logout.php"><button>Sign Out</button></a>
	</div>
	<h1>Ongoing Checkouts Report</h1>
	<p>Total Number of Ongoing Checkouts:
		<?php echo $totalCheckoutsOngoing; ?>
	</p>

	<table>
		<thead>
			<tr>
				<th>Copy ID</th>
				<th>Member ID</th>
				<th>Start Date</th>
				<th>Return Date</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ongoingCheckoutsResult as $checkout): ?>
				<tr>
					<td>
						<?php echo $checkout['CopyID']; ?>
					</td>
					<td>
						<?php echo $checkout['MemberID']; ?>
					</td>
					<td>
						<?php echo $checkout['StartDate']; ?>
					</td>
					<td>
						<?php echo $checkout['ReturnDate']; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</body>

</html>