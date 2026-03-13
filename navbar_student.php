<?php
// Student navbar include
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
$studentName = $_SESSION['student_name'] ?? 'Student';
$studentInitial = strtoupper(substr($studentName, 0, 1));
$studentAvatar = $_SESSION['student_pic'] ?? null;
?>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="student_dashboard.php">
            <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" 
                 width="45" height="45" 
                 class="rounded-circle me-2 border border-primary shadow-sm" 
                 alt="Inspire Tech Logo">
            <span class="brand-text">
                <span class="text-primary">Student</span> Portal
            </span>
        </a>

        <div class="d-flex order-lg-last ms-3 align-items-center">
            <button id="theme-toggle" class="btn theme-btn-circle me-3" title="Toggle Light/Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
            
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center" type="button" id="studentProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if ($studentAvatar): ?>
                        <img src="uploads/<?= htmlspecialchars($studentAvatar) ?>" class="rounded-circle me-2" style="width:32px; height:32px; object-fit:cover;" alt="Student">
                    <?php else: ?>
                        <span class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; font-weight:700;"><?= htmlspecialchars($studentInitial) ?></span>
                    <?php endif; ?>
                    <span class="d-none d-md-inline fw-bold"><?= htmlspecialchars($studentName) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="studentProfileDropdown">
                    <li><a class="dropdown-item" href="student_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="profile_details.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="student_certificate.php"><i class="fas fa-certificate me-2"></i>Certificates</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>

        <button class="navbar-toggler border-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStudent">
            <span class="navbar-toggler-icon"><i class="fas fa-bars text-primary"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarStudent">
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'student_dashboard.php') ? 'active' : ''; ?>" 
                       href="student_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'profile_details.php') ? 'active' : ''; ?>" 
                       href="profile_details.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'student_certificate.php') ? 'active' : ''; ?>" 
                       href="student_certificate.php">Certificates</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
// Theme Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const icon = themeToggle.querySelector('i');

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateIcon(savedTheme);

    themeToggle.addEventListener('click', function() {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcon(newTheme);
    });

    function updateIcon(theme) {
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
        } else {
            icon.className = 'fas fa-moon';
        }
    }
});
</script>
