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

$period = $_GET['period'] ?? 'today';
$period = in_array($period, ['today', 'week', 'month'], true) ? $period : 'today';

$rangeStart = date('Y-m-d');
$rangeEnd = date('Y-m-d');
if ($period === 'week') {
    $rangeStart = date('Y-m-d', strtotime('monday this week'));
    $rangeEnd = date('Y-m-d', strtotime('sunday this week'));
} elseif ($period === 'month') {
    $rangeStart = date('Y-m-01');
    $rangeEnd = date('Y-m-t');
}

$currentMonth = date('m');
$currentYear = date('Y');

$totalEmployees = 0;
$newHires = 0;
$attTotal = 0;
$attPresent = 0;
$lateArrivals = 0;
$attendanceRate = 0;
$pendingLeaves = 0;
$perfData = [];
$notifications = [];
$upcomingHolidays = [];
$attendanceRows = [];
$broadcastMessage = '';
$broadcastType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_broadcast'])) {
    $title = trim($_POST['broadcast_title'] ?? '');
    $message = trim($_POST['broadcast_message'] ?? '');
    if ($title !== '' && $message !== '') {
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (0, ?, ?, 'broadcast')");
        $stmt->bind_param('ss', $title, $message);
        if ($stmt->execute()) {
            $broadcastMessage = 'Notification sent to all employees.';
            $broadcastType = 'success';
        } else {
            $broadcastMessage = 'Failed to send notification.';
            $broadcastType = 'error';
        }
    } else {
        $broadcastMessage = 'Please fill in all fields.';
        $broadcastType = 'error';
    }
}

$empRes = $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee'");
if ($empRes) $totalEmployees = (int) $empRes->fetch_row()[0];

$newRes = $conn->query("SELECT COUNT(*) FROM `user` WHERE MONTH(created_at) = $currentMonth AND YEAR(created_at) = $currentYear");
if ($newRes) $newHires = (int) $newRes->fetch_row()[0];

$attStmt = $conn->prepare("SELECT COUNT(*) AS total, SUM(CASE WHEN LOWER(status) = 'present' THEN 1 ELSE 0 END) AS present, SUM(CASE WHEN LOWER(status) = 'late' THEN 1 ELSE 0 END) AS late FROM attendance WHERE DATE(date) BETWEEN ? AND ?");
if ($attStmt) {
    $attStmt->bind_param('ss', $rangeStart, $rangeEnd);
    $attStmt->execute();
    $attStats = $attStmt->get_result()->fetch_assoc();
    $attTotal = (int) ($attStats['total'] ?? 0);
    $attPresent = (int) ($attStats['present'] ?? 0);
    $lateArrivals = (int) ($attStats['late'] ?? 0);
    $attendanceRate = $attTotal > 0 ? round(($attPresent / $attTotal) * 100, 1) : 0;
}

$plRes = $conn->query("SELECT COUNT(*) FROM leave_requests WHERE LOWER(status) = 'pending'");
if ($plRes) $pendingLeaves = (int) $plRes->fetch_row()[0];

for ($i = 4; $i >= 0; $i--) {
    $m = (int) date('m', strtotime("-$i months"));
    $y = (int) date('Y', strtotime("-$i months"));
    $monthLabel = date('M y', strtotime("-$i months"));

    $perfStmt = $conn->prepare("SELECT COUNT(*) AS total, SUM(CASE WHEN LOWER(status) = 'present' THEN 1 ELSE 0 END) AS present_count, SUM(CASE WHEN LOWER(status) = 'late' THEN 1 ELSE 0 END) AS late_count FROM attendance WHERE MONTH(date) = ? AND YEAR(date) = ?");
    $perfStmt->bind_param('ii', $m, $y);
    $perfStmt->execute();
    $pRes = $perfStmt->get_result()->fetch_assoc();

    $pTotal = (int) ($pRes['total'] ?? 0);
    $pPresent = (int) ($pRes['present_count'] ?? 0);
    $pLate = (int) ($pRes['late_count'] ?? 0);
    $pAttPercent = $pTotal > 0 ? round(($pPresent / $pTotal) * 100) : 0;
    $pLatePercent = $pTotal > 0 ? round(($pLate / $pTotal) * 100) : 0;

    $perfData[] = [
        'month' => $monthLabel,
        'attendance_percent' => $pAttPercent,
        'late_count' => $pLatePercent,
    ];
}

