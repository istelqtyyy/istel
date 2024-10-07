<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'user'; // Your database name
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Message to show the status update
$statusMessage = '';

// Approve or reject vendor status when the button is clicked
if (isset($_GET['action']) && isset($_GET['vendor_id'])) {
    $vendor_id = $_GET['vendor_id'];
    $action = $_GET['action'];

    // Determine action and update status
    if ($action === 'approve') {
        $sql = "UPDATE vendors SET status = 'Approved' WHERE id = ?";
        $statusMessage = "Vendor status updated to Approved.";
    } elseif ($action === 'reject') {
        $sql = "UPDATE vendors SET status = 'Rejected' WHERE id = ?";
        $statusMessage = "Vendor status updated to Rejected.";
    }

    if (isset($sql) && $stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $vendor_id);
        if ($stmt->execute()) {
            // Status message set above
        } else {
            echo "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all vendors
$sql = "SELECT * FROM vendors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendors List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            /* Black background */
            color: #FFD700;
            /* Yellow text */
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            /* Center the container horizontally */
            align-items: center;
            /* Center the container vertically */
            height: 100vh;
            /* Full viewport height */
            position: relative;
            /* Set relative position for the body */
            flex-direction: column;
            /* Stack children vertically */
        }

        .container {
            width: 90%;
            /* Full width with some padding */
            max-width: 1120px;
            /* Limit max width */
            background-color: #222;
            /* Darker background for the container */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            /* Subtle shadow effect */
            display: flex;
            flex-direction: column;
            /* Column direction for content stacking */
            align-items: stretch;
            /* Ensure content fills the container */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            /* Full width for table */
            border-collapse: collapse;
            margin: 20px 0;
            flex-grow: 1;
            /* Allow the table to grow */
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #FFD700;
            /* Yellow border */
        }

        th {
            background-color: #444;
            /* Darker header background */
        }

        tr:nth-child(even) {
            background-color: #333;
            /* Darker row background */
        }

        tr:hover {
            background-color: #555;
            /* Highlight row on hover */
        }

        a {
            color: #FFD700;
            /* Yellow link color */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
            /* Underline on hover */
        }

        /* Notification message styles */
        .notification {
            position: fixed;
            /* Fixed positioning */
            bottom: 20px;
            /* Distance from bottom */
            right: 20px;
            /* Distance from right */
            background-color: rgba(34, 34, 34, 0.8);
            /* Semi-transparent background */
            color: #FFD700;
            /* Yellow text */
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            /* Subtle shadow effect */
            display: none;
            /* Initially hidden */
        }

        /* Show notification */
        .notification.show {
            display: block;
            /* Show when needed */
        }

        /* Back button styles */
        .back-buttons {
            display: flex;
            justify-content: space-between;
            /* Space buttons evenly */
            margin-bottom: 20px;
            /* Space between buttons and the table */
        }

        .btn {
            background-color: #FFD700;
            /* Yellow background */
            color: #000;
            /* Black text */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #f0c000;
            /* Lighter yellow on hover */
        }

        .btn i {
            margin-right: 8px;
            /* Space between icon and text */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="back-buttons">
            <a href="../logistics.php" class="btn"><i class="fas fa-home"></i>Back</a>
            <a href="logistics2form.php" class="btn"><i class="fas fa-plus"></i>Fill Up Form</a>
        </div>

        <h2>Vendors List</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['company_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['city']}</td>
                            <td>{$row['state']}</td>
                            <td>{$row['status']}</td>
                            <td>";
                        // Show action buttons based on current status
                        if ($row['status'] === 'Pending') {
                            echo "<a href='logistics2.php?action=approve&vendor_id={$row['id']}'>Approve</a> | 
                                  <a href='logistics2.php?action=reject&vendor_id={$row['id']}'>Reject</a>";
                        } elseif ($row['status'] === 'Approved') {
                            echo "Approved";
                        } elseif ($row['status'] === 'Rejected') {
                            echo "Rejected";
                        }
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No vendors found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Notification message -->
    <div class="notification <?php echo $statusMessage ? 'show' : ''; ?>">
        <?php echo $statusMessage; ?>
    </div>

    <script>
        // Automatically hide the notification after a few seconds
        const notification = document.querySelector('.notification');
        if (notification.classList.contains('show')) {
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000); // Hide after 5 seconds
        }
    </script>

</body>

</html>

<?php
$conn->close();
?>