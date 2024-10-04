<?php
// Database credentials
$host = 'localhost'; // Use lowercase 'localhost'
$dbname = 'user'; // Your database name
$user = 'root'; // Username, typically 'root'
$pass = ''; // Empty string if no password

// Create connection
$conn = new mysqli('localhost', 'root', '', 'user');


// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
