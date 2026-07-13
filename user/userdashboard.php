<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? 'User';

if ($userId <= 0) {
    header('Location: ../auth/login.php');
    exit;
}

$currentMonth = (int) date('m');
$currentYear = (int) date('Y');
$today = date('Y-m-d');
$monthName = date('F Y');

$presentDays = 0;
$lateDays = 0;
$leaveDays = 0;
$workingDays = 0;
$overtimeHours = 0;
$todayAttendance = null;
$notifications = [];
$calendarRecords = [];
$leaveRecords = [];

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) = 'present' AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $presentDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) = 'late' AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $lateDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM leave_requests WHERE user_id = $userId AND LOWER(status) = 'approved' AND MONTH(start_date) = $currentMonth AND YEAR(start_date) = $currentYear");
if ($r) $leaveDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) IN ('present','late') AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $workingDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COALESCE(SUM(hours), 0) FROM overtime WHERE employee_id = $userId AND MONTH(ot_date) = $currentMonth AND YEAR(ot_date) = $currentYear");
if ($r) $overtimeHours = round((float) $r->fetch_row()[0], 1);

$userInfo = $conn->query("SELECT u.name, ep.position, ep.avatar FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = $userId");
$uData = $userInfo ? $userInfo->fetch_assoc() : null;
$dbName = $uData['name'] ?? $userName;
$dbPosition = $uData['position'] ?? 'Employee';
$empAvatar = $uData['avatar'] ?? '';

$todayAttRes = $conn->query("SELECT check_in, check_out, status FROM attendance WHERE user_id = $userId AND date = '$today' LIMIT 1");
if ($todayAttRes && $todayAttRes->num_rows > 0) {
    $todayAttendance = $todayAttRes->fetch_assoc();
}

$notifRes = $conn->query("SELECT title, message, created_at, type FROM notifications WHERE user_id = $userId OR user_id = 0 ORDER BY created_at DESC LIMIT 5");
if ($notifRes && $notifRes->num_rows > 0) {
    while ($row = $notifRes->fetch_assoc()) {
        $notifications[] = $row;
    }
}

$userUnreadCount = 0;
$unreadRes = $conn->query("SELECT COUNT(*) FROM notifications WHERE (user_id = $userId OR user_id = 0) AND is_read = 0");
if ($unreadRes) $userUnreadCount = (int) $unreadRes->fetch_row()[0];

$calRes = $conn->query("SELECT DAY(date) AS day, status FROM attendance WHERE user_id = $userId AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($calRes && $calRes->num_rows > 0) {
    while ($row = $calRes->fetch_assoc()) {
        $calendarRecords[(int) $row['day']] = strtolower($row['status']);
    }
}

$leaveRes = $conn->query("SELECT lr.reason, lr.start_date, lr.status FROM leave_requests lr WHERE lr.user_id = $userId ORDER BY lr.start_date DESC LIMIT 5");
if ($leaveRes && $leaveRes->num_rows > 0) {
    while ($row = $leaveRes->fetch_assoc()) {
        $leaveRecords[] = $row;
    }
}

$dashCheckInRaw = $todayAttendance['check_in'] ?? null;
$dashCheckOutRaw = $todayAttendance['check_out'] ?? null;
$dashHasCheckIn = $dashCheckInRaw && $dashCheckInRaw !== '00:00:00';
$dashHasCheckOut = $dashCheckOutRaw && $dashCheckOutRaw !== '00:00:00';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
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
                    "borderRadius": { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
                    "spacing": { "xs": "8px", "lg": "24px", "xl": "32px", "md": "16px", "base": "4px", "gutter": "20px", "sm": "12px", "sidebar-width": "260px" },
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
                }
            }
        }
    </script>
    <link rel="stylesheet" href="../config/dashboard.css"/>
    <link rel="stylesheet" href="../config/theme.css"/>
    <script src="../config/theme.js"></script>
    <script>(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);document.documentElement.className=c?'sidebar-closed':'sidebar-open';})();</script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background overflow-hidden">
<?php $activePage = 'dashboard'; ?>
<?php include __DIR__ . '/includes/sidebar_user.php'; ?>

