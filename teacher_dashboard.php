<?php
// 1. Force error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

// Ensure the teacher table has a profile_pic column
$picCol = $conn->query("SHOW COLUMNS FROM teachers LIKE 'profile_pic'");
if ($picCol && $picCol->num_rows === 0) {
    $conn->query("ALTER TABLE teachers ADD COLUMN profile_pic VARCHAR(255) DEFAULT NULL");
}

// 2. Session Check
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_id = (int)$_SESSION['teacher_id'];

// 3. Handle Profile Update
$profile_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newName = mysqli_real_escape_string($conn, $_POST['name']);
    $newSubject = mysqli_real_escape_string($conn, $_POST['subject']);
    $updateFields = [];

    $updateFields[] = "name='$newName'";
    $updateFields[] = "subject='$newSubject'";

    if (!empty($_POST['password'])) {
        $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updateFields[] = "password='$hashed'";
    }

    if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_pic'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowed) && $file['size'] <= 2 * 1024 * 1024) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $dir = __DIR__ . '/uploads/teacher_photos';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filename = "teacher_{$teacher_id}_" . time() . "." . $ext;
            $dest = "$dir/$filename";
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $rel = "uploads/teacher_photos/$filename";
                $updateFields[] = "profile_pic='" . mysqli_real_escape_string($conn, $rel) . "'";
            }
        }
    }

    if (!empty($updateFields)) {
        $conn->query("UPDATE teachers SET " . implode(', ', $updateFields) . " WHERE id=$teacher_id");
        $_SESSION['teacher_name'] = $newName;
        $profile_msg = "Profile updated successfully!";
    }
}

// 4. Fetch Fresh Teacher Data
$teacher_res = $conn->query("SELECT * FROM teachers WHERE id = $teacher_id");
if (!$teacher_res || $teacher_res->num_rows == 0) {
    die("Error: Teacher record not found.");
}
$teacher = $teacher_res->fetch_assoc();

// 5. Fetch Assigned Students (Matches your Admin Assignment Logic)
$students = $conn->query("SELECT * FROM students WHERE assigned_teacher_id = $teacher_id ORDER BY name ASC");

