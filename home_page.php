<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

// 1. FETCH DYNAMIC CONTENT (Corrected Table Names)
$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC LIMIT 1");
$positions = $conn->query("SELECT * FROM dit_positions ORDER BY id DESC LIMIT 4");
$achievements = $conn->query("SELECT * FROM achievements ORDER BY id DESC LIMIT 6");

// 2. FETCH REAL COUNTS
$total_students = $conn->query("SELECT id FROM students")->num_rows;
$total_courses = $conn->query("SELECT id FROM course_fees")->num_rows;
$total_freelancers = 450; // Manual stat
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspire Tech | Premium IT Education</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> </head>
<body class="home-page">

    <?php include 'navbar.php'; ?>

    <section class="hero-section d-flex align-items-center text-center">
        <div class="container">
            <div class="hero-content">
                <span class="badge rounded-pill bg-primary px-3 py-2 mb-3 animate__animated animate__fadeInDown">NOWSHERA'S NO. 1 ACADEMY</span>
                <h1 class="display-2 fw-bold mb-4">Master the Digital Future</h1>
                <p class="lead mb-5 px-md-5">Nurturing minds, fostering innovation, and building the digital leaders of tomorrow. Inspire Tech is your gateway to global success.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="student-registration.php" class="btn btn-premium">Apply Now</a>
                    <a href="courses_1.php" class="btn btn-outline-light px-4">Our Courses</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Notices Section -->
    <section id="notices" class="section-padding reveal bg-warning text-dark">
        <div class="container">
            <h2 class="text-center fw-bold mb-5"><i class="fas fa-bullhorn text-primary"></i> Latest Notice</h2>
            <?php if($notices && $notices->num_rows > 0): ?>
                <?php $notice = $notices->fetch_assoc(); ?>
                <div class="luxury-card p-4 text-center">
                    <h3 class="fw-bold mb-3"><?= htmlspecialchars($notice['title']) ?></h3>
                    <p class="fs-5 mb-0"><?= nl2br(htmlspecialchars($notice['content'])) ?></p>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">No notices available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="positions" class="section-padding reveal">
        <div class="container">
            <h2 class="text-center fw-bold mb-5"><span class="text-primary">DIT</span> Position Holders</h2>
            <div class="row g-4 justify-content-center">
                <?php if($positions && $positions->num_rows > 0): ?>
                    <?php while ($pos = $positions->fetch_assoc()): ?>
                        <div class="col-6 col-md-3">
                            <div class="luxury-card text-center p-4">
                                <div class="badge bg-primary mb-3"><?= htmlspecialchars($pos['position']) ?></div>
                                <h5 class="fw-bold mb-1"><?= htmlspecialchars($pos['student_name']) ?></h5>
                                <small class="text-muted">Batch: <?= htmlspecialchars($pos['batch']) ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-muted">Awaiting upcoming results...</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="counters" class="section-padding bg-dark text-white reveal">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <h2 class="counter-number" data-target="<?= $total_students ?>">0</h2>
                        <p class="mb-0">Students Enrolled</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <h2 class="counter-number" data-target="<?= $total_freelancers ?>">0</h2>
                        <p class="mb-0">Successful Freelancers</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <h2 class="counter-number" data-target="<?= $total_courses ?>">0</h2>
                        <p class="mb-0">IT Courses</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="counter-box">
                        <h2 class="counter-number" data-target="100">0</h2>
                        <p class="mb-0">Job Placement %</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding reveal bg-light">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="image-stack">
                        <img src="uploads/principal.jpg" class="img-fluid rounded-4 shadow-lg main-img" alt="Principal">
                    </div>
                </div>
                <div class="col-lg-7">
                    <h6 class="text-primary fw-bold tracking-widest">MESSAGE FROM PRINCIPAL</h6>
                    <h2 class="display-5 fw-bold mb-4">Raheel Ahmad</h2>
                    <p class="fs-5 text-muted mb-4">"Education is the most powerful weapon which you can use to change the world. At Inspire Tech, we don't just teach tools; we craft careers."</p>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check-circle text-primary fs-4"></i>
                                <span>12+ Years Industry Experience</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check-circle text-primary fs-4"></i>
                                <span>Certified Google Developer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer bg-dark text-white py-5">
        <div class="container text-center">
            <h4 class="academy-logo mb-4">INSPIRE TECH</h4>
            <p class="mb-4">Nowshera Cantt | 0346 2345453</p>
            <div class="social-links mb-4">
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-whatsapp"></i></a>
            </div>
            <hr class="border-secondary">
            <p class="small text-muted mb-0">&copy; 2026 Inspire Tech Academy. Built with Passion.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reveal on Scroll Logic
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 100;

                if (elementTop < windowHeight - elementVisible) {
                    if (!reveals[i].classList.contains("active")) {
                        if (reveals[i].id === "counters") startCounters();
                        reveals[i].classList.add("active");
                    }
                }
            }
        }

        // Counter Logic
        function startCounters() {
            const counters = document.querySelectorAll('.counter-number');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const increment = target / 50;
                let count = 0;
                const update = () => {
                    if (count < target) {
                        count += increment;
                        counter.innerText = Math.ceil(count);
                        setTimeout(update, 30);
                    } else {
                        counter.innerText = target + (target == 100 ? "%" : "+");
                    }
                };
                update();
            });
        }

        window.addEventListener("scroll", reveal);
        window.onload = reveal;
    </script>
</body>
</html>