<!-- Top Navigation Bar -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg z-40 transition-all duration-200">
    <div class="flex items-center gap-lg flex-1">
        <button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
        <div class="flex items-center gap-sm bg-surface-container-low rounded-full px-md py-xs border border-outline-variant">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">schedule</span>
            <span id="liveClock" class="font-data-mono text-data-mono text-on-surface font-bold"></span>
        </div>
    </div>
    <div class="flex items-center gap-md">
        <button class="material-symbols-outlined p-xs rounded-full hover:bg-surface-container-low text-on-surface-variant relative transition-all">
            notifications
            <?php if ($userUnreadCount > 0): ?>
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-error text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1"><?= $userUnreadCount > 99 ? '99+' : $userUnreadCount ?></span>
            <?php endif; ?>
        </button>
    </div>
</header>

<!-- Main Canvas -->
<main id="mainContent" class="pt-16 h-screen overflow-y-auto bg-background">
    <div class="p-lg max-w-[1600px] mx-auto space-y-lg">

        <!-- Page Header -->
        <section>
            <h1 class="font-headline-md text-headline-md text-primary">Welcome back, <?= htmlspecialchars($dbName) ?></h1>
            <p class="text-body-sm text-on-surface-variant mt-xs"><?= $monthName ?> &mdash; <?= date('l, M d, Y') ?></p>
        </section>

        <!-- KPI Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Total Present -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-secondary">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Total Present</span>
                    <span class="bg-secondary/10 text-secondary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-primary"><?= $presentDays ?></span>
                    <span class="text-on-surface-variant font-body-sm text-body-sm">Days</span>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">This month</p>
            </div>

            <!-- Total Leaves -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-error">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Total Leaves</span>
                    <span class="bg-error/10 text-error p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">event_busy</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-primary"><?= $leaveDays ?></span>
                    <span class="text-on-surface-variant font-body-sm text-body-sm">Days</span>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">Approved leaves</p>
            </div>

            <!-- Late Arrivals -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-tertiary-container">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Late Arrivals</span>
                    <span class="bg-tertiary-fixed text-tertiary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-primary"><?= $lateDays ?></span>
                    <span class="text-on-surface-variant font-body-sm text-body-sm">Day<?= $lateDays !== 1 ? 's' : '' ?></span>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">This month</p>
            </div>

            <!-- Working Days -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-primary">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Working Days</span>
                    <span class="bg-primary/10 text-primary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">work_history</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-primary"><?= $workingDays ?></span>
                    <span class="text-on-surface-variant font-body-sm text-body-sm">Days</span>
                </div>
                <?php if ($overtimeHours > 0): ?>
                    <p class="font-body-sm text-body-sm text-error mt-sm font-semibold">+<?= number_format($overtimeHours, 1) ?>h overtime</p>
                <?php else: ?>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">This month</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Main Grid: Today + Notifications -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
            <!-- Today's Attendance -->
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
                    <h3 class="font-headline-sm text-headline-sm text-primary">Today's Attendance</h3>
                    <div class="flex items-center gap-xs text-body-sm text-on-surface-variant bg-surface-container-low px-sm py-xs rounded-lg border border-outline-variant">
                        <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                        <?= date('M d, Y') ?>, <?= date('l') ?>
                    </div>
                </div>
                <div class="p-lg">
                    <?php
                    $attStatus = strtolower($todayAttendance['status'] ?? 'absent');
                    if ($dashHasCheckIn && $dashHasCheckOut) {
                        $statusBadge = 'bg-secondary-container text-on-secondary-container';
                        $statusDot = 'bg-secondary';
                    } elseif ($attStatus === 'late') {
                        $statusBadge = 'bg-error-container text-on-error-container';
                        $statusDot = 'bg-error';
                    } elseif ($dashHasCheckIn) {
                        $statusBadge = 'bg-secondary-container text-on-secondary-container';
                        $statusDot = 'bg-secondary';
                    } else {
                        $statusBadge = 'bg-surface-container-highest text-on-surface-variant';
                        $statusDot = 'bg-outline';
                    }
                    ?>
                    <span class="inline-flex items-center gap-xs px-sm py-0.5 rounded-full <?= $statusBadge ?> text-[11px] font-bold mb-lg">
                        <span class="w-2 h-2 rounded-full <?= $statusDot ?>"></span>
                        <?= htmlspecialchars(ucfirst($todayAttendance['status'] ?? 'Not marked')) ?>
                    </span>

                    <div class="flex flex-col md:flex-row items-center gap-xl">
                        <div class="flex gap-16">
                            <div>
                                <p class="font-label-caps text-label-caps text-on-surface-variant mb-xs">Check-in Time</p>
                                <p class="text-2xl font-bold"><?= htmlspecialchars($dashHasCheckIn ? date('h:i A', strtotime($dashCheckInRaw)) : '-- : --') ?></p>
                            </div>
                            <div>
                                <p class="font-label-caps text-label-caps text-on-surface-variant mb-xs">Check-out Time</p>
                                <p class="text-2xl font-bold <?= $dashHasCheckOut ? '' : 'text-outline' ?>"><?= $dashHasCheckOut ? htmlspecialchars(date('h:i A', strtotime($dashCheckOutRaw))) : '- - : - -' ?></p>
                            </div>
                        </div>

                        <div class="ml-auto">
                            <?php if (!$dashHasCheckIn): ?>
                                <button onclick="dashboardCheckIn()" id="dashCheckinBtn" class="bg-secondary text-on-secondary px-xl py-sm rounded-xl font-label-caps text-label-caps hover:brightness-110 transition-all flex items-center gap-xs shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">login</span>
                                    Check In
                                </button>
                            <?php elseif ($dashHasCheckIn && !$dashHasCheckOut): ?>
                                <button onclick="dashboardCheckOut()" id="dashCheckoutBtn" class="bg-primary text-on-primary px-xl py-sm rounded-xl font-label-caps text-label-caps hover:brightness-110 transition-all flex items-center gap-xs shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    Check Out
                                </button>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-xs px-xl py-sm rounded-xl bg-surface-container-high text-on-surface-variant font-label-caps text-label-caps">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    Completed
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
                    <h3 class="font-headline-sm text-headline-sm text-primary">Notifications</h3>
                </div>
                <div class="divide-y divide-outline-variant">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notif): ?>
                            <div class="px-lg py-md hover:bg-surface-container-low/50 transition-colors">
                                <div class="flex gap-sm">
                                    <span class="material-symbols-outlined text-secondary text-lg mt-0.5">
                                        <?= match(strtolower($notif['type'] ?? 'info')) {
                                            'leave', 'leave_request' => 'event_note',
                                            'password_reset' => 'lock',
                                            'attendance' => 'calendar_today',
                                            'broadcast' => 'campaign',
                                            default => 'info'
                                        } ?>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start gap-xs">
                                            <p class="font-body-sm font-semibold text-on-surface truncate"><?= htmlspecialchars($notif['title']) ?></p>
                                            <span class="text-data-mono text-data-mono text-on-surface-variant whitespace-nowrap"><?= htmlspecialchars(date('M d', strtotime($notif['created_at']))) ?></span>
                                        </div>
                                        <p class="font-body-sm text-on-surface-variant mt-xs line-clamp-2"><?= htmlspecialchars($notif['message']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="px-lg py-xl text-center">
                            <span class="material-symbols-outlined text-outline text-3xl">notifications_off</span>
                            <p class="font-body-sm text-on-surface-variant mt-sm">No notifications</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Calendar + Leave Status -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
            <!-- Attendance Calendar -->
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant">
                    <h3 class="font-headline-sm text-headline-sm text-primary">Attendance Summary</h3>
                </div>
                <div class="p-lg">
                    <div class="grid grid-cols-7 gap-1 text-center">
                        <?php foreach (['S','M','T','W','T','F','S'] as $d): ?>
                            <div class="font-label-caps text-label-caps text-on-surface-variant py-2"><?= $d ?></div>
                        <?php endforeach; ?>
                        <?php
                        $firstDow = (int) date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
                        $totalDays = (int) date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
                        $todayDay = (int) date('j');
                        for ($i = 0; $i < $firstDow; $i++): ?>
                            <div class="py-2 text-body-sm opacity-20">&nbsp;</div>
                        <?php endfor; ?>
                        <?php for ($d = 1; $d <= $totalDays; $d++):
                            $status = $calendarRecords[$d] ?? null;
                            $isToday = ($d === $todayDay);
                            if ($isToday):
                        ?>
                            <div class="py-2 text-body-sm bg-primary text-on-primary font-bold rounded-lg"><?= $d ?></div>
                        <?php elseif ($status === 'present'): ?>
                            <div class="py-2 text-body-sm bg-secondary-container text-on-secondary-container rounded-lg"><?= $d ?></div>
                        <?php elseif ($status === 'late'): ?>
                            <div class="py-2 text-body-sm bg-error-container text-on-error-container rounded-lg"><?= $d ?></div>
                        <?php elseif ($status === 'absent'): ?>
                            <div class="py-2 text-body-sm bg-surface-container-highest text-on-surface-variant rounded-lg"><?= $d ?></div>
                        <?php else: ?>
                            <div class="py-2 text-body-sm"><?= $d ?></div>
                        <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="mt-lg flex flex-wrap gap-md justify-between">
                        <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-secondary"></span><span class="text-body-sm">On Time</span></div>
                        <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-error"></span><span class="text-body-sm">Late</span></div>
                        <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-outline"></span><span class="text-body-sm">Absent</span></div>
                    </div>
                </div>
            </div>

            <!-- Leave Status -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
                    <h3 class="font-headline-sm text-headline-sm text-primary">Leave Status</h3>
                    <a class="text-secondary font-body-sm text-body-sm hover:underline" href="leavestatus.php">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low border-b border-outline-variant">
                            <tr>
                                <th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant">Type</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant">Date</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant font-body-sm text-body-sm">
                            <?php if (!empty($leaveRecords)): ?>
                                <?php foreach ($leaveRecords as $lr): ?>
                                    <?php $ls = strtolower($lr['status']); ?>
                                    <tr>
                                        <td class="px-lg py-md font-semibold"><?= htmlspecialchars(ucfirst($lr['reason'] ?? 'Leave')) ?></td>
                                        <td class="px-lg py-md text-on-surface-variant"><?= htmlspecialchars(date('d M Y', strtotime($lr['start_date']))) ?></td>
                                        <td class="px-lg py-md">
                                            <span class="inline-flex items-center gap-xs px-sm py-0.5 rounded-full text-[11px] font-bold
                                                <?= $ls === 'approved' ? 'bg-secondary-container text-on-secondary-container' :
                                                    ($ls === 'rejected' ? 'bg-error-container text-on-error-container' : 'bg-surface-container-highest text-on-surface-variant') ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?= $ls === 'approved' ? 'bg-secondary' : ($ls === 'rejected' ? 'bg-error' : 'bg-outline') ?>"></span>
                                                <?= htmlspecialchars(ucfirst($lr['status'] ?? 'Pending')) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td class="px-lg py-md text-on-surface-variant" colspan="3">No leave records yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
function updateClock() {
    const now = new Date();
    const el = document.getElementById('liveClock');
    if (el) el.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
}
updateClock();
setInterval(updateClock, 1000);

async function dashboardCheckIn() {
    const btn = document.getElementById('dashCheckinBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">sync</span> Processing...';
    try {
        const form = new FormData();
        form.append('action', 'checkin');
        const res = await fetch('attendance_handler.php', { method: 'POST', body: form });
        const data = await res.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">login</span> Check In';
        }
    } catch (e) {
        alert('Network error');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">login</span> Check In';
    }
}

async function dashboardCheckOut() {
    const btn = document.getElementById('dashCheckoutBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">sync</span> Processing...';
    try {
        const form = new FormData();
        form.append('action', 'checkout');
        const res = await fetch('attendance_handler.php', { method: 'POST', body: form });
        const data = await res.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">logout</span> Check Out';
        }
    } catch (e) {
        alert('Network error');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">logout</span> Check Out';
    }
}
</script>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body>
</html>
