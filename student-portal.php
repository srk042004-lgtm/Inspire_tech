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

<body class="portal-page <?php echo $error ? 'error-state' : ''; ?>">

  <?php include 'navbar_auth.php'; ?>

  <div class="auth-container">
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

  <!-- Cartoon Chishiya Animation -->
  <div class="cartoon-chishiya">
    <div class="chishiya-body">
      <div class="chishiya-head">
        <div class="chishiya-hair"></div>
        <div class="chishiya-face">
          <div class="chishiya-eyes">
            <div class="chishiya-eye left"></div>
            <div class="chishiya-eye right"></div>
          </div>
          <div class="chishiya-mouth"></div>
        </div>
      </div>
      <div class="chishiya-hoodie"></div>
      <div class="chishiya-hand"></div>
      <div class="chishiya-blackboard"></div>
    </div>
  </div>

  <style>
    .cartoon-chishiya {
      position: fixed;
      bottom: 20px;
      left: 20px;
      width: 200px;
      height: 250px;
      z-index: 1000;
      pointer-events: none;
    }

    .chishiya-body {
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #ffffff, #f8f8f8);
      border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
      position: relative;
      border: 3px solid #333;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .chishiya-head {
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: 120px;
      height: 120px;
      background: #fdbcb4;
      border-radius: 50%;
      border: 3px solid #333;
    }

    .chishiya-hair {
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 140px;
      height: 45px;
      background: linear-gradient(45deg, #e8e8e8, #ffffff);
      border-radius: 50% 50% 25% 25%;
      z-index: -1;
      border: 2px solid #333;
    }

    .chishiya-hair::before {
      content: '';
      position: absolute;
      top: 5px;
      left: 20px;
      width: 20px;
      height: 25px;
      background: linear-gradient(45deg, #e8e8e8, #ffffff);
      border-radius: 50%;
      transform: rotate(-15deg);
    }

    .chishiya-hair::after {
      content: '';
      position: absolute;
      top: 5px;
      right: 20px;
      width: 20px;
      height: 25px;
      background: linear-gradient(45deg, #e8e8e8, #ffffff);
      border-radius: 50%;
      transform: rotate(15deg);
    }

    .chishiya-face {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .chishiya-eyes {
      position: absolute;
      top: 35px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 15px;
    }

    .chishiya-eye {
      width: 25px;
      height: 30px;
      background: white;
      border-radius: 50% 50% 45% 45%;
      border: 2px solid #333;
      position: relative;
      overflow: hidden;
    }

    .chishiya-eye::before {
      content: '';
      position: absolute;
      top: 8px;
      left: 8px;
      width: 12px;
      height: 12px;
      background: #333;
      border-radius: 50%;
    }

    .chishiya-mouth {
      position: absolute;
      bottom: 25px;
      left: 50%;
      transform: translateX(-50%);
      width: 20px;
      height: 10px;
      border: 2px solid #333;
      border-top: none;
      border-radius: 0 0 50% 50%;
      background: #ff6b9d;
    }

    .chishiya-hoodie {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 160px;
      height: 80px;
      background: linear-gradient(135deg, #ffffff, #f0f0f0);
      border-radius: 50% 50% 50% 50% / 30% 30% 70% 70%;
      border: 3px solid #333;
      z-index: -2;
    }

    .chishiya-hoodie::before {
      content: '';
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: 40px;
      height: 20px;
      background: #333;
      border-radius: 50% 50% 50% 50% / 100% 100% 0% 0%;
    }

    .chishiya-hand {
      position: absolute;
      bottom: 40px;
      right: 5px;
      width: 30px;
      height: 25px;
      background: #fdbcb4;
      border-radius: 50%;
      border: 2px solid #333;
      transform: rotate(15deg);
    }

    .chishiya-blackboard {
      position: absolute;
      bottom: 20px;
      right: 10px;
      width: 80px;
      height: 60px;
      background: #2c3e50;
      border: 3px solid #333;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .chishiya-blackboard::before {
      content: '';
      position: absolute;
      top: 10px;
      left: 10px;
      right: 10px;
      height: 2px;
      background: white;
      opacity: 0.8;
    }

    .chishiya-blackboard::after {
      content: '';
      position: absolute;
      top: 25px;
      left: 10px;
      right: 10px;
      height: 2px;
      background: white;
      opacity: 0.8;
    }

    /* Writing on blackboard animation */
    .writing .chishiya-hand {
      animation: write-blackboard 1.2s ease-in-out infinite alternate;
    }

    .writing .chishiya-blackboard::before {
      animation: draw-line 1.2s ease-in-out infinite alternate;
    }

    .writing .chishiya-blackboard::after {
      animation: draw-line 1.2s ease-in-out infinite alternate 0.3s;
    }

    /* Laughing animation */
    .laughing .chishiya-mouth {
      width: 25px;
      height: 15px;
      background: #ff4757;
      border-radius: 50% 50% 20% 20%;
      animation: laugh 0.3s ease-in-out infinite alternate;
    }

    .laughing .chishiya-eyes {
      animation: laugh-eyes 0.3s ease-in-out infinite alternate;
    }

    .blinking .chishiya-eye::before {
      animation: blink 0.1s ease-in-out;
    }

    .calculating .chishiya-mouth {
      width: 15px;
      height: 8px;
      background: #ff6b9d;
      border-radius: 50% 50% 30% 30%;
    }

    @keyframes write-blackboard {
      0% { transform: rotate(15deg) translateY(0) translateX(0); }
      50% { transform: rotate(20deg) translateY(-10px) translateX(-5px); }
      100% { transform: rotate(25deg) translateY(-5px) translateX(-10px); }
    }

    @keyframes draw-line {
      0% { width: 0; }
      100% { width: calc(100% - 20px); }
    }

    @keyframes laugh {
      0% { transform: translateX(-50%) scale(1) rotate(0deg); }
      100% { transform: translateX(-50%) scale(1.1) rotate(2deg); }
    }

    @keyframes laugh-eyes {
      0% { transform: translateX(-50%) scaleY(1); }
      100% { transform: translateX(-50%) scaleY(0.8); }
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0; }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const cartoon = document.querySelector('.cartoon-chishiya');
      const inputs = document.querySelectorAll('input');

      // Random blinking
      setInterval(() => {
        cartoon.classList.add('blinking');
        setTimeout(() => {
          cartoon.classList.remove('blinking');
        }, 100);
      }, Math.random() * 3000 + 2000); // Blink every 2-5 seconds

      // Typing animation - writing on blackboard
      inputs.forEach(input => {
        input.addEventListener('input', function() {
          if (this.value.length > 0) {
            cartoon.classList.add('writing');
            cartoon.classList.add('calculating');
          } else {
            cartoon.classList.remove('writing');
            cartoon.classList.remove('calculating');
          }
        });

        // Focus event
        input.addEventListener('focus', function() {
          cartoon.classList.add('calculating');
        });

        input.addEventListener('blur', function() {
          cartoon.classList.remove('calculating');
        });
      });

      // Laughing reaction when account not available
      if (document.body.classList.contains('error-state')) {
        cartoon.classList.add('laughing');
        setTimeout(() => {
          cartoon.classList.remove('laughing');
        }, 4000);
      }

      // Initial calculating state (Chishiya's analytical personality)
      setTimeout(() => {
        cartoon.classList.add('calculating');
      }, 1000);
    });
  </script>
</body>

</html>