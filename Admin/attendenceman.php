<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$role = $_SESSION['user_role'] ?? '';
if ($role !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$adminId = (int) ($_SESSION['user_id'] ?? 0);
$adminName = $_SESSION['user_name'] ?? 'Admin';
$adminAvatar = $_SESSION['user_avatar'] ?? '';
if (empty($adminAvatar) && $adminId > 0) {
    $avQuery = $conn->prepare("SELECT ep.avatar FROM employee_profiles ep WHERE ep.user_id = ?");
    $avQuery->bind_param('i', $adminId);
    $avQuery->execute();
    $avRow = $avQuery->get_result()->fetch_assoc();
    $adminAvatar = $avRow['avatar'] ?? '';
    if (!empty($adminAvatar)) $_SESSION['user_avatar'] = $adminAvatar;
}
$defaultAvatar = 'https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg';
$adminAvatarDisplay = !empty($adminAvatar) ? htmlspecialchars($adminAvatar) : $defaultAvatar;

// Selected date (defaults to today)
$selectedDate = $_GET['date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    $selectedDate = date('Y-m-d');
}

// Present on selected date
$presentStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'present'");
$presentStmt->bind_param('s', $selectedDate);
$presentStmt->execute();
$presentToday = (int) $presentStmt->get_result()->fetch_assoc()['cnt'];

// Late on selected date
$lateStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'late'");
$lateStmt->bind_param('s', $selectedDate);
$lateStmt->execute();
$lateToday = (int) $lateStmt->get_result()->fetch_assoc()['cnt'];

// Absent on selected date (recorded as absent)
$absentStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'absent'");
$absentStmt->bind_param('s', $selectedDate);
$absentStmt->execute();
$absentToday = (int) $absentStmt->get_result()->fetch_assoc()['cnt'];

// Also count users with no attendance record on selected date as absent
$totalUsers = (int) $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee'")->fetch_row()[0];
$totalAttendedToday = $presentToday + $lateToday + $absentToday;
if ($totalUsers > $totalAttendedToday) {
    $absentToday += ($totalUsers - $totalAttendedToday);
}

// Daily attendance log for selected date
$logStmt = $conn->prepare("SELECT u.id, u.name, a.date, a.check_in, a.check_out, a.status FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? ORDER BY a.check_in ASC");
$logStmt->bind_param('s', $selectedDate);
$logStmt->execute();
$logResult = $logStmt->get_result();
$attendanceLog = [];
if ($logResult && $logResult->num_rows > 0) {
    while ($row = $logResult->fetch_assoc()) {
        // Calculate overtime: if check_out > 17:00, overtime = check_out - 17:00
        $overtime = '00:00';
        if (!empty($row['check_out']) && $row['check_out'] !== '00:00:00') {
            $checkoutTime = strtotime($row['check_out']);
            $endOfDay = strtotime('17:00:00');
            if ($checkoutTime > $endOfDay) {
                $otSeconds = $checkoutTime - $endOfDay;
                $otHours = floor($otSeconds / 3600);
                $otMins = floor(($otSeconds % 3600) / 60);
                $overtime = sprintf('%02d:%02d', $otHours, $otMins);
            }
        }
        $row['overtime'] = $overtime;

        // Get department name
        $deptStmt = $conn->prepare("SELECT d.name FROM departments d JOIN `user` u ON u.department_id = d.id WHERE u.id = ?");
        $deptStmt->bind_param('i', $row['id']);
        $deptStmt->execute();
        $deptResult = $deptStmt->get_result()->fetch_assoc();
        $row['department'] = $deptResult['name'] ?? '';
        $deptStmt->close();

        $attendanceLog[] = $row;
    }
}

// Email monthly PDF handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email_monthly_pdf'])) {
    header('Content-Type: application/json');
    $toEmail = trim($_POST['to_email'] ?? '');
    $toName = trim($_POST['to_name'] ?? '');
    $month = trim($_POST['month_label'] ?? '');
    $pdfData = $_POST['pdf_data'] ?? '';

    if (empty($toEmail) || empty($pdfData)) {
        echo json_encode(['success' => false, 'message' => 'Missing email or PDF data.']);
        exit;
    }

    $pdfContent = base64_decode(preg_replace('#^data:application/pdf;base64,#i', '', $pdfData));
    if ($pdfContent === false || strlen($pdfContent) < 100) {
        echo json_encode(['success' => false, 'message' => 'Invalid PDF data.']);
        exit;
    }

    $boundary = md5(uniqid(time()));
    $filename = 'attendance_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $month) . '.pdf';

    $headers = "From: HR Admin <noreply@company.com>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $body .= "Dear " . ($toName ?: 'Employee') . ",\r\n\r\n";
    $body .= "Please find attached your attendance report for " . ($month ?: 'the requested month') . ".\r\n\r\n";
    $body .= "Best regards,\r\nHR Department\r\n\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: application/pdf; name=\"{$filename}\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"{$filename}\"\r\n\r\n";
    $body .= chunk_split(base64_encode($pdfContent));
    $body .= "--{$boundary}--";

    $sent = @mail($toEmail, "Attendance Report - " . ($month ?: ''), $body, $headers);

    if ($sent) {
        echo json_encode(['success' => true, 'message' => 'PDF sent successfully to ' . htmlspecialchars($toEmail) . '.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email. Check mail configuration.']);
    }
    exit;
}

