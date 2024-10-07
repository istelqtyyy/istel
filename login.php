<?php
// Start the session to store user data on successful login
session_start();

// Include the database connection
require 'dbconnect.php'; // Ensure this points to the correct file

// Initialize variables for error messages
$error = '';  // Ensure $error is always defined

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level']; // Get the selected level from the form

    // Prepare and execute the SQL query to check user credentials and level
    $stmt = $conn->prepare("SELECT * FROM usercontrol WHERE username = ? AND level = ?");
    $stmt->bind_param('ss', $username, $level);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password (assuming it's hashed in the database)
        if (password_verify($password, $user['password'])) {
            // Successful login, store user details and level in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level']; // Store the user level in session

            // Redirect to the dashboard based on the user level
            header("Location: dashboard.php"); // Change this to the appropriate dashboard
            exit();
        } else {
            $error = "Invalid username, password, or level!";
        }
    } else {
        $error = "Invalid username, password, or level!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Styles for black and yellow color scheme */
        body {
            background-color: #000;
            color: #FFD700;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }

        h2 {
            text-align: center;
            margin: 0;
            padding-bottom: 10px;
            color: #FFD700;
        }

        label,
        button {
            color: #FFD700;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #FFD700;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #FFD700;
            color: #000;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #FFC107;
        }

        p {
            color: red;
        }
    </style>
</head>

<body>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="level">Level:</label>
            <select name="level" id="level" required>
                <option value="" disabled selected>Select your level</option>
                <option value="HR">HR</option>
                <option value="CORE">CORE</option>
                <option value="LOGISTICS">LOGISTICS</option>
                <option value="FINANCE">FINANCE</option>
                <option value="ADMIN">ADMIN</option>
            </select>
        </div>
        <button type="submit">Login</button>

        <!-- Display error message if any -->
        <?php if (isset($error) && $error): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>

</html>