<?php
$host = "cosc3380mysql.mysql.database.azure.com";
$dbname = "library";
$username = "loctrinh";
$password = "123456789bA";
$port = 3306;
$mysqli = mysqli_init();
mysqli_ssl_set($mysqli, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
if (!$mysqli->real_connect($host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection error: " . $mysqli->connect_error);
}
return $mysqli;