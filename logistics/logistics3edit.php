<?php
include '../dbconnect.php';

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
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];

    // Update the account details
    $updateQuery = "UPDATE usercontrol 
                    SET username = :username, 
                        email = :email, 
                        level = :level,
                        first_name = :first_name,
                        middle_name = :middle_name,
                        surname = :surname,
                        address = :address,
                        birthday = :birthday
                    WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':level', $level, PDO::PARAM_STR);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the employee accounts page after successful update
        header("Location: logistics3.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #FFC107;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #FFC107;
        }

        .buttons {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .buttons a {
            text-decoration: none;
            color: #FFC107;
            background-color: #000;
            padding: 10px 15px;
            border: 1px solid #FFC107;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .buttons a:hover {
            background-color: #FFC107;
            color: black;
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #FFC107;
        }

        input,
        select {
            padding: 12px;
            margin-bottom: 16px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        button {
            grid-column: span 2;
            padding: 10px;
            background-color: #FFC107;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ffb300;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="buttons">
            <a href="logistics3.php"><i class="fas fa-home" style="font-size: 24px;"></i> BACK</a>
        </div>
        <h2>Edit Employee Account</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($account['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($account['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="level">Level</label>
                <select name="level" id="level" required>
                    <option value="HR" <?= $account['level'] === 'HR' ? 'selected' : ''; ?>>HR</option>
                    <option value="LOGISTICS" <?= $account['level'] === 'LOGISTICS' ? 'selected' : ''; ?>>LOGISTICS</option>
                    <option value="CORE" <?= $account['level'] === 'CORE' ? 'selected' : ''; ?>>CORE</option>
                    <option value="FINANCE" <?= $account['level'] === 'FINANCE' ? 'selected' : ''; ?>>FINANCE</option>
                </select>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($account['first_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" value="<?= htmlspecialchars($account['middle_name']); ?>">
            </div>

            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($account['surname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="Address" id="address" value="<?= htmlspecialchars($account['Address']); ?>" required>
            </div>

            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" name="birthday" id="birthday" value="<?= htmlspecialchars($account['birthday']); ?>" required>
            </div>

            <button type="submit">Update Account</button>
        </form>
    </div>

</body>

</html>