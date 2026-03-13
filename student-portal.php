<?php
include 'secure_session.php';

// if user already logged in send directly to dashboard
if (isset($_SESSION['student_id'])) {
  header('Location: student_dashboard.php');
  exit;
}

include 'db_connect.php';

// Handle Step navigation via GET
$step = isset($_GET['step']) ? $_GET['step'] : 'login';

// Generate simple captcha numbers for reset form
if (!isset($_SESSION['captcha_a']) || !isset($_SESSION['captcha_b'])) {
  $_SESSION['captcha_a'] = rand(1, 9);
  $_SESSION['captcha_b'] = rand(1, 9);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // --- STEP 1: VERIFY OLD CREDENTIALS ---
  if (isset($_POST['reset_submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $old = $_POST['old_password'] ?? '';
    $cap = (int)($_POST['captcha'] ?? 0);

    if ($cap !== ($_SESSION['captcha_a'] + $_SESSION['captcha_b'])) {
      $error = 'Captcha answer is incorrect.';
      $step = 'verify';
    } else {
      $stmt = $conn->prepare("SELECT password FROM students WHERE email = ? LIMIT 1");
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $res = $stmt->get_result();

      if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($old, $row['password'])) {
          $step = 'newpass';
          $_SESSION['reset_email'] = $email; // Carry email to final step
        } else {
          $error = 'Old password does not match our records.';
          $step = 'verify';
        }
      } else {
        $error = 'No account found with that email.';
        $step = 'verify';
      }
      $stmt->close();
    }
  }

  // --- STEP 2: UPDATE TO NEW PASSWORD ---
  elseif (isset($_POST['newpass_submit'])) {
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $email = $_SESSION['reset_email'] ?? '';

    if ($new !== $confirm) {
      $error = 'Passwords do not match.';
      $step = 'newpass';
    } elseif (empty($email)) {
      $error = 'Session expired. Please start again.';
      $step = 'login';
    } else {
      $hash = password_hash($new, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE students SET password = ? WHERE email = ?");
      $stmt->bind_param('ss', $hash, $email);
      $stmt->execute();
      $stmt->close();
      unset($_SESSION['reset_email']);
      $success = 'Password updated successfully! Please login.';
      $step = 'login';
    }
  }

  // --- STEP 3: STANDARD LOGIN ---
  else {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
      $error = 'Please enter a valid email address.';
    } else {
      $stmt = $conn->prepare("SELECT id, name, password, enrolled_course, picture FROM students WHERE email = ? LIMIT 1");
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
          session_regenerate_id(true);
          $_SESSION['student_id'] = $row['id'];
          $_SESSION['student_name'] = $row['name'];
          $_SESSION['enrolled_course'] = $row['enrolled_course'];
          $_SESSION['student_pic'] = $row['picture'] ?? '';
          header('Location: student_dashboard.php');
          exit;
        } else {
          $error = 'Incorrect password.';
        }
      } else {
        $error = 'No account found with that email.';
      }

      $stmt->close();
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Portal - Inspire Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body class="portal-page">

  <?php include 'navbar_auth.php'; ?>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="portal-card animate__animated animate__zoomIn">

          <!-- Alerts -->
          <?php if ($error): ?>
            <div class="alert alert-danger py-2 small"><i class="fas fa-times-circle me-2"></i><?= $error ?></div>
          <?php endif; ?>
          <?php if ($success): ?>
            <div class="alert alert-success py-2 small"><i class="fas fa-check-circle me-2"></i><?= $success ?></div>
          <?php endif; ?>

          <!-- LOGIN VIEW -->
          <?php if ($step === 'login'): ?>
            <div class="text-center mb-4">
              <i class="fas fa-user-graduate fa-3x text-info mb-3"></i>
              <h3 class="fw-bold">Student Login</h3>
            </div>
            <form method="POST">
              <div class="mb-3">
                <label class="small text-secondary">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required />
              </div>
              <div class="mb-4">
                <label class="small text-secondary">Password</label>
                <div class="input-group">
                  <input type="password" name="password" id="loginPwd" class="form-control" placeholder="••••••••" required />
                  <span class="input-group-text bg-dark border-secondary text-secondary" onclick="togglePass('loginPwd', this)">
                    <i class="fas fa-eye"></i>
                  </span>
                </div>
              </div>
              <button type="submit" class="btn btn-premium w-100 mb-3">Sign In <i class="fas fa-sign-in-alt ms-2"></i></button>
              <div class="text-center">
                <a href="?step=verify" class="small text-info text-decoration-none">Forgot Password?</a>
              </div>
              <div class="text-center mt-2">
                <span class="small text-secondary">New here? </span>
                <a href="student-registration.php" class="small text-info text-decoration-none fw-bold">Create an account</a>
              </div>
            </form>

            <!-- VERIFY OLD PASS VIEW -->
          <?php elseif ($step === 'verify'): ?>
            <div class="text-center mb-4">
              <h3 class="fw-bold">Verify Identity</h3>
              <p class="small text-secondary">Provide details to reset your password</p>
            </div>
            <form method="POST">
              <input type="hidden" name="reset_submit" value="1" />
              <div class="mb-3">
                <label class="small text-secondary">Your Email</label>
                <input type="email" name="email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="small text-secondary">Old Password</label>
                <input type="password" name="old_password" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="small text-secondary">Security Check: <?= $_SESSION['captcha_a'] ?> + <?= $_SESSION['captcha_b'] ?> = ?</label>
                <input type="number" name="captcha" class="form-control" placeholder="Answer" required />
              </div>
              <button type="submit" class="btn btn-warning w-100 fw-bold">Verify & Proceed</button>
              <div class="text-center mt-3"><a href="?step=login" class="small text-secondary">Back to Login</a></div>
            </form>

            <!-- NEW PASSWORD VIEW -->
          <?php elseif ($step === 'newpass'): ?>
            <div class="text-center mb-4">
              <h3 class="fw-bold">Set New Password</h3>
            </div>
            <form method="POST">
              <input type="hidden" name="newpass_submit" value="1" />
              <div class="mb-3">
                <label class="small text-secondary">New Password</label>
                <div class="input-group">
                  <input type="password" name="new_password" id="newPwd" class="form-control" required />
                  <span class="input-group-text bg-dark border-secondary text-secondary" onclick="togglePass('newPwd', this)">
                    <i class="fas fa-eye"></i>
                  </span>
                </div>
              </div>
              <div class="mb-3">
                <label class="small text-secondary">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required />
              </div>
              <button type="submit" class="btn btn-success w-100 fw-bold">Update Password</button>
            </form>
          <?php endif; ?>

         
        </div>
      </div>
    </div>
  </div>

  <script>
    // Function to toggle password visibility
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
  </script>
</body>

</html>