$adminUnreadCount = 0;
if ($conn->query("SHOW TABLES LIKE 'notifications'")->num_rows > 0) {
    $unreadRes = $conn->query("SELECT COUNT(*) FROM notifications WHERE (user_id = 1 OR user_id = 0) AND is_read = 0");
    if ($unreadRes) $adminUnreadCount = (int) $unreadRes->fetch_row()[0];

    $notifResult = $conn->query("SELECT title, message, created_at FROM notifications ORDER BY created_at DESC LIMIT 5");
    if ($notifResult && $notifResult->num_rows > 0) {
        while ($row = $notifResult->fetch_assoc()) {
            $notifications[] = $row;
        }
    }
}
if (empty($notifications) && $pendingLeaves > 0) {
    $lrResult = $conn->query("SELECT u.name, lr.reason FROM leave_requests lr LEFT JOIN `user` u ON u.id = lr.user_id WHERE LOWER(lr.status) = 'pending' ORDER BY lr.id DESC LIMIT 5");
    if ($lrResult && $lrResult->num_rows > 0) {
        while ($row = $lrResult->fetch_assoc()) {
            $notifications[] = [
                'title' => 'Leave request pending',
                'message' => ($row['name'] ?? 'A staff') . ' requested leave: ' . ($row['reason'] ?? ''),
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
    }
}

if ($conn->query("SHOW TABLES LIKE 'holidays'")->num_rows > 0) {
    $holResult = $conn->query("SELECT name, date, type FROM holidays WHERE date >= CURDATE() ORDER BY date ASC LIMIT 5");
    if ($holResult && $holResult->num_rows > 0) {
        while ($row = $holResult->fetch_assoc()) {
            $upcomingHolidays[] = $row;
        }
    }
}
if (empty($upcomingHolidays)) {
    $upcomingHolidays = [
        ['name' => 'National Day', 'date' => date('Y-m-01'), 'type' => 'Public Holiday'],
        ['name' => 'Company Wellness Day', 'date' => date('Y-m-10'), 'type' => 'Company Holiday'],
        ['name' => 'Annual Festival', 'date' => date('Y-m-24'), 'type' => 'Public Holiday'],
    ];
}
$holidayMonthLabel = date('F Y');
if (!empty($upcomingHolidays[0]['date'])) {
    $holidayMonthLabel = date('F Y', strtotime($upcomingHolidays[0]['date']));
}

$attRowsQuery = "SELECT u.name, d.name AS department_name, a.check_in, a.status FROM attendance a LEFT JOIN `user` u ON u.id = a.user_id LEFT JOIN departments d ON d.id = u.department_id WHERE DATE(a.date) BETWEEN ? AND ? ORDER BY a.check_in ASC LIMIT 10";
$attRowsStmt = $conn->prepare($attRowsQuery);
$attRowsStmt->bind_param('ss', $rangeStart, $rangeEnd);
$attRowsStmt->execute();
$attRowsResult = $attRowsStmt->get_result();
if ($attRowsResult && $attRowsResult->num_rows > 0) {
    while ($row = $attRowsResult->fetch_assoc()) {
        $attendanceRows[] = $row;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>HR Dashboard</title>
<!-- Preconnect for faster font loading -->
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CDN (JIT) -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            "colors": {
                "surface-container-high":"#e7e8e9","on-primary-container":"#96a9be","surface-bright":"#f8f9fa","on-tertiary-fixed-variant":"#6c228c","tertiary-container":"#611381","error-container":"#ffdad6","surface-dim":"#d9dadb","secondary":"#006b58","primary-fixed-dim":"#b5c8df","on-background":"#191c1d","inverse-surface":"#2e3132","secondary-fixed-dim":"#65dabc","secondary-container":"#82f7d8","on-primary-fixed":"#091d2e","error":"#ba1a1a","surface-tint":"#4e6073","on-secondary-fixed-variant":"#005142","on-tertiary-container":"#d788f6","on-primary":"#ffffff","primary":"#162839","tertiary-fixed-dim":"#ecb2ff","on-tertiary":"#ffffff","primary-container":"#2c3e50","inverse-primary":"#b5c8df","on-secondary-container":"#00725e","secondary-fixed":"#82f7d8","outline":"#74777d","on-error-container":"#93000a","on-surface":"#191c1d","outline-variant":"#c4c6cd","surface-container-lowest":"#ffffff","tertiary-fixed":"#f8d8ff","on-tertiary-fixed":"#320047","surface-container":"#edeeef","surface":"#f8f9fa","surface-variant":"#e1e3e4","on-secondary-fixed":"#002019","primary-fixed":"#d1e4fb","surface-container-highest":"#e1e3e4","surface-container-low":"#f3f4f5","on-error":"#ffffff","background":"#f8f9fa","inverse-on-surface":"#f0f1f2","on-primary-fixed-variant":"#36485b","on-secondary":"#ffffff","tertiary":"#43005e","on-surface-variant":"#43474c"
            },
            "borderRadius":{"DEFAULT":"0.125rem","lg":"0.25rem","xl":"0.5rem","full":"0.75rem"},
            "spacing":{"xs":"8px","lg":"24px","xl":"32px","md":"16px","base":"4px","gutter":"20px","sm":"12px","sidebar-width":"260px"},
            "fontFamily":{"headline-md":["Hanken Grotesk"],"body-md":["Inter"],"headline-sm":["Hanken Grotesk"],"display-lg":["Hanken Grotesk"],"label-caps":["Inter"],"data-mono":["JetBrains Mono"],"body-sm":["Inter"],"body-lg":["Inter"]},
            "fontSize":{"headline-md":["24px",{"lineHeight":"32px","fontWeight":"600"}],"body-md":["14px",{"lineHeight":"20px","fontWeight":"400"}],"headline-sm":["20px",{"lineHeight":"28px","fontWeight":"600"}],"display-lg":["32px",{"lineHeight":"40px","letterSpacing":"-0.02em","fontWeight":"700"}],"label-caps":["11px",{"lineHeight":"16px","letterSpacing":"0.05em","fontWeight":"600"}],"data-mono":["12px",{"lineHeight":"16px","fontWeight":"500"}],"body-sm":["13px",{"lineHeight":"18px","fontWeight":"400"}],"body-lg":["16px",{"lineHeight":"24px","fontWeight":"400"}]}
        },
    },
}
</script>
<!-- Shared dashboard layout CSS (sidebar positioning, no-blink) -->
<link rel="stylesheet" href="../config/dashboard.css"/>
<link rel="stylesheet" href="../config/theme.css"/>
<script src="../config/theme.js"></script>
<!-- Set sidebar state BEFORE body paints (eliminates layout blink) -->
<script>
(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);document.documentElement.className=c?'sidebar-closed':'sidebar-open';})();
</script>
</head>
<body class="text-on-surface bg-background">
<?php $activePage = 'dashboard'; ?>
<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>
<!-- Predicted TopNavBar Component -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg z-40 transition-all duration-200">
<div class="flex items-center gap-lg flex-1">
<button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
<h2 class="font-headline-sm text-headline-sm font-semibold text-primary dark:text-inverse-primary shrink-0"><?= htmlspecialchars($adminName) ?></h2>
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input id="dashboardSearchInput" name="search" class="w-full bg-surface-container-low border-none rounded-full py-xs pl-xl pr-md text-body-md focus:ring-2 focus:ring-secondary/50" placeholder="Search employees, files, or reports..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="material-symbols-outlined p-xs rounded-full hover:bg-surface-container-low text-on-surface-variant relative transition-all">
                notifications
                <?php if ($adminUnreadCount > 0): ?>
                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-error text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1"><?= $adminUnreadCount > 99 ? '99+' : $adminUnreadCount ?></span>
                <?php endif; ?>
