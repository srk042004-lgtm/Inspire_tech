<?php
// 1. Force error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

// Ensure the teacher table has a pic column
$picCol = $conn->query("SHOW COLUMNS FROM teachers LIKE 'pic'");
if ($picCol && $picCol->num_rows === 0) {
    $conn->query("ALTER TABLE teachers ADD COLUMN pic VARCHAR(255) DEFAULT NULL");
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
    $errors = [];

    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $errors[] = "Passwords do not match.";
        } else {
            $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $updateFields[] = "password='" . mysqli_real_escape_string($conn, $hashed) . "'";
        }
    }

    if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_pic'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed)) {
            $errors[] = "Invalid image type. Only JPG, PNG, GIF allowed.";
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image too large. Max 2MB.";
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $dir = __DIR__ . '/uploads/teacher_photos';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filename = "teacher_{$teacher_id}_" . time() . "." . $ext;
            $dest = "$dir/$filename";
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $rel = "uploads/teacher_photos/$filename";
                $updateFields[] = "pic='" . mysqli_real_escape_string($conn, $rel) . "'";
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        $updateSql = "UPDATE teachers SET " . implode(', ', $updateFields) . " WHERE id = $teacher_id";
        if ($conn->query($updateSql)) {
            $profile_msg = "Profile updated successfully!";
            // Refresh teacher data
            $teacher = $conn->query("SELECT * FROM teachers WHERE id = $teacher_id")->fetch_assoc();
        } else {
            $profile_msg = "Update failed: " . $conn->error;
        }
    } else {
        $profile_msg = implode('<br>', $errors);
    }
}

// 4. Fetch Fresh Teacher Data
$teacher_res = $conn->query("SELECT * FROM teachers WHERE id = $teacher_id");
if (!$teacher_res || $teacher_res->num_rows == 0) {
    die("Error: Teacher record not found.");
}
$teacher = $teacher_res->fetch_assoc();

// Set session variables for navbar
$_SESSION['teacher_name'] = $teacher['name'];
$_SESSION['teacher_pic'] = $teacher['pic'];

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

