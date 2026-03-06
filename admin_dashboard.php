<?php
session_start();
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
// ensure students can be linked to a teacher for counting purposes
// only add column if it doesn't already exist to avoid duplicate errors
$colCheck = $conn->query("SHOW COLUMNS FROM students LIKE 'assigned_teacher_id'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE students ADD COLUMN assigned_teacher_id INT");
}

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

// --- LOGIC: CONTACT INQUIRIES ---
if (isset($_GET['delete_msg'])) {
    $id = (int)$_GET['delete_msg'];
    $conn->query("DELETE FROM contact_inquiries WHERE id=$id");
    $msg = "Inquiry deleted successfully!";
}  // marking read not available for inquiries, they are always shown


// --- LOGIC: HIRE TEACHER ---
if(isset($_POST['hire_teacher'])) {
    $name = mysqli_real_escape_string($conn, $_POST['t_name']);
    $email = mysqli_real_escape_string($conn, $_POST['t_email']);
    $pass = password_hash($_POST['t_pass'], PASSWORD_DEFAULT);
    $sub = mysqli_real_escape_string($conn, $_POST['t_subject']);
    $sal = (float)$_POST['t_salary'];
    $conn->query("INSERT INTO teachers (name, email, password, subject, salary) VALUES ('$name', '$email', '$pass', '$sub', $sal)");
    $msg = "Teacher added!";
}

// --- LOGIC: FIRE/REHIRE TEACHER ---
if(isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $conn->query("UPDATE teachers SET status = IF(status='active', 'fired', 'active') WHERE id=$id");
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
    $conn->query("UPDATE teachers SET salary = salary + $amt WHERE id=$id");
    $msg = "Base salary adjusted!";
}

// --- LOGIC: DELETE TEACHER ---
if(isset($_GET['delete_teacher'])) {
    $id = (int)$_GET['delete_teacher'];
    // optionally cascade or keep related data
    $conn->query("DELETE FROM teachers WHERE id=$id");
    $msg = "Teacher record removed!";
}

// --- LOGIC: UPDATE TEACHER DETAILS ---
if(isset($_POST['update_teacher'])) {
    $id = (int)$_POST['t_id'];
    $name = mysqli_real_escape_string($conn, $_POST['t_name']);
    $email = mysqli_real_escape_string($conn, $_POST['t_email']);
    $sub = mysqli_real_escape_string($conn, $_POST['t_subject']);
    $sal = (float)$_POST['t_salary'];
    $paid = isset($_POST['t_paid']) ? (float)$_POST['t_paid'] : 0;
    $online = isset($_POST['t_online']) ? (int)$_POST['t_online'] : 0;
    $query = "UPDATE teachers SET name='$name', email='$email', subject='$sub', salary=$sal, paid_salary=$paid, total_online_minutes=$online";
    if(!empty($_POST['t_pass'])) {
        $pass = password_hash($_POST['t_pass'], PASSWORD_DEFAULT);
        $query .= ", password='$pass'";
    }
    $query .= " WHERE id=$id";
    $conn->query($query);
    $msg = "Teacher record updated!";
}

