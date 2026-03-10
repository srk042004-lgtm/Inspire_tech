<?php
// reset_hours.php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('HTTP/1.1 403 Forbidden');
    die('Unauthorized access.');
}

if (isset($_POST['reset_all'])) {
    // Reset all teachers to 0 minutes for the new month
    $sql = "UPDATE teachers SET total_online_minutes = 0";
    
    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php?msg=" . urlencode('Work hours reset successfully'));
    } else {
        header("Location: admin_dashboard.php?error=" . urlencode('Failed to reset hours'));
    }
    exit();
}

// Individual teacher reset if teacher_id is provided
if (isset($_POST['teacher_id'])) {
    $tid = (int)$_POST['teacher_id'];
    $sql = "UPDATE teachers SET total_online_minutes = 0 WHERE id = $tid";
    
    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php?msg=" . urlencode('Teacher hours reset'));
    } else {
        header("Location: admin_dashboard.php?error=" . urlencode('Failed to reset teacher hours'));
    }
    exit();
}
?>