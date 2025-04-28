<?php
$host = "localhost";
$user = "root";
$password = "dummyPass@123"; // update if you have a password
$dbname = "fitness_app";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