// Monthly attendance view
$monthlyUserId = isset($_GET['monthly_user']) ? (int) $_GET['monthly_user'] : 0;
$monthlyMonth = $_GET['monthly_month'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $monthlyMonth)) {
    $monthlyMonth = date('Y-m');
}
$monthlyYear = (int) date('Y', strtotime($monthlyMonth . '-01'));
$monthlyMonthNum = (int) date('m', strtotime($monthlyMonth . '-01'));
$daysInMonth = (int) date('t', strtotime($monthlyMonth . '-01'));

$empListRes = $conn->query("SELECT id, name, email FROM `user` WHERE role = 'employee' ORDER BY name ASC");
$empList = [];
if ($empListRes && $empListRes->num_rows > 0) {
    while ($row = $empListRes->fetch_assoc()) {
        $empList[] = $row;
    }
}

$monthlyAttendance = [];
$selectedEmpName = '';
$selectedEmpEmail = '';
if ($monthlyUserId > 0) {
    $empInfoRes = $conn->query("SELECT name, email FROM `user` WHERE id = $monthlyUserId AND role = 'employee'");
    if ($empInfoRes && $empInfoRes->num_rows > 0) {
        $empInfo = $empInfoRes->fetch_assoc();
        $selectedEmpName = $empInfo['name'];
        $selectedEmpEmail = $empInfo['email'];
    }

    $startOfMonth = $monthlyMonth . '-01';
    $endOfMonth = $monthlyMonth . '-' . str_pad($daysInMonth, 2, '0', STR_PAD_LEFT);

    $monthStmt = $conn->prepare("SELECT DATE(a.date) AS att_date, a.check_in, a.check_out, a.status FROM attendance a WHERE a.user_id = ? AND DATE(a.date) BETWEEN ? AND ? ORDER BY a.date ASC");
    $monthStmt->bind_param('iss', $monthlyUserId, $startOfMonth, $endOfMonth);
    $monthStmt->execute();
    $monthResult = $monthStmt->get_result();
    if ($monthResult && $monthResult->num_rows > 0) {
        while ($row = $monthResult->fetch_assoc()) {
            $day = (int) date('d', strtotime($row['att_date']));
            $overtime = '00:00';
            if (!empty($row['check_out']) && $row['check_out'] !== '00:00:00') {
                $checkoutTime = strtotime($row['check_out']);
                $endOfDay = strtotime('17:00:00');
                if ($checkoutTime > $endOfDay) {
                    $otSecs = $checkoutTime - $endOfDay;
                    $overtime = sprintf('%02d:%02d', floor($otSecs / 3600), floor(($otSecs % 3600) / 60));
                }
            }
            $row['overtime'] = $overtime;
            $monthlyAttendance[$day] = $row;
        }
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Attendance Management</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-high": "#e7e8e9",
                        "on-primary-container": "#96a9be",
                        "surface-bright": "#f8f9fa",
                        "on-tertiary-fixed-variant": "#6c228c",
                        "tertiary-container": "#611381",
                        "error-container": "#ffdad6",
                        "surface-dim": "#d9dadb",
                        "secondary": "#006b58",
                        "primary-fixed-dim": "#b5c8df",
                        "on-background": "#191c1d",
                        "inverse-surface": "#2e3132",
                        "secondary-fixed-dim": "#65dabc",
                        "secondary-container": "#82f7d8",
                        "on-primary-fixed": "#091d2e",
                        "error": "#ba1a1a",
                        "surface-tint": "#4e6073",
                        "on-secondary-fixed-variant": "#005142",
                        "on-tertiary-container": "#d788f6",
                        "on-primary": "#ffffff",
                        "primary": "#162839",
                        "tertiary-fixed-dim": "#ecb2ff",
                        "on-tertiary": "#ffffff",
                        "primary-container": "#2c3e50",
                        "inverse-primary": "#b5c8df",
                        "on-secondary-container": "#00725e",
                        "secondary-fixed": "#82f7d8",
                        "outline": "#74777d",
                        "on-error-container": "#93000a",
                        "on-surface": "#191c1d",
                        "outline-variant": "#c4c6cd",
                        "surface-container-lowest": "#ffffff",
                        "tertiary-fixed": "#f8d8ff",
                        "on-tertiary-fixed": "#320047",
                        "surface-container": "#edeeef",
                        "surface": "#f8f9fa",
                        "surface-variant": "#e1e3e4",
                        "on-secondary-fixed": "#002019",
                        "primary-fixed": "#d1e4fb",
                        "surface-container-highest": "#e1e3e4",
                        "surface-container-low": "#f3f4f5",
                        "on-error": "#ffffff",
                        "background": "#f8f9fa",
                        "inverse-on-surface": "#f0f1f2",
                        "on-primary-fixed-variant": "#36485b",
                        "on-secondary": "#ffffff",
                        "tertiary": "#43005e",
                        "on-surface-variant": "#43474c"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "xs": "8px",
                        "lg": "24px",
                        "xl": "32px",
                        "md": "16px",
                        "base": "4px",
                        "gutter": "20px",
                        "sm": "12px",
                        "sidebar-width": "260px"
                    },
                    "fontFamily": {
                        "headline-md": ["Hanken Grotesk"],
                        "body-md": ["Inter"],
                        "headline-sm": ["Hanken Grotesk"],
                        "display-lg": ["Hanken Grotesk"],
                        "label-caps": ["Inter"],
                        "data-mono": ["JetBrains Mono"],
                        "body-sm": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "display-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "data-mono": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
<link rel="stylesheet" href="../config/dashboard.css"/>    <link rel="stylesheet" href="../config/theme.css"/>
    <script src="../config/theme.js"></script><script>(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var root=document.documentElement;root.classList.remove('sidebar-open','sidebar-closed');root.classList.add(c?'sidebar-closed':'sidebar-open');})();</script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c4c6cd; border-radius: 10px; }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body class="bg-background text-on-background overflow-hidden">
