<?php
$host = "localhost";
$user = "root";
$pass = ""; // Your MySQL password
$db = "user_auth";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
