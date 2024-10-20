<?php
// notification_count.php

include '../dbconnect.php';

// Fetch unread notification count
$stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM form WHERE is_read = 0");
$stmt->execute();
$result = $stmt->get_result();
$notification_count = $result->fetch_assoc()['unread_count'];
$stmt->close();
