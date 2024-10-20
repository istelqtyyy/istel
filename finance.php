<?php include 'header.php'; ?>

<style>
    body {
        margin: 0;
        /* Remove default margin */
        padding: 0;
        /* Remove default padding */
        background-color: #e6e6fa;
        /* Set the background color */
        min-height: 100vh;
        /* Ensure body takes full height of viewport */
        display: flex;
        /* Use flex to align items properly */
        flex-direction: column;
        /* Allow vertical stacking */
    }

    .main-content {
        flex-grow: 1;
        /* Allow the main content to grow and fill the remaining space */
        padding: 40px;
        /* Keep padding around the main content */
        /* No need to set background-color here since body covers it */
    }

    .dashboard-folders {
        display: flex;
        /* Use flexbox for better layout */
        justify-content: center;
        /* Center the items */
        flex-wrap: wrap;
        /* Allow items to wrap on smaller screens */
        gap: 20px;
        /* Space between items */
    }

    .folder-item {
        text-align: center;
        background-color: #2a283c;
        /* Background for folder items */
        border-radius: 10px;
        padding: 20px;
        width: 150px;
        /* Fixed width for uniformity */
        transition: transform 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Add shadow for depth */
    }

    .folder-item:hover {
        transform: scale(1.05);
        /* Scale effect on hover */
    }

    .folder-icon {
        font-size: 50px;
        /* Larger icon size */
        color: gold;
        /* Icon color */
    }

    .folder-item p {
        color: gold;
        /* Change text color to gold */
        margin-top: 10px;
        /* Spacing between icon and text */
        font-weight: bold;
        /* Make text bold */
    }

    h1 {
        color: #2a283c;
        /* Title color */
    }

    p {
        color: #2a283c;
        /* Subtitle color */
    }
</style>


<div class="main-content">
    <center>
        <h1>Finance</h1>
        <p>Welcome to the Finance Dashboard!</p>
    </center>

    <!-- Dashboard Folder Icons -->
    <div class="dashboard-folders">
        <div class="folder-item">
            <a href="finance/finance1.php">
                <i class="folder-icon fas fa-file-alt"></i> <!-- Legal documents icon -->
                <p>LEGAL DOCUMENTS</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="finance/finance2.php">
                <i class="folder-icon fas fa-check-circle"></i> <!-- Approval icon -->
                <p>APPROVAL</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="finance/finance3.php">
                <i class="folder-icon fas fa-users"></i> <!-- Employee accounts icon -->
                <p>EMPLOYEE ACC.</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="finance/finance4.php">
                <i class="folder-icon fas fa-chart-line"></i> <!-- Monitoring icon -->
                <p>EMPLOYEE ACC. MONITORING</p>
            </a>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>