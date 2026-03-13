<?php
// Simple header for authentication pages (login/register)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="home_page.php">
            <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" width="40" class="rounded-circle me-2" alt="Inspire Tech">
            <span class="text-primary">Inspire</span>Tech
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAuth">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAuth">
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'home_page.php') ? 'active' : ''; ?>" href="home_page.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'student-portal.php') ? 'active' : ''; ?>" href="student-portal.php">Student Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'teacher_login.php') ? 'active' : ''; ?>" href="teacher_login.php">Teacher Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'admin_login.php') ? 'active' : ''; ?>" href="admin_login.php">Admin Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
