<?php
include '../header.php';
include '../dbconnect.php';

// Fetch the latest data from sales_data
$query = "SELECT sales, profit, expenses FROM sales_data ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $latest_sales = $data['sales'];
    $latest_profit = $data['profit'];
    $latest_expenses = $data['expenses'];
} else {
    // Default values if no data found
    $latest_sales = 0;
    $latest_profit = 0;
    $latest_expenses = 0;
}

// Fetch the total number of sales (assuming you count them in the sales_data table)
$total_sales_count_query = "SELECT COUNT(*) as total_sales FROM sales_data";
$total_sales_count_result = $conn->query($total_sales_count_query);
$total_sales_count = $total_sales_count_result->fetch_assoc()['total_sales'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background-color: #121212;
            /* Dark background */
            font-family: 'Poppins', sans-serif;
            color: #f5f5f5;
            /* Light text */
        }

        .dashboard-header {
            background-color: #162447;
            /* Darker header */
            color: #e43f5a;
            /* Accent color for title */
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 36px;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            background-color: #FFD700;
            /* Card background color */
            padding: 20px;
            margin: 0 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #e8e8e8;
        }

        .stat-card h2 {
            font-size: 18px;
            color: #a7a7c4;
            /* Subtle text for card titles */
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
            color: #e43f5a;
            /* Highlighted value color */
        }

        .stat-card .stat-increment {
            color: #4cd137;
            /* Green for positive changes */
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #e43f5a;
            /* Accent button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #f74a6b;
        }

        .chart-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            /* Smaller margin */
        }

        .chart-box {
            flex: 0 1 45%;
            /* Reduce the width of each chart box */
            background-color: #FFD700;
            padding: 10px;
            /* Smaller padding */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        canvas {
            width: 100%;
            /* Full width within the container */
            height: 300px;
            /* Reduce chart height */
        }


        @media (max-width: 100px) {

            .stats-container,
            .chart-container {
                flex-direction: column;
                gap: 20px;
            }

            .stat-card,
            .chart-box {
                margin: 0;
            }
        }

        .chart-container {
            flex-direction: row;
            gap: 20px;
        }

        .chart-box {
            margin: 0;
        }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="dashboard-header">
        <h1>Sales and Monitoring</h1>
    </div>

    <!-- Stats Section with Update Buttons -->
    <div class="stats-container">
        <div class="stat-card">
            <h2>Sales</h2>
            <div class="stat-value">$<?php echo number_format($latest_sales, 2); ?></div>
            <div class="stat-increment">▲ 5%</div>
            <div class="form-container">
                <form action="../sales/process_sales.php" method="post">
                    <button type="submit">Update Sales</button>
                </form>
            </div>
        </div>
        <div class="stat-card">
            <h2>Profit</h2>
            <div class="stat-value">$<?php echo number_format($latest_profit, 2); ?></div>
            <div class="stat-increment">▲ 3%</div>
            <div class="form-container">
                <form action="../sales/process_sales.php" method="post">
                    <button type="submit">Update Profit</button>
                </form>
            </div>
        </div>
        <div class="stat-card">
            <h2>Number of Sales</h2>
            <div class="stat-value"><?php echo $total_sales_count; ?></div>
            <div class="stat-increment">▲ 4%</div>
            <div class="form-container">
                <form action="../sales/process_sales.php" method="post">
                    <button type="submit">Update Sales Count</button>
                </form>
            </div>
        </div>
        <div class="stat-card">
            <h2>Expenses Incurred</h2>
            <div class="stat-value">$<?php echo number_format($latest_expenses, 2); ?></div>
            <div class="stat-increment">▼ 2%</div>
            <div class="form-container">
                <form action="../sales/process_sales.php" method="post">
                    <button type="submit">Update Expenses</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="chart-container">
        <div class="chart-box">
            <h2>Sales vs Cost vs Profit</h2>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="chart-box">
            <h2>Most Consume Products</h2>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <script>
        // Sales chart data
        const salesData = {
            labels: ['Jan 2024', 'Feb 2024', 'Mar 2024', 'Apr 2024', 'May 2024', 'Jun 2024', 'July 2024', 'Aug 2024', 'Sep 2024', 'Oct 2024', 'Nov 2024', 'Dec 2024'],
            datasets: [{
                    label: 'Total Sales',
                    data: [15000, 20000, 25000, 30000, 28000, 32000, 28000, 32000, 28000, 32000, 28000, 32000], // Replace with actual data from your database if necessary
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Cost',
                    data: [10000, 15000, 18000, 25000, 22000, 24000, 10000, 15000, 18000, 25000, 22000, 24000], // Replace with actual data from your database if necessary
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Profit',
                    data: [5000, 5000, 7000, 5000, 6000, 8000, 5000, 7000, 5000, 6000, 8000, 3500], // Replace with actual data from your database if necessary
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        };

        // Pie chart data
        const pieData = {
            labels: ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6', 'Product 7', 'Product 8'],
            datasets: [{
                data: [81500, 74800, 82800, 110700, 90000, 82000, 87000, 94000], // Ensure 8 data points for 8 products
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#fff', '#000', '#FFA500', '#800080'], // Colors for all 8
                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#fff', '#000', '#FFA500', '#800080']
            }]
        };

        // Configuring the sales line chart
        const salesConfig = {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Configuring the pie chart
        const pieConfig = {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        };

        // Rendering the sales chart
        const salesChart = new Chart(
            document.getElementById('salesChart'),
            salesConfig
        );

        // Rendering the pie chart
        const pieChart = new Chart(
            document.getElementById('pieChart'),
            pieConfig
        );
    </script>

    <?php include '../include/footer.php'; ?>
</body>

</html>