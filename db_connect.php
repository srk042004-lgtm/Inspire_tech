<?php
// single source of truth for DB connection used across the project
// Note: If the database does not exist, we attempt to create it automatically.
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inspire_tech";

// Disable mysqli exception mode so we can handle missing DB ourselves.
mysqli_report(MYSQLI_REPORT_OFF);

// First try to connect to the desired database.
$conn = @new mysqli($host, $user, $pass, $db);

// If the database is missing, create it automatically.
if ($conn->connect_errno === 1049) {
    $conn = @new mysqli($host, $user, $pass);
    if ($conn->connect_errno) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $createSql = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conn->query($createSql)) {
        die("Failed to create database '$db': " . $conn->error);
    }

    // Select the newly created or existing database.
    if (!$conn->select_db($db)) {
        die("Failed to select database '$db': " . $conn->error);
    }
}

if ($conn->connect_errno) {
    die("Database connection failed: " . $conn->connect_error);
}

// optional: set charset for proper encoding
mysqli_set_charset($conn, 'utf8mb4');

// ------------------------------------------------------------
// Light schema migration (fix missing tables/columns due to manual DB changes)
// ------------------------------------------------------------
function _ensureTable($conn, $name, $createSql) {
    $res = $conn->query("SHOW TABLES LIKE '$name'");
    if (!$res || $res->num_rows === 0) {
        $conn->query($createSql);
    }
}

function _ensureColumn($conn, $table, $column, $definition) {
    $res = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if (!$res || $res->num_rows === 0) {
        $conn->query("ALTER TABLE `$table` ADD COLUMN $definition");
    }
}

// Ensure core tables exist (admin/users, students, etc.)
_ensureTable($conn, 'admins', "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_approved TINYINT(1) NOT NULL DEFAULT 0
)");

_ensureTable($conn, 'students', "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(50) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    enrolled_course VARCHAR(255) DEFAULT NULL,
    assigned_teacher_id INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

// Add columns if missing (these are used across the application)
_ensureColumn($conn, 'students', 'fname', 'fname VARCHAR(255) DEFAULT ""');
_ensureColumn($conn, 'students', 'dob', 'dob DATE DEFAULT NULL');
_ensureColumn($conn, 'students', 'district', 'district VARCHAR(255) DEFAULT ""');
_ensureColumn($conn, 'students', 'nic', 'nic VARCHAR(50) DEFAULT ""');
_ensureColumn($conn, 'students', 'qualification', 'qualification VARCHAR(255) DEFAULT ""');
_ensureColumn($conn, 'students', 'last_degree', 'last_degree VARCHAR(100) DEFAULT ""');
_ensureColumn($conn, 'students', 'mobile', 'mobile VARCHAR(50) DEFAULT ""');
_ensureColumn($conn, 'students', 'fmobile', 'fmobile VARCHAR(50) DEFAULT ""');
_ensureColumn($conn, 'students', 'password', 'password VARCHAR(255) DEFAULT ""');
_ensureColumn($conn, 'students', 'picture', 'picture VARCHAR(255) DEFAULT ""');
_ensureColumn($conn, 'students', 'course_fee_id', 'course_fee_id INT DEFAULT NULL');
_ensureColumn($conn, 'students', 'total_fee', 'total_fee DECIMAL(10,2) NOT NULL DEFAULT 0');
_ensureColumn($conn, 'students', 'paid_fee', 'paid_fee DECIMAL(10,2) NOT NULL DEFAULT 0');
_ensureColumn($conn, 'students', 'enrollment_status', "enrollment_status VARCHAR(20) NOT NULL DEFAULT 'pending'");
_ensureColumn($conn, 'students', 'enrollment_requested_at', 'enrollment_requested_at DATETIME DEFAULT CURRENT_TIMESTAMP');
_ensureColumn($conn, 'students', 'enrollment_approved_at', 'enrollment_approved_at DATETIME DEFAULT NULL');

// Ensure contact inquiries table exists (some setups used contact_messages)
$hasContactInquiries = ($conn->query("SHOW TABLES LIKE 'contact_inquiries'")->num_rows > 0);
$hasContactMessages = ($conn->query("SHOW TABLES LIKE 'contact_messages'")->num_rows > 0);
if (!$hasContactInquiries) {
    $conn->query("CREATE TABLE IF NOT EXISTS contact_inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        subject VARCHAR(200),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    if ($hasContactMessages) {
        $conn->query("INSERT INTO contact_inquiries (name, email, subject, message, created_at) SELECT name, email, subject, message, received_at FROM contact_messages");
    }
}

// Ensure tables for home_page.php
_ensureTable($conn, 'notices', "CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
)");

_ensureTable($conn, 'dit_positions', "CREATE TABLE IF NOT EXISTS dit_positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL
)");

_ensureTable($conn, 'achievements', "CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL
)");

_ensureTable($conn, 'course_fees', "CREATE TABLE IF NOT EXISTS course_fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course VARCHAR(255) NOT NULL,
    fee DECIMAL(10,2) NOT NULL DEFAULT 0
)");
