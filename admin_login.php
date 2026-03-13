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

<body class="admin-auth-page">

    <?php include 'navbar_auth.php'; ?>

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

</body>

</html>