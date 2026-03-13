<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Courses | Inspire Tech School of IT</title>
    <base href="http://localhost/Inspire_tech/Ai-full-project/">

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="style.css">
  </head>
  <body class="courses-page">

    <?php include 'navbar.php'; ?>

    <header class="page-header">
      <div class="container">
        <h1 class="display-4 fw-bold">Our Computer Courses</h1>
        <p class="lead">Professional Diploma and Short Courses in Nowshera</p>
      </div>
    </header>

    <section class="container py-5">
      <div class="row g-4">
        <?php
        // reusable course data array
        $courses = [
            ['icon' => 'fa-file-word', 'title' => 'MS Office', 'desc' => 'Word, Excel, PowerPoint Complete Training'],
            ['icon' => 'fa-computer', 'title' => 'CIT', 'desc' => 'Certificate in Information Technology'],
            ['icon' => 'fa-brands fa-python', 'title' => 'Python', 'desc' => 'Modern Programming Language'],
            ['icon' => 'fa-laptop-code', 'title' => 'DIT', 'desc' => 'Diploma in Information Technology'],
            ['icon' => 'fa-keyboard', 'title' => 'Typing', 'desc' => 'English and Urdu Typing Course'],
            ['icon' => 'fa-bullhorn', 'title' => 'Digital Marketing', 'desc' => 'Facebook, YouTube, Freelancing'],
            ['icon' => 'fa-code', 'title' => 'Web Development', 'desc' => 'Create Professional Websites'],
            ['icon' => 'fa-robot', 'title' => 'Artificial Intelligence', 'desc' => 'Become an AI Engineer'],
        ];
        foreach ($courses as $course): ?>
        <div class="col-md-4">
          <div class="custom-card text-center">
            <i class="fa-solid <?= $course['icon'] ?> card-icon"></i>
            <h3 class="fw-bold"><?= $course['title'] ?></h3>
            <p><?= $course['desc'] ?></p>
            <a href="contact.php" class="btn-main">View Details</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <footer class="main-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-4">
            <h4>Location</h4>
            <p>Second Floor Khattak Building,<br />Nowshera Cantt, Pakistan</p>
          </div>
          <div class="col-md-4 mb-4">
            <h4>Quick Links</h4>
            <p>
              For inquiries
              <a href="contact.php" class="text-info">contact us</a>.
            </p>
            <p>
              Visit our
              <a href="student-portal.php" class="text-info">Student Portal</a>.
            </p>
          </div>
          <div class="col-md-4 mb-4 text-md-end">
            <h4>Follow Us</h4>
            <div class="social-icons">
              <i class="fab fa-facebook"></i>
              <i class="fab fa-youtube"></i>
              <i class="fab fa-whatsapp"></i>
              <i class="fab fa-instagram"></i>
            </div>
            <p class="mt-3">© Inspire Tech Computer Academy</p>
          </div>
        </div>
      </div>
    </footer>

    <a href="https://wa.me/923462345453" class="whatsapp-float">
      <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
