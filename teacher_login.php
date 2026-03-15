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
    <link rel="stylesheet" href="style.css">
    <style>
        .cartoon-chishiya {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 220px;
            height: 280px;
            z-index: 10;
            pointer-events: none;
        }

        .chishiya-body {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .chishiya-head {
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 140px;
            height: 140px;
            background: #fdbcb4;
            border-radius: 50%;
            border: 2px solid #333;
            z-index: 2;
        }

        .chishiya-hair {
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 160px;
            height: 60px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa, #e9ecef);
            border-radius: 50% 50% 30% 30%;
            z-index: 1;
            border: 2px solid #333;
        }

        .chishiya-hair::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 25px;
            width: 25px;
            height: 35px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border-radius: 50%;
            transform: rotate(-20deg);
            border: 1px solid #333;
        }

        .chishiya-hair::after {
            content: '';
            position: absolute;
            top: 8px;
            right: 25px;
            width: 25px;
            height: 35px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border-radius: 50%;
            transform: rotate(20deg);
            border: 1px solid #333;
        }

        .chishiya-face {
            position: absolute;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 90px;
        }

        .chishiya-eyes {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
        }

        .chishiya-eye {
            width: 22px;
            height: 25px;
            background: white;
            border-radius: 50% 50% 40% 40%;
            border: 2px solid #333;
            position: relative;
            overflow: hidden;
        }

        .chishiya-eye::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 6px;
            width: 10px;
            height: 10px;
            background: #333;
            border-radius: 50%;
        }

        .chishiya-mouth {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 8px;
            border: 2px solid #333;
            border-top: none;
            border-radius: 0 0 40% 40%;
            background: #ff6b9d;
        }

        .chishiya-hoodie {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 120px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border-radius: 50% 50% 40% 40%;
            border: 2px solid #333;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .chishiya-hoodie::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #333;
            border-radius: 2px;
        }

        .chishiya-hand {
            position: absolute;
            bottom: 50px;
            right: 15px;
            width: 25px;
            height: 30px;
            background: #fdbcb4;
            border-radius: 50%;
            border: 2px solid #333;
            transform: rotate(20deg);
            z-index: 3;
        }

        .chishiya-blackboard {
            position: absolute;
            bottom: 25px;
            right: 35px;
            width: 70px;
            height: 55px;
            background: #2c3e50;
            border: 2px solid #333;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            z-index: 3;
        }

        .chishiya-blackboard::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            height: 2px;
            background: white;
            opacity: 0.8;
        }

        .chishiya-blackboard::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 8px;
            right: 8px;
            height: 2px;
            background: white;
            opacity: 0.8;
        }

        /* Writing animation */
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
            width: 22px;
            height: 12px;
            background: #ff4757;
            border-radius: 50% 50% 15% 15%;
            animation: laugh 0.3s ease-in-out infinite alternate;
        }

        .laughing .chishiya-eyes {
            animation: laugh-eyes 0.3s ease-in-out infinite alternate;
        }

        /* Blinking */
        .blinking .chishiya-eye::before {
            animation: blink 0.1s ease-in-out;
        }

        /* Calculating expression */
        .calculating .chishiya-mouth {
            width: 12px;
            height: 6px;
            background: #ff6b9d;
            border-radius: 50% 50% 25% 25%;
        }

        @keyframes write-blackboard {
            0% { transform: rotate(20deg) translateY(0) translateX(0); }
            50% { transform: rotate(30deg) translateY(-12px) translateX(-8px); }
            100% { transform: rotate(35deg) translateY(-6px) translateX(-12px); }
        }

        @keyframes draw-line {
            0% { width: 0; }
            100% { width: calc(100% - 16px); }
        }

        @keyframes laugh {
            0% { transform: translateX(-50%) scale(1) rotate(0deg); }
            100% { transform: translateX(-50%) scale(1.1) rotate(1deg); }
        }

        @keyframes laugh-eyes {
            0% { transform: translateX(-50%) scaleY(1); }
            100% { transform: translateX(-50%) scaleY(0.7); }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>
<body class="teacher-login-page <?php echo $error ? 'error-state' : ''; ?>">

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

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card p-4 shadow" style="min-width: 360px;">
            <h2 class="mb-3 text-center">Teacher Portal</h2>
            <p class="text-muted text-center">#1 Tech Academy in Nowshera</p>

            <?php if($error): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach(explode('<br>', $error) as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="teacher@inspire.com" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Password</label>
                    <input type="password" name="pass" class="form-control" id="pass" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn btn-purple w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Need help? Contact Admin</small>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chishiya = document.querySelector('.cartoon-chishiya');
            const inputs = document.querySelectorAll('input');

            // Random blinking
            setInterval(() => {
                chishiya.classList.add('blinking');
                setTimeout(() => {
                    chishiya.classList.remove('blinking');
                }, 100);
            }, Math.random() * 3000 + 2000); // Blink every 2-5 seconds

            // Typing animation - writing on blackboard
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        chishiya.classList.add('writing');
                        chishiya.classList.add('calculating');
                    } else {
                        chishiya.classList.remove('writing');
                        chishiya.classList.remove('calculating');
                    }
                });

                // Focus event
                input.addEventListener('focus', function() {
                    chishiya.classList.add('calculating');
                });

                input.addEventListener('blur', function() {
                    chishiya.classList.remove('calculating');
                });
            });

            // Laughing reaction when account not available
            if (document.body.classList.contains('error-state')) {
                chishiya.classList.add('laughing');
                setTimeout(() => {
                    chishiya.classList.remove('laughing');
                }, 4000);
            }

            // Initial calculating state (Chishiya's analytical personality)
            setTimeout(() => {
                chishiya.classList.add('calculating');
            }, 1000);
        });
    </script>
</body>
</html>
