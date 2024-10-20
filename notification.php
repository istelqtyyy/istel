<?php
include '../dbconnect.php';

// Fetch all unread notifications
$stmt = $conn->prepare("SELECT * FROM form ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Mark all notifications as read after displaying them
//$update_stmt = $conn->prepare("UPDATE form SET is_read = 1 WHERE is_read = 0");
//$update_stmt->execute();
//$update_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        /* Your existing styles here... */

        .notification-list {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            max-width: 600px;
            max-height: 400px;
            background-color: #f9f9f9;
            overflow-y: auto;
        }

        .notification-item {
            cursor: pointer;
            /* Change cursor to pointer */
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

        /* Styles for the toast notification */
        .toast {
            visibility: hidden;
            /* Hidden by default */
            min-width: 250px;
            /* Set a minimum width */
            margin-left: -125px;
            /* Center the toast */
            background-color: #333;
            /* Black background color */
            color: #fff;
            /* White text color */
            text-align: center;
            /* Centered text */
            border-radius: 2px;
            /* Rounded corners */
            padding: 16px;
            /* Padding */
            position: fixed;
            /* Sit on top of the screen */
            z-index: 1;
            /* Add a z-index to show it on top */
            left: 50%;
            /* Center the toast */
            bottom: 30px;
            /* Position the toast from the bottom */
            font-size: 17px;
            /* Font size */
        }

        .toast.show {
            visibility: visible;
            /* Show the toast */
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
            /* Fade in and fade out */
        }

        /* Animation for fading the toast in */
        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                /* Stay 30px from the bottom */
                opacity: 1;
            }
        }

        /* Animation for fading the toast out */
        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <h2>Notifications</h2>

    <div class="notification-list">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item" style="background-color: <?php echo ($notification['is_read'] ? 'cyan' : 'green'); ?>;">
                    <?php echo htmlspecialchars($notification['message']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Button to go to dashboard -->
    <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>

    <!-- Toast Notification -->
    <div id="toast" class="toast">New Notification!</div>

    <script>
        // Function to show toast notification
        function showToast(message) {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show"; // Add the "show" class to the toast

            setTimeout(function() {
                toast.className = toast.className.replace("show", ""); // Remove the "show" class after 3 seconds
            }, 3000);
        }

        // Simulating showing a toast when a new notification is received
        <?php if (!empty($notifications)): ?>
            showToast("You have new notifications!"); // Call the showToast function
        <?php endif; ?>

        // Change the color of notification on click
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                // Change the background color to cyan when clicked
                this.style.backgroundColor = 'cyan';
            });
        });
    </script>
</body>

</html>