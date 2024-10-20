<?php
// Adjust the path according to your directory structure
include '../dbconnect.php'; // Ensure this path is correct

// Handle Delete
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    if (!empty($deleteId) && is_numeric($deleteId)) {
        $deleteQuery = "DELETE FROM usercontrol WHERE id = ?";
        $stmt = $mysqli->prepare($deleteQuery);
        $stmt->bind_param("i", $deleteId);
        if ($stmt->execute()) {
            header("Location: hr4.php"); // Redirect after deletion
            exit();
        } else {
            die('Failed to delete the account.');
        }
    } else {
        die('ID not provided or invalid.');
    }
}

// Handle Search
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Fetch accounts leveled as 'HR' with optional search
$searchTerm = "%" . $searchQuery . "%";
$query = "SELECT * FROM usercontrol WHERE level = 'HR' AND (username LIKE ? OR email LIKE ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
$accounts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Employee Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #343a40;
            color: #f8f9fa;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .account {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ffc107;
            border-radius: 4px;
            background-color: #495057;
        }

        .account a {
            text-decoration: none;
            color: #ffc107;
            margin-right: 10px;
        }

        .account a.delete {
            color: red;
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 300px;
        }

        .search-bar button {
            padding: 10px;
            margin-left: 5px;
            background-color: #ffc107;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #ffca2c;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            width: 100%;
        }

        .nav-buttons a {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .nav-buttons a i {
            margin-right: 5px;
        }

        .nav-buttons a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <h2>HR Employee Accounts</h2>

    <div class="nav-buttons">
        <a href="../dashboard.php"><i class="fas fa-home" style="font-size: 24px;"></i> Home</a>
        <a href="../user.php"><i class="fas fa-user-plus" style="font-size: 24px;"></i> Register Account</a>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by username or email" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div>
        <?php if (empty($accounts)): ?>
            <p>No accounts found.</p>
        <?php else: ?>
            <?php foreach ($accounts as $account): ?>
                <div class="account">
                    <div>
                        <strong><?= htmlspecialchars($account['username']) ?></strong>
                    </div>
                    <div>
                        <?= htmlspecialchars($account['email']) ?>
                    </div>
                    <div>
                        <?= htmlspecialchars($account['level']) ?>
                    </div>
                    <div>
                        <em>Password: <span style="color: #ffc107;">(hashed)</span></em>
                    </div>
                    <div>
                        <a href="hr4edit.php?id=<?= $account['id'] ?>">Edit</a>
                        <a href="hr4changepassword.php?id=<?= $account['id'] ?>">Change Password</a>
                        <a href="?delete=<?= $account['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</body>

</html>