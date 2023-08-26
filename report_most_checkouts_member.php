<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Retrieve the member with the most checkouts
$mostCheckoutsSql = "SELECT m.MemberID, m.Fname, m.Lname, COUNT(*) as TotalCheckouts FROM member m 
                    INNER JOIN checkout c ON m.MemberID = c.MemberID
                    GROUP BY m.MemberID 
                    ORDER BY TotalCheckouts DESC LIMIT 1";
$mostCheckoutsResult = $mysqli->query($mostCheckoutsSql);
$mostCheckoutsRow = $mostCheckoutsResult->fetch_assoc();
$mostCheckoutsMemberID = $mostCheckoutsRow["MemberID"];
$mostCheckoutsMember = $mostCheckoutsRow["Fname"] . " " . $mostCheckoutsRow["Lname"];
$mostCheckoutsCount = $mostCheckoutsRow["TotalCheckouts"];

// Retrieve the list of checkouts for the member with the most checkouts
$totalCheckoutsSql = "SELECT CopyID, MemberID, StartDate, ReturnDate FROM checkout WHERE MemberID = $mostCheckoutsMemberID";
$totalCheckoutsResult = $mysqli->query($totalCheckoutsSql);
$totalCheckouts = $totalCheckoutsResult->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Most Checkouts Report</title>
	<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
	<div>
		<a href="emain.php"><button>Home</button></a>
		<a href="report.php"><button>Report</button></a>
		<a href="logout.php"><button>Sign Out</button></a>
	</div>
	<h1>Most Checkouts Report</h1>
	<p>Member with the most checkouts:
		<?php echo $mostCheckoutsMember ?> (
		<?php echo $mostCheckoutsCount ?> checkouts)
	</p>
	<h2>List of Checkouts</h2>
	<table>
		<thead>
			<tr>
				<th>Copy ID</th>
				<th>Member ID</th>
				<th>Name</th>
				<th>Start Date</th>
				<th>Return Date</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($totalCheckouts as $checkout): ?>
				<tr>
					<td>
						<?php echo $checkout['CopyID']; ?>
					</td>
					<td>
						<?php echo $checkout['MemberID']; ?>
					</td>
					<td>
						<?php echo $mostCheckoutsMember; ?>
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