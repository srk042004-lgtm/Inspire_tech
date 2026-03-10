<?php
session_start();
include 'db_connect.php';

// Suppress errors to ensure the header redirect isn't blocked by a warning
error_reporting(0);

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['pass'];
    
    // 1. First check if teacher exists at all in database
    $check_query = "SELECT * FROM teachers WHERE email='$email' LIMIT 1";
    $check_res = $conn->query($check_query);
    
    if ($check_res && $check_res->num_rows == 1) {
        $t = $check_res->fetch_assoc();
        
        // 2. Check if teacher is active
        if ($t['status'] === 'fired') {
            $error = "Your account has been deactivated. Please contact the admin or principal.";
        }
        // 3. Verify Password
        else if (password_verify($pass, $t['password'])) {
            
            // 4. Password correct - Set Session variables
            $_SESSION['teacher_id'] = $t['id'];
            $_SESSION['teacher_name'] = $t['name'];
            $_SESSION['teacher_email'] = $t['email'];
            
            // 5. Mark teacher as ONLINE in database
            $conn->query("UPDATE teachers SET is_online = 1, last_login = NOW() WHERE id = " . $t['id']);
            
            // 6. REDIRECT TO TEACHER DASHBOARD
            header("Location: teacher_dashboard.php");
            exit(); 
            
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        // Teacher data not found in database
        $error = "Your account is not registered in the system. Please contact the admin or principal for access.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f0f2f5; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 6px solid #6f42c1;
        }
        .btn-purple {
            background: #6f42c1;
            color: white;
            font-weight: 600;
        }
        .btn-purple:hover {
            background: #5a32a3;
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <i class="fas fa-university fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold">Teacher Login</h3>
            <p class="text-muted">Access your dashboard</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-warning py-3 small">
                <i class="fas fa-exclamation-triangle me-2"></i> 
                <strong>⚠️ Access Issues</strong><br>
                <?= $error ?>
                <br><br>
                <small class="text-muted">
                    <i class="fas fa-phone me-1"></i> Contact Admin or Principal for assistance
                </small>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="teacher@inspire.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="pass" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn btn-purple w-100 py-2">
                Log In <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>
    </div>

</body>
</html>