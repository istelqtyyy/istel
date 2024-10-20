<?php
include '../dbconnect.php';

// Handle password change
if (isset($_POST['change_password'])) {
    $userId = $_POST['id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the new password

    $updateQuery = "UPDATE usercontrol SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        header("Location: logistics3.php"); // Redirect after updating password
        exit();
    } else {
        die('Failed to change the password.');
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
        <a href="logistics3.php">Back to Logistics Accounts</a>
    </div>

    <h2>CHANGE PASSWORD</h2>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit" name="change_password">Change Password</button>
    </form>

</body>

</html>