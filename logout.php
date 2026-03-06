<?php
// simple logout script
session_start();
// clear all session variables
$_SESSION = array();
// destroy the session cookie if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
// finally destroy session data on server
session_destroy();
// redirect back to login form
header('Location: student-portal.php');
exit;
?>
