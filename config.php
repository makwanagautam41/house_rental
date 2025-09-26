<?php
$servername = "localhost";  // or "127.0.0.1"
$username = "root";
$password = "mysql"; // set this to the password used in Workbench
$database = "house_rental";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
