<?php
/**
 * update_status.php
 * This file is called every 60 seconds via JavaScript fetch() from the dashboard.
 * It tracks active time and sets the online status.
 */

header('Content-Type: application/json');
session_start();
include 'db_connect.php';

// 1. Check if the session exists
if (isset($_SESSION['teacher_id'])) {
    $teacher_id = (int)$_SESSION['teacher_id'];

    // 2. Comprehensive Update Query
    // This updates: 
    // - total_online_minutes (+1 minute)
    // - is_online (sets to 1 so they show as 'Active' on Admin panel)
    // - last_seen (updates the timestamp to the current moment)
    $sql = "UPDATE teachers SET 
            total_online_minutes = total_online_minutes + 1, 
            is_online = 1, 
            last_seen = NOW() 
            WHERE id = $teacher_id";
    
    if ($conn->query($sql)) {
        // Return success response to the JavaScript console
        echo json_encode([
            "status" => "success", 
            "teacher_id" => $teacher_id,
            "timestamp" => date('Y-m-d H:i:s')
        ]);
    } else {
        // Return database error if the query fails
        echo json_encode([
            "status" => "error", 
            "message" => $conn->error
        ]);
    }
} else {
    // If the session has expired or the teacher logged out in another tab
    http_response_code(401);
    echo json_encode([
        "status" => "unauthorized",
        "message" => "Session expired. Please log in again."
    ]);
}
?>