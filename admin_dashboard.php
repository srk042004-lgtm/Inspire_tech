<?php
include 'secure_session.php';
include 'db_connect.php';

// --- DATABASE TABLES SETUP ---
// Ensure all necessary tables exist
$conn->query("CREATE TABLE IF NOT EXISTS certificate_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_name VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Logs sent enrollment approval/rejection notifications
$conn->query("CREATE TABLE IF NOT EXISTS enrollment_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    action VARCHAR(50),
    email VARCHAR(255),
    subject VARCHAR(255),
    body TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// teacher records for admin panel
$conn->query("CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    subject VARCHAR(255) DEFAULT '',
    salary DECIMAL(10,2) DEFAULT 0,
    paid_salary DECIMAL(10,2) DEFAULT 0,
    status ENUM('active','fired') DEFAULT 'active',
    total_online_minutes INT DEFAULT 0,
    is_online TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
// Ensure is_online column exists
$colCheck = $conn->query("SHOW COLUMNS FROM teachers LIKE 'is_online'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE teachers ADD COLUMN is_online TINYINT(1) DEFAULT 0");
}

// Ensure last_seen column exists for monitoring
$colCheck = $conn->query("SHOW COLUMNS FROM teachers LIKE 'last_seen'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE teachers ADD COLUMN last_seen DATETIME DEFAULT NULL");
}

// ensure students can be linked to a teacher for counting purposes
// only add column if it doesn't already exist to avoid duplicate errors
$colCheck = $conn->query("SHOW COLUMNS FROM students LIKE 'assigned_teacher_id'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE students ADD COLUMN assigned_teacher_id INT");
}

// Allow students to be linked to a course fee definition
$colCheck = $conn->query("SHOW COLUMNS FROM students LIKE 'course_fee_id'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE students ADD COLUMN course_fee_id INT");
}

// Define course fee structure (for quick fee collection)
$conn->query("CREATE TABLE IF NOT EXISTS course_fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(255) UNIQUE,
    total_fee DECIMAL(10,2) DEFAULT 0
)");

// Seed default course list if missing
$seedCheck = $conn->query("SELECT COUNT(*) as cnt FROM course_fees")->fetch_assoc();
if ($seedCheck && $seedCheck['cnt'] == 0) {
    $courses = [
        ['DIT', 15000],
        ['CIT', 18000],
        ['MsOffice', 12000],
        ['Web-dev', 25000],
        ['Python', 22000],
        ['AI', 28000]
    ];
    foreach ($courses as $c) {
        $course = $conn->real_escape_string($c[0]);
        $fee = (float)$c[1];
        $conn->query("INSERT INTO course_fees (course_name, total_fee) VALUES ('$course', $fee)");
    }
}

// Ensure fee payments table exists
$conn->query("CREATE TABLE IF NOT EXISTS fee_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    amount_paid DECIMAL(10,2) DEFAULT 0,
    payment_month VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255),
    achievement_title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// contact inquiries table used by contact.php
$conn->query("CREATE TABLE IF NOT EXISTS contact_inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
} elseif (isset($_GET['error'])) {
    $msg = "⚠️ " . htmlspecialchars($_GET['error']);
}

// --- LOGIC: CONTACT INQUIRIES ---
if (isset($_GET['delete_msg'])) {
    $id = (int)$_GET['delete_msg'];
    $stmt = $conn->prepare("DELETE FROM contact_inquiries WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $msg = "Inquiry deleted successfully!";
}  // marking read not available for inquiries, they are always shown


// --- LOGIC: HIRE TEACHER ---
if(isset($_POST['hire_teacher'])) {
    $name = trim($_POST['t_name']);
    $email = trim($_POST['t_email']);
    $pass = $_POST['t_pass'];
    $sub = trim($_POST['t_subject']);
    $sal = isset($_POST['t_salary']) ? (float)$_POST['t_salary'] : 0;

    if (empty($name) || empty($email) || empty($pass) || empty($sub) || $sal <= 0) {
        $msg = "⚠️ Please fill in all teacher fields correctly.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "⚠️ Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM teachers WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "⚠️ Error: This email is already registered to another teacher!";
            $stmt->close();
        } else {
            $stmt->close();
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO teachers (name, email, password, subject, salary) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssd', $name, $email, $hash, $sub, $sal);
            $stmt->execute();
            $stmt->close();
            $msg = "✅ Teacher added successfully!";
        }
    }
}

