<?php
// Include your database connection
include '../header.php';
include '../dbconnect.php';  // Adjust the path as necessary

// Initialize success and error messages
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a metric is set for updating
    if (isset($_POST['metric']) && isset($_POST['value'])) {
        $metric = $_POST['metric'];
        $value = $_POST['value'];
        $id = $_POST['id'];

        // Prepare an SQL statement to update data
        $sql = "UPDATE sales_data SET $metric = ? WHERE id = ?";

        // Prepare and execute the query
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("di", $value, $id); // d means decimal, i means integer

            // Execute the statement and check for success
            if ($stmt->execute()) {
                $success_message = ucfirst($metric) . " successfully updated.";
            } else {
                $error_message = "Error executing query: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $error_message = "Error preparing query: " . $conn->error;
        }
    } elseif (isset($_POST['metric']) && $_POST['metric'] === 'product_sales') {
        // Handle sales by product form
        $items = [];
        for ($i = 1; $i <= 8; $i++) {
            $items["item$i"] = $_POST["product$i"];
        }

        // Prepare SQL to update product sales
        $sql = "UPDATE sales_data SET item1 = ?, item2 = ?, item3 = ?, item4 = ?, item5 = ?, item6 = ?, item7 = ?, item8 = ? WHERE id = 1"; // Assume id 1 for product sales

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("dddddddd", ...array_values($items));

            if ($stmt->execute()) {
                $success_message = "Product sales data successfully updated.";
            } else {
                $error_message = "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing query: " . $conn->error;
        }
    } elseif (isset($_POST['metric']) && $_POST['metric'] === 'graph_data') {
        // Handle graphs (Sales vs Cost vs Profit)
        $amount = $_POST['amount'];
        $month = $_POST['month'];
        $year = $_POST['year'];

        // Update the sales data for graphs
        $sql = "UPDATE sales_data SET sale1 = ?, cost1 = ?, profit1 = ? WHERE month = ? AND year = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ddsss", $amount, $amount, $amount, $month, $year);

            if ($stmt->execute()) {
                $success_message = "Graph data successfully updated.";
            } else {
                $error_message = "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing query: " . $conn->error;
        }
    } else {
        $error_message = "Please fill in the value.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000;
            color: #fff;
            padding: 20px;
        }

        .stats-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .stat-card {
            flex: 1;
            min-width: 200px;
            background-color: #ffcc00;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 2rem;
            margin-bottom: 5px;
            color: #000;
        }

        .form-container {
            margin-top: 15px;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #000;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #ffb300;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .stat-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="stats-container">
        <div class="stat-card">
            <h2>Sales</h2>
            <div class="stat-value">$35.7K</div>
            <div class="form-container">
                <form action="" method="post">
                    <input type="hidden" name="metric" value="sales">
                    <input type="hidden" name="id" value="1">
                    <input type="number" name="value" step="0.01" placeholder="Enter Sales Amount" required>
                    <button type="submit">Update Sales</button>
                </form>
            </div>
        </div>

        <div class="stat-card">
            <h2>Cost</h2>
            <div class="stat-value">$10.0K</div>
            <div class="form-container">
                <form action="" method="post">
                    <input type="hidden" name="metric" value="cost">
                    <input type="hidden" name="id" value="2">
                    <input type="number" name="value" step="0.01" placeholder="Enter Cost" required>
                    <button type="submit">Update Cost</button>
                </form>
            </div>
        </div>

        <div class="stat-card">
            <h2>Profit</h2>
            <div class="stat-value">$5.0K</div>
            <div class="form-container">
                <form action="" method="post">
                    <input type="hidden" name="metric" value="profit">
                    <input type="hidden" name="id" value="3">
                    <input type="number" name="value" step="0.01" placeholder="Enter Profit" required>
                    <button type="submit">Update Profit</button>
                </form>
            </div>
        </div>

        <div class="stat-card">
            <h2>Expenses</h2>
            <div class="stat-value">$3.0K</div>
            <div class="form-container">
                <form action="" method="post">
                    <input type="hidden" name="metric" value="expenses">
                    <input type="hidden" name="id" value="4">
                    <input type="number" name="value" step="0.01" placeholder="Enter Expenses Amount" required>
                    <button type="submit">Update Expenses</button>
                </form>
            </div>
        </div>
    </div>

    <h2>Update Product Sales</h2>
    <div class="form-container">
        <form action="" method="post">
            <input type="hidden" name="metric" value="product_sales">
            <?php for ($i = 1; $i <= 8; $i++): ?>
                <label for="product<?php echo $i; ?>">Product <?php echo $i; ?> Sales:</label>
                <input type="number" name="product<?php echo $i; ?>" step="0.01" placeholder="Enter Product <?php echo $i; ?> Sales" required>
            <?php endfor; ?>
            <button type="submit">Update Product Sales</button>
        </form>
    </div>

    <h2>Update Graph Data</h2Here is the remaining part of the *Sales Dashboard* code:

            ```html
            <div class="form-container">
        <form action="" method="post">
            <input type="hidden" name="metric" value="graph_data">
            <label for="amount">Sales, Cost, Profit:</label>
            <input type="number" name="amount" step="0.01" placeholder="Enter Sales, Cost, and Profit Amount" required>

            <label for="month">Month:</label>
            <select name="month" required>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <!-- Add all months -->
            </select>

            <label for="year">Year:</label>
            <input type="number" name="year" placeholder="Enter Year" required>

            <button type="submit">Update Graph Data</button>
        </form>
        </div>

        <!-- Display success or error message -->
        <?php if (isset($success_message) && $metric !== 'cost'): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif;
        ?>

        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

</body>

</html>