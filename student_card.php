<?php
session_start();

// 1. DATABASE CONNECTION
include 'db_connect.php';
// Base data fetching depends on this connection and should be based on provided database parameters.

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student-portal.php');
    exit();
}

$id = $_SESSION['student_id'];
$message = "";

// 2. FETCH ACADEMY LOGO FILENAME FROM DATABASE
// This dependent query fetching and display logic must be included for automatic icon display.
$academy_query = "SELECT picture FROM students WHERE id = '$id'";
$academy_result = mysqli_query($conn, $academy_query);
$academy_data = mysqli_fetch_assoc($academy_result);
$academy_icon = htmlspecialchars($academy_data['picture']);

// 3. FETCH STUDENT'S CURRENT PROFILE DATA FROM DATABASE
// The profile card must fetch data (like 'picture' column) for it to function correctly.
$query = "SELECT * FROM students WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// 4. HANDLE THE UPDATE ACTION
if (isset($_POST['update_profile'])) {
    $mobile        = mysqli_real_escape_string($conn, $_POST['mobile']);
    $fmobile       = mysqli_real_escape_string($conn, $_POST['fmobile']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $email         = mysqli_real_escape_string($conn, $_POST['email']);
    $district      = mysqli_real_escape_string($conn, $_POST['district']);

    // Handle Optional Image Upload
    $pic_query = "";
    if (!empty($_FILES['new_pic']['name'])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["new_pic"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["new_pic"]["tmp_name"], $target_file)) {
            $pic_query = ", picture = '$file_name'";
            // Update the session pic so the dashboard updates too
            $_SESSION['student_pic'] = $file_name;
        }
    }

    // UPDATE QUERY (Notice Name, DOB, and NIC are NOT in this query)
    $update_sql = "UPDATE students SET 
                    mobile = '$mobile', 
                    fmobile = '$fmobile', 
                    qualification = '$qualification', 
                    email = '$email',
                    district = '$district'
                    $pic_query 
                    WHERE id = '$id'";

    if (mysqli_query($conn, $update_sql)) {
        $message = "<div class='alert alert-success animate__animated animate__fadeIn'>Profile updated successfully! Refreshing...</div>";
        // Refresh data.
        header("Refresh:1");
    } else {
        $message = "<div class='alert alert-danger'>Update Error: " . mysqli_error($conn) . "</div>";
    }
}

// 5. LOGIC: CHANGE PASSWORD
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="style.css">
            border-bottom: 1px solid #334155;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .badge-id {
            background: rgba(0, 255, 213, 0.1);
            color: #00ffd5;
            border: 1px solid #00ffd5;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.8rem;
        }

        .id-badge {
            background: rgba(0, 255, 213, 0.1);
            color: #00ffd5;
            border: 1px solid #00ffd5;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .locked-field {
            background: #111827 !important;
            color: #4b5563 !important;
            cursor: not-allowed;
            border-color: #1e293b !important;
        }
    </style>
</head>

<body class="profile-page">

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="main-card animate__animated animate__fadeInUp">

                    <div class="academy-icon-display mb-4 text-center">
                        <img src="uploads/<?php echo $academy_icon; ?>" alt="Academy Icon" class="academy-profile-icon" style="height: 100px; width: 100px;">
                        <br>
                        <small class="text-secondary">Academy Profile Icon</small>
                    </div>

                    <div class="text-center mb-5">
                        <div class="profile-pic-container mb-3">
                            <img src="uploads/<?php echo $user['picture']; ?>" class="profile-pic">
                        </div>
                        <h2 class="fw-bold mb-1"><?php echo $user['name']; ?></h2>
                        <p class="text-secondary mb-2">Student ID: <span class="text-info">IT-<?php echo 1000 + $user['id']; ?></span></p>
                        <div class="badge bg-success p-2">Status: Active Account</div>
                    </div>

                    <?php if ($message) echo "<div class='alert alert-success border-0 bg-success text-white'>$message</div>"; ?>

                    <div class="row">
                        <div class="col-md-7 border-end border-secondary border-opacity-25 pe-md-5">
                            <form method="POST" enctype="multipart/form-data">
                                <h6 class="section-title">Academic Records (Permanent)</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1 text-secondary">Full Name</label>
                                        <input type="text" class="form-control locked" value="<?php echo $user['name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1 text-secondary">Date of Birth</label>
                                        <input type="text" class="form-control locked" value="<?php echo $user['dob']; ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small mb-1 text-secondary">CNIC / B-Form</label>
                                        <input type="text" class="form-control locked" value="<?php echo $user['nic']; ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="small mb-1 text-secondary">Enrolled Course</label>
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
                                        00>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>