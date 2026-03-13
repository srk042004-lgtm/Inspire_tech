<?php
// Admin navbar include
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminInitial = strtoupper(substr($adminName, 0, 1));
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="admin_dashboard.php">
            <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" width="40" class="rounded-circle me-2 border border-primary" alt="Inspire Tech Logo">
            <span class="text-info">Admin</span>Panel
        </a>

        <div class="d-flex order-lg-last align-items-center gap-2">
            <button id="theme-toggle" class="btn btn-outline-light btn-sm rounded-circle" title="Toggle Theme">
                <i class="fas fa-moon"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center" type="button" id="adminProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar bg-info text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; font-weight:700;"><?php echo htmlspecialchars($adminInitial); ?></span>
                    <span class="d-none d-md-inline"><?php echo htmlspecialchars($adminName); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminProfileDropdown">
                    <li><a class="dropdown-item" href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="admin_cron_manager.php"><i class="fas fa-robot me-2"></i>Automation</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'admin_cron_manager.php') ? 'active' : ''; ?>" href="admin_cron_manager.php">Automation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'home_page.php') ? 'active' : ''; ?>" href="home_page.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    toggleBtn.addEventListener('click', () => {
        const current = html.getAttribute('data-theme');
        const next = current === 'light' ? 'dark' : 'light';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateThemeIcon(next);
    });

    function updateThemeIcon(theme) {
        toggleBtn.innerHTML = theme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
    }
});
</script>
