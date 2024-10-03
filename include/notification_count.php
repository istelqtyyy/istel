<?php
// notification_count.php

// Connect to the database
$host = 'localhost';
$dbname = 'user'; // Your database name
$user = 'root'; // Replace with your actual database username
$pass = ''; // Leave empty if no password
$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unread notification count
$stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM form WHERE is_read = 0");
$stmt->execute();
$result = $stmt->get_result();
$notification_count = $result->fetch_assoc()['unread_count'];
$stmt->close();
