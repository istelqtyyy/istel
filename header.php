<?php
include 'dbconnect.php';

// Fetch unread notification count
$stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM form WHERE is_read = 0");
$stmt->execute();
$result = $stmt->get_result();
$notification_count = $result->fetch_assoc()['unread_count'];
$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Include your styles -->
    <style>
        /* Navbar Styles */
        .navbar {
            display: flex;
            align-items: center;
            background-color: #333;
            padding: 1rem;
        }

        .navbar ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-grow: 1;
            justify-content: space-between;
        }

        .navbar-image {
            height: 40px;
            margin-right: auto;
        }

        .notification-icon {
            color: white;
        }

        /* Hamburger Button Styles */
        .hamburger-btn {
            background-color: #34495e;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 20px;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            position: fixed;
            left: -250px;
            top: 0;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .sidebar.open {
            left: 0;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.closed+.content-wrapper {
            margin-left: 0;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <ul>
                <li>
                    <img src="img/gwa.jpg" alt="Your Image" class="navbar-image"> <!-- Image on the left -->
                    <span class="navbar-title">GREAT WALL ARTS</span>
                </li>
                <li><a href="include/home.php">Home</a></li>
                <li><a href="user/logout.php">Logout</a></li>
                <!-- Bell Icon in header.php -->
                <a href="notification.php" class="notification-icon" onclick="markNotificationsAsRead()">
                    <i class="fas fa-bell"></i> <!-- Bell icon -->
                    <?php if ($notification_count > 0): ?>
                        <span class="badge"><?php echo $notification_count; ?></span>
                    <?php endif; ?>
                </a>

                <script>
                    function markNotificationsAsRead() {
                        // Simple way to trigger the notification read action
                        window.location.href = '../notification.php';
                    }
                </script>

            </ul>
        </nav>
    </header>

    <!-- Hamburger Button -->
    <button class="hamburger-btn" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar -->
    <div class="sidebar closed" id="sidebar">
        <div class="sidebar-content">
            <ul class="sidebar-list">
                <li class="sidebar-item"><a href="dashboard.php">ADMIN</a></li>
                <li class="sidebar-item"><a href="core.php">CORE</a></li>
                <li class="sidebar-item"><a href="hr.php">HR</a></li>
                <li class="sidebar-item"><a href="finance.php">FINANCE</a></li>
                <li class="sidebar-item"><a href="logistics.php">LOGISTICS</a></li>
                <li class="sidebar-item"><a href="user/user.php">CREATE USER ACCOUNT</a></li>

            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <!-- Main content goes here -->
    </div>

    <!-- JavaScript to Toggle Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open'); // Toggle 'open' class to show/hide the sidebar
        }
    </script>
</body>

</html>