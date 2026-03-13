<?php
include 'secure_session.php';
include 'db_connect.php';

// 1. SECURITY CHECK
if (!isset($_SESSION['student_id'])) {
    header('Location: student-portal.php');
    exit();
}

// 2. DATA ASSIGNMENT (from DB)
$studentId = (int)$_SESSION['student_id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $studentId);
$stmt->execute();
$studentData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$studentData) {
    // If the student record disappears, force logout.
    session_destroy();
    header('Location: student-portal.php');
    exit();
}

$name = htmlspecialchars($studentData['name']);
$myCourse = !empty($studentData['enrolled_course']) ? $studentData['enrolled_course'] : 'none';
$enrollmentStatus = $studentData['enrollment_status'] ?? 'pending';

// Map human-friendly stored course names to internal course keys used for classroom links
$courseNameToKey = [
    'AI' => 'ai',
    'Web-dev' => 'web-dev',
    'DIT' => 'dit',
    'CIT' => 'cit',
    'MsOffice' => 'msoffice',
    'Python' => 'python',
    'Digital Marketing' => 'digital-marketing',
    'Typing' => 'typing',
];
if (isset($courseNameToKey[$myCourse])) {
    $myCourse = $courseNameToKey[$myCourse];
}

// Keep session in sync
$_SESSION['enrolled_course'] = $myCourse;
$_SESSION['student_name'] = $studentData['name'];
$_SESSION['enrollment_status'] = $enrollmentStatus;

// Fetch assigned teacher (if available)
$assignedTeacherId = $studentData['assigned_teacher_id'] ?? null;
$assignedTeacherName = null;
if ($assignedTeacherId) {
    $stmt = $conn->prepare("SELECT name FROM teachers WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $assignedTeacherId);
    $stmt->execute();
    $tRow = $stmt->get_result()->fetch_assoc();
    $assignedTeacherName = $tRow ? $tRow['name'] : null;
    $stmt->close();
}

// Handle student re-requesting enrollment approval
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['re_request_enrollment'])) {
    $conn->query("UPDATE students SET enrollment_status='pending', enrollment_requested_at=NOW() WHERE id=$studentId");
    header('Location: student_dashboard.php?re_request=1');
    exit;
}

// Optional: include per-course content if available
$courseContentDir = __DIR__ . '/course_content';
$courseIncludes = [
    'web-dev' => 'web_development.php',
    'ai-course' => 'artificial_intelligence.php',
    'ai' => 'artificial_intelligence.php',
];
if (isset($courseIncludes[$myCourse])) {
    $includePath = $courseContentDir . '/' . $courseIncludes[$myCourse];
    if (file_exists($includePath)) {
        include $includePath;
    }
}

$academyLogo = "uploads/340827876_5872631156182041_1179006399808807244_n.jpg";

// 3. FETCH DYNAMIC FEE DATA
$feeQuery = $conn->query("SELECT total_fee, paid_fee FROM students WHERE id=$studentId");
$feeData = $feeQuery->fetch_assoc();
$pendingFee = ($feeData) ? ($feeData['total_fee'] - $feeData['paid_fee']) : 0;

// ensure certificate_requests table exists
$conn->query("CREATE TABLE IF NOT EXISTS certificate_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_name VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// handle request submission
$certMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['req_cert'])) {
    $stmt = $conn->prepare("SELECT status FROM certificate_requests WHERE student_id = ? AND (status = 'pending' OR status = 'issued')");
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
    $existingReq = $stmt->get_result();
    $stmt->close();

    if ($existingReq->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO certificate_requests (student_id, course_name) VALUES (?, ?)");
        $stmt->bind_param('is', $studentId, $myCourse);
        $stmt->execute();
        $stmt->close();

        header('Location: student_dashboard.php?cert_submitted=1');
        exit;
    }
}

if (isset($_GET['cert_submitted'])) {
    $certMsg = 'Your certificate request has been sent. Please wait for admin approval.';
}

$certReq = $conn->query("SELECT * FROM certificate_requests WHERE student_id=$studentId ORDER BY id DESC LIMIT 1")->fetch_assoc();

// Profile Pic logic
$profilePic = (isset($_SESSION['student_pic']) && !empty($_SESSION['student_pic']))
    ? 'uploads/' . $_SESSION['student_pic']
    : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="style.css">

</head>

