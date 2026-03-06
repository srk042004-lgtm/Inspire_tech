<?php
// 1. Database connection
include 'db_connect.php';

// ensure students table has all fields we need for registration
$conn->query("ALTER TABLE students 
    ADD COLUMN IF NOT EXISTS fname VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS dob DATE DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS district VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS email VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS nic VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS qualification VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS last_degree VARCHAR(100) DEFAULT '',
    ADD COLUMN IF NOT EXISTS mobile VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS fmobile VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS password VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS picture VARCHAR(255) DEFAULT '',
    ADD COLUMN IF NOT EXISTS enrolled_course VARCHAR(255) DEFAULT ''");

$message = "";

// 2. PHP Logic to handle the form submission
if (isset($_POST['register'])) {
    // Collecting data from the form (use null coalescing to avoid undefined index warnings)
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $fname = mysqli_real_escape_string($conn, $_POST['fname'] ?? '');
    $dob = $_POST['dob'] ?? null;
    $district = mysqli_real_escape_string($conn, $_POST['district'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $nic = mysqli_real_escape_string($conn, $_POST['nic'] ?? '');
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification'] ?? '');
    $last_degree = $_POST['last_degree'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $fmobile = $_POST['fmobile'] ?? '';
    $enrolled_course = $_POST['enrolled_course'] ?? ''; // ensure course field supplied
    
    // Hash password for security
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle Image Upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $pic_name = time() . "_" . basename($_FILES["pic"]["name"]);
    $target_file = $target_dir . $pic_name;

    if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
        // Insert into Database
        $sql = "INSERT INTO students (name, fname, dob, district, email, nic, qualification, last_degree, mobile, fmobile, password, picture, enrolled_course) 
                VALUES ('$name', '$fname', '$dob', '$district', '$email', '$nic', '$qualification', '$last_degree', '$mobile', '$fmobile', '$password', '$pic_name', '$enrolled_course')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='student-portal.php';</script>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Failed to upload picture. Make sure 'uploads' folder exists.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="http://localhost/Inspire_tech/Ai-full-project/">
    <title>Student Registration - Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
      body {
        font-family: "Segoe UI", sans-serif;
        background: #0f172a;
        color: white;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      .navbar {
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #1e293b;
      }

      .card {
        background: #1e293b;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        border: 1px solid #334155;
        margin-top: 50px;
      }

      .btn-premium {
        background: linear-gradient(45deg, #00ffd5, #00a8ff);
        border: none;
        font-weight: bold;
        color: #0f172a;
        padding: 12px;
        transition: 0.3s;
      }

      .btn-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 255, 213, 0.3);
        color: #0f172a;
      }

      .form-control {
        background: #0f172a;
        border: 1px solid #334155;
        color: white;
        padding: 12px;
      }
      /* ensure placeholder text is visible */
      .form-control::placeholder {
        color: #a1a1aa;
      }

      .form-control:focus {
        background: #1e293b;
        color: white;
        border-color: #00ffd5;
        box-shadow: 0 0 0 0.25rem rgba(0, 255, 213, 0.1);
      }

      .footer {
        background: #000;
        padding: 30px;
        margin-top: auto;
        color: #64748b;
      }

      .academy-logo {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 1px;
        background: linear-gradient(45deg, #00ffd5, #00a8ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }

      .input-group-text {
        cursor: pointer;
      }

      /* light theme overrides */
      .light-theme body {
        background: #f8fafc;
        color: #0f172a;
      }
      .light-theme .navbar {
        background: rgba(255, 255, 255, 0.8);
        border-bottom: 1px solid #cbd5e1;
      }
      .light-theme .navbar .text-secondary {
        color: #0f172a !important;
      }
      .light-theme .card {
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
      }
      .light-theme .form-control {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #0f172a;
      }
      .light-theme .form-control:focus {
        background: #ffffff;
        color: #0f172a;
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.1);
      }
      .light-theme .btn-premium {
        color: #0f172a;
      }
      .light-theme .footer {
        background: #f1f5f9;
        color: #64748b;
      }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand academy-logo" href="home_page.php">INSPIRE TECH SCHOOL OF IT</a>
        <div class="ms-auto d-flex align-items-center gap-3">
          <button id="themeBtn" class="btn btn-sm btn-outline-light" title="Toggle light/dark">
            <i class="fas fa-sun"></i>
          </button>
          <a href="home_page.php" class="text-secondary text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Back to Home</a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card animate__animated animate__zoomIn">
                <h3 class="text-center mb-4 fw-bold text-primary">Student Registration Form</h3>
                
                <?php echo $message; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Student Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Father Name</label>
                            <input type="text" name="fname" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">District</label>
                            <input type="text" name="district" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">CNIC / B-Form</label>
                            <input type="text" name="nic" class="form-control" placeholder="XXXXX-XXXXXXX-X" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Last Completed Degree</label>
                            <select name="last_degree" class="form-control" required>
                                <option value="">Select Degree</option>
                                <option>Matric</option>
                                <option>Intermediate</option>
                                <option>Bachelor</option>
                                <option>Master</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-primary">Course to Enroll</label>
                            <select name="enrolled_course" class="form-select border-primary" required>
                                <option value="">Choose Course...</option>
                                <option value="ai-course">Artificial Intelligence (AI)</option>
                                <option value="web-dev">Web Development</option>
                                <option value="python">Python Programming</option>
                                <option value="DIT">DIT</option>
                                <option value="CIT">CIT</option>
                                <option value="MS Office">MS Office</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Create Password</label>
                            <div class="input-group">
                              <input type="password" name="password" id="regPwd" class="form-control" required>
                              <span class="input-group-text bg-dark border-secondary text-secondary" onclick="togglePass('regPwd', this)">
                                <i class="fas fa-eye"></i>
                              </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Upload Profile Picture</label>
                            <input type="file" name="pic" class="form-control" accept="image/*" required>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="register" class="btn btn-premium px-5 py-2">Register Student Account</button>
                        <br>
                        <a href="student-portal.php" class="d-block mt-3 text-decoration-none fw-bold">
                            Already Registered? Click here to Sign In
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    Inspire Tech Computer Academy<br>
    Nowshera Cantt | Owner: Raheel Ahmad | 03462345453
</div>
<footer style="text-align: center; padding: 20px;">
    <p>&copy; 2026 Inspire Tech Academy</p>
    <a href="admin_dashboard.php" style="color: transparent; text-decoration: none; font-size: 1px;">.</a>
</footer>

  <script>
    // server check
    if (window.location.protocol === 'file:') {
      alert('Please open this page via the web server.');
      window.location.href = 'student-registration.php';
    }

    function togglePass(id, el) {
      const input = document.getElementById(id);
      const icon = el.querySelector('i');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }

    // theme toggler
    const themeBtn = document.getElementById('themeBtn');
    function applyTheme(theme) {
      document.documentElement.classList.toggle('light-theme', theme === 'light');
      if (themeBtn) {
        themeBtn.innerHTML = theme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
      }
    }
    function toggleTheme() {
      const current = localStorage.getItem('theme') || 'dark';
      const next = current === 'dark' ? 'light' : 'dark';
      localStorage.setItem('theme', next);
      applyTheme(next);
    }
    if (themeBtn) {
      themeBtn.addEventListener('click', toggleTheme);
      const saved = localStorage.getItem('theme') || 'dark';
      applyTheme(saved);
    }
  </script>

  <script src="support-hub.js"></script>
</body>
</html>