<?php $activePage = 'attendance'; ?>
<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>
<!-- TopNavBar (Shared Component) -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-12 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg z-40 transition-all duration-200">
<div class="flex items-center gap-lg flex-1">
<button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>

</div>

</header>
<!-- Main Canvas -->
<main id="mainContent" class="pt-16 h-screen overflow-y-auto bg-background">
<div class="p-lg max-w-[1600px] mx-auto space-y-lg">
<!-- Page Header & Filter Controls -->
<section class="flex flex-col md:flex-row md:items-end justify-between gap-md   ">

<div class="flex items-center gap-sm bg-surface-container-lowest p-xs rounded-xl shadow-sm border border-outline-variant"><div class="flex flex-col px-sm border-r border-outline-variant">
<label class="font-label-caps text-label-caps text-outline uppercase">Search</label>
<input id="attendance-search" class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent w-48" placeholder="Username or ID" type="text">
</div>
<div class="flex flex-col px-sm">
<label class="font-label-caps text-label-caps text-outline uppercase">Date</label>
<input id="attendance-date" class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent" type="date" value="<?= htmlspecialchars($selectedDate) ?>">
</div>
<div class="w-px h-8 bg-outline-variant"></div>
<div class="flex flex-col px-sm min-w-[140px]">
<label class="font-label-caps text-label-caps text-outline uppercase">Department</label>
<select id="attendance-department" class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent appearance-none">
<option value="">All Departments</option>
<option>Software Engineering</option>
<option>Cyber Security</option>
<option>Infrastructure & Cloud Operation</option>
<option>Data Science & Analytics</option>
<option>Quality Assurance & Testing</option>
<option>Finance & Procurement</option>
<option>Human Resources</option>
<option>Technical Support & Helpdesk</option>
<option>UI/UX Design</option>
</select>
</div>
<div class="w-px h-8 bg-outline-variant"></div>
<div class="flex flex-col px-sm min-w-[120px]">
<label class="font-label-caps text-label-caps text-outline uppercase">Overtime</label>
<select id="attendance-overtime" class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent appearance-none">
<option value="">All Status</option>
<option value="with">With Overtime</option>
</select>
</div>
</div>
</section>
<!-- KPI Cards - Summary Stats -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- Total Present -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-secondary">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Present <?= $selectedDate === date('Y-m-d') ? 'Today' : date('M d, Y', strtotime($selectedDate)) ?></span>
<span class="bg-secondary/10 text-secondary p-xs rounded-full">
<span class="material-symbols-outlined text-[18px]">check_circle</span>
</div>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-on-surface"><?= $presentToday ?></span>
</div>
</div>
<!-- Absent -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-error">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Absent</span>
<span class="bg-error/10 text-error p-xs rounded-full">
<span class="material-symbols-outlined text-[18px]">cancel</span>
</span>
</div>
  <div class="flex items-baseline gap-xs">
            <span class="font-display-lg text-display-lg text-on-surface"><?= $absentToday ?></span>
        </div>
    </div>