</button>
<div class="h-8 w-[1px] bg-outline-variant mx-xs"></div>
<a class="bg-secondary text-white px-md py-xs rounded-lg font-body-md flex items-center gap-xs hover:bg-secondary/90 active:scale-95 transition-all" href="employee_management.php">
<span class="material-symbols-outlined !text-[18px]">person_add</span>
                Add Employee
            </a>
</header>
<!-- Main Content Canvas -->
<main id="mainContent" class="pt-16 min-h-screen p-lg max-w-[1600px]">
<div class="flex flex-col gap-lg">
<!-- Welcome Header -->
<section class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
<div>
<h2 class="font-headline-md text-headline-md text-primary">Dashboard Overview</h2>
<p class="font-body-md text-on-surface-variant">Summary of human resources performance and daily activity.</p>
</div>
<div class="flex items-center gap-sm bg-surface-container p-base rounded-lg border border-outline-variant">
<a href="dashboard.php?period=today" class="px-md py-xs rounded text-label-caps <?= $period === 'today' ? 'bg-surface-container-lowest shadow-sm' : 'hover:bg-surface-container-high transition-colors' ?>">TODAY</a>
<a href="dashboard.php?period=week" class="px-md py-xs rounded text-label-caps <?= $period === 'week' ? 'bg-surface-container-lowest shadow-sm' : 'hover:bg-surface-container-high transition-colors' ?>">WEEK</a>
<a href="dashboard.php?period=month" class="px-md py-xs rounded text-label-caps <?= $period === 'month' ? 'bg-surface-container-lowest shadow-sm' : 'hover:bg-surface-container-high transition-colors' ?>">MONTH</a>
</div>
</section>
<!-- KPI Cards Bento Grid -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- KPI Card: Total Employees -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-primary transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-primary bg-primary-fixed p-xs rounded-lg">groups</span>
</div>
<p class="font-label-caps text-label-caps text-on-surface-variant">TOTAL EMPLOYEES</p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs"><?= number_format($totalEmployees) ?></h3>
<p class="text-[10px] text-on-surface-variant mt-xs italic"><?= $newHires ?> New hires this month</p>
</div>
<!-- KPI Card: Attendance % -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-secondary transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-secondary bg-secondary-container p-xs rounded-lg">calendar_today</span></div>
<p class="font-label-caps text-label-caps text-on-surface-variant">ATTENDANCE RATE <?= $period === 'month' ? 'THIS MONTH' : ($period === 'week' ? 'THIS WEEK' : 'TODAY') ?></p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs"><?= $attendanceRate ?>%</h3>
<p class="text-[10px] text-on-surface-variant mt-xs italic">System average: <?= $attendanceRate ?>%</p>
</div>
<!-- KPI Card: Late Arrivals -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-error transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-error bg-error-container p-xs rounded-lg">schedule</span>
</div>
<p class="font-label-caps text-label-caps text-on-surface-variant">LATE ARRIVALS <?= $period === 'month' ? 'THIS MONTH' : ($period === 'week' ? 'THIS WEEK' : 'TODAY') ?></p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs"><?= $lateArrivals ?></h3>
</div>
<!-- KPI Card: Pending Leave -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-tertiary-container transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-tertiary-container bg-tertiary-fixed p-xs rounded-lg">pending_actions</span>
<div class="bg-tertiary-fixed text-on-tertiary-fixed px-xs rounded text-[10px] font-bold">URGENT</div>
</div>
<p class="font-label-caps text-label-caps text-on-surface-variant">PENDING LEAVE REQUESTS</p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs"><?= $pendingLeaves ?></h3>
<p class="text-[10px] text-on-surface-variant mt-xs italic"><?= $pendingLeaves ?> awaiting your approval</p>
</div>
</section>
<!-- Charts & Notification Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
<!-- Performance Chart Container -->
<div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm flex flex-col">
<div class="flex justify-between items-center mb-xl">
<div>
<h4 class="font-headline-sm text-headline-sm text-primary">Monthly Performance Report</h4>
<p class="font-body-sm text-on-surface-variant">Attendance vs. Late arrivals trends</p>
</div>
<select class="bg-surface-container border-none rounded-lg text-body-sm px-md py-xs focus:ring-secondary/50">
<option>Last 30 Days</option>
<option>Last Quarter</option>
</select>
</div>
<!-- Custom Visual Chart Implementation -->
<div class="flex-grow flex items-end justify-between h-64 gap-md pb-xs border-b border-outline-variant relative">
<!-- Chart Lines (Simulated Grid) -->
<div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-10">
<div class="border-b border-on-surface w-full"></div>
<div class="border-b border-on-surface w-full"></div>
<div class="border-b border-on-surface w-full"></div>
<div class="border-b border-on-surface w-full"></div>
</div>
<?php foreach ($perfData as $item): ?>
<div class="flex flex-col items-center flex-1 group">
<div class="flex items-end gap-[2px] w-full justify-center h-full">
<div class="bg-primary w-6 chart-bar rounded-t-sm" style="height: <?= (int) ($item['attendance_percent'] ?? 0) ?>%;"></div>
<div class="bg-error w-4 chart-bar rounded-t-sm opacity-60" style="height: <?= max(5, (int) ($item['late_count'] ?? 0)) ?>%;"></div>
</div>
<span class="text-[10px] font-label-caps mt-sm text-on-surface-variant"><?= htmlspecialchars($item['month'] ?? '') ?></span>
</div>
<?php endforeach; ?>
</div>
<div class="flex items-center gap-lg mt-lg">
<div class="flex items-center gap-xs">
<span class="w-3 h-3 bg-primary rounded-full"></span>
<span class="text-body-sm text-on-surface-variant">Attendance (%)</span>
</div>
<div class="flex items-center gap-xs">
<span class="w-3 h-3 bg-error opacity-60 rounded-full"></span>
<span class="text-body-sm text-on-surface-variant">Late Arrivals</span>
</div>
</div>
</div>
<!-- Notifications Panel -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
<div class="flex justify-between items-center mb-lg">
<h4 class="font-headline-sm text-headline-sm text-primary">Recent Notifications</h4>
<span class="bg-error text-white text-[10px] font-bold px-sm py-base rounded-full"><?= count($notifications) ?> NEW</span>
</div>
<div class="space-y-sm">
<?php if (!empty($notifications)): ?>
<?php foreach ($notifications as $notification): ?>
<div class="flex gap-md p-sm bg-surface-container-low rounded-lg border border-outline-variant/30 hover:border-secondary transition-colors cursor-pointer">
<div class="w-10 h-10 rounded-full bg-secondary-container text-secondary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined">assignment_late</span>
</div>
<div>
<p class="text-body-sm font-semibold text-primary"><?= htmlspecialchars($notification['title']) ?></p>
<p class="text-[11px] text-on-surface-variant mb-base"><?= htmlspecialchars($notification['message']) ?></p>
<span class="text-[10px] text-outline font-data-mono uppercase"><?= htmlspecialchars(date('M d, H:i', strtotime($notification['created_at']))) ?></span>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="text-sm text-on-surface-variant">No new notifications.</div>
<?php endif; ?>
</div>
<button class="w-full mt-lg text-label-caps text-secondary font-bold hover:underline py-sm border border-secondary/20 rounded-lg">VIEW ALL NOTIFICATIONS</button>
<button onclick="openBroadcastModal()" class="w-full mt-sm text-label-caps text-primary font-bold hover:underline py-sm border border-primary/20 rounded-lg flex items-center justify-center gap-xs">
<span class="material-symbols-outlined text-sm">campaign</span> SEND NOTIFICATION TO ALL
</button>

