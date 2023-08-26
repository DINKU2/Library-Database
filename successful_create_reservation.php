<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["MemberID"])) {
  header("Location: login.php");
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Reservation Successful</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<div style="display: inline-block;">
        <a href="main.php"><button>Home</button></a>
        <a href="add_to_cart.php"><button>Cart</button></a>
        <a href="myorders.php"><button>Order & Reservation</button></a>
        <a href="update_member.php"><button>Change Information</button></a>
        <a href="latefee.php"><button>Late Fee</button></a>
        <a href="messages.php"><button>Messages</button></a>
        <a href="logout.php"><button>Sign Out</button></a>
    </div>
<body>
  <div class="content">
    <h2>Reservation Successful</h2>
    <?php if (isset($_SESSION["message"])) { ?>
      <p class="success"><?php echo $_SESSION["message"]; ?></p>
      <?php unset($_SESSION["message"]); ?>
    <?php } ?>
    <p>You can view your reservations on the <a href="myreservation.php">My Reservation</a> page.</p>
  </div>

</body>
</html>
