<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Title</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your existing styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        /* Navbar styles */
        .navbar {
            background-color: #222;
            /* Dark background */
            padding: 10px;
        }

        .navbar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .navbar li {
            position: relative;
            /* Position relative for dropdown */
            margin-right: 20px;
        }

        .navbar a {
            color: #FFD700;
            /* Yellow text */
            text-decoration: none;
            padding: 8px 12px;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="login.php">Logout</a></li>
            <li>
                <a href="dashboard.php" class="notification-icon">
                    <i class="fas fa-bell"></i> <!-- Bell icon -->
                </a>
            </li>
        </ul>
    </nav>

</body>

</html>