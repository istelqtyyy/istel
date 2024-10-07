<?php
// Database credentials
$host = 'localhost';
$dbname = 'user'; // Your database name
$user = 'root'; // Username
$pass = ''; // Password

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>
    <style>
        /* Styles for black and yellow color scheme */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #000;
            /* Black background */
            color: #FFD700;
            /* Yellow text */
            font-family: Arial, sans-serif;
            margin: 0;
            position: relative;
            /* Position relative to place button */
        }

        .form-container {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #FFD700;
            /* Yellow border */
            border-radius: 5px;
            background-color: #1c1c1c;
            /* Dark gray background */
            z-index: 1;
            /* Ensure form is above the button */
        }

        h2 {
            text-align: center;
            color: #FFD700;
            /* Yellow text */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #333;
            /* Dark background for inputs */
            color: #FFD700;
            /* Yellow text */
            border: 1px solid #FFD700;
            /* Yellow border */
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #FFD700;
            /* Yellow button */
            color: #000;
            /* Black text */
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #FFC107;
            /* Lighter yellow on hover */
        }

        .home-container {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            /* Cursor pointer for both icon and text */
        }

        .home-button {
            background: none;
            border: none;
            color: #FFD700;
            /* Yellow text */
            font-size: 24px;
            /* Adjust size for better visibility */
        }

        .home-button:hover {
            color: #FFC107;
            /* Lighter yellow on hover */
        }

        .back-text {
            margin-left: 5px;
            /* Space between icon and text */
            color: #FFD700;
            /* Yellow text */
        }

        .back-text:hover {
            color: #FFC107;
            /* Lighter yellow on hover */
        }
    </style>
</head>

<body>
    <div class="home-container" onclick="location.href='dashboard.php'">
        <button class="home-button">🏠</button> <!-- Home icon -->
        <span class="back-text">Back</span>
    </div>

    <div class="form-container">
        <h2>Create User Account</h2>
        <form action="user.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="level">Level</label>
            <select id="level" name="level" required>
                <option value="HR">HR</option>
                <option value="CORE">CORE</option>
                <option value="LOGISTICS">LOGISTICS</option>
                <option value="FINANCE">FINANCE</option>
                <option value="ADMIN">ADMIN</option> <!-- Added ADMIN option -->
            </select>

            <button type="submit" name="register">Create Account</button>
        </form>
    </div>
</body>

</html>

<?php
// Include the database connection file
include 'dbconnect.php'; // Create this file for your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Get the form input data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL insert statement
    $sql = "INSERT INTO usercontrol (username, email, password, level) VALUES (?, ?, ?, ?)";

    // Use a prepared statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $level);

        // Execute the query
        if ($stmt->execute()) {
            echo "<p style='color: #FFD700; text-align: center;'>User account created successfully!</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Error preparing the statement.</p>";
    }

    // Close the database connection
    $conn->close();
}
?>