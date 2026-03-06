<?php
session_start();

// 1. DATABASE CONNECTION
include 'db_connect.php';

// SECURITY CHECK: If not logged in, redirect to login page
if (!isset($_SESSION['student_id'])) {
    header('Location: student-portal.php');
    exit();
}

$id = $_SESSION['student_id'];
$success_msg = "";
$error_msg = "";

// 2. FETCH CURRENT STUDENT DATA
$query = "SELECT * FROM students WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// 3. LOGIC: UPDATE PROFILE INFORMATION
if (isset($_POST['update_profile'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $fmobile = mysqli_real_escape_string($conn, $_POST['fmobile']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);

    // Image Upload Logic
    $img_sql = "";
    $file_name = '';
    if (!empty($_FILES['new_pic']['name'])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["new_pic"]["name"]);
        if (move_uploaded_file($_FILES["new_pic"]["tmp_name"], $target_dir . $file_name)) {
            $img_sql = ", picture = '$file_name'";
        }
    }

    $sql = "UPDATE students SET 
            email = '$email', mobile = '$mobile', fmobile = '$fmobile', 
            district = '$district', qualification = '$qualification' 
            $img_sql WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        $success_msg = "Profile updated successfully!";
        // refresh session values from database
        $refresh = mysqli_query($conn, "SELECT name, picture FROM students WHERE id='$id'");
        if ($refresh && mysqli_num_rows($refresh) === 1) {
            $r = mysqli_fetch_assoc($refresh);
            $_SESSION['student_name'] = $r['name'];
            if (!empty($r['picture'])) {
                $_SESSION['student_pic'] = $r['picture'];
            }
        }
        header("Refresh:2");
    } else {
        $error_msg = "Profile update failed: " . mysqli_error($conn);
    }
}

// 4. LOGIC: CHANGE PASSWORD
if (isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Verify current password first
    if (password_verify($current_pass, $user['password'])) {
        if ($new_pass === $confirm_pass) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $pass_sql = "UPDATE students SET password = '$hashed_pass' WHERE id = '$id'";
            if (mysqli_query($conn, $pass_sql)) {
                $success_msg = "Password changed successfully!";
            }
        } else {
            $error_msg = "New passwords do not match!";
        }
    } else {
        $error_msg = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive Student Profile | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --accent: #00ffd5;
            --bg: #0f172a;
            --card: #1e293b;
        }

        body {
            background: var(--bg);
            color: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-card {
            background: var(--card);
            border: 1px solid #334155;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .profile-pic-container {
            position: relative;
            width: 150px;
            margin: 0 auto;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
        }

        .form-control {
            background: #0f172a;
            border: 1px solid #334155;
            color: white;
            padding: 12px;
        }

        .form-control:focus {
            background: #0f172a;
            color: white;
            border-color: var(--accent);
            box-shadow: none;
        }

        .locked {
            background: #111827 !important;
            color: #64748b !important;
            cursor: not-allowed;
            border-color: #1e293b !important;
        }

        .btn-glow {
            background: linear-gradient(45deg, var(--accent), #00a8ff);
            color: #000;
            font-weight: bold;
            border: none;
            transition: 0.3s;
        }

        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 213, 0.4);
            color: #000;
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #334155;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="main-card">
                    <div class="text-center mb-5">
                        <div class="profile-pic-container mb-3">
                            <img src="uploads/<?php echo $user['picture']; ?>" class="profile-pic">
                        </div>
                        <h2 class="fw-bold mb-1"><?php echo $user['name']; ?></h2>
                        <p class="text-secondary mb-2">Student ID: <span class="text-info">IT-<?php echo 1000 + $user['id']; ?></span></p>
                        <div class="badge bg-success p-2">Status: Active Account</div>
                    </div>

                    <?php if ($success_msg) echo "<div class='alert alert-success border-0 bg-success text-white'>$success_msg</div>"; ?>
                    <?php if ($error_msg) echo "<div class='alert alert-danger border-0 bg-danger text-white'>$error_msg</div>"; ?>

                    <div class="row">
                        <div class="col-md-7 border-end border-secondary border-opacity-25 pe-md-5">
                            <form method="POST" enctype="multipart/form-data">
                                <h6 class="section-title">Academic Records (Fixed)</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small text-secondary mb-1">Full Name</label>
                                        <input type="text" class="form-control locked" value="<?php echo $user['name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-secondary mb-1">Date of Birth</label>
                                        <input type="text" class="form-control locked" value="<?php echo $user['dob']; ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small text-secondary mb-1">CNIC / B-Form</label>
                                        <input type="text" class="form-control locked" value="<?php echo $user['nic']; ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small text-secondary mb-1">Enrolled Course</label>
                                        <input type="text" class="form-control locked" value="<?php echo strtoupper($user['enrolled_course']); ?>" readonly>
                                    </div>

                                    <h6 class="section-title mt-5">Contact Information (Editable)</h6>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Personal Mobile</label>
                                        <input type="text" name="mobile" class="form-control" value="<?php echo $user['mobile']; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Father's Mobile</label>
                                        <input type="text" name="fmobile" class="form-control" value="<?php echo $user['fmobile']; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Current District</label>
                                        <input type="text" name="district" class="form-control" value="<?php echo $user['district']; ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small mb-1">Update Qualification</label>
                                        <input type="text" name="qualification" class="form-control" value="<?php echo $user['qualification']; ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small mb-1">Change Profile Picture</label>
                                        <input type="file" name="new_pic" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" name="update_profile" class="btn btn-glow w-100 py-3">Update Profile Details</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-5 ps-md-5 mt-5 mt-md-0">
                            <h6 class="section-title">Security & Password</h6>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="small mb-1">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Required to authorize changes" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="At least 6 characters" required>
                                </div>
                                <div class="mb-4">
                                    <label class="small mb-1">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password" required>
                                </div>
                                <button type="submit" name="change_password" class="btn btn-outline-info w-100 py-3">Change Password</button>
                            </form>

                            <div class="mt-5 p-3 border border-secondary border-opacity-25 rounded-3">
                                <p class="small text-secondary mb-0"><i class="fas fa-info-circle me-1"></i> For serious record changes (Name or NIC), please visit the administration office.</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="student_dashboard.php" class="text-secondary text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Return to Student Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>