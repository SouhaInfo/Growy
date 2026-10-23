<?php
$host = 'localhost'; // Or the host where your database is
$dbname = 'growy_db'; // Your actual database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Establish a database connection using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


