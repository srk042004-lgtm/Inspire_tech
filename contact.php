<?php
session_start();
include 'db_connect.php';

// Logic to handle the form submission
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Ensure 'contact_inquiries' table exists
    $conn->query("CREATE TABLE IF NOT EXISTS contact_inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        subject VARCHAR(200),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $sql = "INSERT INTO contact_inquiries (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    
    if ($conn->query($sql)) {
        $msg = "<div class='alert alert-success'>Message sent successfully! We will contact you soon.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error sending message. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Inspire Tech School of IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="contact-page">

<div id="contact" class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Get In <span class="text-info">Touch</span></h1>
        <p class="text-secondary">Have questions? Reach out to the Inspire Tech Team.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="contact-card h-100">
                <h4 class="mb-4">Contact Information</h4>
                
                <a href="tel:+923462345453" class="social-box">
                    <i class="fas fa-phone-alt text-primary"></i>
                    <div>
                        <div class="small text-secondary">Call Us</div>
                        <div class="fw-bold">+92 346 2345453</div>
                    </div>
                </a>

                <a href="https://wa.me/923462345453" target="_blank" class="social-box">
                    <i class="fab fa-whatsapp"></i>
                    <div>
                        <div class="small text-secondary">WhatsApp</div>
                        <div class="fw-bold">Chat with Admin</div>
                    </div>
                </a>

                <a href="https://facebook.com/yourpage" target="_blank" class="social-box">
                    <i class="fab fa-facebook"></i>
                    <div>
                        <div class="small text-secondary">Facebook</div>
                        <div class="fw-bold">Inspire Tech Official</div>
                    </div>
                </a>

                <a href="https://linkedin.com/in/yourprofile" target="_blank" class="social-box">
                    <i class="fab fa-linkedin"></i>
                    <div>
                        <div class="small text-secondary">LinkedIn</div>
                        <div class="fw-bold">Professional Network</div>
                    </div>
                </a>

                <a href="https://tiktok.com/@yourprofile" target="_blank" class="social-box">
                    <i class="fab fa-tiktok"></i>
                    <div>
                        <div class="small text-secondary">TikTok</div>
                        <div class="fw-bold">Learning Short Clips</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-7">
            <div class="contact-card">
                <h4 class="mb-4">Send a Message</h4>
                <?= $msg ?>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-send">SEND MESSAGE <i class="fas fa-paper-plane ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="contact-card p-0 overflow-hidden" style="height: 350px;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13233.123456789!2d71.97!3d34.01!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM0TCsDAwJzAwLjAiTiA3McKwNTgnMDAuMCJF!5e0!3m2!1sen!2s!4v1234567890" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>