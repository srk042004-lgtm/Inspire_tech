<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header('Location: student-portal.php');
    exit();
}

$name = htmlspecialchars($_SESSION['student_name']);
$myCourse = isset($_SESSION['enrolled_course']) ? $_SESSION['enrolled_course'] : 'none';
$student_id = $_SESSION['student_id'];

// DATABASE MOCKUP: In a real DB, you would fetch 'cert_status' (0=None, 1=Pending, 2=Approved)
// For now, let's assume it's "Pending" to show you the look.
$certStatus = 0; // Set to 0 to see Request, 1 for Pending, 2 for Approved
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Certification | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="certificate-page">

    <div class="cert-container">

        <div class="no-print text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold mb-3">Course Completion & Certification</h2>
            <p class="text-secondary">Official Credentials from Inspire Tech Academy</p>
            <hr class="border-secondary mb-5">

            <?php if ($certStatus == 0): ?>
                <div class="card bg-dark border-secondary p-5 mx-auto" style="max-width: 600px;">
                    <i class="fas fa-graduation-cap fa-4x text-info mb-4"></i>
                    <h4>Ready for Graduation?</h4>
                    <p class="small text-secondary mb-4">By clicking the button below, your course history will be sent to <b>Principal Raheel Ahmad</b> for final review and approval.</p>
                    <button onclick="submitRequest()" id="reqBtn" class="btn btn-request">SUBMIT CERTIFICATE REQUEST</button>
                </div>
            <?php elseif ($certStatus == 1): ?>
                <div class="alert alert-warning p-4 animate__animated animate__pulse animate__infinite" style="max-width: 600px; margin: 0 auto;">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Request Submitted!</strong> Your credentials are being reviewed by the Principal. Please check back in 24-48 hours.
                </div>
            <?php endif; ?>
        </div>

        <?php if ($certStatus == 2): ?>
            <div id="mainCertificate" class="animate__animated animate__zoomIn">
                <div class="cert-header">
                    <img src="uploads/340827876_5872631156182041_1179006399808807244_n.jpg" class="cert-logo">
                    <div class="cert-title">CERTIFICATE</div>
                    <div class="cert-subtitle">OF COMPLETION</div>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-1 text-uppercase small">This is to certify that</p>
                    <div class="student-name"><?php echo strtoupper($name); ?></div>
                    <p class="praise-text">
                        Has successfully demonstrated exceptional dedication, technical proficiency, and a commitment to excellence by completing the <b><?php echo strtoupper(str_replace('-', ' ', $myCourse)); ?></b> program. Their consistent hard work and innovative approach signify a promising future in the tech industry.
                    </p>
                </div>

                <div class="cert-footer">
                    <div class="text-start">
                        <div class="signature">
                            <span style="font-family: 'Cursive'; font-size: 20px;">Raheel Ahmad</span><br>
                            <small>PRINCIPAL & FOUNDER</small>
                        </div>
                        <div class="mt-2 small">Date: <?php echo date('F d, Y'); ?></div>
                    </div>

                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Gold_Seal.png" class="seal">

                    <div class="text-end">
                        <div class="signature">
                            <small>ACADEMY SEAL</small>
                        </div>
                        <div class="mt-2 small">ID: ITA-<?php echo rand(10000, 99999); ?></div>
                    </div>
                </div>
            </div>

            <div class="mt-5 no-print">
                <button onclick="window.print()" class="btn btn-lg btn-outline-light me-3"><i class="fas fa-print me-2"></i> Print Certificate</button>
                <button id="downloadCertBtn" class="btn btn-lg btn-request"><i class="fas fa-download me-2"></i> Save as Image</button>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function submitRequest() {
            // Here you would normally use AJAX to update the DB. 
            // For now, we simulate the submission message.
            const btn = document.getElementById('reqBtn');
            btn.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Submitting...";
            setTimeout(() => {
                alert("Success! Your request has been submitted to Principal Raheel Ahmad. You will be notified once approved.");
                window.location.reload();
            }, 2000);
        }

        // Download Logic
        document.getElementById('downloadCertBtn')?.addEventListener('click', function() {
            const cert = document.getElementById('mainCertificate');
            html2canvas(cert, {
                scale: 2
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = '<?php echo $name; ?>-InspireTech-Certificate.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        });
    </script>

</body>

</html>