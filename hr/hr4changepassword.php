<?php
include '../dbconnect.php';

// Handle password change
if (isset($_POST['change_password'])) {
    $userId = $_POST['id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the new password

    // Prepare the update query using MySQLi
    $updateQuery = "UPDATE usercontrol SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    if ($stmt) {
        // Bind the parameters: 's' for string (newPassword) and 'i' for integer (userId)
        $stmt->bind_param('si', $newPassword, $userId);

        if ($stmt->execute()) {
            // Redirect after updating password
            header("Location: hr4.php");
            exit();
        } else {
            die('Failed to change the password.');
        }
    } else {
        die('Failed to prepare the statement.');
    }
}


// Fetch the user ID from the URL
$userId = $_GET['id'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #343a40;
            /* Dark background */
            color: #f8f9fa;
            /* Light text */
            padding: 20px;
        }

        form {
            max-width: 350px;
            margin: auto;
            background-color: #495057;
            padding: 20px;
            border-radius: 4px;
            border: 1px solid #ffc107;
            /* Yellow border */
        }

        input[type="password"] {
            width: 50%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #ffc107;
            /* Yellow */
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ffca2c;
            /* Darker yellow */
        }

        .back-button {
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        .back-button a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 4px;
        }

        .back-button a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="back-button">
        <a href="hr4.php">Back to HR Accounts</a>
    </div>

    <h2>CHANGE PASSWORD</h2>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit" name="change_password">Change Password</button>
    </form>

</body>

</html>