<!-- Late -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-tertiary-container">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Late Arrivals</span>
<span class="bg-tertiary-fixed text-tertiary p-xs rounded-full">
<span class="material-symbols-outlined text-[18px]">schedule</span>
</span>
</div>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-on-surface"><?= $lateToday ?></span>
        </div>
    </div>
<!-- On Leave -->

</section>
<!-- Attendance Data Table -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
<h3 class="font-headline-sm text-headline-sm text-primary">Daily Attendance Log</h3>

</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Employee</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">ID</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider text-center">Date</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Check-in</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Check-out</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Overtime</th><th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant font-body-md text-on-surface">
<?php if (!empty($attendanceLog)): ?>
<?php foreach ($attendanceLog as $log): ?>
<?php
$statusLower = strtolower($log['status'] ?? 'present');
$badgeClass = 'bg-secondary/10 text-on-secondary-container';
$dotClass = 'bg-secondary';
if ($statusLower === 'late') {
    $badgeClass = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
    $dotClass = 'bg-tertiary';
} elseif ($statusLower === 'absent') {
    $badgeClass = 'bg-error/10 text-on-error-container';
    $dotClass = 'bg-error';
} elseif ($statusLower === 'on leave') {
    $badgeClass = 'bg-surface-variant text-on-surface-variant';
    $dotClass = 'bg-outline';
}
?>
<tr class="hover:bg-secondary/5 transition-colors group"
     data-username="<?= htmlspecialchars(strtolower($log['name'] ?? '')) ?>"
     data-id="uid-<?= str_pad($log['id'], 4, '0', STR_PAD_LEFT) ?>"
     data-date="<?= htmlspecialchars($log['date'] ?? '') ?>"
     data-department="<?= htmlspecialchars($log['department'] ?? '') ?>"
     data-overtime="<?= ($log['overtime'] !== '00:00') ? 'with' : 'no' ?>">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center text-sm font-semibold text-primary"><?= htmlspecialchars(strtoupper(substr($log['name'], 0, 1))) ?></div>
<span class="font-semibold"><?= htmlspecialchars($log['name'] ?? '—') ?></span>
</div>
</td>
<td class="px-lg py-md font-data-mono text-data-mono text-on-surface-variant">UID-<?= str_pad($log['id'], 4, '0', STR_PAD_LEFT) ?></td>
<td class="px-lg py-md text-center"><?= htmlspecialchars(date('M d, Y', strtotime($log['date']))) ?></td>
<td class="px-lg py-md"><?= htmlspecialchars($log['check_in'] ?? '--:--') ?></td>
<td class="px-lg py-md"><?= htmlspecialchars($log['check_out'] ?? '--:--') ?></td>
<td class="px-lg py-md font-data-mono <?= $log['overtime'] !== '00:00' ? 'text-error font-bold' : 'text-outline' ?>"><?= htmlspecialchars($log['overtime']) ?></td>
<td class="px-lg py-md">
<span class="<?= $badgeClass ?> px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span>
<?= htmlspecialchars(ucfirst($log['status'] ?? 'Present')) ?>
</span>
</td>

