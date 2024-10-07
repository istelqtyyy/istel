<?php
// Database configuration
$servername = "localhost"; // Change if your server is different
$username = "root"; // Your database username
$password = ""; // Leave this empty if there is no password
$dbname = "user"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch HR employees
$sql = "SELECT id, username, email, last_login FROM usercontrol WHERE level = 'HR'";
$result = $conn->query($sql);

// Start HTML output
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Employee List</title>";
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

// Home button to link to core.php
echo "<a href='../hr.php' class='home-button'>🏠 Home</a>";

// Check if there are results
if ($result->num_rows > 0) {
    // Start table
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Last Login</th></tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["last_login"] . "</td>";
        echo "</tr>";
    }

    // End table
    echo "</table>";
} else {
    echo "<p>0 results found</p>";
}

// Close the connection
$conn->close();

echo "</body>";
echo "</html>";
