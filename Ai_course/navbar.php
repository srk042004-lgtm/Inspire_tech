<?php
// Shared navbar for Ai_course pages
$currentPage = basename($_SERVER['PHP_SELF']);

function isActive($target) {
    global $currentPage;
    return $currentPage === $target ? 'active' : '';
}

?>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/Inspire_tech/Ai-full-project/home_page.php">Inspire Tech</a>
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?php echo isActive('home_page.php'); ?>" href="/Inspire_tech/Ai-full-project/home_page.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo isActive('courses_1.php'); ?>" href="/Inspire_tech/Ai-full-project/courses_1.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link <?php echo isActive('student-portal.php'); ?>" href="/Inspire_tech/Ai-full-project/student-portal.php">Student Portal</a></li>
                <li class="nav-item"><a class="nav-link <?php echo isActive('contact.php'); ?>" href="/Inspire_tech/Ai-full-project/contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