</div>
</div>
</div>
<!-- Holidays & Secondary Dashboard Section -->
<section class="grid grid-cols-1 xl:grid-cols-4 gap-lg pt-6">
<!-- Holidays Summary -->
<div class="xl:col-span-1 bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
<h4 class="font-headline-sm text-headline-sm text-primary mb-lg">Upcoming Holidays</h4>
<p class="text-body-sm text-on-surface-variant mb-md">Month: <?= htmlspecialchars($holidayMonthLabel) ?></p>
<div class="space-y-sm">
<?php foreach ($upcomingHolidays as $holiday): ?>
<div class="flex items-center gap-md p-xs border-b border-outline-variant/30">
<div class="bg-primary/10 text-primary w-12 h-12 flex flex-col items-center justify-center rounded-lg">
<span class="text-xs font-bold"><?= htmlspecialchars(strtoupper(date('M', strtotime($holiday['date'])))) ?></span>
<span class="text-lg font-bold"><?= htmlspecialchars(date('d', strtotime($holiday['date']))) ?></span>
</div>
<div>
<p class="text-body-sm font-semibold text-primary"><?= htmlspecialchars($holiday['name']) ?></p>
<p class="text-[10px] text-on-surface-variant"><?= htmlspecialchars($holiday['type']) ?></p>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
<!-- Recent Activities / Attendance Table -->
<div class="xl:col-span-3 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
<div class="p-lg border-b border-outline-variant flex justify-between items-center">
<div>
<h4 class="font-headline-sm text-headline-sm text-primary"><?= $period === 'today' ? "Today's" : ($period === 'week' ? "This Week's" : "This Month's") ?> Attendance Detail</h4>
<p class="text-body-sm text-on-surface-variant">Live feed of employee clock-ins (<?= $rangeStart ?> to <?= $rangeEnd ?>)</p>
</div>
<button class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-full">more_vert</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low">
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">EMPLOYEE</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">DEPARTMENT</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">CLOCK-IN</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">STATUS</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<?php if (!empty($attendanceRows)): ?>
<?php foreach ($attendanceRows as $record): ?>
<tr class="hover:bg-secondary/5 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center text-sm font-semibold text-primary"><?= htmlspecialchars(substr($record['name'], 0, 1)) ?></div>
<span class="text-body-sm font-semibold text-primary"><?= htmlspecialchars($record['name']) ?></span>
</div>
</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant"><?= htmlspecialchars($record['department_name'] ?? '') ?></td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= htmlspecialchars($record['check_in'] ?? '') ?></td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-xs px-sm py-base rounded-full <?= strtolower($record['status'] ?? 'present') === 'late' ? 'bg-error-container/30 text-error' : 'bg-secondary-container/30 text-secondary' ?> text-[10px] font-bold">
<span class="w-1.5 h-1.5 rounded-full <?= strtolower($record['status'] ?? 'present') === 'late' ? 'bg-error' : 'bg-secondary' ?>"></span> <?= htmlspecialchars(strtoupper($record['status'] ?? 'PRESENT')) ?>
                                        </span>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-lg py-md text-body-sm text-on-surface-variant" colspan="4">No attendance records for this <?= $period ?> yet.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</section>
