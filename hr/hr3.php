<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Approval System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles for the document table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Styles for the submission form */
        .submission-form {
            margin-bottom: 20px;
        }

        .submission-form input,
        .submission-form button {
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>

<body>

    <h2>Document Approval System</h2>

    <!-- Document Submission Form -->
    <div class="submission-form">
        <h3>Submit Document for Approval</h3>
        <form action="" method="POST">
            <input type="email" name="user_email" placeholder="Your Email" required>
            <input type="text" name="document_name" placeholder="Document Name" required>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <h3>Submitted Documents</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User Email</th>
                <th>Document Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'user'); // Update with your DB credentials

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle Document Submission
            if (isset($_POST['submit'])) {
                $userEmail = $conn->real_escape_string($_POST['user_email']);
                $documentName = $conn->real_escape_string($_POST['document_name']);

                // Insert into the approval table
                $conn->query("INSERT INTO approval (user_email, document_name, status) VALUES ('$userEmail', '$documentName', 'pending')");
            }

            // Fetch documents
            $result = $conn->query("SELECT * FROM approval WHERE status = 'pending'");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['user_email']}</td>
                    <td>{$row['document_name']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='?approve={$row['id']}'>Approve</a> | 
                        <a href='?reject={$row['id']}'>Reject</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Handle Approve
    if (isset($_GET['approve'])) {
        $id = $_GET['approve'];
        $conn->query("UPDATE approval SET status = 'approved' WHERE id = $id");

        // Fetch user email
        $email = $conn->query("SELECT user_email FROM approval WHERE id = $id")->fetch_assoc()['user_email'];

        // Send notification email
        mail($email, "Document Approval", "Your document has been approved.");

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Handle Reject
    if (isset($_GET['reject'])) {
        $id = $_GET['reject'];
        $conn->query("UPDATE approval SET status = 'rejected' WHERE id = $id");

        // Fetch user email
        $email = $conn->query("SELECT user_email FROM approval WHERE id = $id")->fetch_assoc()['user_email'];

        // Send notification email
        mail($email, "Document Rejection", "Your document has been rejected.");

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Close connection
    $conn->close();
    ?>
</body>

</html>