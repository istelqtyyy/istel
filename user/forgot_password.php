<?php
session_start(); // Start session to store user data
require '../dbconnect.php'; // Ensure this points to the correct file

// Initialize error variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data and sanitize it
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $middle_name = htmlspecialchars(trim($_POST['middle_name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $address = htmlspecialchars(trim($_POST['address']));
    $birthday = htmlspecialchars(trim($_POST['birthday']));
    $level = htmlspecialchars(trim($_POST['level']));

    // Prepare the SQL query to check user details
    $stmt = $conn->prepare("SELECT * FROM usercontrol WHERE first_name = ? AND middle_name = ? AND surname = ? AND email = ? AND username = ? AND address = ? AND birthday = ? AND level = ?");
    $stmt->bind_param('ssssssss', $first_name, $middle_name, $surname, $email, $username, $address, $birthday, $level);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        // Store user data in session and redirect to new password page
        $_SESSION['username'] = $username; // Store username in session
        header("Location: new_password.php");
        exit();
    } else {
        // Error if user details do not match
        $error = "Information does not match our records!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Styles for black and yellow color scheme */
        body {
            background-image: url('img/gg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #FFD700;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: rgba(28, 28, 28, 0.8);
            padding: 40px;
            border-radius: 10px;
            width: 1000px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            margin: 0;
            padding-bottom: 50px;
            color: #FFD700;
        }

        label,
        button {
            color: #FFD700;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group div {
            flex: 1;
            min-width: 45%;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: 95%;
            padding: 10px;
            background-color: #333;
            color: #FFD700;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button {
            width: 20%;
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
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="POST" action="forgot_password.php">
            <h2>Forgot Password</h2>

            <!-- Display error message if any -->
            <?php if ($error): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="form-group">
                <div>
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" required>
                </div>
                <div>
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" name="middle_name" id="middle_name" required>
                </div>
                <div>
                    <label for="surname">Surname:</label>
                    <input type="text" name="surname" id="surname" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" required>
                </div>
                <div>
                    <label for="birthday">Birthday:</label>
                    <input type="date" name="birthday" id="birthday" required>
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
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>

</html>