</div>
</main>
<!-- Broadcast Modal -->
<div id="broadcastModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40" style="display:none;">
<div class="bg-surface-container-lowest rounded-xl shadow-xl max-w-lg w-full mx-lg">
<form method="POST" class="p-xl">
<div class="flex items-center justify-between mb-lg">
<h4 class="font-headline-sm text-headline-sm text-primary">Send Notification to All Employees</h4>
<button type="button" onclick="closeBroadcastModal()" class="p-base hover:bg-surface-container-low rounded-lg">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<div class="space-y-md">
<div class="space-y-xs">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Title</label>
<input name="broadcast_title" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20" placeholder="e.g. Office Closure Notice" required/>
</div>
<div class="space-y-xs">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Message</label>
<textarea name="broadcast_message" rows="4" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20 resize-none" placeholder="Enter your message here..." required></textarea>
</div>
</div>
<div class="flex justify-end gap-md mt-xl pt-lg border-t border-outline-variant/30">
<button type="button" onclick="closeBroadcastModal()" class="px-md py-xs border border-outline-variant text-on-surface-variant rounded-lg hover:bg-surface-container-low transition-colors text-body-sm font-semibold">Cancel</button>
<button type="submit" name="send_broadcast" class="px-md py-xs bg-primary text-on-primary rounded-lg hover:bg-primary-container transition-colors text-body-sm font-semibold flex items-center gap-xs">
<span class="material-symbols-outlined text-sm">send</span> Send
</button>
</div>
</form>
</div>
</div>
<!-- Micro-interaction Scripts -->
<script>
   
