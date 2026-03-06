<?php
session_start();
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
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = $conn->query("SELECT * FROM admins WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "<div class='alert alert-danger'>Email already exists!</div>";
        $showRegister = true;
    } else {
        // First user ever? Approve immediately (Status 1). Others? Stay pending (Status 0).
        $status = ($adminCount == 0) ? 1 : 0;

        $sql = "INSERT INTO admins (fullname, email, password, is_approved) VALUES ('$name', '$email', '$pass', $status)";

        if ($conn->query($sql)) {
            if ($status == 1) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $name;
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $message = "<div class='alert alert-success'>Request sent! Wait for Principal approval.</div>";
                $showRegister = false;
            }
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

/**
 * 4. LOGIN LOGIC
 */
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM admins WHERE email='$email'");
    if ($res->num_rows > 0) {
        $admin = $res->fetch_assoc();
        // Check password
        if (password_verify($pass, $admin['password'])) {
            // Check if Principal has approved this staff member
            if ($admin['is_approved'] == 1) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $admin['fullname'];
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
    <style>
        body {
            background: #020617;
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .auth-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .logo-img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #06b6d4;
            margin-bottom: 15px;
        }

        .btn-cyan {
            background: #06b6d4;
            color: #fff;
            border: none;
            font-weight: 600;
            transition: 0.3s;
            padding: 12px;
        }

        .btn-cyan:hover {
            background: #0891b2;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.2);
        }

        .form-control {
            background: #1e293b;
            border: 1px solid #334155;
            color: #fff;
            padding: 12px;
        }

        .form-control:focus {
            background: #1e293b;
            color: #fff;
            border-color: #06b6d4;
            box-shadow: none;
        }

        .toggle-link {
            color: #06b6d4;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
        }

        label {
            margin-bottom: 5px;
            font-weight: 500;
        }
    </style>
</head>

<body>

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