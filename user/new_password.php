<?php
session_start(); // Start session to retrieve user data
require '../dbconnect.php'; // Ensure this points to the correct file

// Initialize error and success variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data and sanitize it
    $new_password = htmlspecialchars(trim($_POST['new_password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
    $username = $_SESSION['username']; // Get username from session

    // Check if new password and confirm password match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE usercontrol SET password = ? WHERE username = ?");
        $stmt->bind_param('ss', $hashed_password, $username);
        if ($stmt->execute()) {
            $success = "Your password has been updated successfully!";
            // Wait for 3 seconds to show the success message and then redirect to login.php
            sleep(3);
            header("Location: login.php");
            exit();
        } else {
            $error = "Error updating password. Please try again.";
        }

        $stmt->close();
    } else {
        $error = "New password and confirmation do not match!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Styles for black and yellow color scheme */
        body {
            background-image: url('img/gg.jpg');
            /* Set the background image */
            background-size: cover;
            /* Cover the entire viewport */
            background-position: center;
            /* Center the background image */
            background-repeat: no-repeat;
            /* Prevent the background from repeating */
            color: #FFD700;
            /* Text color */
            font-family: Arial, sans-serif;
            /* Font family */
            height: 100vh;
            /* Full viewport height */
            margin: 0;
            /* Remove default margin */
            display: flex;
            justify-content: center;
            align-items: center;
            /* Center the form container */
        }

        .form-container {
            background-color: rgba(28, 28, 28, 0.8);
            /* Semi-transparent background for the form */
            padding: 20px;
            /* Form padding */
            border-radius: 10px;
            /* Rounded corners */
            width: 400px;
            /* Fixed width */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            /* Add a shadow to the container */
        }

        h2 {
            text-align: center;
            /* Centered heading */
            margin: 0;
            /* Remove margin */
            padding-bottom: 10px;
            /* Bottom padding */
            color: #FFD700;
            /* Heading color */
        }

        label,
        button {
            color: #FFD700;
            /* Button and label color */
        }

        input[type="password"] {
            width: 95%;
            /* Full width */
            padding: 10px;
            /* Padding */
            background-color: #333;
            /* Input background color */
            color: #FFD700;
            /* Input text color */
            border: none;
            /* Remove border */
            border-radius: 5px;
            /* Rounded corners */
            margin-bottom: 15px;
            /* Bottom margin */
        }

        button {
            width: 100%;
            /* Full width */
            padding: 10px;
            /* Padding */
            background-color: #FFD700;
            /* Button background color */
            color: #000;
            /* Button text color */
            border: none;
            /* Remove border */
            cursor: pointer;
            /* Pointer cursor */
        }

        button:hover {
            background-color: #FFC107;
            /* Button hover color */
        }

        p {
            text-align: center;
            /* Center align error or success message */
        }

        .form-container p {
            margin-bottom: 15px;
        }

        /* Success message color */
        p[style="color: green;"] {
            color: #28a745;
        }

        /* Error message color */
        p[style="color: red;"] {
            color: red;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="POST" action="new_password.php">
            <h2>Reset Password</h2>

            <!-- Display success message if any -->
            <?php if ($success): ?>
                <p style="color: green;"><?php echo $success; ?></p>
            <?php endif; ?>

            <!-- Display error message if any -->
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div>
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <button type="submit">Update Password</button>
        </form>
    </div>
</body>

</html>