document.getElementById('dashboardSearchInput').addEventListener('input', function() {
    // 1. Grab what the user typed and make it lowercase
    const searchString = this.value.toLowerCase().trim();
    
    // 2. Select all active data rows inside your data table body
    const dataRows = document.querySelectorAll('table tbody tr');

    dataRows.forEach(row => {
        // 3. Scan the first column (Employee Name) and second column (Department/Details)
        const cellName = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
        const cellDetails = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';

        // 4. If a row contains the typed text, keep it; otherwise, hide it
        if (cellName.includes(searchString) || cellDetails.includes(searchString)) {
            row.style.display = ''; // Show row
        } else {
            row.style.display = 'none'; // Hide row
        }
    });
});


function openBroadcastModal() {
    document.getElementById('broadcastModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeBroadcastModal() {
    document.getElementById('broadcastModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('broadcastModal').addEventListener('click', function(e) {
    if (e.target === this) closeBroadcastModal();
});

<?php if ($broadcastMessage !== ''): ?>
alert('<?= htmlspecialchars($broadcastMessage) ?>');
<?php endif; ?>

document.addEventListener('DOMContentLoaded', () => {
    const bars = document.querySelectorAll('.chart-bar');
    bars.forEach(bar => {
        const finalHeight = bar.style.height;
        bar.style.height = '0';
        setTimeout(() => {
            bar.style.height = finalHeight;
        }, 300);
    });
});
</script>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body></html>