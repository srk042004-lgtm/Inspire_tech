<?php
include 'db_connect.php';

// 1. FETCH DYNAMIC CONTENT
$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC LIMIT 1");
$positions = $conn->query("SELECT * FROM position_holders ORDER BY id DESC LIMIT 4");
$achievements = $conn->query("SELECT * FROM achievements ORDER BY id DESC LIMIT 6");

// 2. FETCH REAL COUNTS FROM DATABASE (Dynamic Stats)
$total_students = $conn->query("SELECT id FROM students")->num_rows;
// If you don't have a freelancers table yet, we can use a fixed number or total graduates
$total_freelancers = 450;
$total_courses = 12;
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="http://localhost/Inspire_tech/Ai-full-project/">
    <title>Inspire Tech | Premium IT Education</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
                <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" width="45" class="rounded-circle me-2 border border-primary">
                <span class="text-primary">Inspire</span>Tech
            </a>

            <div class="d-flex order-lg-last ms-3 align-items-center">
                <button id="theme-toggle" class="btn btn-sm btn-outline-primary rounded-circle me-3">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="https://wa.me/923462345453" class="btn btn-premium d-none d-md-block">Enroll Now</a>
            </div>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-primary"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="home_page.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="courses_1.php">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="home_page.php#history">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="student-portal.php">Portal</a></li>
                    <li class="nav-item"><a class="nav-link" href="verify.php">Verify</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section d-flex align-items-center text-center">
        <div class="container">
            <div class="reveal">
                <span class="badge rounded-pill bg-primary px-3 py-2 mb-3">NOWSHERA'S NO. 1 ACADEMY</span>
                <h1 class="display-2 fw-bold mb-4">Master the Digital Future</h1>
                <p class="lead mb-5 px-md-5">"Nurturing minds, fostering innovation, and building the digital leaders of tomorrow." Inspire Tech is your gateway to global success in AI and Design.</p>
                <a href="1_courses.html" class="btn btn-premium">Explore Courses</a>
            </div>
        </div>
    </section>

    <section id="positions" class="section-padding reveal">
        <div class="container">
            <h2 class="text-center fw-bold mb-5"><span class="text-primary">DIT</span> Position Holders</h2>
            <div class="row g-4 justify-content-center">
                <?php while ($pos = $positions->fetch_assoc()): ?>
                    <div class="col-6 col-md-3">
                        <div class="luxury-card text-center p-4 h-100">
                            <div class="badge bg-primary mb-3"><?= htmlspecialchars($pos['position_rank']) ?></div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($pos['student_name']) ?></h5>
                            <small class="text-muted">Batch: <?= htmlspecialchars($pos['batch_year']) ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="history" class="section-padding reveal">
        <div class="container">
            <div class="luxury-card">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h2 class="fw-bold text-primary mb-4">Our Institute History</h2>
                        <p class="fs-5">Founded with a vision to bridge the digital divide, Inspire Tech started as a mission to empower local youth with high-end tech skills. We have transformed from a basic computer center into a state-of-the-art Academy. Today, we stand as a beacon of hope for hundreds of students who have launched successful careers through our elite guidance.</p>
                    </div>
                    <div class="col-lg-5 text-center mt-4 mt-lg-0">
                        <div class="p-5 bg-light rounded-circle border border-primary d-inline-block">
                            <h2 class="fw-bold text-primary mb-0">12+</h2>
                            <small class="text-uppercase fw-bold">Years of Excellence</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="counters" class="section-padding bg-light reveal">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <span class="counter-number" data-target="<?= $total_students ?>">0</span>
                        <span class="counter-text">Total Students</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <span class="counter-number" data-target="<?= $total_freelancers ?>">0</span>
                        <span class="counter-text">Freelancers</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <span class="counter-number" data-target="<?= $total_courses ?>">0</span>
                        <span class="counter-text">Active Courses</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <span class="counter-number" data-target="100">0</span>
                        <span class="counter-text">Success Rate %</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="achievements" class="section-padding reveal bg-white">
        <div class="container">
            <h2 class="text-center fw-bold mb-2">Student <span class="text-primary">Success</span></h2>
            <p class="text-center text-muted mb-5">Real stories from our high-achieving techies</p>
            <div class="row g-4">
                <?php while ($ach = $achievements->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="luxury-card h-100">
                            <i class="fas fa-trophy text-warning mb-3 fs-3"></i>
                            <h5 class="fw-bold"><?= htmlspecialchars($ach['student_name']) ?></h5>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($ach['achievement_title']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="principal" class="section-padding bg-light reveal">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 text-center">
                    <img src="uploads/principal.jpg" class="img-fluid rounded-4 shadow-lg" alt="Raheel Ahmad" style="border-bottom: 10px solid var(--primary);">
                </div>
                <div class="col-lg-7">
                    <h6 class="text-primary fw-bold">THE VISIONARY BEHIND</h6>
                    <h2 class="display-5 fw-bold mb-4">Principal Raheel Ahmad</h2>
                    <div class="row g-4 mb-4">
                        <div class="col-6">
                            <div class="p-3 border-start border-primary border-4 bg-white shadow-sm">
                                <h4 class="fw-bold mb-0">12 Years</h4>
                                <small>Graphic Design Expert</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border-start border-info border-4 bg-white shadow-sm">
                                <h4 class="fw-bold mb-0">5 Years</h4>
                                <small>Full Stack Developer</small>
                            </div>
                        </div>
                    </div>
                    <p class="fst-italic fs-5">"My 17-year journey in tech has taught me that education is not about filling a bucket, but lighting a fire. At Inspire Tech, we light that fire for every student."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- contact section removed; use dedicated page -->
    <div class="text-center py-5">
        <a href="contact.php#contact" class="btn btn-premium">Contact Us</a>
    </div>

    <footer class="py-5 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-1">Have questions? <a href="contact.php#contact" class="text-info">Contact us</a></p>
            <p class="mb-0">© 2026 Inspire Tech Computer Academy. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Reveal on Scroll Logic
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    // Trigger counter animation if it's the counter section
                    if (reveals[i].id === "counters" && !reveals[i].classList.contains("active")) {
                        startCounters();
                    }
                    reveals[i].classList.add("active");
                }
            }
        }

        // Counter Animation Logic
        function startCounters() {
            const counters = document.querySelectorAll('.counter-number');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const increment = target / 100;
                let count = 0;

                const updateCount = () => {
                    if (count < target) {
                        count += increment;
                        counter.innerText = Math.ceil(count);
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = target + (target === 100 ? "%" : "+");
                    }
                };
                updateCount();
            });
        }

        window.addEventListener("scroll", reveal);
        reveal(); // Run once on load

        // Theme Switcher Logic
        const toggleBtn = document.getElementById('theme-toggle');
        const html = document.documentElement;
        toggleBtn.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            toggleBtn.innerHTML = next === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
        });
        function reveal() {
    var reveals = document.querySelectorAll(".reveal");
    for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 100; // Trigger slightly earlier for smoother flow

        if (elementTop < windowHeight - elementVisible) {
            // Check if it's the counter section
            if (reveals[i].id === "counters" && !reveals[i].classList.contains("active")) {
                startCounters();
            }
            reveals[i].classList.add("active");
        }
    }
}
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>