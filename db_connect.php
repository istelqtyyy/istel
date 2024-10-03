<?php
// Database credentials
$host = 'Localhost';
$dbname = 'user'; // Your database name is 'user'
$user = 'root'; // Typically 'root' if no specific username is set, adjust if necessary
$pass = 'KNKGoN@384B-HHv1'; // Empty string since there is no password
$socket = '/run/mysqld/mysqld.sock';

// Create connection
$conn = new mysqli($host, $user, $pass, $db, null, $socket);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
