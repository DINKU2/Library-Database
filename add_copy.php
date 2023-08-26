<?php

$mysqli = require __DIR__ . "/database.php";

if (isset($_POST["add_copy"])) {
  $itemID = $_POST["itemID"];
  $libraryID = $_POST["libraryID"];
  $quantity = $_POST["quantity"];

  // Loop through quantity and insert copies
  for ($i = 0; $i < $quantity; $i++) {
    $insertSql = "INSERT INTO copy (ItemID, LibraryID, Status) VALUES ('$itemID', '$libraryID', 0)";
    $mysqli->query($insertSql);
  }

  // Redirect back to main.php
  header("Location: item.php?id=$itemID");
  exit();
}

?>