// --- LOGIC: FIRE/REHIRE TEACHER ---
if(isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $stmt = $conn->prepare("UPDATE teachers SET status = IF(status='active', 'fired', 'active') WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $msg = "Teacher status updated!";
}

// --- LOGIC: PAY SALARY ---
if(isset($_POST['pay_salary'])) {
    $id = (int)$_POST['t_id'];
    $amt = (float)$_POST['amount'];
    $conn->query("UPDATE teachers SET paid_salary = paid_salary + $amt WHERE id=$id");
    $msg = "Salary recorded!";
}

// --- LOGIC: ADD ONLINE HOURS ---
if(isset($_POST['add_hours'])) {
    $id = (int)$_POST['t_id'];
    // hours posted in minutes
    $mins = (int)$_POST['minutes'];
    $conn->query("UPDATE teachers SET total_online_minutes = total_online_minutes + $mins WHERE id=$id");
    $msg = "Online time updated!";
}

// --- LOGIC: ADJUST BASE SALARY ---
if(isset($_POST['adjust_salary'])) {
    $id = (int)$_POST['t_id'];
    $amt = (float)$_POST['amount'];
    // allow positive or negative adjustment
    $stmt = $conn->prepare("UPDATE teachers SET salary = salary + ? WHERE id = ?");
    $stmt->bind_param('di', $amt, $id);
    $stmt->execute();
    $stmt->close();
    $msg = "Base salary adjusted!";
}

// --- LOGIC: DELETE TEACHER ---
if(isset($_GET['delete_teacher'])) {
    $id = (int)$_GET['delete_teacher'];
    // optionally cascade or keep related data
    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $msg = "Teacher record removed!";
}

// --- LOGIC: UPDATE TEACHER DETAILS ---
if(isset($_POST['update_teacher'])) {
    $id = (int)$_POST['t_id'];
    $name = trim($_POST['t_name']);
    $email = trim($_POST['t_email']);
    $sub = trim($_POST['t_subject']);
    $sal = (float)$_POST['t_salary'];
    $paid = isset($_POST['t_paid']) ? (float)$_POST['t_paid'] : 0;
    $online = isset($_POST['t_online']) ? (int)$_POST['t_online'] : 0;

    if (empty($name) || empty($email) || empty($sub) || $sal < 0 || $paid < 0 || $online < 0) {
        $msg = "⚠️ Invalid input for teacher update.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "⚠️ Please enter a valid email address.";
    } else {
        $query = "UPDATE teachers SET name=?, email=?, subject=?, salary=?, paid_salary=?, total_online_minutes=?";
        $types = 'sssddd';
        $params = [$name, $email, $sub, $sal, $paid, $online];

        if (!empty($_POST['t_pass'])) {
            $pass = password_hash($_POST['t_pass'], PASSWORD_DEFAULT);
            $query .= ", password=?";
            $types .= 's';
            $params[] = $pass;
        }

        $query .= " WHERE id=?";
        $types .= 'i';
        $params[] = $id;

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();

        $msg = "Teacher record updated!";
    }
}

// --- LOGIC: REGISTER/UPDATE STUDENT ---
if (isset($_POST['register_student'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);
    $total = (int)$_POST['total_fee'];
    $paid = (int)$_POST['paid_fee'];

    if ($total < 0 || $paid < 0) {
        $msg = "⚠️ Invalid fee values.";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, phone_number, enrolled_course, total_fee, paid_fee) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssdd', $name, $phone, $course, $total, $paid);
        $stmt->execute();
        $stmt->close();
        $msg = "Student Registered!";
    }
}

if (isset($_POST['admin_add_student'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $dob = $_POST['dob'] ?? null;
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $last_degree = mysqli_real_escape_string($conn, $_POST['last_degree']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $fmobile = mysqli_real_escape_string($conn, $_POST['fmobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $courseFeeId = (int)$_POST['course_fee_id'];
    $courseRow = $conn->query("SELECT course_name, total_fee FROM course_fees WHERE id = $courseFeeId")->fetch_assoc();
    $courseName = $courseRow ? mysqli_real_escape_string($conn, $courseRow['course_name']) : '';
    $totalFee = $courseRow ? (float)$courseRow['total_fee'] : 0;

    $assignedTeacher = isset($_POST['assigned_teacher']) ? (int)$_POST['assigned_teacher'] : 0;

    // Image upload
    $pic_name = '';
    if (!empty($_FILES['pic']['name'])) {
        $target_dir = 'uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $pic_name = time() . '_' . basename($_FILES['pic']['name']);
        move_uploaded_file($_FILES['pic']['tmp_name'], $target_dir . $pic_name);
    }

    $conn->query("INSERT INTO students (name, fname, dob, district, email, nic, qualification, last_degree, mobile, fmobile, password, picture, enrolled_course, course_fee_id, total_fee, paid_fee, assigned_teacher_id) 
        VALUES ('$name', '$fname', '$dob', '$district', '$email', '$nic', '$qualification', '$last_degree', '$mobile', '$fmobile', '$password', '$pic_name', '$courseName', $courseFeeId, $totalFee, 0, " . ($assignedTeacher ? $assignedTeacher : 'NULL') . ")");
    $msg = "Student added successfully!";
}

if (isset($_POST['update_student'])) {
    $id = (int)$_POST['student_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $total = (int)$_POST['total_fee'];
    $paid = (int)$_POST['paid_fee'];
    $conn->query("UPDATE students SET name='$name', phone_number='$phone', enrolled_course='$course', total_fee=$total, paid_fee=$paid WHERE id=$id");
    $msg = "Record Updated!";
}

// --- LOGIC: ASSIGN STUDENT TO TEACHER ---
if (isset($_POST['assign_student'])) {
    $studentId = (int)$_POST['student_id'];
    $teacherId = (int)$_POST['teacher_id'];
    if ($teacherId === 0) {
        $conn->query("UPDATE students SET assigned_teacher_id = NULL WHERE id = $studentId");
        $msg = "Student unassigned from teacher.";
    } else {
        $conn->query("UPDATE students SET assigned_teacher_id = $teacherId WHERE id = $studentId");
        $msg = "Student assigned to teacher successfully.";
    }
}

// --- LOGIC: FEES & NOTICES ---
if (isset($_POST['collect_fee'])) {
    $s_id = (int)$_POST['student_id'];
    $courseFeeId = (int)$_POST['course_fee_id'];
    $amount = (float)$_POST['amount'];
    $month = mysqli_real_escape_string($conn, $_POST['month']);

    // Ensure course info is consistent
    $courseRow = $conn->query("SELECT course_name, total_fee FROM course_fees WHERE id = $courseFeeId")->fetch_assoc();
    if (!$courseRow) {
        $msg = "⚠️ Invalid course selected.";
    } else {
        $courseName = $conn->real_escape_string($courseRow['course_name']);
        $totalFee = (float)$courseRow['total_fee'];

        $conn->query("INSERT INTO fee_payments (student_id, amount_paid, payment_month) VALUES ($s_id, $amount, '$month')");
        $conn->query("UPDATE students SET paid_fee = paid_fee + $amount, total_fee = $totalFee, enrolled_course = '$courseName', course_fee_id = $courseFeeId WHERE id = $s_id");
        $msg = "Fee Recorded!";
    }
}

// --- LOGIC: COURSE FEE STRUCTURE MANAGEMENT ---
if (isset($_POST['add_course_fee'])) {
    $courseName = mysqli_real_escape_string($conn, $_POST['course_name']);
    $courseFee = (float)$_POST['course_fee'];
    $conn->query("INSERT INTO course_fees (course_name, total_fee) VALUES ('$courseName', $courseFee)");
    $msg = "Course fee added!";
}

if (isset($_POST['update_course_fee'])) {
    $cfid = (int)$_POST['course_fee_id'];
    $courseName = mysqli_real_escape_string($conn, $_POST['course_name']);
    $courseFee = (float)$_POST['course_fee'];
    $conn->query("UPDATE course_fees SET course_name = '$courseName', total_fee = $courseFee WHERE id = $cfid");
    $msg = "Course fee updated!";
}

if (isset($_GET['delete_course_fee'])) {
    $cfid = (int)$_GET['delete_course_fee'];
    $conn->query("DELETE FROM course_fees WHERE id = $cfid");
    $msg = "Course fee removed!";
}

if (isset($_POST['add_notice'])) {
    $txt = mysqli_real_escape_string($conn, $_POST['notice_text']);
    $conn->query("INSERT INTO notices (title, content) VALUES ('Notice', '$txt')");
    $msg = "Notice Sent!";
}

if (isset($_POST['add_achievement'])) {
    $ach_name = mysqli_real_escape_string($conn, $_POST['ach_name']);
    $ach_title = mysqli_real_escape_string($conn, $_POST['ach_title']);
    $conn->query("INSERT INTO achievements (student_name, achievement_title) VALUES ('$ach_name', '$ach_title')");
    $msg = "Achievement Added!";
}

// --- LOGIC: CERTIFICATES ---
if (isset($_GET['approve_cert'])) {
    $req_id = (int)$_GET['approve_cert'];
    $row = $conn->query("SELECT student_id FROM certificate_requests WHERE id=$req_id")->fetch_assoc();
    $conn->query("UPDATE certificate_requests SET status='issued' WHERE id=$req_id");
    $msg = "Certificate Marked as Issued!";
    if ($row && isset($row['student_id'])) {
        echo "<script>window.open('generate_cert.php?id=" . $row['student_id'] . "','_blank');</script>";
    }
}

// Enrollment approval workflow
if (isset($_GET['approve_enrollment'])) {
    $sid = (int)$_GET['approve_enrollment'];
    $conn->query("UPDATE students SET enrollment_status='approved', enrollment_approved_at=NOW() WHERE id=$sid");
    $msg = "Student enrollment approved!";

    // Notify student by email (requires mail server configuration)
    $studentRow = $conn->query("SELECT email, name FROM students WHERE id=$sid LIMIT 1")->fetch_assoc();
    if ($studentRow && !empty($studentRow['email'])) {
        $to = $studentRow['email'];
        $subject = "Your Inspire Tech enrollment has been approved";
        $bodyHtml = "<p>Hello " . htmlspecialchars($studentRow['name']) . ",</p>\n" .
            "<p>Your enrollment request has been approved by the admin. You can now log in and access your course.</p>\n" .
            "<p><strong>Next Steps:</strong></p>\n" .
            "<ul>\n" .
            "<li>Log in at <a href='" . (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '') . "/student-portal.php'>Student Portal</a></li>\n" .
            "<li>Go to the Dashboard to access your classroom.</li>\n" .
            "</ul>\n" .
            "<p>Best regards,<br>Inspire Tech Team</p>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Inspire Tech <no-reply@inspire-tech.local>" . "\r\n";

        @mail($to, $subject, $bodyHtml, $headers);

        // Log notification for audit
        $logStmt = $conn->prepare("INSERT INTO enrollment_notifications (student_id, action, email, subject, body) VALUES (?, ?, ?, ?, ?)");
        if ($logStmt) {
            $action = 'approved';
            $logStmt->bind_param('issss', $sid, $action, $to, $subject, $bodyHtml);
            $logStmt->execute();
            $logStmt->close();
        }
    }
}

if (isset($_GET['reject_enrollment'])) {
    $sid = (int)$_GET['reject_enrollment'];
    $conn->query("UPDATE students SET enrollment_status='rejected' WHERE id=$sid");
    $msg = "Student enrollment request rejected.";

    $studentRow = $conn->query("SELECT email, name FROM students WHERE id=$sid LIMIT 1")->fetch_assoc();
    if ($studentRow && !empty($studentRow['email'])) {
        $to = $studentRow['email'];
        $subject = "Your Inspire Tech enrollment has been declined";
        $bodyHtml = "<p>Hello " . htmlspecialchars($studentRow['name']) . ",</p>\n" .
            "<p>Your enrollment request was declined by the admin. If you believe this is a mistake, please contact the admin team.</p>\n" .
            "<p>Best regards,<br>Inspire Tech Team</p>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Inspire Tech <no-reply@inspire-tech.local>" . "\r\n";

        @mail($to, $subject, $bodyHtml, $headers);

        // Log notification for audit
        $logStmt = $conn->prepare("INSERT INTO enrollment_notifications (student_id, action, email, subject, body) VALUES (?, ?, ?, ?, ?)");
        if ($logStmt) {
            $action = 'rejected';
            $logStmt->bind_param('issss', $sid, $action, $to, $subject, $bodyHtml);
            $logStmt->execute();
            $logStmt->close();
        }
    }
}

// --- ANALYTICS ---
$stats = $conn->query("SELECT COUNT(*) as total, SUM(paid_fee) as rev, SUM(total_fee - paid_fee) as dues FROM students")->fetch_assoc();
$pendingCerts = (int)$conn->query("SELECT COUNT(*) as cnt FROM certificate_requests WHERE status='pending'")->fetch_assoc()['cnt'];
$pendingEnrolls = (int)$conn->query("SELECT COUNT(*) as cnt FROM students WHERE enrollment_status='pending'")->fetch_assoc()['cnt'];

// Course fee list (for quick fee template)
$courseFeesRes = $conn->query("SELECT id, course_name, total_fee FROM course_fees ORDER BY course_name");
$courseFees = [];
$courseFeesMap = [];
while ($cf = $courseFeesRes->fetch_assoc()) {
    $courseFees[] = $cf;
    $courseFeesMap[$cf['id']] = $cf;
}

// Student fee data for JS lookup
$studentFeeRes = $conn->query("SELECT id, name, enrolled_course, course_fee_id, total_fee, paid_fee FROM students ORDER BY name");
$studentFees = [];
while ($s = $studentFeeRes->fetch_assoc()) {
    $studentFees[$s['id']] = $s;
}

// Teacher monitoring (online status + hours + pending payment)
$teacher_monitor = $conn->query("SELECT id, name, subject, is_online, last_seen, total_online_minutes, (total_online_minutes / 60) AS total_hours, (salary - paid_salary) AS pending_payment FROM teachers ORDER BY is_online DESC, last_seen DESC");

// total inquiries count for nav badge
$msgCountRow = $conn->query("SELECT COUNT(*) as cnt FROM contact_inquiries")->fetch_assoc();
$msgCount = $msgCountRow ? (int)$msgCountRow['cnt'] : 0;

// Build teacher map for assignment display
$teacherMap = [];
$teacherListRes = $conn->query("SELECT id, name FROM teachers ORDER BY name");
while ($tr = $teacherListRes->fetch_assoc()) {
    $teacherMap[$tr['id']] = $tr['name'];
}

// Fetch contact inquiries
$messages = $conn->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspire Tech | Admin Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-page admin-dashboard">
    <?php
        $adminName = $_SESSION['admin_name'] ?? 'Administrator';
        $adminInitial = strtoupper(substr($adminName, 0, 1));
    ?>
    <div class="sidebar">
        <div class="sidebar-profile">
            <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" alt="Inspire Tech" class="sidebar-img">
            <h6><?php echo htmlspecialchars($adminName); ?></h6>
            <small>Administrator</small>
        </div>
        <a href="admin_dashboard.php" class="nav-link-custom active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="admin_cron_manager.php" class="nav-link-custom"><i class="fas fa-robot"></i>Automation</a>
        <a href="home_page.php" class="nav-link-custom"><i class="fas fa-home"></i>Home</a>
        <a href="logout.php" class="nav-link-custom text-danger"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
            <div>
                <h1 class="mb-1">Admin Dashboard</h1>
                <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($adminName); ?>.</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button id="theme-toggle" class="btn btn-outline-secondary btn-sm" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Total Students</h6>
                    <h3><?= $stats['total'] ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Revenue</h6>
                    <h3>Rs. <?= number_format($stats['rev']) ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Total Dues</h6>
                    <h3 class="text-danger">Rs. <?= number_format($stats['dues']) ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6>Teachers</h6>
                    <h3><?= $conn->query("SELECT id FROM teachers")->num_rows ?></h3>
                </div>
            </div>
        </div>

        <!-- Teacher Monitoring Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Faculty Real-Time Monitor</h5>
                    <small id="monitorLastUpdated" class="text-muted">Last updated: just now</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form action="reset_hours.php" method="POST" onsubmit="return confirm('Are you sure? This will set ALL teacher hours to zero for the new month.');">
                        <button type="submit" name="reset_all" class="btn btn-sm btn-danger">
                            <i class="fas fa-redo me-1"></i> Reset All Hours
                        </button>
                    </form>
                    <button id="monitorRefreshBtn" class="btn btn-sm btn-outline-secondary" onclick="refreshTeacherMonitor();">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="monitorSummary" class="mb-2 text-muted small">Loading status...</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Teacher Name</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Last Active</th>
                                <th>Total Workload</th>
                                <th>Pending Salary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="monitorBody">
                            <?php $monitor = $conn->query("SELECT id, name, subject, is_online, last_seen, total_online_minutes, (total_online_minutes / 60) AS total_hours, (salary - paid_salary) AS pending_payment FROM teachers ORDER BY is_online DESC, last_seen DESC");
                            while($row = $monitor->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($row['name']) ?></div>
                                    <small class="text-muted">ID: #<?= $row['id'] ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark"><?= htmlspecialchars($row['subject']) ?></span></td>
                                <td>
                                    <?php if($row['is_online'] == 1): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle me-1 small"></i> Online
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Offline</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= $row['last_seen'] ? date('M d, h:i A', strtotime($row['last_seen'])) : 'N/A' ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2"><?= round($row['total_hours'], 1) ?> hrs</span>
                                        <div class="progress w-100" style="height: 5px;">
                                            <div class="progress-bar bg-info" style="width: <?= min(($row['total_hours']/100)*100, 100) ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-danger fw-bold">
                                    Rs. <?= number_format($row['pending_payment']) ?>
                                </td>
                                <td>
                                    <form action="reset_hours.php" method="POST" onsubmit="return confirm('Reset hours for this teacher only?');" style="display:inline;">
                                        <input type="hidden" name="teacher_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-link btn-sm text-decoration-none text-muted">
                                            <i class="fas fa-history"></i> Reset
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#payModal<?= $row['id'] ?>">
                                        Pay Salary
                                    </button>

                                    <!-- Simple Payment Modal -->
                                    <div class="modal fade" id="payModal<?= $row['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="process_payment.php" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Pay Teacher: <?= htmlspecialchars($row['name']) ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="teacher_id" value="<?= $row['id'] ?>">
                                                        <label>Enter Amount to Pay (Rs.):</label>
                                                        <input type="number" name="amount" class="form-control" max="<?= $row['pending_payment'] ?>" required>
                                                        <small class="text-muted">Max pending: Rs. <?= number_format($row['pending_payment']) ?></small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Confirm Payment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if ($msg): ?><div class="alert alert-success alert-dismissible fade show"><?= $msg ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

        <div id="messagesSection" class="action-panel border-start border-4 border-primary">
            <h5 class="fw-bold mb-3"><i class="fas fa-comment-dots text-primary me-2"></i> Recent Inquiries</h5>
            <?php if ($messages->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($m = $messages->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($m['name']) ?></strong><br><small><?= htmlspecialchars($m['email']) ?></small></td>
                                    <td><?= htmlspecialchars($m['subject']) ?></td>
                                    <td><?= htmlspecialchars($m['message']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="mailto:<?= htmlspecialchars($m['email']) ?>?subject=Re: <?= rawurlencode($m['subject']) ?>" class="btn btn-sm btn-primary"><i class="fas fa-reply"></i></a>
                                            <a href="?delete_msg=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete inquiry?')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?><p class="text-muted">No inquiries found.</p><?php endif; ?>
        </div>

        <div id="feeSection" class="action-panel">
            <h5 class="fw-bold mb-3">Quick Fee Collection</h5>
            <form method="POST" class="row g-2" id="feeForm">
                <div class="col-md-4">
                    <select name="student_id" id="feeStudent" class="form-select" required>
                        <option value="">Choose Student</option>
                        <?php foreach ($studentFees as $sid => $info): ?>
                            <option value="<?= $sid ?>"><?= htmlspecialchars($info['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="course_fee_id" id="feeCourse" class="form-select" required>
                        <option value="">Choose Course</option>
                        <?php foreach ($courseFees as $cf): ?>
                            <option value="<?= $cf['id'] ?>" data-fee="<?= $cf['total_fee'] ?>"><?= htmlspecialchars($cf['course_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="month" name="month" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <input type="text" id="feeTotal" class="form-control" placeholder="Total Fee" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" id="feePaid" class="form-control" placeholder="Already Paid" readonly>
                </div>
                <div class="col-md-3">
                    <input type="text" id="feeRemaining" class="form-control" placeholder="Remaining" readonly>
                </div>
                <div class="col-md-3">
                    <input type="number" name="amount" id="feeAmount" class="form-control" placeholder="Pay Now" required min="0">
                </div>

                <div class="col-md-2">
                    <button name="collect_fee" class="btn btn-success w-100">Save</button>
                </div>
            </form>
            <div class="text-muted small mt-2">Select a student and course to auto-fill fees. Enter the amount to collect and click Save.</div>
        </div>

        <div id="courseFeeSection" class="action-panel border-start border-4 border-secondary">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold"><i class="fas fa-dollar-sign text-secondary me-2"></i> Course Fee Structure</h5>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#courseFeeForm" aria-expanded="false" aria-controls="courseFeeForm">
                    <i class="fas fa-plus me-1"></i> Add Course
                </button>
            </div>
            <div class="collapse mb-3" id="courseFeeForm">
                <form method="POST" class="row g-2">
                    <div class="col-md-5"><input type="text" name="course_name" class="form-control" placeholder="Course name" required></div>
                    <div class="col-md-5"><input type="number" name="course_fee" class="form-control" placeholder="Total fee" step="0.01" required></div>
                    <div class="col-md-2"><button name="add_course_fee" class="btn btn-success w-100">Save</button></div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Fee</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $courseList = $conn->query("SELECT * FROM course_fees ORDER BY course_name");
                        while ($cf = $courseList->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($cf['course_name']) ?></td>
                            <td>Rs. <?= number_format($cf['total_fee'], 2) ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-secondary edit-course-fee" 
                                    data-id="<?= $cf['id'] ?>" 
                                    data-name="<?= htmlspecialchars($cf['course_name'], ENT_QUOTES) ?>" 
                                    data-fee="<?= $cf['total_fee'] ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editCourseFeeModal">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="?delete_course_fee=<?= $cf['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this course fee?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Course Fee Modal -->
        <div class="modal fade" id="editCourseFeeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Course Fee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="course_fee_id" id="editCourseFeeId">
                            <div class="mb-3">
                                <label class="form-label">Course Name</label>
                                <input type="text" name="course_name" id="editCourseName" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Fee</label>
                                <input type="number" step="0.01" name="course_fee" id="editCourseFee" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_course_fee" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="certSection" class="action-panel border-start border-4 border-warning">
            <h5 class="fw-bold mb-3"><i class="fas fa-award text-warning"></i> Certificate Requests</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $certs = $conn->query("SELECT c.*, COALESCE(s.name,'<em>unknown</em>') AS name FROM certificate_requests c LEFT JOIN students s ON c.student_id = s.id WHERE c.status='pending'");
                        if ($certs->num_rows == 0) echo "<tr><td colspan='3' class='text-muted'>No pending requests</td></tr>";
                        while ($c = $certs->fetch_assoc()): ?>
                            <tr>
                                <td><?= $c['name'] ?></td>
                                <td><?= $c['course_name'] ?></td>
                                <td>
                                    <a href="generate_cert.php?id=<?= $c['student_id'] ?>" target="_blank" class="btn btn-sm btn-primary">Preview</a>
                                    <a href="?approve_cert=<?= $c['id'] ?>" class="btn btn-sm btn-success">Issue</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="enrollmentSection" class="action-panel border-start border-4 border-primary">
            <h5 class="fw-bold mb-3"><i class="fas fa-user-clock text-primary"></i> Enrollment Requests</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pendingEnrollRes = $conn->query("SELECT id, name, enrolled_course, enrollment_requested_at FROM students WHERE enrollment_status='pending' ORDER BY enrollment_requested_at DESC");
                        if ($pendingEnrollRes->num_rows == 0) echo "<tr><td colspan='4' class='text-muted'>No pending enrollment requests</td></tr>";
                        while ($pe = $pendingEnrollRes->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($pe['name']) ?></td>
                                <td><?= htmlspecialchars($pe['enrolled_course']) ?></td>
                                <td><?= date('M d, Y H:i', strtotime($pe['enrollment_requested_at'])) ?></td>
                                <td>
                                    <a href="?approve_enrollment=<?= $pe['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                                    <a href="?reject_enrollment=<?= $pe['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="action-panel">
                    <h6>Broadcast Notice</h6>
                    <form method="POST"><textarea name="notice_text" class="form-control mb-2" rows="2" placeholder="Write something..."></textarea><button name="add_notice" class="btn btn-primary w-100 btn-sm">Post</button></form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="action-panel">
                    <h6>Web Achievement</h6>
                    <form method="POST" class="row g-2">
                        <div class="col-6"><input type="text" name="ach_name" class="form-control form-control-sm" placeholder="Student"></div>
                        <div class="col-6"><input type="text" name="ach_title" class="form-control form-control-sm" placeholder="Rank/Title"></div>
                        <div class="col-12"><button name="add_achievement" class="btn btn-warning w-100 btn-sm">Add Achievement</button></div>
                    </form>
                </div>
            </div>
        </div>

        <div id="studentSection" class="action-panel">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="fw-bold">Student Registry</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-user-plus me-1"></i> Add Student
                    </button>
                    <input type="text" id="search" class="form-control w-25 form-control-sm" placeholder="Search students...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Fees Balance</th>
                            <th>Teacher</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="studentTable">
                        <?php $res = $conn->query("SELECT s.*, t.name AS teacher_name FROM students s LEFT JOIN teachers t ON s.assigned_teacher_id = t.id ORDER BY s.id DESC");
                        while ($s = $res->fetch_assoc()): 
                            $status = $s['enrollment_status'] ?? 'pending';
                            $statusClass = 'secondary';
                            if ($status === 'approved') $statusClass = 'success';
                            elseif ($status === 'rejected') $statusClass = 'danger';
                            elseif ($status === 'pending') $statusClass = 'warning';
                        ?>
                            <tr>
                                <td class="fw-bold"><?= $s['name'] ?></td>
                                <td><?= $s['enrolled_course'] ?></td>
                                <td><small>Paid: <?= $s['paid_fee'] ?> / Total: <?= $s['total_fee'] ?></small></td>
                                <td><small><?= htmlspecialchars($s['teacher_name'] ?? '—') ?></small></td>
                                <td><span class="badge bg-<?= $statusClass ?>"><?= ucfirst($status) ?></span></td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn"
                                        data-id="<?= $s['id'] ?>" data-name="<?= $s['name'] ?>"
                                        data-phone="<?= $s['phone_number'] ?>" data-course="<?= $s['enrolled_course'] ?>"
                                        data-total="<?= $s['total_fee'] ?>" data-paid="<?= $s['paid_fee'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="assignSection" class="action-panel border-start border-4 border-success">
            <h5 class="fw-bold mb-3"><i class="fas fa-handshake text-success me-2"></i> Assign Students to Teachers</h5>
            <form method="POST" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small">Student</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Select student...</option>
                        <?php
                        $studentList = $conn->query("SELECT id, name, assigned_teacher_id FROM students ORDER BY name");
                        while ($st = $studentList->fetch_assoc()):
                            $assigned = $st['assigned_teacher_id'];
                            $assignedName = $assigned && isset($teacherMap[$assigned]) ? " (" . htmlspecialchars($teacherMap[$assigned]) . ")" : "";
                        ?>
                            <option value="<?= $st['id'] ?>"><?= htmlspecialchars($st['name']) . $assignedName ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small">Teacher</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="0">Unassign</option>
                        <?php foreach ($teacherMap as $tid => $tname): ?>
                            <option value="<?= $tid ?>"><?= htmlspecialchars($tname) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button name="assign_student" class="btn btn-success w-100">Assign</button>
                </div>
            </form>
            <small class="text-muted">Select a student and the teacher to assign. Use "Unassign" to clear.</small>
        </div>

        <!-- teacher management block -->
        <div id="teacherSection" class="action-panel border-start border-4 border-info">
            <h5 class="fw-bold mb-3"><i class="fas fa-chalkboard-teacher text-info"></i> Faculty Management</h5>
            <!-- inline add teacher form -->
            <form method="POST" class="row g-3 mb-4 add-form">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="t_name" class="form-control" placeholder="Full Name" required>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="t_email" class="form-control" placeholder="Email Address" required>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" name="t_pass" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                        <input type="text" name="t_subject" class="form-control" placeholder="Subject/Course">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        <input type="number" name="t_salary" class="form-control" placeholder="Monthly Salary" step="0.01">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                    <button name="hire_teacher" class="btn btn-primary w-100" style="font-size: 0.95rem; padding: 0.6rem 1rem;">
                        <i class="fas fa-plus me-2"></i>Add Teacher
                    </button>
                </div>
            </form>
            <div class="d-flex justify-content-between mb-2 flex-wrap">
                <h6 class="mb-0">Existing Teachers</h6>
                <input type="text" id="teacherSearch" class="form-control form-control-sm w-25" placeholder="Search teachers...">
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Name / Subject</th>
                            <th>Online</th>
                            <th>Hours</th>
                            <th>Pending Pay</th>
                            <th>Actions<br><small>(edit/update)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Use the monitoring query (includes online/last_seen/hours/pending payment)
                        $teachers = $teacher_monitor;
                        while ($t = $teachers->fetch_assoc()):
                        ?>
                        <tr>
                            <td><span class="fw-bold"><?= htmlspecialchars($t['name']) ?></span><br><small class="text-muted"><?= htmlspecialchars($t['subject']) ?></small></td>
                            <td>
                                <?php if ($t['is_online'] == 1): ?>
                                    <span class="badge bg-success"><i class="fas fa-circle me-1" style="font-size:0.6rem;"></i>Online</span><br>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="fas fa-circle me-1" style="font-size:0.6rem;"></i>Offline</span><br>
                                <?php endif; ?>
                                <small class="text-muted">Last seen <?= $t['last_seen'] ? date('M d, h:i A', strtotime($t['last_seen'])) : 'N/A' ?></small>
                            </td>
                            <td><span class="timer-badge"><?= round($t['total_hours'], 1) ?>h</span></td>
                            <td><span class="text-danger">Rs. <?= number_format($t['pending_payment'], 2) ?></span></td>
                            <td>
                                <div class="btn-group teacher-actions">
                                    <!-- edit teacher record -->
                                    <button class="btn btn-sm btn-outline-primary edit-teacher-btn" 
                                        data-id="<?= $t['id'] ?>" 
                                        data-name="<?= htmlspecialchars($t['name']) ?>" 
                                        data-email="<?= htmlspecialchars($t['email'] ?? '') ?>" 
                                        data-subject="<?= htmlspecialchars($t['subject']) ?>" 
                                        data-salary="<?= $t['pending_payment'] + 0 ?>" 
                                        data-bs-toggle="modal" data-bs-target="#editTeacherModal" title="Edit teacher">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="setPayID(<?= $t['id'] ?>)" data-bs-toggle="modal" data-bs-target="#payModal" title="Record paid salary"><i class="fas fa-money-bill"></i></button>
                                    <button class="btn btn-sm btn-outline-info" onclick="setHoursID(<?= $t['id'] ?>)" data-bs-toggle="modal" data-bs-target="#hoursModal" title="Add online hours"><i class="fas fa-clock"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="setAdjustID(<?= $t['id'] ?>)" data-bs-toggle="modal" data-bs-target="#adjustModal" title="Adjust base salary"><i class="fas fa-hand-holding-dollar"></i></button>
                                    <a href="?toggle_status=<?= $t['id'] ?>" class="btn btn-sm <?= $t['is_online'] == 1 ? 'btn-outline-danger' : 'btn-outline-primary' ?>" title="<?= $t['is_online'] == 1 ? 'Mark Offline' : 'Mark Online' ?>">
                                        <i class="fas <?= $t['is_online'] == 1 ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                    </a>
                                    <a href="?delete_teacher=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete teacher?')" title="Remove teacher"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="hireModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header"><h5>Hire Faculty Member</h5></div>
                    <div class="modal-body">
                        <input type="text" name="t_name" class="form-control mb-2" placeholder="Full Name" required>
                        <input type="email" name="t_email" class="form-control mb-2" placeholder="Email (for login)" required>
                        <input type="password" name="t_pass" class="form-control mb-2" placeholder="Temp Password" required>
                        <input type="text" name="t_subject" class="form-control mb-2" placeholder="Assigned Subject">
                        <input type="number" name="t_salary" class="form-control mb-2" placeholder="Monthly Salary">
                    </div>
                    <div class="modal-footer"><button name="hire_teacher" class="btn btn-primary w-100">Register Teacher</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Pay Salary -->
    <div class="modal fade" id="payModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header"><h5>Pay Salary</h5></div>
                    <div class="modal-body">
                        <input type="hidden" name="t_id" id="pay_teacher_id">
                        <div class="mb-2"><label>Amount</label><input type="number" name="amount" class="form-control" required></div>
                    </div>
                    <div class="modal-footer"><button name="pay_salary" class="btn btn-success w-100">Submit</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Adjust Base Salary -->
    <div class="modal fade" id="adjustModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header"><h5>Adjust Base Salary</h5></div>
                    <div class="modal-body">
                        <input type="hidden" name="t_id" id="adjust_teacher_id">
                        <div class="mb-2"><label>Amount (use negative to reduce)</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                    </div>
                    <div class="modal-footer"><button name="adjust_salary" class="btn btn-secondary w-100">Apply</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add Online Hours -->
    <div class="modal fade" id="hoursModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header"><h5>Add Online Time</h5></div>
                    <div class="modal-body">
                        <input type="hidden" name="t_id" id="hours_teacher_id">
                        <div class="mb-2"><label>Minutes to add</label><input type="number" name="minutes" class="form-control" required></div>
                    </div>
                    <div class="modal-footer"><button name="add_hours" class="btn btn-info w-100">Add Hours</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Teacher -->
    <div class="modal fade" id="editTeacherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header"><h5>Edit Faculty Details</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <input type="hidden" name="t_id" id="edit_t_id">
                        <div class="row g-2">
                            <div class="col-12 col-md-6"><input type="text" name="t_name" id="edit_t_name" class="form-control" placeholder="Full Name" required></div>
                            <div class="col-12 col-md-6"><input type="email" name="t_email" id="edit_t_email" class="form-control" placeholder="Email" required></div>
                            <div class="col-12 col-md-6"><input type="password" name="t_pass" class="form-control" placeholder="New Password (leave blank to keep)"></div>
                            <div class="col-12 col-md-6"><input type="text" name="t_subject" id="edit_t_subject" class="form-control" placeholder="Assigned Subject"></div>
                            <div class="col-12 col-md-6"><input type="number" name="t_salary" id="edit_t_salary" class="form-control" placeholder="Monthly Salary"></div>
                            <div class="col-12 col-md-6"><input type="number" step="0.01" name="t_paid" id="edit_t_paid" class="form-control" placeholder="Paid Salary"></div>
                            <div class="col-12 col-md-6"><input type="number" name="t_online" id="edit_t_online" class="form-control" placeholder="Total Online Minutes"></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button name="update_teacher" class="btn btn-primary w-100">Update Teacher</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input type="text" name="fname" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">District</label>
                                <input type="text" name="district" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CNIC / B-Form</label>
                                <input type="text" name="nic" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alternate Mobile</label>
                                <input type="text" name="fmobile" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Degree</label>
                                <select name="last_degree" class="form-select" required>
                                    <option value="">Select Degree</option>
                                    <option>Matric</option>
                                    <option>Intermediate</option>
                                    <option>Bachelor</option>
                                    <option>Master</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Course</label>
                                <select name="course_fee_id" class="form-select" required>
                                    <option value="">Choose course</option>
                                    <?php foreach ($courseFees as $cf): ?>
                                        <option value="<?= $cf['id'] ?>"><?= htmlspecialchars($cf['course_name']) ?> (Rs. <?= number_format($cf['total_fee']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Assign Teacher</label>
                                <select name="assigned_teacher" class="form-select">
                                    <option value="0">No teacher assigned</option>
                                    <?php foreach ($teacherMap as $tid => $tname): ?>
                                        <option value="<?= $tid ?>"><?= htmlspecialchars($tname) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Create Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="pic" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="admin_add_student" class="btn btn-success">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5>Edit Student Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="edit_phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <input type="text" name="edit_course" id="edit_course" class="form-control">
                        </div>
                        <div class="row g-2">
                            <div class="col">
                                <label class="form-label">Total Fee</label>
                                <input type="number" step="0.01" name="edit_total" id="edit_total" class="form-control">
                            </div>
                            <div class="col">
                                <label class="form-label">Paid</label>
                                <input type="number" step="0.01" name="edit_paid" id="edit_paid" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update_student" class="btn btn-primary w-100">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div> <!-- end main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Real-time Search (students)
        document.getElementById('search').onkeyup = function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#studentTable tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
            });
        };

        // Real-time Search (teachers)
        document.getElementById('teacherSearch').onkeyup = function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#teacherSection tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
            });
        };

        // Edit Modal Data Filler (students)
        document.querySelectorAll('.edit-btn').forEach(b => {
            b.onclick = function() {
                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_phone').value = this.dataset.phone;
                document.getElementById('edit_course').value = this.dataset.course;
                document.getElementById('edit_total').value = this.dataset.total;
                document.getElementById('edit_paid').value = this.dataset.paid;
            };
        });

        // Edit Teacher Modal Filler
        document.querySelectorAll('.edit-teacher-btn').forEach(b => {
            b.onclick = function() {
                document.getElementById('edit_t_id').value = this.dataset.id;
                document.getElementById('edit_t_name').value = this.dataset.name;
                document.getElementById('edit_t_email').value = this.dataset.email;
                document.getElementById('edit_t_subject').value = this.dataset.subject;
                document.getElementById('edit_t_salary').value = this.dataset.salary;
            };
        });

        // Edit Course Fee Modal Filler
        document.querySelectorAll('.edit-course-fee').forEach(b => {
            b.onclick = function() {
                document.getElementById('editCourseFeeId').value = this.dataset.id;
                document.getElementById('editCourseName').value = this.dataset.name;
                document.getElementById('editCourseFee').value = this.dataset.fee;
            };
        });

        // set pay modal hidden field
        function setPayID(id) {
            document.getElementById('pay_teacher_id').value = id;
        }

        // set adjust modal hidden field
        function setAdjustID(id) {
            document.getElementById('adjust_teacher_id').value = id;
        }

        // Auto-refresh teacher monitor section
        let monitorRefreshing = false;
        function refreshTeacherMonitor() {
            if (monitorRefreshing) return;
            monitorRefreshing = true;

            const button = document.getElementById('monitorRefreshBtn');
            const spinner = button ? button.querySelector('.spinner-border') : null;
            const updatedLabel = document.getElementById('monitorLastUpdated');

            if (button) button.disabled = true;
            if (spinner) spinner.classList.remove('d-none');

            fetch('teacher_monitor.php')
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not OK');
                    return res.json();
                })
                .then(data => {
                    const tbody = document.getElementById('monitorBody');
                    const summary = document.getElementById('monitorSummary');
                    if (tbody && data.html) tbody.innerHTML = data.html;
                    if (summary && data.summary) summary.textContent = data.summary;
                    if (updatedLabel) updatedLabel.textContent = 'Last updated: ' + new Date().toLocaleTimeString();
                })
                .catch(() => {
                    const summary = document.getElementById('monitorSummary');
                    if (summary) summary.textContent = 'Failed to load monitor data.';
                    if (updatedLabel) updatedLabel.textContent = 'Last updated: failed to refresh';
                    console.warn('Unable to refresh teacher monitor');
                })
                .finally(() => {
                    if (button) button.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                    monitorRefreshing = false;
                });
        }

        // Initial load + periodic refresh (every 20 seconds)
        refreshTeacherMonitor();
        setInterval(refreshTeacherMonitor, 20000);

        // === Fee collection helpers ===
        const studentFeeData = <?= json_encode($studentFees) ?>;
        const courseFeesMap = <?= json_encode($courseFeesMap) ?>;

        const feeStudent = document.getElementById('feeStudent');
        const feeCourse = document.getElementById('feeCourse');
        const feeTotal = document.getElementById('feeTotal');
        const feePaid = document.getElementById('feePaid');
        const feeRemaining = document.getElementById('feeRemaining');
        const feeAmount = document.getElementById('feeAmount');

        function updateFeeFields() {
            const studentId = feeStudent?.value;
            const courseId = feeCourse?.value;
            const student = studentFeeData[studentId] || null;
            const course = courseFeesMap[courseId] || null;

            const total = course ? parseFloat(course.total_fee) : (student ? parseFloat(student.total_fee) : 0);
            const paid = student ? parseFloat(student.paid_fee) : 0;
            const amount = parseFloat(feeAmount?.value) || 0;

            const remaining = Math.max(0, total - (paid + amount));

            if (feeTotal) feeTotal.value = total ? 'Rs. ' + total.toFixed(2) : '';
            if (feePaid) feePaid.value = 'Rs. ' + paid.toFixed(2);
            if (feeRemaining) feeRemaining.value = 'Rs. ' + remaining.toFixed(2);
        }

        function syncCourseWithStudent() {
            const studentId = feeStudent?.value;
            const student = studentFeeData[studentId] || null;
            if (!student) return;

            if (student.course_fee_id && feeCourse) {
                feeCourse.value = student.course_fee_id;
            }
            updateFeeFields();
        }

        if (feeStudent) feeStudent.addEventListener('change', () => {
            syncCourseWithStudent();
        });
        if (feeCourse) feeCourse.addEventListener('change', updateFeeFields);
        if (feeAmount) feeAmount.addEventListener('input', updateFeeFields);

        // Initialize fee form values
        updateFeeFields();

        // Theme toggle (persisted)
        (function() {
            const toggleBtn = document.getElementById('theme-toggle');
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            function updateThemeIcon(theme) {
                if (!toggleBtn) return;
                toggleBtn.innerHTML = theme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
            }

            if (!toggleBtn) return;
            toggleBtn.addEventListener('click', () => {
                const current = html.getAttribute('data-theme');
                const next = current === 'light' ? 'dark' : 'light';
                html.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                updateThemeIcon(next);
            });
        })();
    </script>
</body>

</html>