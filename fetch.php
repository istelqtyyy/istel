<?php
include '../dbconnect.php';

// Fetch all unread notifications
$stmt = $conn->prepare("SELECT * FROM form WHERE is_read = 0 ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Debugging: Check number of notifications
echo "Number of unread notifications: " . count($notifications); // This line can be removed later

// Optionally mark all notifications as read (comment this out for testing)
$update_stmt = $conn->prepare("UPDATE form SET is_read = 1 WHERE is_read = 0");
$update_stmt->execute();
$update_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        .notification-list {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            max-width: 600px;
            max-height: 400px;
            /* Limit height to create a scrollable area */
            background-color: #f9f9f9;
            overflow-y: auto;
            /* Add vertical scroll bar */
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item a {
            text-decoration: none;
            color: #333;
        }

        /* Style for the button */
        .dashboard-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Notifications</h2>

    <div class="notification-list">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item">
                    <?php echo htmlspecialchars($notification['message']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Button to go to dashboard -->
    <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
</body>

</html>