<body class="teacher-dashboard-page">
    <?php include 'navbar_teacher.php'; ?>

    <div class="container-fluid" style="padding-top: 20px;">
            <div class="row mb-4 animate__animated animate__fadeInDown">
                <div class="col-md-8">
                    <h1 class="fw-bold">Welcome back, <span class="text-info"><?php echo htmlspecialchars($teacher['name']); ?></span>!</h1>
                    <p class="text-secondary">Teacher Dashboard - Inspire Tech Academy</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="badge bg-dark p-3 border border-secondary">
                        <i class="fas fa-calendar-alt text-info me-2"></i> <?php echo date('jS M Y'); ?>
                    </div>
                </div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-xl-4 animate__animated animate__fadeInLeft">
                    <div class="stat-card">
                        <div class="text-center">
                            <i class="fas fa-users fa-2x text-primary mb-3"></i>
                            <h3 class="fw-bold text-primary"><?php echo ($students) ? $students->num_rows : 0; ?></h3>
                            <small class="text-muted">ASSIGNED STUDENTS</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 animate__animated animate__fadeInUp">
                    <div class="stat-card">
                        <div class="text-center">
                            <i class="fas fa-clock fa-2x text-success mb-3"></i>
                            <h3 class="fw-bold text-success"><?php echo round($teacher['total_online_minutes'] / 60, 1); ?> <small>Hrs</small></h3>
                            <small class="text-muted">TEACHING HOURS</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 animate__animated animate__fadeInRight">
                    <div class="stat-card">
                        <div class="text-center">
                            <i class="fas fa-money-bill-wave fa-2x text-warning mb-3"></i>
                            <h3 class="fw-bold text-success">Rs. <?php echo number_format($teacher['salary'] - $teacher['paid_salary']); ?></h3>
                            <small class="text-muted">REMAINING SALARY</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8 animate__animated animate__fadeInLeft">
                    <div class="stat-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">My Student Roster</h5>
                            <span class="badge bg-primary rounded-pill"><?php echo ($students) ? $students->num_rows : 0; ?> Total</span>
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
                                        <?php $students->data_seek(0); // Reset pointer ?>
                                        <?php while ($s = $students->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                                                            <?php echo substr($s['name'], 0, 1); ?>
                                                        </div>
                                                        <strong><?php echo htmlspecialchars($s['name']); ?></strong>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($s['enrolled_course']); ?></span></td>
                                                <td><?php echo htmlspecialchars($s['mobile'] ?? 'N/A'); ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-primary view-student"
                                                        data-name="<?php echo htmlspecialchars($s['name']); ?>"
                                                        data-course="<?php echo htmlspecialchars($s['enrolled_course']); ?>"
                                                        data-phone="<?php echo htmlspecialchars($s['mobile'] ?? 'N/A'); ?>"
                                                        data-email="<?php echo htmlspecialchars($s['email'] ?? 'N/A'); ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#studentDetailModal">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">No students assigned yet.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 animate__animated animate__fadeInRight">
                    <!-- Profile Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>My Profile</h5>
                        </div>
                        <div class="card-body text-center">
                            <?php if ($profile_msg): ?>
                                <div class="alert alert-info small mb-3"><?php echo $profile_msg; ?></div>
                            <?php endif; ?>
                            <?php
                            $profilePic = !empty($teacher['pic']) && file_exists(__DIR__ . "/uploads/teacher_photos/{$teacher['pic']}") ? "uploads/teacher_photos/{$teacher['pic']}" : "uploads/default-avatar.png";
                            ?>
                            <img src="<?= $profilePic ?>" alt="Profile" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($teacher['name']) ?></h6>
                            <p class="text-muted small mb-3">Teacher ID: #<?= $teacher['id'] ?></p>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal">Update Profile</button>
                        </div>
                    </div>

                    <!-- Faculty Monitor Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Faculty Monitor</h5>
                            <button id="monitorRefreshBtn" class="btn btn-light btn-sm" onclick="refreshTeacherMonitor();">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="monitorSummary" class="mb-2 text-muted small">Loading status...</div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Teacher</th>
                                            <th>Status</th>
                                            <th>Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody id="monitorBody">
                                        <!-- AJAX loaded -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Campus Notices</h5>
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <?php if ($notices && $notices->num_rows > 0): ?>
                                <?php $notices->data_seek(0); // Reset pointer ?>
                                <?php while ($n = $notices->fetch_assoc()): ?>
                                    <div class="notice-item mb-3 p-3 bg-light rounded">
                                        <p class="mb-2 fw-semibold small"><?php echo htmlspecialchars($n['notice_text']); ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted" style="font-size: 0.7rem;"><i class="far fa-clock me-1"></i><?php echo date('M d, Y', strtotime($n['created_at'])); ?></small>
                                            <span class="badge bg-secondary" style="font-size: 0.6rem;">Update</span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-3 opacity-50">
                                    <i class="fas fa-check-double fa-2x mb-2"></i>
                                    <p class="small">No new notices today.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-5 rounded p-3 text-center border-top">
                <p class="mb-0 text-muted small">&copy; 2026 Inspire Tech Institute | Teacher Management System</p>
            </footer>
        </div>
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

    <!-- Modal: Profile Update -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Update Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3 text-center">
                            <img id="previewImg" src="<?= $profilePic ?>" alt="Profile Preview" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="pic" class="form-control" accept="image/*" onchange="previewImage(event)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modal Data Filler
        document.querySelectorAll('.view-student').forEach(button => {
            button.onclick = function() {
                document.getElementById('detailName').innerText = this.getAttribute('data-name');
                document.getElementById('detailCourse').innerText = this.getAttribute('data-course');
                document.getElementById('detailPhone').innerText = this.getAttribute('data-phone');
                document.getElementById('detailEmail').innerText = this.getAttribute('data-email');
            };
        });

        // Profile Image Preview
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Online Status Update (optional background heartbeat)
        function updateOnlineTime() {
            fetch('update_status.php').catch(() => {});
        }
        setInterval(updateOnlineTime, 60000); // Update every minute
        updateOnlineTime();

        // Teacher Monitor Refresh
        let monitorRefreshing = false;
        function refreshTeacherMonitor() {
            if (monitorRefreshing) return;
            monitorRefreshing = true;

            const button = document.getElementById('monitorRefreshBtn');
            const spinner = button ? button.querySelector('.spinner-border') : null;

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
                })
                .catch(() => {
                    const summary = document.getElementById('monitorSummary');
                    if (summary) summary.textContent = 'Failed to load monitor data.';
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
    </script>
</body>

</html>