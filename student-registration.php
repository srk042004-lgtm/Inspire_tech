<?php
include 'secure_session.php';
include 'db_connect.php';

// 1. Ensure Table Structure is correct
$conn->query("ALTER TABLE students 
    ADD COLUMN IF NOT EXISTS fname VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS dob DATE DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS district VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS nic VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS qualification VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS last_degree VARCHAR(100) DEFAULT '',
    ADD COLUMN IF NOT EXISTS mobile VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS fmobile VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS password VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS picture VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS enrolled_course VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS enrollment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    ADD COLUMN IF NOT EXISTS enrollment_requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS total_fee DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS paid_fee DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS course_fee_id INT DEFAULT NULL");

// 2. Ensure Course Fees table exists and has data
$conn->query("CREATE TABLE IF NOT EXISTS course_fees (id INT AUTO_INCREMENT PRIMARY KEY, course_name VARCHAR(255) UNIQUE, total_fee DECIMAL(10,2) DEFAULT 0)");
$seedCheck = $conn->query("SELECT COUNT(*) as cnt FROM course_fees")->fetch_assoc();
if ($seedCheck['cnt'] == 0) {
  $conn->query("INSERT INTO course_fees (course_name, total_fee) VALUES ('DIT', 15000), ('CIT', 18000), ('Web-dev', 25000), ('AI', 28000)");
}

$courseFees = $conn->query("SELECT id, course_name, total_fee FROM course_fees ORDER BY course_name");
$message = "";

// 3. Form Submission Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $courseFeeId = isset($_POST['course_fee_id']) ? (int)$_POST['course_fee_id'] : 0;
  $password = $_POST['password'] ?? '';

  if (empty($name) || empty($email) || $courseFeeId <= 0 || empty($password)) {
    $message = "<div class='alert alert-danger'>Please fill in all required fields.</div>";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "<div class='alert alert-danger'>Please enter a valid email address.</div>";
  } else {
    // Prevent duplicate registrations
    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $message = "<div class='alert alert-danger'>Email already registered!</div>";
      $stmt->close();
    } else {
      $stmt->close();

      // Get course fee details
      $stmt = $conn->prepare("SELECT course_name, total_fee FROM course_fees WHERE id = ?");
      $stmt->bind_param('i', $courseFeeId);
      $stmt->execute();
      $result = $stmt->get_result();
      $courseRow = $result->fetch_assoc();
      $stmt->close();

      if (!$courseRow) {
        $message = "<div class='alert alert-danger'>Invalid course selection.</div>";
      } else {
        $courseName = $courseRow['course_name'];
        $total_fee = $courseRow['total_fee'];

        // Map human-friendly course names to internal course keys used in the dashboard
        $courseKeyMap = [
          'AI' => 'ai',
          'Web-dev' => 'web-dev',
          'DIT' => 'dit',
          'CIT' => 'cit',
          'MsOffice' => 'msoffice',
          'Python' => 'python',
          'Digital Marketing' => 'digital-marketing',
          'Typing' => 'typing',
        ];
        $courseKey = $courseKeyMap[$courseName] ?? strtolower(str_replace(' ', '-', $courseName));

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Handle file upload (image only, max 2MB)
        $uploadOk = true;
        $pic_name = '';
        if (!empty($_FILES['pic']['name'])) {
          $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
          $fileSize = $_FILES['pic']['size'];

          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $fileType = $finfo ? finfo_file($finfo, $_FILES['pic']['tmp_name']) : ($_FILES['pic']['type'] ?? '');
          if ($finfo) {
            finfo_close($finfo);
          }

          if (!in_array($fileType, $allowedTypes) || $fileSize > 2 * 1024 * 1024) {
            $uploadOk = false;
            $message = "<div class='alert alert-danger'>Invalid profile picture. Only JPG/PNG files under 2MB are allowed.</div>";
          } else {
            if (!is_dir('uploads')) {
              mkdir('uploads', 0755, true);
            }

            $extension = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
            $pic_name = hash('sha256', uniqid('', true)) . '.' . $extension;
            if (!move_uploaded_file($_FILES['pic']['tmp_name'], 'uploads/' . $pic_name)) {
              $uploadOk = false;
              $message = "<div class='alert alert-danger'>Unable to upload profile picture. Please try again.</div>";
            }
          }
        }

        if ($uploadOk) {
          $stmt = $conn->prepare("INSERT INTO students (name, fname, dob, district, email, nic, last_degree, mobile, fmobile, password, picture, enrolled_course, enrollment_status, enrollment_requested_at, course_fee_id, total_fee, paid_fee) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), ?, ?, 0)");
          $stmt->bind_param('ssssssssssssii', $name, $_POST['fname'], $_POST['dob'], $_POST['district'], $email, $_POST['nic'], $_POST['last_degree'], $_POST['mobile'], $_POST['fmobile'], $passwordHash, $pic_name, $courseKey, $courseFeeId, $total_fee);

          if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Registration successful! Your enrollment request has been sent to the admin. <a href='student-portal.php' class='fw-bold text-dark'>Login here</a></div>";
          } else {
            $message = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
          }
          $stmt->close();
        }
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Student Registration | Inspire Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body class="registration-page">

  <?php include 'navbar_auth.php'; ?>

  <div class="container mb-5">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card p-4 animate__animated animate__fadeInUp">
          <h3 class="text-center mb-4">Student Registration Form</h3>
          <?php echo $message; ?>

          <form method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Full Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Father Name</label>
                <input type="text" name="fname" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="small fw-bold">Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="small fw-bold">District</label>
                <input type="text" name="district" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="small fw-bold">CNIC / B-Form</label>
                <input type="text" name="nic" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Mobile Number</label>
                <input type="text" name="mobile" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Father's Mobile</label>
                <input type="text" name="fmobile" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Last Degree</label>
                <select name="last_degree" class="form-select" required>
                  <option value="Matric">Matric</option>
                  <option value="Intermediate">Intermediate</option>
                  <option value="Bachelor">Bachelor</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold text-info">Course to Enroll</label>
                <select name="course_fee_id" class="form-select border-info" required>
                  <option value="">Select Course...</option>
                  <?php while ($cf = $courseFees->fetch_assoc()): ?>
                    <option value="<?= $cf['id'] ?>"><?= $cf['course_name'] ?> (Rs. <?= number_format($cf['total_fee']) ?>)</option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="small fw-bold">Profile Picture</label>
                <input type="file" name="pic" class="form-control" accept="image/*" required>
              </div>
              <div class="col-md-12 mb-3">
                <label class="small fw-bold">Password</label>
                <div class="input-group">
                  <input type="password" name="password" id="regPass" class="form-control" required>
                  <span class="input-group-text bg-dark text-white border-secondary" onclick="togglePass()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                  </span>
                </div>
              </div>
            </div>

            <button type="submit" name="register" class="btn btn-premium w-100 mt-3">REGISTER ACCOUNT</button>
            <div class="text-center mt-3">
              <a href="student-portal.php" class="text-decoration-none">Already have an account? <strong>Sign In</strong></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePass() {
      const p = document.getElementById('regPass');
      const i = document.getElementById('eyeIcon');
      if (p.type === "password") {
        p.type = "text";
        i.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        p.type = "password";
        i.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }


  </script>

</body>

</html>