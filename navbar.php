<?php
/**
 * Inspire Tech - Standard Navbar Component
 * This file handles dynamic active states and theme switching logic.
 */

// Get the current file name to highlight the active menu item
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="home_page.php">
            <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" 
                 width="45" height="45" 
                 class="rounded-circle me-2 border border-primary shadow-sm" 
                 alt="Inspire Tech Logo">
            <span class="brand-text">
                <span class="text-primary">Inspire</span>Tech
            </span>
        </a>

        <div class="d-flex order-lg-last ms-3 align-items-center">
            <button id="theme-toggle" class="btn theme-btn-circle me-3" title="Toggle Light/Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
            
            <a href="student-registration.php" class="btn btn-premium d-none d-md-block px-4">
                Enroll Now
            </a>

            <button class="navbar-toggler border-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fas fa-bars text-primary"></i></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'home_page.php') ? 'active' : ''; ?>" 
                       href="home_page.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'courses_1.php') ? 'active' : ''; ?>" 
                       href="courses_1.php">Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($current_page, 'contact') !== false) ? 'active' : ''; ?>" 
                       href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'student-portal.php' || $current_page == 'student_dashboard.php') ? 'active' : ''; ?>" 
                       href="student-portal.php">Portal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'verify.php') ? 'active' : ''; ?>" 
                       href="verify.php">Verify</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
/**
 * Theme Management Script
 * Persists user choice in localStorage and updates the <html> data-theme attribute.
 */
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle');
    const rootElement = document.documentElement;
    const icon = toggleBtn.querySelector('i');

    // 1. Check for saved theme or system preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // 2. Apply initial theme
    applyTheme(savedTheme);

    // 3. Toggle Click Event
    toggleBtn.addEventListener('click', () => {
        const currentTheme = rootElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        applyTheme(newTheme);
    });

    function applyTheme(theme) {
        rootElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Update Icon Class
        if (theme === 'dark') {
            icon.classList.replace('fa-moon', 'fa-sun');
        } else {
            icon.classList.replace('fa-sun', 'fa-moon');
        }
    }
});
</script>