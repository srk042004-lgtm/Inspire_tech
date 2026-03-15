<?php
include 'secure_session.php';
include('db_connect.php');

/**
 * 1. SESSION CHECK
 * If already logged in, skip login page and go to dashboard.
 */
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit;
}

$message = "";
$showRegister = false;

/**
 * 2. VIEW LOGIC
 * Check if the user clicked "Register" or if NO admins exist at all.
 */
$adminCountResult = $conn->query("SELECT COUNT(*) as cnt FROM admins");
$adminCount = $adminCountResult->fetch_assoc()['cnt'];

if (isset($_GET['mode']) && $_GET['mode'] === 'register') {
    $showRegister = true;
}

// If no admins exist, force registration to create the first Principal account
if ($adminCount == 0) {
    $showRegister = true;
    $message = "<div class='alert alert-info'>No admin found. Please register the first Principal account.</div>";
}

/**
 * 3. REGISTRATION LOGIC
 */
if (isset($_POST['register'])) {
    $name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $message = "<div class='alert alert-danger'>Please fill all required fields.</div>";
        $showRegister = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Please provide a valid email address.</div>";
        $showRegister = true;
    } else {
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "<div class='alert alert-danger'>Email already exists!</div>";
            $showRegister = true;
            $stmt->close();
        } else {
            $stmt->close();

            $status = ($adminCount == 0) ? 1 : 0;
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO admins (fullname, email, password, is_approved) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('sssi', $name, $email, $hash, $status);

            if ($stmt->execute()) {
                if ($status == 1) {
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    $message = "<div class='alert alert-success'>Request sent! Wait for Principal approval.</div>";
                    $showRegister = false;
                }
            } else {
                $message = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
            }
            $stmt->close();
        }
    }
}

/**
 * 4. LOGIN LOGIC
 */
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $message = "<div class='alert alert-danger'>Please enter both email and password.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Please enter a valid email address.</div>";
    } else {
        $stmt = $conn->prepare("SELECT id, fullname, password, is_approved FROM admins WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($pass, $admin['password'])) {
                if ($admin['is_approved'] == 1) {
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_name'] = htmlspecialchars($admin['fullname'], ENT_QUOTES, 'UTF-8');
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    $message = "<div class='alert alert-warning'>Your account is pending Principal approval.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Incorrect password.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Admin email not found.</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Inspire Tech School of IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body class="admin-auth-page <?php echo (strpos($message, 'alert-danger') !== false) ? 'error-state' : ''; ?>">

    <?php include 'navbar_auth.php'; ?>

    <div class="auth-container">
        <div class="auth-card text-center animate__animated animate__zoomIn">
        <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" class="logo-img" alt="Logo">
        <h4 class="fw-bold mb-1">Inspire Tech</h4>
        <p class="text-secondary small mb-4">School of IT Admin Panel</p>

        <?php echo $message; ?>

        <form id="login-form" method="POST" style="display: <?php echo $showRegister ? 'none' : 'block'; ?>;">
            <div class="mb-3 text-start">
                <label class="small text-secondary">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@inspire.com" required>
            </div>
            <div class="mb-4 text-start">
                <label class="small text-secondary">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn btn-cyan w-100 mb-3">Login to Dashboard</button>
            <p class="small text-secondary">Access required? <span class="toggle-link" onclick="toggleAuth()">Register Here</span></p>
        </form>

        <form id="register-form" method="POST" style="display: <?php echo $showRegister ? 'block' : 'none'; ?>;">
            <div class="mb-3 text-start">
                <label class="small text-secondary">Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="e.g. Raheel Ahmad" required>
            </div>
            <div class="mb-3 text-start">
                <label class="small text-secondary">Work Email</label>
                <input type="email" name="email" class="form-control" placeholder="staff@inspire.com" required>
            </div>
            <div class="mb-4 text-start">
                <label class="small text-secondary">Choose Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="register" class="btn btn-cyan w-100 mb-3">Send Access Request</button>
            <p class="small text-secondary">Already authorized? <span class="toggle-link" onclick="toggleAuth()">Back to Login</span></p>
        </form>
    </div>
    </div>

    <script>
        function toggleAuth() {
            const login = document.getElementById('login-form');
            const register = document.getElementById('register-form');

            if (login.style.display === "none") {
                login.style.display = "block";
                register.style.display = "none";
            } else {
                login.style.display = "none";
                register.style.display = "block";
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