// --- LOGIC: REGISTER/UPDATE STUDENT ---
if (isset($_POST['register_student'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $total = (int)$_POST['total_fee'];
    $paid = (int)$_POST['paid_fee'];
    $conn->query("INSERT INTO students (name, phone_number, enrolled_course, total_fee, paid_fee) VALUES ('$name', '$phone', '$course', $total, $paid)");
    $msg = "Student Registered!";
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

// --- LOGIC: FEES & NOTICES ---
if (isset($_POST['collect_fee'])) {
    $s_id = (int)$_POST['student_id'];
    $amount = (int)$_POST['amount'];
    $month = mysqli_real_escape_string($conn, $_POST['month']);
    $conn->query("INSERT INTO fee_payments (student_id, amount_paid, payment_month) VALUES ($s_id, $amount, '$month')");
    $conn->query("UPDATE students SET paid_fee = paid_fee + $amount WHERE id = $s_id");
    $msg = "Fee Recorded!";
}

if (isset($_POST['add_notice'])) {
    $txt = mysqli_real_escape_string($conn, $_POST['notice_text']);
    $conn->query("INSERT INTO notices (notice_text) VALUES ('$txt')");
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

// --- ANALYTICS ---
$stats = $conn->query("SELECT COUNT(*) as total, SUM(paid_fee) as rev, SUM(total_fee - paid_fee) as dues FROM students")->fetch_assoc();
$pendingCerts = (int)$conn->query("SELECT COUNT(*) as cnt FROM certificate_requests WHERE status='pending'")->fetch_assoc()['cnt'];

// total inquiries count for nav badge
$msgCountRow = $conn->query("SELECT COUNT(*) as cnt FROM contact_inquiries")->fetch_assoc();
$msgCount = $msgCountRow ? (int)$msgCountRow['cnt'] : 0;

// Fetch contact inquiries
$messages = $conn->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspire Tech | Admin Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --bg: #f4f7f6;
            --card: #ffffff;
            --text: #212529;
        }

        [data-theme="dark"] {
            --bg: #121212;
            --card: #1e1e1e;
            --text: #e0e0e0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', sans-serif;
            transition: 0.3s;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--card);
            position: fixed;
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .stat-card {
            background: var(--card);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 4px solid var(--primary);
        }

        .action-panel {
            background: var(--card);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .nav-link {
            color: var(--text);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary);
            color: white !important;
        }

        .unread-row {
            background-color: rgba(13, 110, 253, 0.05) !important;
        }

        /* teacher status colours */
        .status-active { color: #198754; font-weight: bold; }
        .status-fired { color: #dc3545; font-weight: bold; }
        .timer-badge { background: #0d6efd; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem; }
        /* teacher action icons spacing */
        .teacher-actions .btn { margin-right: 4px; }
        /* ensure inline form inputs wrap and don't overflow */
        #teacherSection form input { min-width: 0; }
        /* search bar spacing when wrapped */
        #teacherSection .d-flex input { margin-top: 0.5rem; }
        /* beautify teacher form */
        #teacherSection .add-form { background: #f8f9fa; padding: 1rem; border-radius: .75rem; box-shadow: 0 2px 5px rgba(0,0,0,.05); }
        #teacherSection .add-form .form-control { border-radius: .5rem; }
        #teacherSection .add-form .input-group-text { background: white; border-right: none; }
        #teacherSection .add-form .btn-primary { font-weight: 600; }
        #teacherSection table tbody tr:hover { background: rgba(13,110,253,.075); }

        @media (max-width: 992px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">Inspire Tech</h4>
            <button id="theme-toggle" class="btn btn-sm btn-outline-secondary w-100 mt-2">Toggle Theme</button>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="#"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a class="nav-link" href="#messagesSection">
                <i class="fas fa-envelope me-2"></i> Messages
                <?php if ($msgCount > 0): ?><span class="badge bg-danger ms-1"><?= $msgCount ?></span><?php endif; ?>
            </a>
            <a class="nav-link" href="#feeSection"><i class="fas fa-money-check-alt me-2"></i> Fees</a>
            <a class="nav-link" href="#certSection">
                <i class="fas fa-certificate me-2"></i> Certificates
                <span id="cert-badge"><?php if ($pendingCerts > 0): ?><span class="badge bg-danger ms-1"><?= $pendingCerts ?></span><?php endif; ?></span>
            </a>
            <a class="nav-link" href="#studentSection"><i class="fas fa-users me-2"></i> Students</a>
            <a class="nav-link" href="#teacherSection"><i class="fas fa-chalkboard-teacher me-2"></i> Teachers</a>
            <hr>
            <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
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
            <form method="POST" class="row g-2">
                <div class="col-md-4">
                    <select name="student_id" class="form-select" required>
                        <option value="">Choose Student</option>
                        <?php $s_list = $conn->query("SELECT id, name FROM students");
                        while ($r = $s_list->fetch_assoc()) echo "<option value='" . $r['id'] . "'>" . $r['name'] . "</option>"; ?>
                    </select>
                </div>
                <div class="col-md-3"><input type="month" name="month" class="form-control" required></div>
                <div class="col-md-3"><input type="number" name="amount" class="form-control" placeholder="Amount" required></div>
                <div class="col-md-2"><button name="collect_fee" class="btn btn-success w-100">Save</button></div>
            </form>
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
                <input type="text" id="search" class="form-control w-25 form-control-sm" placeholder="Search students...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Fees Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        <?php $res = $conn->query("SELECT * FROM students ORDER BY id DESC");
                        while ($s = $res->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold"><?= $s['name'] ?></td>
                                <td><?= $s['enrolled_course'] ?></td>
                                <td><small>Paid: <?= $s['paid_fee'] ?> / Total: <?= $s['total_fee'] ?></small></td>
                                <td>
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
    </div>

        <!-- teacher management block -->
        <div id="teacherSection" class="action-panel border-start border-4 border-info">
            <h5 class="fw-bold mb-3"><i class="fas fa-chalkboard-teacher text-info"></i> Faculty Management</h5>
            <!-- inline add teacher form -->
            <form method="POST" class="row g-2 mb-3 add-form">
                <div class="col-12 col-md-4 col-lg-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="t_name" class="form-control" placeholder="Name" required>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="t_email" class="form-control" placeholder="Email" required>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" name="t_pass" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                        <input type="text" name="t_subject" class="form-control" placeholder="Subject">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        <input type="number" name="t_salary" class="form-control" placeholder="Salary">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <button name="hire_teacher" class="btn btn-sm btn-primary w-100">Add Teacher</button>
                </div>
            </form>
            <div class="d-flex justify-content-between mb-2 flex-wrap">
                <h6 class="mb-0">Existing Teachers</h6>
                <input type="text" id="teacherSearch" class="form-control form-control-sm w-25" placeholder="Search teachers...">
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Name / Subject</th>
                            <th>Salary</th>
                            <th>Hours / Students</th>
                            <th>Availability</th>
                            <th>Actions<br><small>(edit/update)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $teachers = $conn->query("SELECT t.*, (SELECT COUNT(*) FROM students WHERE assigned_teacher_id = t.id) as student_count FROM teachers t ORDER BY status ASC, name ASC");
                        while ($t = $teachers->fetch_assoc()):
                            $balance = $t['salary'] - $t['paid_salary'];
                        ?>
                        <tr>
                            <td><span class="fw-bold"><?= htmlspecialchars($t['name']) ?></span><br><small class="text-muted"><?= htmlspecialchars($t['subject']) ?></small></td>
                            <td><small>Total: <?= $t['salary'] ?></small><br><span class="text-danger small">Due: <?= $balance ?></span></td>
                            <td>
                                <span class="timer-badge"><?= round($t['total_online_minutes'] / 60, 1) ?> Hours</span><br>
                                <small class="text-primary"><?= $t['student_count'] ?? 0 ?> Students</small>
                            </td>
                            <td>
                                <?php if($t['status']=='fired'): ?>
                                    <span class="badge bg-danger">Fired</span>
                                <?php else: /* active teacher */ ?>
                                    <?php if($t['total_online_minutes'] > 0): ?>
                                        <span class="badge bg-success">Online</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Offline</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group teacher-actions">
                                    <!-- edit teacher record -->
                                    <button class="btn btn-sm btn-outline-primary edit-teacher-btn" 
                                        data-id="<?= $t['id'] ?>" 
                                        data-name="<?= htmlspecialchars($t['name']) ?>" 
                                        data-email="<?= htmlspecialchars($t['email']) ?>" 
                                        data-subject="<?= htmlspecialchars($t['subject']) ?>" 
                                        data-salary="<?= $t['salary'] ?>" 
                                        data-bs-toggle="modal" data-bs-target="#editTeacherModal" title="Edit teacher">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="setPayID(<?= $t['id'] ?>)" data-bs-toggle="modal" data-bs-target="#payModal" title="Record paid salary"><i class="fas fa-money-bill"></i></button>
                                    <button class="btn btn-sm btn-outline-info" onclick="setHoursID(<?= $t['id'] ?>)" data-bs-toggle="modal" data-bs-target="#hoursModal" title="Add online hours"><i class="fas fa-clock"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="setAdjustID(<?= $t['id'] ?>)" data-bs-toggle="modal" data-bs-target="#adjustModal" title="Adjust base salary"><i class="fas fa-hand-holding-dollar"></i></button>
                                    <a href="?toggle_status=<?= $t['id'] ?>" class="btn btn-sm <?= $t['status']=='active' ? 'btn-outline-danger' : 'btn-outline-primary' ?>" title="<?= $t['status']=='active' ? 'Fire' : 'Rehire' ?> teacher">
                                        <i class="fas <?= $t['status']=='active' ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
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

    <!-- Modal: Hire Teacher -->
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

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5>Edit Student Details</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body"><input type="hidden" name="student_id" id="edit_id">
                        <div class="mb-2"><label>Name</label><input type="text" name="name" id="edit_name" class="form-control"></div>
                        <div class="mb-2"><label>Phone</label><input type="text" name="phone" id="edit_phone" class="form-control"></div>
                        <div class="mb-2"><label>Course</label><input type="text" name="course" id="edit_course" class="form-control"></div>
                        <div class="row">
                            <div class="col-6"><label>Total Fee</label><input type="number" name="total_fee" id="edit_total" class="form-control"></div>
                            <div class="col-6"><label>Paid Fee</label><input type="number" name="paid_fee" id="edit_paid" class="form-control"></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" name="update_student" class="btn btn-success w-100">Update Record</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme Toggle
        document.getElementById('theme-toggle').onclick = () => {
            const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
        };

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

        // set pay modal hidden field
        function setPayID(id) {
            document.getElementById('pay_teacher_id').value = id;
        }

        // set adjust modal hidden field
        function setAdjustID(id) {
            document.getElementById('adjust_teacher_id').value = id;
        }
    </script>
</body>

</html>