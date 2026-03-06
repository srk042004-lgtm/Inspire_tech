<?php
include 'db_connect.php';
session_start();

// --- THE CRON LOGIC (The "Job") ---
$log_message = "";
if (isset($_POST['run_sync'])) {
    // 1. Identify students with pending fees
    $query = "SELECT name, phone_number, (total_fee - paid_fee) as balance 
              FROM students WHERE paid_fee < total_fee";
    $result = $conn->query($query);

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        // Here you would normally integrate an SMS API or Email function
        // For now, we log the action
        $log_message .= "[" . date('H:i:s') . "] Reminder queued for: " . $row['name'] . " (Balance: " . $row['balance'] . ")\n";
        $count++;
    }
    $log_message .= "[" . date('H:i:s') . "] SUCCESS: $count reminders processed.\n";
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automation Manager | Inspire Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="navbar border-bottom py-3">
        <div class="container">
            <span class="navbar-brand fw-bold"><i class="fas fa-robot me-2"></i>Inspire Task Runner</span>
            <div class="d-flex align-items-center">
                <i class="fas fa-moon theme-switch me-3" id="themeBtn"></i>
                <a href="admin_dashboard.php" class="btn btn-sm btn-outline-secondary">Exit</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="automation-card h-100">
                    <h5 class="fw-bold mb-4">Automation Status</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Fee Reminders</span>
                        <span class="status-badge bg-success-subtle text-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Cert Verification</span>
                        <span class="status-badge bg-success-subtle text-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Last Global Sync</span>
                        <span class="text-muted small"><?= date('d M, Y H:i') ?></span>
                    </div>
                    <form method="POST">
                        <button name="run_sync" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="fas fa-sync-alt me-2"></i> Run Manual Sync
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="automation-card">
                    <h5 class="fw-bold mb-3">Real-time Execution Logs</h5>
                    <div class="log-container" id="logBox">
                        <?php
                        if ($log_message) echo nl2br($log_message);
                        else echo "[" . date('H:i:s') . "] System standby. Waiting for trigger...";
                        ?>
                    </div>
                    <p class="small text-muted mt-3 mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        This script checks for unpaid balances and prepares WhatsApp/Email queues.
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="automation-card">
                    <h5 class="fw-bold mb-4">Upcoming Scheduled Tasks</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>TASK NAME</th>
                                    <th>FREQUENCY</th>
                                    <th>DATABASE TABLE</th>
                                    <th>NEXT RUN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-envelope-open-text text-primary me-2"></i>Monthly Fee Reminder</td>
                                    <td>1st of Month</td>
                                    <td><code>fee_payments</code></td>
                                    <td>April 01, 2026</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-database text-warning me-2"></i>Database Auto-Backup</td>
                                    <td>Weekly</td>
                                    <td>ALL</td>
                                    <td>Every Sunday</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle Logic
        const themeBtn = document.getElementById('themeBtn');
        const html = document.documentElement;

        themeBtn.addEventListener('click', () => {
            if (html.getAttribute('data-theme') === 'light') {
                html.setAttribute('data-theme', 'dark');
                themeBtn.classList.replace('fa-moon', 'fa-sun');
            } else {
                html.setAttribute('data-theme', 'light');
                themeBtn.classList.replace('fa-sun', 'fa-moon');
            }
        });

        // Auto-scroll logs to bottom
        const logBox = document.getElementById('logBox');
        logBox.scrollTop = logBox.scrollHeight;
    </script>

</body>

</html>