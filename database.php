<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "esewa";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("No connection " . $conn->connect_error);
}
?>