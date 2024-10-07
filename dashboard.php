<?php include 'include/header.php'; ?>

<div class="main-content">
    <center>
        <h1>ADMIN</h1>
        <p>Welcome to admin dashboard!</p>
    </center>
    <div class="dashboard-folders">
        <div class="folder-item">
            <a href="hr.php">
                <i class="folder-icon">📁</i> <!-- Replace with an icon library if desired -->
                <p>HR</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="core.php">
                <i class="folder-icon">📁</i>
                <p>CORE</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="finance.php">
                <i class="folder-icon">📁</i>
                <p>FINANCE</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="logistics.php">
                <i class="folder-icon">📁</i>
                <p>LOGISTICS</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="email/email.php">
                <i class="folder-icon">📁</i>
                <p>EMAIL</p>
            </a>
        </div>
        <div class="folder-item">
            <a href="monitoring.php">
                <i class="folder-icon">📁</i>
                <p>ADMIN ACCOUNT MONITORING</p>
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