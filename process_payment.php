<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('HTTP/1.1 403 Forbidden');
    die('Unauthorized access.');
}

if (!isset($_POST['teacher_id']) || !isset($_POST['amount'])) {
    header('Location: admin_dashboard.php?error=' . urlencode('Missing required payment information.'));
    exit;
}

$teacherId = (int)$_POST['teacher_id'];
$amount = (float)$_POST['amount'];

if ($amount <= 0) {
    header('Location: admin_dashboard.php?error=' . urlencode('Payment must be greater than zero.'));
    exit;
}

// Calculate remaining pending salary for validation
$row = $conn->query("SELECT salary - paid_salary AS pending_payment FROM teachers WHERE id = $teacherId")->fetch_assoc();
if (!$row) {
    header('Location: admin_dashboard.php?error=' . urlencode('Teacher not found.'));
    exit;
}

$pending = (float)$row['pending_payment'];
if ($amount > $pending) {
    header('Location: admin_dashboard.php?error=' . urlencode('Amount exceeds pending payment.'));
    exit;
}

$conn->query("UPDATE teachers SET paid_salary = paid_salary + $amount WHERE id = $teacherId");

header('Location: admin_dashboard.php?msg=' . urlencode('Payment recorded successfully.'));
exit;
