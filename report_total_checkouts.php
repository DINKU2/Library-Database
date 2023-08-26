<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";


// Retrieve number of checkouts for current month
$currentMonth = date('Y-m');
$totalCheckoutsSql = "SELECT * FROM checkout WHERE StartDate LIKE '$currentMonth%'";
$totalCheckoutsResult = $mysqli->query($totalCheckoutsSql);
$totalCheckouts = $totalCheckoutsResult->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Checkout Report</title>
	<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
	<div>
		<a href="emain.php"><button>Home</button></a>
		<a href="report.php"><button>Report</button></a>
		<a href="logout.php"><button>Sign Out</button></a>
	</div>
	<h1>Checkout Report</h1>
	<p>Total checkouts this month:
		<?php echo count($totalCheckouts); ?>
	</p> <!-- Add this line -->
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
			<?php foreach ($totalCheckouts as $checkout): ?>
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