<body class="dashboard-page">

    <?php include 'navbar_student.php'; ?>

    <div class="sidebar">
        <?php
        // Fetch full student profile for display
        $studentProfile = $conn->query("SELECT email, mobile, district, nic, qualification, last_degree FROM students WHERE id=$studentId LIMIT 1")->fetch_assoc();
        ?>
        <div class="sidebar-profile">
            <img src="<?php echo $profilePic; ?>" class="sidebar-img" alt="Student">
            <h6 class="text-white mb-0"><?php echo $name; ?></h6>
            <small class="text-info"><?php echo ucfirst(str_replace('-', ' ', $myCourse)); ?></small>
            <?php if ($assignedTeacherName): ?>
                <div class="mt-2 small text-secondary">
                    <i class="fas fa-chalkboard-teacher me-1"></i>
                    Assigned Teacher: <strong><?php echo htmlspecialchars($assignedTeacherName); ?></strong>
                </div>
            <?php else: ?>
                <div class="mt-2 small text-secondary">
                    <i class="fas fa-user-clock me-1"></i>
                    <strong>No teacher assigned</strong>
                </div>
            <?php endif; ?>
            <?php if ($studentProfile): ?>
                <div class="mt-3 small text-secondary">
                    <div><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($studentProfile['email']); ?></div>
                    <div><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($studentProfile['mobile']); ?></div>
                    <?php if (!empty($studentProfile['district'])): ?>
                        <div><i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($studentProfile['district']); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <a href="#" class="nav-link-custom active"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="profile_details.php" class="nav-link-custom"><i class="fas fa-user-graduate me-2"></i> My Profile</a>
        <a href="student_certificate.php" class="nav-link-custom"><i class="fas fa-certificate me-2"></i> Certificates</a>
        <hr class="text-secondary">
        <a href="logout.php" class="nav-link-custom text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4 animate__animated animate__fadeInDown">
                <div class="col-md-8">
                    <h1 class="fw-bold">Welcome back, <span class="text-info"><?php echo $name; ?></span>!</h1>
                    <p class="text-secondary">#1 Tech Academy in Nowshera - Learning & Innovation.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="badge bg-dark p-3 border border-secondary">
                        <i class="fas fa-calendar-alt text-info me-2"></i> <?php echo date('jS M Y'); ?>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-xl-7 animate__animated animate__fadeInLeft">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">Digital Identity Card</h4>
                        <div>
                            <button onclick="printCard()" class="btn btn-sm btn-outline-info me-2"><i class="fas fa-print"></i></button>
                            <button id="downloadBtn" class="btn btn-sm btn-glow"><i class="fas fa-save me-1"></i> Download</button>
                        </div>
                    </div>
                    <div id="studentIDCard">
                        <div class="id-card-body">
                            <div class="id-card-left">
                                <img src="<?php echo $academyLogo; ?>" alt="Logo" class="id-card-logo">
                                <img src="<?php echo $profilePic; ?>" alt="Student" class="id-card-photo">
                                <div class="id-card-student-label">STUDENT</div>
                            </div>
                            <div class="id-card-right">
                                <div class="mb-1 text-info small fw-bold">INSPIRE TECH ACADEMY</div>
                                <h4 class="fw-bold mb-0"><?php echo strtoupper($name); ?></h4>
                                <p class="small text-secondary mb-3">Reg ID: IT-<?php echo 1000 + $studentId; ?></p>
                                <div class="row mb-3">
                                    <div class="col-7"><small class="text-secondary d-block">COURSE</small><span class="fw-bold small"><?php echo strtoupper(str_replace('-', ' ', $myCourse)); ?></span></div>
                                    <div class="col-5"><small class="text-secondary d-block">SESSION</small><span class="fw-bold small"><?php echo date('Y'); ?></span></div>
                                </div>
                                <div class="border border-info text-center py-1 rounded small authorized-badge">AUTHORIZED DIGITAL CREDENTIAL</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 animate__animated animate__fadeInRight">
                    <div class="notice-board">
                        <h4 class="fw-bold mb-4"><i class="fas fa-bullhorn text-warning me-2"></i> Notice Board</h4>
                        <?php
                        $notices = $conn->query("SELECT * FROM notices ORDER BY id DESC LIMIT 3");
                        if ($notices->num_rows > 0):
                            while ($n = $notices->fetch_assoc()): ?>
                                <div class="notice-item">
                                    <h6 class="mb-1 text-info">Update from Admin</h6>
                                    <p class="small text-secondary mb-0"><?= $n['notice_text'] ?></p>
                                    <small class="notice-date"><?= date('M d, Y', strtotime($n['created_at'])) ?></small>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <p class="text-muted small">No new notices at the moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-5">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3 class="<?= ($pendingFee > 0) ? 'text-danger' : 'text-success' ?>">Rs. <?= number_format($pendingFee) ?></h3>
                        <small class="text-secondary">Pending Fee</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3 class="text-warning">Active</h3><small class="text-secondary">Portal Status</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3 class="text-primary">12</h3><small class="text-secondary">Lessons</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <h3 class="text-success">A+</h3><small class="text-secondary">Rank</small>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <h4 class="fw-bold mb-3">Academic Recognition</h4>
                <?php if ($certMsg): ?>
                    <div class="alert alert-info animate__animated animate__pulse"><?= $certMsg ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['re_request'])): ?>
                    <div class="alert alert-success animate__animated animate__pulse">Your request has been resubmitted. Please wait for admin approval.</div>
                <?php endif; ?>

                <?php if ($certReq && $certReq['status'] === 'pending'): ?>
                    <div class="alert alert-warning border-0 bg-dark text-warning">
                        <i class="fas fa-clock me-2"></i> Your certificate request is <strong>Pending</strong>. Admin will review your fee records and progress soon.
                    </div>
                <?php elseif ($certReq && $certReq['status'] === 'issued'): ?>
                    <div class="alert alert-success border-0 bg-dark text-success">
                        <i class="fas fa-check-circle me-2"></i> Congratulations! Your certificate is issued.
                        <a href="generate_cert.php?id=<?= $studentId ?>" target="_blank" class="btn btn-sm btn-success ms-3">Print Certificate</a>
                    </div>
                <?php else: ?>
                    <div class="course-access-card border-warning">
                        <h5>Request Official Certificate</h5>
                        <p class="small text-secondary">Only request once you have completed your course and cleared all dues.</p>
                        <form method="POST">
                            <button name="req_cert" class="btn btn-glow">Submit Request</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <h4 class="fw-bold mb-4">My Enrolled Classroom</h4>
            <div class="row">
                <?php
                $courses = [
                    // Course keys from registration (normalized)
                    'ai-course' => ['AI Specialist Masterclass', 'Master Python & Machine Learning', 'Ai_course/1st%20Ai%20intro%20and%20Space%20Search.html', 'border-info'],
                    'ai' => ['AI Specialist Masterclass', 'Master Python & Machine Learning', 'Ai_course/1st%20Ai%20intro%20and%20Space%20Search.html', 'border-info'],
                    'web-dev' => ['Full Stack Web Development', 'HTML, CSS, JS, PHP & MySQL', 'Web_development/Html%20and%20Css/Class%201%20html.html', 'border-success'],
                    'python' => ['Python for Everybody', 'From Basics to Advanced Automation', 'python/class1.html', 'border-primary'],
                    'msoffice' => ['MS Office Specialist', 'Word, Excel, PPT & Outlook', 'ms_office/word_intro.html', 'border-light'],
                    'ms-office' => ['MS Office Specialist', 'Word, Excel, PPT & Outlook', 'ms_office/word_intro.html', 'border-light'],
                    'dit' => ['DIT (Diploma in IT)', 'Professional IT Diploma Course', 'diploma/intro.html', 'border-warning'],
                    'cit' => ['CIT (Certificate in IT)', 'Foundations of Information Tech', 'diploma/intro.html', 'border-warning'],
                    'typing' => ['Professional Typing', 'Speed Building & Accuracy', 'typing/practice.html', 'border-secondary'],
                    'digital-marketing' => ['Digital Marketing', 'SEO, SMM & Business Growth', 'marketing/seo_basics.html', 'border-danger']
                ];

                if ($enrollmentStatus === 'pending'): ?>
                    <div class="col-md-8">
                        <div class="course-access-card text-center">
                            <i class="fas fa-clock fa-3x mb-3 text-secondary"></i>
                            <h5>Enrollment Pending Approval</h5>
                            <p class="text-secondary">Your course request has been sent to the admin. Once approved, your classroom link will appear here.</p>
                        </div>
                    </div>
                <?php elseif ($enrollmentStatus === 'rejected'): ?>
                    <div class="col-md-8">
                        <div class="course-access-card text-center">
                            <i class="fas fa-times-circle fa-3x mb-3 text-danger"></i>
                            <h5>Enrollment Request Declined</h5>
                            <p class="text-secondary">Your request was declined. Please contact admin for more details.</p>
                            <form method="POST" class="mt-3">
                                <button name="re_request_enrollment" class="btn btn-glow">Re-request Approval</button>
                            </form>
                        </div>
                    </div>
                <?php elseif ($enrollmentStatus === 'approved' && array_key_exists($myCourse, $courses)):
                    $c = $courses[$myCourse];
                ?>
                    <div class="col-md-8 animate__animated animate__zoomIn">
                        <div class="course-access-card <?php echo $c[3]; ?>">
                            <span class="badge bg-dark border border-secondary mb-3">ACTIVE COURSE</span>
                            <h3 class="fw-bold"><?php echo $c[0]; ?></h3>
                            <p class="text-secondary"><?php echo $c[1]; ?></p>
                            <a href="<?php echo $c[2]; ?>" class="btn btn-access">ENTER CLASSROOM</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-8">
                        <div class="course-access-card text-center"><i class="fas fa-lock fa-3x mb-3 text-secondary"></i>
                            <h5>No Enrollment Active</h5><a href="https://wa.me/923462345453" class="btn btn-outline-info mt-2">Contact Admin</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function printCard() {
            var printContents = document.getElementById('studentIDCard').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }

        document.getElementById('downloadBtn').addEventListener('click', function() {
            html2canvas(document.getElementById('studentIDCard'), {
                scale: 3,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'InspireTech-ID-<?php echo $name; ?>.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        });

        // Sidebar toggle for mobile
        const navbarToggler = document.querySelector('.navbar-toggler');
        const sidebar = document.querySelector('.sidebar');
        navbarToggler.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>