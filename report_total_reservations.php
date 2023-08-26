<?php
// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Retrieve all data from the "item" table
$sql = "SELECT * FROM item";
$result = $mysqli->query($sql);

// Retrieve total number of reservations for the current month
$totalReservationsSql = "SELECT COUNT(*) FROM reservation WHERE MONTH(ReservationDate) = MONTH(CURRENT_DATE()) AND YEAR(ReservationDate) = YEAR(CURRENT_DATE())";
$totalReservationsResult = $mysqli->query($totalReservationsSql);
$totalReservations = $totalReservationsResult->fetch_row()[0];

// Retrieve all reservations for the current month
$reservationsSql = "SELECT * FROM reservation WHERE MONTH(ReservationDate) = MONTH(CURRENT_DATE()) AND YEAR(ReservationDate) = YEAR(CURRENT_DATE())";
$reservationsResult = $mysqli->query($reservationsSql);

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
	<p>Total Number of Reservations:
		<?php echo $totalReservations; ?>
	</p>

	<h2>Reservations for the Current Month</h2>
	<table>
		<tr>
			<th>Reservation ID</th>
			<th>Member ID</th>
			<th>Reservation Date</th>
		</tr>
		<?php while ($row = $reservationsResult->fetch_assoc()): ?>
			<tr>
				<td>
					<?php echo $row['ReservationID']; ?>
				</td>
				<td>
					<?php echo $row['MemberID']; ?>
				</td>
				<td>
					<?php echo $row['ReservationDate']; ?>
				</td>
			</tr>
		<?php endwhile; ?>
	</table>
</body>

</html>