// 6. Fetch Latest Notices
$notices = $conn->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6f42c1;
            --bg: #f8f9fa;
            --card: #ffffff;
            --text: #212529;
            --sidebar: #ffffff;
        }

        [data-theme="dark"] {
            --bg: #121212;
            --card: #1e1e1e;
            --text: #e0e0e0;
            --sidebar: #181818;
        }

        body {
            background: var(--bg);
            color: var(--text);
            transition: 0.3s;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: var(--sidebar);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        #sidebar.active {
            margin-left: -280px;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            transition: 0.3s;
            min-height: 100vh;
        }

        .main-content.active {
            margin-left: 0;
        }

        .nav-link {
            color: var(--text);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 0 10px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary);
            color: white !important;
        }

        .stat-card {
            background: var(--card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .notice-item {
            border-left: 4px solid var(--primary);
            background: var(--bg);
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        #sidebar .form-control-sm {
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: var(--text);
        }

        [data-theme="dark"] #sidebar .form-control-sm {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -280px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <nav id="sidebar">
        <div class="p-4 text-center">
            <h4 class="fw-bold text-primary">Teacher Hub</h4>
            <div class="mt-3">
                <?php $avatar = !empty($teacher['profile_pic']) ? $teacher['profile_pic'] : "https://ui-avatars.com/api/?name=" . urlencode($teacher['name']) . "&background=6f42c1&color=fff"; ?>
                <img src="<?= $avatar ?>" class="rounded-circle mb-2 border border-3 border-primary" style="height: 80px; width: 80px; object-fit: cover;">
                <h6><?= htmlspecialchars($teacher['name']) ?></h6>
                <span class="badge bg-success small">Active Session</span>
            </div>

            <div class="mt-4 text-start p-3 rounded shadow-sm" style="background: rgba(0,0,0,0.02);">
                <form method="POST" enctype="multipart/form-data">
                    <p class="fw-bold small mb-2"><i class="fas fa-user-edit me-1"></i> Quick Update</p>
                    <input name="name" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($teacher['name']) ?>" placeholder="Name" required>
                    <input name="subject" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($teacher['subject']) ?>" placeholder="Subject">
                    <input type="password" name="password" class="form-control form-control-sm mb-2" placeholder="New Password">
                    <label class="small text-muted">Profile Photo</label>
                    <input type="file" name="profile_pic" accept="image/*" class="form-control form-control-sm mb-3">
                    <button type="submit" name="update_profile" class="btn btn-sm btn-primary w-100 shadow-sm">Save Changes</button>
                </form>
                <?php if ($profile_msg): ?>
                    <div class="alert alert-success mt-2 py-1 px-2 small mb-0"><?= $profile_msg ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="nav flex-column mt-2">
            <a href="#" class="nav-link active"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="#studentSection" class="nav-link"><i class="fas fa-users me-2"></i> My Students</a>
            <hr class="mx-3">
            <button id="theme-toggle" class="btn btn-sm btn-outline-secondary mx-4 mb-2">Toggle Dark Mode</button>
            <a href="teacher_logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </nav>

    <div class="main-content" id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg mb-4 rounded shadow-sm px-3" style="background: var(--card);">
            <button type="button" id="sidebarCollapse" class="btn btn-primary btn-sm"><i class="fas fa-bars"></i></button>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 d-none d-md-inline text-muted small">Specialization: <strong><?= htmlspecialchars($teacher['subject']) ?></strong></span>
                <span id="session-timer" class="badge bg-dark p-2">00:00:00</span>
            </div>
        </nav>

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card border-bottom border-primary border-4">
                    <h6 class="text-muted small">ASSIGNED STUDENTS</h6>
                    <h3 class="fw-bold"><?= ($students) ? $students->num_rows : 0 ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card border-bottom border-success border-4">
                    <h6 class="text-muted small">TEACHING HOURS</h6>
                    <h3 class="fw-bold"><?= round($teacher['total_online_minutes'] / 60, 1) ?> <small class="h6">Hrs</small></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card border-bottom border-warning border-4">
                    <h6 class="text-muted small">REMAINING SALARY</h6>
                    <h3 class="text-success fw-bold">Rs. <?= number_format($teacher['salary'] - $teacher['paid_salary']) ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Student Table -->
            <div class="col-lg-8" id="studentSection">
                <div class="stat-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">My Student Roster</h5>
                        <span class="badge bg-primary rounded-pill"><?= ($students) ? $students->num_rows : 0 ?> Total</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Enrolled Course</th>
                                    <th>Contact</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($students && $students->num_rows > 0): ?>
                                    <?php while ($s = $students->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                                                        <?= substr($s['name'], 0, 1) ?>
                                                    </div>
                                                    <strong><?= htmlspecialchars($s['name']) ?></strong>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($s['enrolled_course']) ?></span></td>
                                            <td><?= htmlspecialchars($s['phone_number'] ?? 'N/A') ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary view-student"
                                                    data-name="<?= htmlspecialchars($s['name']) ?>"
                                                    data-course="<?= htmlspecialchars($s['enrolled_course']) ?>"
                                                    data-phone="<?= htmlspecialchars($s['phone_number']) ?>"
                                                    data-email="<?= htmlspecialchars($s['email'] ?? 'No Email Provided') ?>"
                                                    data-bs-toggle="modal" data-bs-target="#studentDetailModal">
                                                    <i class="fas fa-search-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="50" class="opacity-25 mb-2"><br>
                                            <span class="text-muted">No students assigned by Admin yet.</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notices Section -->
            <div class="col-lg-4" id="noticeSection">
                <div class="stat-card h-100">
                    <h5 class="fw-bold mb-3"><i class="fas fa-bullhorn text-warning me-2"></i>Campus Notices</h5>
                    <?php if ($notices && $notices->num_rows > 0): ?>
                        <?php while ($n = $notices->fetch_assoc()): ?>
                            <div class="notice-item shadow-sm">
                                <p class="mb-1 fw-semibold small"><?= htmlspecialchars($n['notice_text']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted" style="font-size: 0.7rem;"><i class="far fa-clock me-1"></i><?= date('M d, Y', strtotime($n['created_at'])) ?></small>
                                    <span class="badge bg-secondary" style="font-size: 0.6rem;">Update</span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="fas fa-check-double fa-2x mb-2"></i>
                            <p class="small">No new notices today.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <footer class="mt-5 rounded p-3 text-center border-top">
            <p class="mb-0 text-muted small">&copy; 2026 Inspire Tech Institute | Teacher Management System</p>
        </footer>
    </div>

    <!-- Modal: Student Details -->
    <div class="modal fade" id="studentDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-graduate me-2"></i>Student Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="display-4 text-primary"><i class="fas fa-id-card"></i></div>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <th class="border-0">Full Name:</th>
                            <td class="border-0" id="detailName"></td>
                        </tr>
                        <tr>
                            <th>Course:</th>
                            <td id="detailCourse"></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td id="detailPhone"></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td id="detailEmail"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close Profile</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarCollapse').onclick = function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        };

        // Dark Mode Logic
        document.getElementById('theme-toggle').onclick = () => {
            const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
        };

        // Modal Data Filler
        document.querySelectorAll('.view-student').forEach(button => {
            button.onclick = function() {
                document.getElementById('detailName').innerText = this.getAttribute('data-name');
                document.getElementById('detailCourse').innerText = this.getAttribute('data-course');
                document.getElementById('detailPhone').innerText = this.getAttribute('data-phone');
                document.getElementById('detailEmail').innerText = this.getAttribute('data-email');
            };
        });

        // Online Status Update
        function updateOnlineTime() {
            fetch('update_status.php').catch(err => console.log("Status Ping Failed"));
        }
        setInterval(updateOnlineTime, 60000); // Update every minute
        updateOnlineTime();

        // Session Timer
        let totalSec = 0;
        setInterval(() => {
            totalSec++;
            let h = Math.floor(totalSec / 3600);
            let m = Math.floor((totalSec % 3600) / 60);
            let s = totalSec % 60;
            document.getElementById('session-timer').innerText =
                `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }, 1000);
    </script>
</body>

</html>