<?php
// single source of truth for DB connection used across the project
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inspire_tech";   // make sure this database exists in your XAMPP/MySQL installation

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    // if you're seeing this message in the browser, the server is running but the credentials are wrong
    die("Database connection failed: " . mysqli_connect_error());
}

// optional: set charset for proper encoding
mysqli_set_charset($conn, 'utf8mb4');
