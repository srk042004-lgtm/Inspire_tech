<?php
// simple endpoint to return pending certificate count as JSON
include 'db_connect.php';
$row = $conn->query("SELECT COUNT(*) as cnt FROM certificate_requests WHERE status='pending'")->fetch_assoc();
$cnt = $row ? (int)$row['cnt'] : 0;
header('Content-Type: application/json');
echo json_encode(['pending' => $cnt]);
