<?php
include 'secure_session.php';
include 'db_connect.php';

// Ensure a valid student ID is provided
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: student-portal.php');
    exit;
}

// Only allow the student themselves or an admin to generate the certificate
$allowed = false;
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $allowed = true;
} elseif (isset($_SESSION['student_id']) && $_SESSION['student_id'] === $id) {
    $allowed = true;
}

if (!$allowed) {
    header('Location: student-portal.php');
    exit;
}

$stmt = $conn->prepare("SELECT name, enrolled_course FROM students WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$s = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$s) {
    echo "<p>Student record not found.</p>";
    exit;
}

// Escape values to prevent XSS
$studentName = htmlspecialchars($s['name'] ?? '', ENT_QUOTES, 'UTF-8');
$courseName = htmlspecialchars($s['enrolled_course'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="generate-cert-page" onload="window.print()">
    <div class="cert">
        <div class="praise">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَـٰنِ ٱلرَّحِيمِ</div>
        <img src="uploads/logo.jpg" class="logo">
        <h1>CERTIFICATE</h1>
        <p>All praise is due to <strong>ALLAH SWT</strong>, the Most Merciful.</p>
        <p style="margin-top:25px;">This is to certify that</p>
        <div class="name"><?= strtoupper($studentName) ?></div>
        <p>in recognition of exemplary performance and unwavering commitment to learning,</p>
        <div class="course"><?= $courseName ?></div>
        <p>has successfully completed the above course</p>
        <p>conducted by <strong>Inspire Tech School of IT, Nowshera Cantt.</strong></p>
        <p style="margin-top:20px; font-style:italic;">Your dedication, perseverance and passion for knowledge are truly commendable. May you continue to achieve excellence in every endeavor.</p>
        <p style="margin-top:10px; font-size:14px;">This institution is registered with the Skill Development Council.</p>

        <div class="date">Dated: <?= date('d-M-Y') ?></div>
        <div class="sig">
            Principal<br>
            Raheel Ahmad
        </div>
    </div>
</body>

</html>