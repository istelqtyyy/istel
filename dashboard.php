<?php include 'header.php'; ?>

<div class="main-content">
    <center>
        <h1>ADMIN</h1>
        <p>Welcome to the admin dashboard!</p>
    </center>
    <div class="dashboard-folders">
        <div class="folder-item">
            <a href="hr.php">
                <i class="fas fa-user folder-icon"></i>
                <p class="folder-text">HR</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="core.php">
                <i class="fas fa-cogs folder-icon"></i>
                <p class="folder-text">CORE</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="finance.php">
                <i class="fas fa-money-bill-wave folder-icon"></i>
                <p class="folder-text">FINANCE</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="logistics.php">
                <i class="fas fa-truck folder-icon"></i>
                <p class="folder-text">LOGISTICS</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="email/email.php">
                <i class="fas fa-envelope folder-icon"></i>
                <p class="folder-text">EMAIL</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="monitoring.php">
                <i class="fas fa-user-shield folder-icon"></i>
                <p class="folder-text">ADMIN ACCOUNT MONITORING</p>
            </a>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>

<script>
    function fetchNotificationCount() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_notifications.php", true);
        xhr.onload = function() {
            if (this.status === 200) {
                const response = JSON.parse(this.responseText);
                const count = response.count;
                const badge = document.querySelector('.notification-icon .badge');

                if (count > 0) {
                    if (badge) {
                        badge.textContent = count;
                    } else {
                        const span = document.createElement('span');
                        span.className = 'badge';
                        span.textContent = count;
                        document.querySelector('.notification-icon').appendChild(span);
                    }
                } else if (badge) {
                    badge.remove(); // Remove badge if no unread notifications
                }
            }
        };
        xhr.send();
    }

    // Fetch notification count every 10 seconds
    setInterval(fetchNotificationCount, 10000);
</script>

<style>
    /* Main Content Styles */
    .main-content {
        padding: 100px;
        background-color: #e6e6fa;
        /* Background color */
        color: black;
        /* Changed text color to black */
    }

    .dashboard-folders {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 20px;
    }

    .folder-item {
        background-color: #333;
        /* Folder background color */
        border-radius: 10px;
        padding: 30px;
        margin: 10px;
        width: 120px;
        /* Width of each folder item */
        text-align: center;
        transition: transform 0.3s;
        /* Smooth hover effect */
    }

    .folder-item a {
        text-decoration: none;
        /* Remove underline */
        color: gold !important;
        /* Set link color to gold with !important */
    }

    .folder-text {
        color: white !important;
        /* Text color for folder items with !important */
    }

    .folder-item:hover {
        transform: translateY(-5px);
        /* Slight lift on hover */
        background-color: #444;
        /* Darken background on hover */
    }

    .folder-icon {
        font-size: 36px;
        /* Size of the folder icon */
        margin-bottom: 30px;
        /* Space below the icon */
        color: gold !important;
        /* Change icon color to gold with !important */
    }
</style>