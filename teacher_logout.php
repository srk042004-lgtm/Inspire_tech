<?php
// teacher_logout.php
session_start();
include 'db_connect.php';

// 1. Mark teacher as OFFLINE in the database before destroying the session
if (isset($_SESSION['teacher_id'])) {
    $tid = (int)$_SESSION['teacher_id'];

    // Optional: Ensure the 'is_online' column exists (Safety Check)
    $checkCol = $conn->query("SHOW COLUMNS FROM teachers LIKE 'is_online'");
    if ($checkCol && $checkCol->num_rows === 0) {
        $conn->query("ALTER TABLE teachers ADD COLUMN is_online TINYINT(1) DEFAULT 0");
    }

    // Set status to 0 (Offline)
    $conn->query("UPDATE teachers SET is_online = 0 WHERE id = $tid");
}

// 2. Clear all session variables
$_SESSION = array();

// 3. If it's desired to kill the session, also delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session.
session_destroy();

// 5. Redirect to the login page
header("Location: teacher_login.php");
exit();
?>