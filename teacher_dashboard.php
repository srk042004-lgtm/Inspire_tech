<?php
session_start();
include 'db_connect.php';

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch Teacher Data
$teacher = $conn->query("SELECT * FROM teachers WHERE id = $teacher_id")->fetch_assoc();

// Fetch Assigned Students
$students = $conn->query("SELECT * FROM students WHERE assigned_teacher_id = $teacher_id");

// Fetch Latest Notices from Admin
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
        :root { --primary: #6f42c1; --bg: #f8f9fa; --card: #ffffff; --text: #212529; --sidebar: #ffffff; }
        [data-theme="dark"] { --bg: #121212; --card: #1e1e1e; --text: #e0e0e0; --sidebar: #181818; }
        
        body { background: var(--bg); color: var(--text); transition: 0.3s; overflow-x: hidden; }
        
        /* Sidebar Logic */
        #sidebar { width: 250px; height: 100vh; position: fixed; background: var(--sidebar); border-right: 1px solid rgba(0,0,0,0.1); transition: 0.3s; z-index: 1000; }
        #sidebar.active { margin-left: -250px; }
        .main-content { margin-left: 250px; padding: 20px; transition: 0.3s; min-height: 100vh; }
        .main-content.active { margin-left: 0; }
        
        .nav-link { color: var(--text); padding: 12px 20px; border-radius: 0; }
        .nav-link:hover, .nav-link.active { background: var(--primary); color: white !important; }
        
        .stat-card { background: var(--card); border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .notice-item { border-left: 4px solid var(--primary); background: var(--bg); padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        
        footer { background: var(--card); padding: 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.1); }

        @media (max-width: 768px) {
            #sidebar { margin-left: -250px; }
            #sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
            .main-content.active { margin-left: 250px; }
        }
    </style>
</head>
<body>

    <nav id="sidebar">
        <div class="p-4 text-center">
            <h4 class="fw-bold text-primary">Teacher Hub</h4>
            <div class="mt-3">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($teacher['name']) ?>&background=6f42c1&color=fff" class="rounded-circle mb-2" width="70">
                <h6><?= $teacher['name'] ?></h6>
                <span class="badge bg-success">Online</span>
            </div>
        </div>
        <div class="nav flex-column mt-3">
            <a href="#" class="nav-link active"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="#studentSection" class="nav-link"><i class="fas fa-user-graduate me-2"></i> My Students</a>
            <a href="#noticeSection" class="nav-link"><i class="fas fa-bullhorn me-2"></i> Notices</a>
            <hr>
            <button id="theme-toggle" class="btn btn-sm btn-outline-secondary mx-4 mb-2">Change Theme</button>
            <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </nav>

    <div class="main-content" id="content">
        <nav class="navbar navbar-expand-lg mb-4 rounded shadow-sm" style="background: var(--card);">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fas fa-align-left"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 d-none d-md-inline">Welcome, <strong><?= $teacher['subject'] ?> Specialist</strong></span>
                    <span id="session-timer" class="badge bg-dark p-2">Session: 00:00</span>
                </div>
            </div>
        </nav>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted">Total Students</h6>
                    <h3><?= $students->num_rows ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted">Online Time (Total)</h6>
                    <h3><?= round($teacher['total_online_minutes'] / 60, 1) ?> Hours</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted">Wallet Balance</h6>
                    <h3 class="text-success">Rs. <?= number_format($teacher['salary'] - $teacher['paid_salary']) ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8" id="studentSection">
                <div class="stat-card">
                    <h5 class="fw-bold mb-3">Assigned Students</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Course</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($s = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $s['name'] ?></td>
                                    <td><?= $s['enrolled_course'] ?></td>
                                    <td><?= $s['phone_number'] ?></td>
                                    <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" id="noticeSection">
                <div class="stat-card">
                    <h5 class="fw-bold mb-3"><i class="fas fa-bell text-warning me-2"></i>Admin Notices</h5>
                    <div class="notice-board">
                        <?php if($notices->num_rows > 0): ?>
                            <?php while($n = $notices->fetch_assoc()): ?>
                                <div class="notice-item">
                                    <p class="mb-1 small"><?= $n['notice_text'] ?></p>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= date('d M, h:i A', strtotime($n['created_at'])) ?></small>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted">No notices today.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="mt-5 rounded shadow-sm">
            <p class="mb-0 text-muted">&copy; 2026 Inspire Tech Institute. All Rights Reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarCollapse').onclick = function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        };

        // Theme Toggle
        document.getElementById('theme-toggle').onclick = () => {
            const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
        };

        // --- REAL TIME ONLINE TRACKING ---
        // This function sends a "ping" to the server every 1 minute
        function updateOnlineTime() {
            fetch('update_status.php')
                .then(response => response.text())
                .then(data => console.log("Status Updated"));
        }
        
        setInterval(updateOnlineTime, 60000); // Every 60 seconds
        updateOnlineTime(); // Initial call

        // Simple Session Timer
        let sec = 0;
        setInterval(() => {
            sec++;
            let mins = Math.floor(sec / 60);
            let s = sec % 60;
            document.getElementById('session-timer').innerText = `Session: ${mins.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }, 1000);
    </script>
</body>
</html>