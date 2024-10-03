<?php
// Start the session to store user data on successful login
session_start();

// Include the database connection from user.php
require 'user.php';

// Initialize variables for error messages
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to check user credentials
    $stmt = $conn->prepare("SELECT * FROM usercontrol WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password (assuming it's hashed in the database)
        if (password_verify($password, $user['password'])) {
            // Successful login, store user details in session
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php"); // Redirect to dashboard or another page
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
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
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Optional for your CSS -->
</head>

<body>
    <h2>Login</h2>

    <form method="POST" action="login.php">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>

        <!-- Display error message if any -->
        <?php if ($error): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>

</html>