</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-lg py-md text-body-sm text-on-surface-variant text-center" colspan="8">No attendance records found for <?= $selectedDate === date('Y-m-d') ? 'today' : date('M d, Y', strtotime($selectedDate)) ?>.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="px-lg py-md border-t border-outline-variant flex justify-between items-center bg-surface-container-low/30">
<span id="attendance-count" class="font-body-sm text-on-surface-variant">Showing <?= count($attendanceLog) ?> of <?= count($attendanceLog) ?> records</span>
<div class="flex gap-xs">
<button class="px-md py-xs border border-outline-variant rounded-lg font-body-sm hover:bg-surface-container transition-all">Previous</button>
<button class="px-md py-xs bg-primary text-on-primary rounded-lg font-body-sm hover:opacity-90 transition-all">Next</button>
</div>
</div>
</section>
<!-- Monthly Attendance Report -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-lg py-md border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-md bg-surface-bright">
<h3 class="font-headline-sm text-headline-sm text-primary">Monthly Attendance Report</h3>
<div class="flex gap-sm">
<?php if ($monthlyUserId > 0): ?>
<button onclick="generateMonthlyPDF()" class="bg-secondary text-on-secondary px-md py-xs rounded-lg font-label-caps text-label-caps hover:opacity-90 transition-opacity flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Download PDF
</button>
<button onclick="emailMonthlyPDF()" class="bg-primary text-on-primary px-md py-xs rounded-lg font-label-caps text-label-caps hover:opacity-90 transition-opacity flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]">email</span> Email PDF
</button>
<?php endif; ?>
</div>
</div>
<div class="px-lg py-md flex flex-col md:flex-row gap-md items-end border-b border-outline-variant">
<div class="flex flex-col flex-1 min-w-[200px]">
<label class="font-label-caps text-label-caps text-outline uppercase">Employee</label>
<select id="monthly-employee" class="border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary text-body-sm py-2 px-md w-full">
<option value="">Select Employee</option>
<?php foreach ($empList as $emp): ?>
<option value="<?= (int) $emp['id'] ?>" <?= $monthlyUserId === (int) $emp['id'] ? 'selected' : '' ?>><?= htmlspecialchars($emp['name']) ?> (UID-<?= str_pad($emp['id'], 4, '0', STR_PAD_LEFT) ?>)</option>
<?php endforeach; ?>
</select>
</div>
<div class="flex flex-col">
<label class="font-label-caps text-label-caps text-outline uppercase">Month</label>
<input id="monthly-month" type="month" value="<?= htmlspecialchars($monthlyMonth) ?>" class="border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary text-body-sm py-2 px-md">
</div>
<button onclick="loadMonthlyReport()" class="bg-secondary text-on-secondary px-lg py-2 rounded-lg font-label-caps text-label-caps hover:opacity-90 transition-opacity">
Load Report
</button>
</div>
<?php if ($monthlyUserId > 0 && $selectedEmpName !== ''): ?>
<div id="monthly-pdf-content" class="p-lg">
<div class="mb-md flex justify-between items-start">
<div>
<h4 class="font-headline-sm text-headline-sm text-primary"><?= htmlspecialchars($selectedEmpName) ?></h4>
<p class="text-body-sm text-on-surface-variant">Monthly Attendance &mdash; <?= date('F Y', strtotime($monthlyMonth . '-01')) ?></p>
</div>
<div class="text-right text-body-sm text-on-surface-variant">
<p>ID: UID-<?= str_pad($monthlyUserId, 4, '0', STR_PAD_LEFT) ?></p>
<p><?= htmlspecialchars($selectedEmpEmail) ?></p>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low">
<th class="px-md py-sm font-label-caps text-label-caps text-outline uppercase">Day</th>
<th class="px-md py-sm font-label-caps text-label-caps text-outline uppercase">Date</th>
<th class="px-md py-sm font-label-caps text-label-caps text-outline uppercase">Check-in</th>
<th class="px-md py-sm font-label-caps text-label-caps text-outline uppercase">Check-out</th>
<th class="px-md py-sm font-label-caps text-label-caps text-outline uppercase">Overtime</th>
<th class="px-md py-sm font-label-caps text-label-caps text-outline uppercase">Status</th>
</tr>
</thead>
<tbody class="font-body-md text-on-surface">
<?php
$monthPresent = 0;
$monthAbsent = 0;
$monthLate = 0;
$monthNoRecord = 0;
$monthWeekend = 0;
?>
<?php for ($d = 1; $d <= $daysInMonth; $d++): ?>
<?php
$dayData = $monthlyAttendance[$d] ?? null;
$dayDate = sprintf('%04d-%02d-%02d', $monthlyYear, $monthlyMonthNum, $d);
$dayOfWeek = date('D', strtotime($dayDate));
$isWeekend = in_array($dayOfWeek, ['Sat', 'Sun']);
if ($dayData) {
    $st = strtolower($dayData['status'] ?? 'present');
    if ($st === 'present') $monthPresent++;
    elseif ($st === 'late') $monthLate++;
    elseif ($st === 'absent') $monthAbsent++;
} else {
    if ($isWeekend) $monthWeekend++;
    else $monthNoRecord++;
}
?>
<tr class="border-b border-surface-container <?= $isWeekend ? 'bg-surface-container-low/30' : '' ?>">
<td class="px-md py-sm font-data-mono text-data-mono text-on-surface-variant"><?= $d ?></td>
<td class="px-md py-sm"><?= date('D, M d', strtotime($dayDate)) ?></td>
<td class="px-md py-sm font-data-mono"><?= $dayData ? htmlspecialchars($dayData['check_in'] ?? '—') : '—' ?></td>
<td class="px-md py-sm font-data-mono"><?= $dayData ? htmlspecialchars($dayData['check_out'] ?? '—') : '—' ?></td>
<td class="px-md py-sm font-data-mono <?= ($dayData && !empty($dayData['overtime']) && $dayData['overtime'] !== '00:00') ? 'text-error font-bold' : 'text-outline' ?>"><?= $dayData ? htmlspecialchars($dayData['overtime'] ?? '00:00') : '00:00' ?></td>
<td class="px-md py-sm">
<?php
$status = $dayData ? strtolower($dayData['status'] ?? 'present') : ($isWeekend ? 'weekend' : 'no record');
$bClass = 'bg-secondary/10 text-on-secondary-container';
$dClass = 'bg-secondary';
if ($status === 'late') { $bClass = 'bg-tertiary-fixed text-on-tertiary-fixed-variant'; $dClass = 'bg-tertiary'; }
elseif ($status === 'absent') { $bClass = 'bg-error/10 text-on-error-container'; $dClass = 'bg-error'; }
elseif ($status === 'weekend') { $bClass = 'bg-surface-variant text-on-surface-variant'; $dClass = 'bg-outline'; }
elseif ($status === 'no record') { $bClass = 'bg-surface-variant text-on-surface-variant'; $dClass = 'bg-outline'; }
$label = ucfirst($status === 'no record' ? 'No Record' : ($status === 'weekend' ? 'Weekend' : ($dayData['status'] ?? 'Present')));
?>
<span class="<?= $bClass ?> px-sm py-base rounded-full text-[11px] font-semibold inline-flex items-center gap-xs">
<span class="w-1.5 h-1.5 rounded-full <?= $dClass ?>"></span>
<?= $label ?>
</span>
</td>
</tr>
<?php endfor; ?>
</tbody>
</table>
</div>
<div class="mt-md flex flex-wrap gap-md text-body-sm text-on-surface-variant">
<span class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-secondary"></span> Present: <?= $monthPresent ?></span>
<span class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-tertiary"></span> Late: <?= $monthLate ?></span>
<span class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-error"></span> Absent: <?= $monthAbsent ?></span>
<span class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-outline"></span> No Record: <?= $monthNoRecord ?></span>
<span class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-outline"></span> Weekend: <?= $monthWeekend ?></span>
</div>
</div>
<?php else: ?>
<div class="p-xl text-center text-on-surface-variant">
<span class="material-symbols-outlined text-[48px] text-outline/40 block mb-sm">calendar_month</span>
<p class="text-body-md">Select an employee and month to view their attendance report.</p>
</div>
<?php endif; ?>
</section>
</div>
</main>
<!-- Micro-interaction Script -->
<script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    const icon = row.querySelector('.material-symbols-outlined');
                    if (icon) icon.style.fontVariationSettings = "'FILL' 1";
                });
                row.addEventListener('mouseleave', () => {
                    const icon = row.querySelector('.material-symbols-outlined');
                    if (icon) icon.style.fontVariationSettings = "'FILL' 0";
                });
            });
        });

        const filterAttendanceRows = () => {
            const search = document.querySelector('#attendance-search').value.trim().toLowerCase();
            const selectedDate = document.querySelector('#attendance-date').value;
            const department = document.querySelector('#attendance-department').value;
            const overtime = document.querySelector('#attendance-overtime').value;
            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const username = row.dataset.username?.toLowerCase() || '';
                const id = row.dataset.id?.toLowerCase() || '';
                const rowDate = row.dataset.date || '';
                const rowDept = row.dataset.department || '';
                const rowOvertime = row.dataset.overtime || '';

                const matchesSearch = !search || username.includes(search) || id.includes(search);
                const matchesDepartment = !department || rowDept === department;
                const matchesOvertime = !overtime || rowOvertime === overtime;
                const matchesDate = !selectedDate || rowDate === selectedDate;

                const visible = matchesSearch && matchesDepartment && matchesOvertime && matchesDate;
                row.style.display = visible ? '' : 'none';
                if (visible) visibleCount += 1;
            });

            const countLabel = document.querySelector('#attendance-count');
            if (countLabel) {
                countLabel.textContent = `Showing ${visibleCount} of ${rows.length} records`;
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Date picker: reload page with selected date
            const datePicker = document.querySelector('#attendance-date');
            if (datePicker) {
                datePicker.addEventListener('change', () => {
                    const dateVal = datePicker.value;
                    if (dateVal) {
                        window.location.href = 'attendenceman.php?date=' + encodeURIComponent(dateVal);
                    }
                });
            }

            // Other filters: client-side filtering
            const controls = [
                document.querySelector('#attendance-search'),
                document.querySelector('#attendance-department'),
                document.querySelector('#attendance-overtime')
            ];
            controls.forEach(control => {
                if (control) control.addEventListener('input', filterAttendanceRows);
            });
            filterAttendanceRows();
        });

        function loadMonthlyReport() {
            const emp = document.querySelector('#monthly-employee').value;
            const month = document.querySelector('#monthly-month').value;
            if (!emp) { alert('Please select an employee.'); return; }
            window.location.href = 'attendenceman.php?monthly_user=' + emp + '&monthly_month=' + encodeURIComponent(month);
        }

        function generateMonthlyPDF() {
            const element = document.getElementById('monthly-pdf-content');
            if (!element) { alert('No report to export.'); return; }
            const monthLabel = document.querySelector('#monthly-month').value || 'report';
            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     'attendance_' + monthLabel + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        }

        function emailMonthlyPDF() {
            const element = document.getElementById('monthly-pdf-content');
            if (!element) { alert('No report to email.'); return; }
            const empId = document.querySelector('#monthly-employee').value;
            const month = document.querySelector('#monthly-month').value;
            const empSelect = document.querySelector('#monthly-employee');
            const empOption = empSelect.options[empSelect.selectedIndex];
            const empName = empOption ? empOption.textContent.split('(')[0].trim() : '';
            const empEmail = '<?= htmlspecialchars($selectedEmpEmail) ?>';
            const monthLabel = empOption ? month : month;

            if (!empEmail) { alert('Employee email not found.'); return; }

            const btn = event.target.closest('button');
            const origHTML = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span> Generating...';
            btn.disabled = true;

            const opt = {
                margin:       [10, 10, 10, 10],
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).outputPdf('datauristring').then(function(pdfData) {
                btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span> Sending...';

                const formData = new FormData();
                formData.append('email_monthly_pdf', '1');
                formData.append('to_email', empEmail);
                formData.append('to_name', empName);
                formData.append('month_label', monthLabel);
                formData.append('pdf_data', pdfData);

                fetch('attendenceman.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        btn.innerHTML = origHTML;
                        btn.disabled = false;
                        if (data.success) {
                            alert(data.message);
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(() => {
                        btn.innerHTML = origHTML;
                        btn.disabled = false;
                        alert('Failed to send email. Please try again.');
                    });
            });
        }
    </script>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body></html>