<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

// Restrict to admin or teacher for security
if (!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['teacher_logged_in'])) {
    http_response_code(403);
    echo json_encode(["html" => "<tr><td colspan=\"3\" class=\"text-danger\">Unauthorized</td></tr>", "summary" => "Unauthorized"]);
    exit;
}

$isAdmin = isset($_SESSION['admin_logged_in']);
$isTeacher = isset($_SESSION['teacher_logged_in']);

$query = "SELECT id, name, subject, is_online, last_seen, total_online_minutes, (total_online_minutes / 60) AS total_hours, (salary - paid_salary) AS pending_payment FROM teachers";
if ($isTeacher) {
    $teacherId = (int)$_SESSION['teacher_id'];
    $query .= " WHERE id = $teacherId";
}
$query .= " ORDER BY is_online DESC, last_seen DESC";

$result = $conn->query($query);

$rowsHtml = '';
$onlineCount = 0;
$offlineCount = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['name']);
        $lastSeen = $row['last_seen'] ? date('M d, h:i A', strtotime($row['last_seen'])) : 'N/A';
        $hours = round($row['total_hours'], 1);
        $online = $row['is_online'] == 1;
        $statusBadge = $online
            ? '<span class="badge bg-success"><i class="fas fa-circle me-1 small"></i> Online</span>'
            : '<span class="badge bg-secondary">Offline</span>';

        if ($online) {
            $onlineCount++;
        } else {
            $offlineCount++;
        }

        if ($isTeacher) {
            // For teachers: 3 columns - Teacher, Status, Hours
            $rowsHtml .= "<tr>";
            $rowsHtml .= "<td><div class=\"fw-bold\">{$name}</div></td>";
            $rowsHtml .= "<td>{$statusBadge}</td>";
            $rowsHtml .= "<td><span class=\"timer-badge\">{$hours}h</span></td>";
            $rowsHtml .= "</tr>";
        } else {
            // For admin: full 7 columns
            $subject = htmlspecialchars($row['subject']);
            $percent = min(($hours / 100) * 100, 100);
            $pendingRaw = (float)$row['pending_payment'];
            $pending = number_format($pendingRaw);
            $rowClass = $online ? 'monitor-online' : 'monitor-offline';

            $rowsHtml .= "<tr class=\"{$rowClass}\">";
            $rowsHtml .= "<td><div class=\"fw-bold\">{$name}</div><small class=\"text-muted\">ID: #{$row['id']}</small></td>";
            $rowsHtml .= "<td><span class=\"badge bg-light text-dark\">{$subject}</span></td>";
            $rowsHtml .= "<td>{$statusBadge}</td>";
            $rowsHtml .= "<td><small>{$lastSeen}</small></td>";
            $rowsHtml .= "<td><div class=\"d-flex align-items-center\"><span class=\"me-2\">{$hours} hrs</span><div class=\"progress w-100\" style=\"height: 5px;\"><div class=\"progress-bar bg-info\" style=\"width: {$percent}%\"></div></div></div></td>";
            $rowsHtml .= "<td class=\"text-danger fw-bold\">Rs. {$pending}</td>";

            // Actions: reset hours + pay salary modal
            $rowsHtml .= "<td>";
            $rowsHtml .= "<form action=\"reset_hours.php\" method=\"POST\" onsubmit=\"return confirm('Reset hours for this teacher only?');\" style=\"display:inline;\">";
            $rowsHtml .= "<input type=\"hidden\" name=\"teacher_id\" value=\"{$row['id']}\">";
            $rowsHtml .= "<button type=\"submit\" class=\"btn btn-link btn-sm text-decoration-none text-muted\">";
            $rowsHtml .= "<i class=\"fas fa-history\"></i> Reset";
            $rowsHtml .= "</button></form>";
            
            $modalId = 'payModal' . $row['id'];
            $rowsHtml .= "<button class=\"btn btn-sm btn-success\" data-bs-toggle=\"modal\" data-bs-target=\"#{$modalId}\">Pay Salary</button>";
            
            // Modal markup
            $rowsHtml .= "<div class=\"modal fade\" id=\"{$modalId}\" tabindex=\"-1\">";
            $rowsHtml .= "<div class=\"modal-dialog\">";
            $rowsHtml .= "<div class=\"modal-content\">";
            $rowsHtml .= "<form action=\"process_payment.php\" method=\"POST\">";
            $rowsHtml .= "<div class=\"modal-header\">";
            $rowsHtml .= "<h5 class=\"modal-title\">Pay Teacher: {$name}</h5>";
            $rowsHtml .= "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\"></button>";
            $rowsHtml .= "</div>";
            $rowsHtml .= "<div class=\"modal-body\">";
            $rowsHtml .= "<input type=\"hidden\" name=\"teacher_id\" value=\"{$row['id']}\">";
            $rowsHtml .= "<label>Enter Amount to Pay (Rs.):</label>";
            $rowsHtml .= "<input type=\"number\" name=\"amount\" class=\"form-control\" max=\"{$pendingRaw}\" required>";
            $rowsHtml .= "<small class=\"text-muted\">Max pending: Rs. {$pending}</small>";
            $rowsHtml .= "</div>";
            $rowsHtml .= "<div class=\"modal-footer\">";
            $rowsHtml .= "<button type=\"submit\" class=\"btn btn-primary\">Confirm Payment</button>";
            $rowsHtml .= "</div>";
            $rowsHtml .= "</form>";
            $rowsHtml .= "</div></div></div>";

            $rowsHtml .= "</td>";
            $rowsHtml .= "</tr>";
        }
    }
} else {
    $colspan = $isTeacher ? 3 : 7;
    $rowsHtml = "<tr><td colspan=\"{$colspan}\" class=\"text-muted\">No teacher data available.</td></tr>";
}

if ($isTeacher) {
    $summary = $onlineCount > 0 ? "You are currently online" : "You are currently offline";
} else {
    $summary = "Online: {$onlineCount} | Offline: {$offlineCount}";
}

echo json_encode(["html" => $rowsHtml, "summary" => $summary]);
