<?php
$host = 'localhost';
$user = 'root';
$password = 'root'; // Your MySQL password
$dbname = 'tours_travels';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
