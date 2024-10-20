<?php
// Start the session
session_start();

include '../dbconnect.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Get the current username and level from the session
    $username = $_SESSION['username'];

    // Get the current date and time (for logout time)
    $logout_time = date("Y-m-d H:i:s");

    // Update the 'logout' field in the database for this user
    $sql = "UPDATE login SET logout = ? WHERE username = ? AND logout IS NULL"; // Update only if logout is null
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $logout_time, $username);
    $stmt->execute();
    $stmt->close();

    // Destroy the session to log the user out
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session

    // Redirect to the login page or another page
    header("Location: login.php");
    exit();
} else {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit();
}

// Close the connection
$conn->close();
