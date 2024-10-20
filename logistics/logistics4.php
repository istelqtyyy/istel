<?php
// Start session
session_start();

include '../dbconnect.php';

// Check if user is logged in and has a level
if (!isset($_SESSION['level'])) {
    die("You must be logged in to view this page.");
}

// Get user level from session
$user_level = $_SESSION['level'];

// Check if the logged-in user's level is ADMIN or LOGISTICS
if ($user_level !== 'ADMIN' && $user_level !== 'LOGISTICS') {
    die("You do not have permission to view this page.");
}

// SQL query to fetch login attempts for users with the level LOGISTICS only
$sql = "SELECT username, level, login, success FROM login WHERE level = 'LOGISTICS'"; // Changed 'attempted_at' to 'login'
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Logistics Login Attempts</title>";
echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #000; /* Black background */
        color: #FFD700; /* Yellow text */
        margin: 0;
        padding: 20px;
    }
    .home-button {
        background-color: #FFD700; /* Yellow background */
        color: #000; /* Black text */
        border: none;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin: 10px 0;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
    }
    .home-button:hover {
        background-color: #FFCC00; /* Lighter yellow on hover */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #FFD700; /* Yellow border */
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #FFD700; /* Yellow background for headers */
        color: #000; /* Black text for headers */
    }
    tr:nth-child(even) {
        background-color: #333; /* Dark gray for even rows */
    }
    tr:hover {
        background-color: #555; /* Darker gray on hover */
    }
</style>";
echo "</head>";
echo "<body>";

// Home button to link to dashboard.php
echo "<a href='../dashboard.php' class='home-button'>🏠 Home</a>";

// Check if there are results
if ($result->num_rows > 0) {
    // Start table
    echo "<table>";
    echo "<tr><th>Username</th><th>Level</th><th>Login</th><th>Success</th></tr>"; // Changed 'Attempted At' to 'Login'

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["level"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["login"]) . "</td>"; // Changed 'attempted_at' to 'login'
        echo "<td>" . ($row["success"] ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }

    // End table
    echo "</table>";
} else {
    echo "<p>No login attempts found for Logistics users.</p>";
}

// Close the connection
$conn->close();

echo "</body>";
echo "</html>";
