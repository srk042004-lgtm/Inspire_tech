<?php
session_start();
include 'db_connect.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    
    $res = $conn->query("SELECT * FROM teachers WHERE email='$email' AND status='active'");
    if ($res->num_rows == 1) {
        $t = $res->fetch_assoc();
        if (password_verify($pass, $t['password'])) {
            $_SESSION['teacher_id'] = $t['id'];
            $_SESSION['login_time'] = time(); // Start the clock
            
            // Update last login
            $conn->query("UPDATE teachers SET last_login = NOW() WHERE id=".$t['id']);
            header("Location: teacher_dashboard.php");
        }
    }
}
?>