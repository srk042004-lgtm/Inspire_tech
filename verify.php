<?php
include 'db_connect.php';

$student = null;
$search_query = '';

if (isset($_GET['id'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['id']);
    // Search by Student ID or Phone Number
    $query = "SELECT * FROM students WHERE id = '$search_query' OR phone_number = '$search_query' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        $error = "No record found for this ID/Roll Number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="http://localhost/Inspire_tech/Ai-full-project/">
    <title>Verify Certificate | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body class="verify-page">
  <?php include 'navbar.php'; ?>

    <div class="page-header">
        <div class="container text-center">
            <h1 class="fw-bold"><i class="fas fa-user-check me-2"></i>Verify Student Record</h1>
            <p>Confirm the authenticity of Inspire Tech Certificates</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="GET" class="mb-5">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" name="id" class="form-control" placeholder="Enter Roll No or Phone..." value="<?= $search_query ?>" required>
                        <button class="btn btn-primary px-4" type="submit">Verify Now</button>
                    </div>
                </form>

                <?php if ($student): ?>
                    <div class="verify-card animate__animated animate__fadeInUp">
                        <div class="p-4 text-center border-bottom bg-light">
                            <span class="verified-badge"><i class="fas fa-check-circle me-1"></i> VERIFIED STUDENT</span>
                        </div>
                        <div class="p-4 d-flex align-items-center">
                            <img src="uploads/<?= $student['picture'] ? $student['picture'] : 'default.png' ?>" class="student-photo me-4" alt="Student">
                            <div>
                                <h4 class="mb-0 fw-bold"><?= strtoupper($student['name']) ?></h4>
                                <p class="text-primary mb-1 fw-bold"><?= $student['enrolled_course'] ?></p>
                                <p class="text-muted small mb-0">Roll No: IT-<?= $student['id'] ?></p>
                            </div>
                        </div>
                        <div class="p-4 bg-light">
                            <div class="row text-center small">
                                <div class="col-6 border-end">
                                    <p class="text-muted mb-0">Status</p>
                                    <p class="fw-bold text-success mb-0">Active / Completed</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Verified On</p>
                                    <p class="fw-bold mb-0"><?= date('d M, Y') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

  <footer class="main-footer text-center">
    <div class="container">
      <p class="mb-1 fw-bold">Inspire Tech Computer Academy</p>
      <p class="mb-2">Second Floor Khattak Building Nowshera Cantt</p>
      <div class="d-flex justify-content-center gap-3">
        <span class="small"><i class="fas fa-phone text-info me-1"></i> 03462345453</span>
        <span class="small"><i class="fas fa-user text-info me-1"></i> Raheel Ahmad</span>
      </div>
      <p class="mt-4 small">&copy; 2026 Inspire Tech Academy</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="support-hub.js"></script>
</body>
</html>

</html>