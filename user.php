<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>
    <style>
        /* Basic styles for form */
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Create User Account</h2>
        <form action="user.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="access_level">Access Level</label>
            <select id="access_level" name="access_level" required>
                <option value="viewer">Admin</option>
                <option value="editor">HR</option>
                <option value="admin">CORE</option>
                <option value="editor">LOGISTICS</option>
                <option value="admin">FINANCE</option>
            </select>

            <button type="submit" name="register">Create Account</button>
        </form>
    </div>
</body>

</html>

<?php
// Include the database connection file
include 'db_connect.php'; // Create this file for your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Get the form input data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $access_level = $_POST['access_level'];

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL insert statement
    $sql = "INSERT INTO usercontrol (username, email, password, access_level) VALUES (?, ?, ?, ?)";

    // Use a prepared statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $access_level);

        // Execute the query
        if ($stmt->execute()) {
            echo "User account created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement.";
    }

    // Close the database connection
    $conn->close();
}
?>