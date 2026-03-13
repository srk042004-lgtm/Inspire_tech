<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

echo "<h1>Database Connection Test</h1>";

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    echo "Connected successfully to database: " . $db . "<br>";

    // Test queries
    $tables = ['notices', 'dit_positions', 'achievements', 'course_fees'];
    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "Table '$table' has " . $row['count'] . " rows.<br>";
        } else {
            echo "Error querying table '$table': " . $conn->error . "<br>";
        }
    }
}

$conn->close();
?>