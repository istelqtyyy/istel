<?php
// record_attendance.php

include '../dbconnect.php';

// Prepare and bind
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name']; // Get name from form
    $date = $_POST['date']; // Get date from form
    $time_in = $_POST['time_in']; // Get time in from form
    $time_out = $_POST['time_out']; // Get time out from form

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO attendance (name, date, time_in, time_out) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $date, $time_in, $time_out); // Bind parameters

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record saved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!-- Attendance Form -->
<h2>Record Attendance</h2>
<form action="record_attendance.php" method="POST">
    <label for="name">Employee Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="date">Date:</label>
    <input type="date" id="date" name="date" required>

    <label for="time_in">Time In:</label>
    <input type="time" id="time_in" name="time_in" required>

    <label for="time_out">Time Out:</label>
    <input type="time" id="time_out" name="time_out" required>

    <button type="submit">Submit</button>
</form>