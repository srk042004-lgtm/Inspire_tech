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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="teacher-dashboard">

    <?php include 'navbar_teacher.php'; ?>

    <div class="container mt-4">
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