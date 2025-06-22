<?php
$host = "";
$user = "";
$pass = ""; // Add password if needed
$db = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>