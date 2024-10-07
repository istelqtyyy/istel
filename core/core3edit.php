<?php
// Database connection
$host = 'localhost'; // Your database host
$dbname = 'user'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if id is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch account details based on id
    $query = "SELECT * FROM usercontrol WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        die('Account not found.');
    }
} else {
    die('ID not provided.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $level = $_POST['level'];

    // Update the account details
    $updateQuery = "UPDATE usercontrol SET username = :username, email = :email, level = :level WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':level', $level, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the employee accounts page after successful update
        header("Location: core3.php");
        exit();
    } else {
        die('Failed to update the account.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            /* Light background for contrast */
        }

        .container {
            width: 400px;
            padding: 20px;
            background-color: #000;
            /* Black background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #FFC107;
            /* Yellow text */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #FFC107;
            /* Yellow */
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .buttons a {
            text-decoration: none;
            color: #FFC107;
            /* Yellow */
            background-color: #000;
            /* Black background */
            padding: 10px 15px;
            border: 1px solid #FFC107;
            /* Yellow border */
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .buttons a:hover {
            background-color: #FFC107;
            /* Yellow background on hover */
            color: black;
            /* Black text on hover */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #FFC107;
            /* Yellow */
        }

        input,
        select {
            padding: 10px;
            margin-bottom: 16px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #fff;
            /* White background for inputs */
        }

        button {
            padding: 10px;
            background-color: #FFC107;
            /* Yellow */
            color: black;
            /* Black text for contrast */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ffb300;
            /* Darker yellow on hover */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="buttons">
            <a href="core3.php"><i class="fas fa-home" style="font-size: 24px;"></i> BACK</a>
        </div>
        <h2>Edit Employee Account</h2>
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($account['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($account['email']); ?>" required>

            <label for="level">Level</label>
            <select name="level" id="level" required>
                <option value="HR" <?= $account['level'] === 'HR' ? 'selected' : ''; ?>>HR</option>
                <option value="LOGISTICS" <?= $account['level'] === 'LOGISTICS' ? 'selected' : ''; ?>>LOGISTICS</option>
                <option value="CORE" <?= $account['level'] === 'CORE' ? 'selected' : ''; ?>>CORE</option>
                <option value="FINANCE" <?= $account['level'] === 'FINANCE' ? 'selected' : ''; ?>>FINANCE</option>
            </select>

            <button type="submit">Update Account</button>
        </form>
    </div>

</body>

</html>