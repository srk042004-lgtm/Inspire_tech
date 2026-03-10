<?php
session_start();
include 'db_connect.php';

// 1. SECURITY CHECK
if (!isset($_SESSION['student_id'])) {
    header('Location: student-portal.php');
    exit();
}

// 2. DATA ASSIGNMENT
$studentId = $_SESSION['student_id'];
$name = htmlspecialchars($_SESSION['student_name']);
$myCourse = isset($_SESSION['enrolled_course']) ? $_SESSION['enrolled_course'] : 'none';

// Fetch assigned teacher (if available)
$assignedTeacherId = null;
$assignedTeacherName = null;
$teacherRes = $conn->query("SELECT assigned_teacher_id FROM students WHERE id = $studentId LIMIT 1");
if ($teacherRes && $row = $teacherRes->fetch_assoc()) {
    $assignedTeacherId = $row['assigned_teacher_id'];
    if ($assignedTeacherId) {
        $tRow = $conn->query("SELECT name FROM teachers WHERE id = $assignedTeacherId LIMIT 1")->fetch_assoc();
        $assignedTeacherName = $tRow ? $tRow['name'] : null;
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
    $existingReq = $conn->query("SELECT status FROM certificate_requests WHERE student_id=$studentId AND (status='pending' OR status='issued')");

    if ($existingReq->num_rows == 0) {
        $course = mysqli_real_escape_string($conn, $myCourse);
        $conn->query("INSERT INTO certificate_requests (student_id, course_name) VALUES ($studentId, '$course')");
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
    <style>
        :root {
            --primary-glow: #00ffd5;
            --secondary-glow: #00a8ff;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
        }

        body {
            background: var(--bg-dark);
            color: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            background: #000;
            padding: 30px 15px;
            position: fixed;
            width: 250px;
            border-right: 1px solid #334155;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            min-height: 100vh;
        }

        .sidebar-profile {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #334155;
        }

        .sidebar-img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            border: 2px solid var(--primary-glow);
            object-fit: cover;
            margin-bottom: 10px;
        }

        .nav-link-custom {
            color: #94a3b8;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            background: rgba(0, 255, 213, 0.1);
            color: var(--primary-glow);
        }

        .id-card-body {
            background: linear-gradient(135deg, #1e293b 0%, #000 100%);
            border: 2px solid var(--primary-glow);
            border-radius: 15px;
            display: flex;
            overflow: hidden;
        }

        .notice-board {
            background: #1e293b;
            border-radius: 20px;
            border-top: 4px solid var(--primary-glow);
            padding: 25px;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .notice-item {
            border-left: 3px solid var(--secondary-glow);
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #334155;
            text-align: center;
            transition: 0.3s;
            height: 100%;
        }

        .stat-card:hover {
            border-color: var(--secondary-glow);
            transform: translateY(-5px);
        }

        .course-access-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 20px;
            padding: 30px;
            transition: 0.4s;
        }

        .btn-access {
            background: linear-gradient(45deg, var(--primary-glow), var(--secondary-glow));
            color: #000;
            font-weight: bold;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn-glow {
            background: var(--primary-glow);
            color: #000;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
        }

        @media (max-width: 991px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
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
                            <div style="width: 160px; background: rgba(0,0,0,0.3); padding: 20px; text-align: center; border-right: 1px solid #334155;">
                                <img src="<?php echo $academyLogo; ?>" alt="Logo" style="width: 60px; height: 60px; margin-bottom: 15px; border-radius: 50%; object-fit: cover;">
                                <img src="<?php echo $profilePic; ?>" alt="Student" style="width: 110px; height: 110px; border-radius: 10px; border: 2px solid var(--primary-glow); object-fit: cover;">
                                <div class="mt-3" style="letter-spacing: 2px; font-size: 0.7rem; color: var(--primary-glow); font-weight: 900;">STUDENT</div>
                            </div>
                            <div style="padding: 25px; flex-grow: 1;">
                                <div class="mb-1 text-info small fw-bold">INSPIRE TECH ACADEMY</div>
                                <h4 class="fw-bold mb-0"><?php echo strtoupper($name); ?></h4>
                                <p class="small text-secondary mb-3">Reg ID: IT-<?php echo 1000 + $studentId; ?></p>
                                <div class="row mb-3">
                                    <div class="col-7"><small class="text-secondary d-block">COURSE</small><span class="fw-bold small"><?php echo strtoupper(str_replace('-', ' ', $myCourse)); ?></span></div>
                                    <div class="col-5"><small class="text-secondary d-block">SESSION</small><span class="fw-bold small"><?php echo date('Y'); ?></span></div>
                                </div>
                                <div class="border border-info text-center py-1 rounded small" style="color:var(--primary-glow); font-size: 10px;">AUTHORIZED DIGITAL CREDENTIAL</div>
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
                                    <small style="font-size: 0.7rem; color: #64748b;"><?= date('M d, Y', strtotime($n['created_at'])) ?></small>
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
                    'ai-course' => ['AI Specialist Masterclass', 'Master Python & Machine Learning', 'Ai_course/1st%20Ai%20intro%20and%20Space%20Search.html', 'border-info'],
                    'web-dev' => ['Full Stack Web Development', 'HTML, CSS, JS, PHP & MySQL', 'Web_development/Html%20and%20Css/Class%201%20html.html', 'border-success'],
                    'python' => ['Python for Everybody', 'From Basics to Advanced Automation', 'python/class1.html', 'border-primary'],
                    'msoffice' => ['MS Office Specialist', 'Word, Excel, PPT & Outlook', 'ms_office/word_intro.html', 'border-light'],
                    'dit' => ['DIT (Diploma in IT)', 'Professional IT Diploma Course', 'diploma/intro.html', 'border-warning'],
                    'cit' => ['CIT (Certificate in IT)', 'Foundations of Information Tech', 'diploma/intro.html', 'border-warning'],
                    'typing' => ['Professional Typing', 'Speed Building & Accuracy', 'typing/practice.html', 'border-secondary'],
                    'digital-marketing' => ['Digital Marketing', 'SEO, SMM & Business Growth', 'marketing/seo_basics.html', 'border-danger']
                ];

                if (array_key_exists($myCourse, $courses)):
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>