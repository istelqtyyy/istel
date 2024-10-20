<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Include your styles -->
    <style>
        /* Add your existing styles here */
        /* Navbar Styles */
        .navbar {
            display: flex;
            align-items: center;
            background-color: #2a283c;
            padding: 1rem;
            justify-content: space-between;
            /* Added to space between left and right */
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-right {
            margin-left: auto;
            /* Pushes the right items to the far right */
        }

        .navbar-right ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            margin: 0 20px;
            /* Adjusted margin for spacing */
        }

        .navbar a {
            color: gold;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .navbar-image {
            height: 20px;
            margin-right: 20px;
        }

        .navbar-title {
            color: gold;
            font-size: 18px;
        }

        .notification-icon {
            color: gold;
        }

        /* Hamburger Button Styles */
        .hamburger-btn {
            background-color: #333;
            color: gold;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 20px;
            position: fixed;
            top: 70px;
            left: 15px;
            z-index: 1000;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 90vh;
            background-color: #2a283c;
            position: fixed;
            left: -250px;
            top: 60px;
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
            background-color: #e6e6fa;
        }

        .sidebar.closed+.content-wrapper {
            margin-left: 0;
        }

        .sidebar-item a {
            display: list-item;
            padding: 15px;
            color: gold;
            text-decoration: none;
            text-align: start;
            border-radius: 5px;
            transition: background-color 0.3s;
            height: 60px;
        }

        .sidebar-item a:hover {
            background-color: #555;
        }

        /* Info Box Styles */
        .info-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 80px;
        }

        .info-box {
            padding: 20px;
            background: #2a283c;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 250px;
            height: 100px;
            margin: 0 auto;
            color: gold;
        }

        .info-box .icon {
            font-size: 40px;
            color: gold;
            margin-bottom: 20px;
        }

        .info-box .count {
            font-size: 24px;
            font-weight: bold;
            color: gold;
        }

        .info-box .label {
            font-size: 16px;
            color: #AAAAAA;
        }

        /* Employee Table Styles */
        .employee-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .employee-table th,
        .employee-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .employee-table th {
            background-color: #555;
            color: gold;
        }

        .employee-table tr:nth-child(even) {
            background-color: #333;
            color: gold;
        }

        .employee-table tr:nth-child(odd) {
            background-color: #2a283c;
            color: gold;
        }

        /* Form Styles */
        form {
            margin-top: 20px;
            background: #222;
            padding: 20px;
            border-radius: 10px;
        }

        form label {
            color: gold;
        }

        form input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }

        form button {
            background-color: gold;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #FFD700;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <img src="bgg.jpg" class="navbar-image"> <!-- Image on the left -->
                <span class="navbar-title">GREAT WALL ARTS</span>
            </div>
            <div class="navbar-right"> <!-- New container for right items -->
                <ul>
                    <li><a href="../user/logout.php">LOGOUT</a></li>
                    <li>
                        <a href="../notification.php" class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <?php if (isset($notification_count) && $notification_count > 0): ?>
                                <span class="badge"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Hamburger Button -->
    <button class="hamburger-btn" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar -->
    <div class="sidebar closed" id="sidebar">
        <div class="sidebar-content">
            <ul class="sidebar-list">
                <li class="sidebar-item"><a href="../dashboard.php">ADMIN</a></li>
                <li class="sidebar-item"><a href="../core.php">CORE</a></li>
                <li class="sidebar-item"><a href="../hr.php">HR</a></li>
                <li class="sidebar-item"><a href="../finance.php">FINANCE</a></li>
                <li class="sidebar-item"><a href="../logistics.php">LOGISTICS</a></li>
                <li class="sidebar-item"><a href="../user/user.php">CREATE USER ACCOUNT</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <!-- Info Boxes for Folders, Employees, and Files -->
        <div class="info-container">
            <div class="info-box">
                <i class="fas fa-folder icon"></i>
                <div class="count" id="folder-count">
                    <?php
                    include '../dbconnect.php';

                    // Query to count the number of folders
                    $folderCountSql = "SELECT COUNT(*) as total FROM folder";
                    $folderCountResult = $conn->query($folderCountSql);
                    $folderCount = 0;

                    if ($folderCountResult) {
                        $row = $folderCountResult->fetch_assoc();
                        $folderCount = $row['total']; // Get the total number of folders
                    }

                    // Close connection
                    $conn->close();
                    echo $folderCount; // Display the total count
                    ?>
                </div>
                <div class="label">Number of Folders</div>
            </div>
            <div class="info-box">
                <i class="fas fa-users icon"></i>
                <div class="count">123</div>
                <div class="label">Number of Employees</div>
            </div>
            <div class="info-box">
                <i class="fas fa-file icon"></i>
                <div class="count" id="file-count">
                    <?php
                    include '../dbconnect.php';

                    // Query to count the number of files
                    $fileCountSql = "SELECT COUNT(*) as total FROM files";
                    $fileCountResult = $conn->query($fileCountSql);
                    $fileCount = 0;

                    if ($fileCountResult) {
                        $row = $fileCountResult->fetch_assoc();
                        $fileCount = $row['total']; // Get the total number of files
                    }

                    // Close connection
                    $conn->close();
                    echo $fileCount; // Display the total count
                    ?>
                </div>
                <div class="label">Total Files</div>
            </div>

        </div>

        <!-- Attendance Form -->
        <h2 style="color: gold;">Record Attendance</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="name">Employee Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="time_in">Time In:</label>
            <input type="time" id="time_in" name="time_in" required>

            <label for="time_out">Time Out:</label>
            <input type="time" id="time_out" name="time_out" required>

            <button type="submit">Submit</button>
        </form>

        <h2 style="color: gold;">Employee Attendance Records</h2>
        <table class="employee-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../dbconnect.php';

                // Check if the form was submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Prepare and bind
                    $stmt = $conn->prepare("INSERT INTO attendance (name, date, time_in, time_out) VALUES (?, ?, ?, ?)");
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }

                    // Bind parameters
                    $stmt->bind_param("ssss", $name, $date, $time_in, $time_out);

                    // Set parameters and execute
                    $name = $_POST['name'];
                    $date = $_POST['date'];
                    $time_in = $_POST['time_in'];
                    $time_out = $_POST['time_out'];

                    if ($stmt->execute()) {
                        echo "<p style='color: gold;'>New attendance record created successfully</p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>"; // Print error message
                    }

                    // Close the statement
                    $stmt->close();
                }

                // Fetch attendance records
                $sql = "SELECT name, date, time_in, time_out FROM attendance";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['time_in']) . "</td>
                                <td>" . htmlspecialchars($row['time_out']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }

                // Close connection for attendance
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript to Toggle Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open'); // Toggle 'open' class to show/hide the sidebar
            const content = document.querySelector('.content-wrapper');
            content.classList.toggle('closed'); // Adjust content margin when sidebar is toggled
        }

        function markNotificationsAsRead() {
            window.location.href = 'notification.php';
        }
    </script>
</body>

</html>