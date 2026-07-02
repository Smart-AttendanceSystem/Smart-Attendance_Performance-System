<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? '';

if ($userId <= 0) {
    header('Location: ../auth/login.php');
    exit;
}

$currentMonth = date('m');
$currentYear = date('Y');
$today = date('Y-m-d');
$todayDay = (int) date('j');
$monthName = date('F Y');
$employeeId = 'YGN-' . str_pad($userId, 4, '0', STR_PAD_LEFT);

$employeeInfo = $conn->query("SELECT u.name, ep.position, ep.avatar FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = $userId");
$empData = $employeeInfo ? $employeeInfo->fetch_assoc() : null;
$empName = $empData['name'] ?? $userName;
$empPosition = $empData['position'] ?? 'Employee';
$empAvatar = $empData['avatar'] ?? '';

// Today's attendance
$todayAttendance = null;
$todayAttRes = $conn->query("SELECT check_in, check_out, status, TIMESTAMPDIFF(MINUTE, check_in, check_out) AS minutes_worked FROM attendance WHERE user_id = $userId AND date = '$today' LIMIT 1");
if ($todayAttRes && $todayAttRes->num_rows > 0) {
    $todayAttendance = $todayAttRes->fetch_assoc();
}

// Monthly summary counts
$presentCount = 0; $absentCount = 0; $lateCount = 0; $halfDayCount = 0;
$summaryRes = $conn->query("SELECT status, COUNT(*) AS cnt FROM attendance WHERE user_id = $userId AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear GROUP BY LOWER(status)");
if ($summaryRes && $summaryRes->num_rows > 0) {
    while ($row = $summaryRes->fetch_assoc()) {
        $s = strtolower($row['status']);
        $c = (int) $row['cnt'];
        if ($s === 'present') $presentCount = $c;
        elseif ($s === 'late') $lateCount = $c;
        elseif ($s === 'absent') $absentCount = $c;
        elseif ($s === 'half day' || $s === 'half-day') $halfDayCount = $c;
    }
}

// Calendar records: day => status
$calendarRecords = [];
$calRes = $conn->query("SELECT DAY(date) AS day, status, check_in, check_out FROM attendance WHERE user_id = $userId AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($calRes && $calRes->num_rows > 0) {
    while ($row = $calRes->fetch_assoc()) {
        $calendarRecords[(int) $row['day']] = $row;
    }
}

// Attendance history (last 30 days)
$historyRows = [];
$histRes = $conn->query("SELECT date, check_in, check_out, status, TIMESTAMPDIFF(MINUTE, check_in, check_out) AS minutes_worked FROM attendance WHERE user_id = $userId ORDER BY date DESC LIMIT 30");
if ($histRes && $histRes->num_rows > 0) {
    while ($row = $histRes->fetch_assoc()) {
        $historyRows[] = $row;
    }
}

function formatHours($mins) {
    if ($mins === null || $mins <= 0) return '00h 00m';
    $h = intdiv($mins, 60);
    $m = $mins % 60;
    return sprintf('%02dh %02dm', $h, $m);
}

function formatTime($t) {
    if (!$t || $t === '00:00:00') return '--:-- --';
    return date('h:i A', strtotime($t));
}
?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Attendance - Smart Attendance System</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
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
<style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .chart-bar { transition: height 1s ease-in-out; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .timer-circle {
            width: 160px; height: 160px; border-radius: 50%;
            background: conic-gradient(#2563eb 0deg, #e2e8f0 0deg);
            display: flex; align-items: center; justify-content: center;
            position: relative; flex-shrink: 0;
        }
        .timer-circle::before {
            content: ''; position: absolute; inset: 6px;
            border-radius: 50%; background: white;
        }
        .timer-circle .inner {
            position: relative; z-index: 1; text-align: center;
        }
        .timer-circle .time {
            font-size: 28px; font-weight: 900; color: #1e293b;
            font-family: 'JetBrains Mono', monospace; letter-spacing: 0.5px;
        }
        .timer-circle .seconds {
            font-size: 14px; font-weight: 600; color: #64748b;
            font-family: 'JetBrains Mono', monospace;
        }
        .timer-circle .label {
            font-size: 10px; color: #94a3b8; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase; margin-top: 2px;
        }
    </style>
</head>
<body class="text-on-surface bg-background">
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>
<!-- Predicted SideNavBar Component -->
<aside id="sidebar" class="fixed left-0 top-0 h-full w-[260px] bg-primary dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col py-lg z-50 overflow-y-auto scrollbar-hide -translate-x-full transition-transform duration-300">
<div class="px-md mb-xl">

<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary tracking-tight">Smart Attendence</h1>

</div>
<nav class="flex-grow space-y-1">
<!-- Dashboard is Active -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="userdashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="attendence.php">
<span class="material-symbols-outlined">schedule</span>
<span class="font-label-caps text-label-caps">Attendence</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaveform.php">
<span class="material-symbols-outlined">event_note</span>
<span class="font-label-caps text-label-caps">Leave Request</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leavestatus.php">
<span class="material-symbols-outlined">assignment_turned_in</span>
<span class="font-label-caps text-label-caps">Leave Status</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="profile.php">
<span class="material-symbols-outlined">person</span>
<span class="font-label-caps text-label-caps">Profile</span>
</a>
</nav>
<div class="mt-auto border-t border-on-primary-fixed-variant/20 pt-lg space-y-1">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="requestpassword.php">
<span class="material-symbols-outlined">lock</span>
<span class="font-label-caps text-label-caps">Change Password</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="userdashboard.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
</div>
</aside>
<!-- END: Sidebar -->
<!-- BEGIN: MainContent -->
<main class="flex-1 md:ml-64 flex flex-col min-h-screen">
<!-- BEGIN: Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10" data-purpose="header">
<div class="flex items-center gap-4">
<button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
<i class="fa-solid fa-bars text-xl"></i>
</button>
<div>
<h1 class="font-bold text-lg text-slate-800">Welcome back, <?= htmlspecialchars(explode(' ', $empName)[0]) ?> 👋</h1>
<p class="text-sm text-gray-500">Employee ID : <?= htmlspecialchars($employeeId) ?></p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="relative">

</div>
<div class="flex items-center gap-3 pl-4 border-l border-gray-200">
<div class="text-right">
<img src="<?= $empAvatar ? '../uploads/avatars/' . htmlspecialchars($empAvatar) : 'https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg' ?>" class="w-8 h-8 rounded-full object-cover" alt="">
<p class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($empName) ?></p>
<p class="text-xs text-gray-500"><?= htmlspecialchars($empPosition) ?></p>
</div>
<i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1"></i>
</div>
</div>
</header>
<!-- END: Header -->
<!-- BEGIN: DashboardBody -->
<div class="p-8 space-y-6">
<!-- Breadcrumb and Title -->
<div class="flex justify-between items-end">
<div>
<h2 class="text-2xl font-bold text-slate-800">Attendence</h2>
<nav class="text-sm text-gray-400 mt-1">
</nav>
</div>
<div class="flex items-center bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
<i class="fa-regular fa-calendar-days text-blue-600 mr-3"></i>
<span class="text-sm font-medium text-slate-700"><?= date('j F Y') ?>, <?= date('l') ?></span>
<i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-3"></i>
</div>
</div>
<!-- Attendance Overview Section -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6" data-purpose="attendance-overview">
<!-- Today's Attendance Card -->
<div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
<div class="p-6 flex items-center justify-between">
<h3 class="font-bold text-slate-800">Today's Attendance</h3>
<?php
$attStatus = strtolower($todayAttendance['status'] ?? 'absent');
$statusBadgeClass = match($attStatus) {
    'present' => 'bg-green-100 text-green-600',
    'late' => 'bg-orange-100 text-orange-600',
    'absent' => 'bg-red-100 text-red-500',
    default => 'bg-slate-100 text-slate-500',
};
$statusLabel = $todayAttendance['status'] ?? 'Not Marked';
$checkIn = $todayAttendance['check_in'] ?? null;
$checkOut = $todayAttendance['check_out'] ?? null;
$hasCheckIn = $checkIn && $checkIn !== '00:00:00';
$hasCheckOut = $checkOut && $checkOut !== '00:00:00';
$minutesWorked = (int) ($todayAttendance['minutes_worked'] ?? 0);
// Overtime this month
$otThisMonth = 0;
$otRes = $conn->query("SELECT COALESCE(SUM(hours), 0) AS total_ot FROM overtime WHERE employee_id = $userId AND MONTH(ot_date) = $currentMonth AND YEAR(ot_date) = $currentYear");
if ($otRes) $otThisMonth = (float) $otRes->fetch_assoc()['total_ot'];
?>
<span class="<?= $statusBadgeClass ?> text-xs font-bold px-3 py-1 rounded-md"><?= htmlspecialchars(ucfirst($statusLabel)) ?></span>
</div>
<div class="px-6 pb-6 flex flex-col items-center">
<!-- Timer Visualization -->
<div class="timer-circle mb-6">
<div class="inner">
<p class="time" id="liveClock"><?= date('h:i A') ?></p>
<p class="seconds" id="liveSeconds"><?= date('s') ?></p>
<p class="label">Current Time</p>
</div>
</div>
<!-- Check-in Info -->
<div class="w-full space-y-4 mb-8">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-500">
<i class="fa-solid fa-calendar-check text-lg"></i>
</div>
<div>
<p class="text-xs text-gray-400">Check-In</p>
<p class="text-sm font-bold text-slate-800"><?= htmlspecialchars(formatTime($checkIn)) ?></p>
</div>
</div>
<p class="text-xs text-slate-400 font-medium"><?= date('j F Y') ?></p>
</div>
<div class="flex items-center justify-between <?= $hasCheckOut ? '' : 'opacity-70' ?>">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-400">
<i class="fa-solid fa-calendar-minus text-lg"></i>
</div>
<div>
<p class="text-xs text-gray-400">Check-Out</p>
<p class="text-sm font-bold text-slate-800"><?= htmlspecialchars(formatTime($checkOut)) ?></p>
</div>
</div>
<p class="text-xs text-slate-400 font-medium italic"><?= $hasCheckOut ? 'Completed' : 'Not Checked Out' ?></p>
</div>
</div>
<!-- Actions -->
<div class="flex gap-4 w-full" id="actionButtons">
<?php if (!$hasCheckIn): ?>
<button onclick="handleCheckIn()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-md shadow-blue-200 transition-all flex items-center justify-center gap-2">
<i class="fa-solid fa-right-to-bracket"></i>
                  Check In
                </button>
<?php elseif (!$hasCheckOut): ?>
<button onclick="handleCheckOut()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-md shadow-green-200 transition-all flex items-center justify-center gap-2">
<i class="fa-solid fa-right-from-bracket"></i>
                  Check Out
                </button>
<?php else: ?>
<button disabled class="flex-1 bg-gray-300 text-gray-500 font-bold py-3 rounded-xl flex items-center justify-center gap-2 cursor-not-allowed">
<i class="fa-solid fa-check-circle"></i>
                  Completed
                </button>
<?php endif; ?>
</div>
</div>
<div class="mt-auto p-4 border-t border-gray-50 bg-gray-50/50 rounded-b-2xl flex justify-between px-6">
<span class="text-sm text-slate-500 font-medium">Working Hours</span>
<span class="text-sm text-slate-800 font-bold"><?= formatHours($minutesWorked) ?></span>
</div>
<?php if ($otThisMonth > 0): ?>
<div class="px-6 pb-4 -mt-2">
<span class="text-xs text-orange-600 font-semibold">Overtime this month: <?= number_format($otThisMonth, 1) ?> hrs</span>
</div>
<?php endif; ?>
</div>
<!-- Attendance Summary Calendar Card -->
<div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
<div class="p-6">
<h3 class="font-bold text-slate-800">Attendance Summary <span class="text-gray-400 font-normal">(<?= $monthName ?>)</span></h3>
</div>
<div class="px-6">
<!-- Summary Grid -->
<div class="grid grid-cols-4 gap-4 mb-8">
<!-- Present -->
<div class="bg-green-50/50 border border-green-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-calendar-check"></i>
</div>
<p class="text-2xl font-black text-slate-800"><?= $presentCount ?></p>
<p class="text-xs text-green-600 font-bold uppercase">Present</p>
</div>
<!-- Absent -->
<div class="bg-red-50/50 border border-red-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-calendar-xmark"></i>
</div>
<p class="text-2xl font-black text-slate-800"><?= $absentCount ?></p>
<p class="text-xs text-red-500 font-bold uppercase">Absent</p>
</div>
<!-- Late -->
<div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-clock"></i>
</div>
<p class="text-2xl font-black text-slate-800"><?= $lateCount ?></p>
<p class="text-xs text-orange-500 font-bold uppercase">Late</p>
</div>
<!-- Half Day -->
<div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-calendar"></i>
</div>
<p class="text-2xl font-black text-slate-800"><?= $halfDayCount ?></p>
<p class="text-xs text-blue-500 font-bold uppercase">Half Day</p>
</div>
</div>
<!-- Calendar Representation -->
<div class="border border-gray-100 rounded-xl p-6" data-purpose="monthly-calendar">
<div class="grid grid-cols-7 text-center text-xs font-bold text-gray-400 mb-4 uppercase tracking-wider">
<div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
</div>
<div class="grid grid-cols-7 gap-y-4 text-center">
<?php
$firstDow = (int) date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
$totalDays = (int) date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
for ($i = 0; $i < $firstDow; $i++):
    $prevDay = (int) date('t', mktime(0, 0, 0, $currentMonth - 1, 1, $currentYear)) - $firstDow + 1 + $i;
?>
<div class="text-sm text-gray-300"><?= $prevDay ?></div>
<?php endfor; ?>
<?php for ($d = 1; $d <= $totalDays; $d++):
    $rec = $calendarRecords[$d] ?? null;
    $status = $rec ? strtolower($rec['status']) : null;
    $isToday = ($d === $todayDay);
    if ($isToday):
        $todayColor = match($status) {
            'present' => 'bg-green-600',
            'late' => 'bg-orange-500',
            'absent' => 'bg-red-500',
            default => 'bg-green-600',
        };
?>
<div class="relative flex items-center justify-center">
<span class="w-8 h-8 <?= $todayColor ?> text-white rounded-full flex items-center justify-center text-sm font-bold z-10"><?= $d ?></span>
</div>
<?php elseif ($status): ?>
<div class="text-sm font-medium text-slate-800 relative flex items-center justify-center">
<span><?= $d ?></span>
<span class="absolute -bottom-1 w-1.5 h-1.5 <?= match($status) { 'present' => 'bg-green-500', 'late' => 'bg-orange-500', 'absent' => 'bg-red-400', default => '' } ?> rounded-full"></span>
</div>
<?php else: ?>
<div class="text-sm <?= $d <= $todayDay ? 'text-slate-800 font-medium' : 'text-gray-300' ?>"><?= $d ?></div>
<?php endif; ?>
<?php endfor; ?>
<?php
$rem = 7 - (($firstDow + $totalDays) % 7);
if ($rem < 7):
    for ($i = 1; $i <= $rem; $i++):
?>
<div class="text-sm text-gray-300"><?= $i ?></div>
<?php endfor;
endif; ?>
</div>
</div>
</div>
<!-- Legend -->
<div class="mt-auto flex justify-center gap-6 py-6 border-t border-gray-50">
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-green-600 rounded-full"></span> Present
              </div>
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> Absent
              </div>
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-orange-500 rounded-full"></span> Late
              </div>
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Half Day
              </div>
</div>
</div>
</div>
<!-- Attendance History Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-purpose="attendance-history">
<div class="p-6 flex items-center justify-between border-b border-gray-50">
<h3 class="font-bold text-slate-800 text-lg">Attendance History</h3>
<button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
<i class="fa-solid fa-sliders text-blue-600"></i>
              Filters
            </button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead>
<tr class="bg-gray-50/50 text-[11px] font-black text-gray-400 uppercase tracking-widest">
<th class="px-6 py-4">Date</th>
<th class="px-6 py-4">Check-In</th>
<th class="px-6 py-4">Check-Out</th>
<th class="px-6 py-4">Working Hours</th>
<th class="px-6 py-4">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-50 text-sm">
<?php if (!empty($historyRows)): ?>
<?php foreach ($historyRows as $h): ?>
<?php
$hStatus = strtolower($h['status'] ?? '');
$hIn = $h['check_in'] ?? null;
$hOut = $h['check_out'] ?? null;
$hMins = (int) ($h['minutes_worked'] ?? 0);
$isWeekend = in_array((int) date('w', strtotime($h['date'])), [0, 6]);
$badgeClass = match(true) {
    $isWeekend => 'bg-blue-50 text-blue-500 border border-blue-100',
    $hStatus === 'present' => 'bg-green-100 text-green-600',
    $hStatus === 'late' => 'bg-orange-100 text-orange-600',
    $hStatus === 'absent' || (!$hIn && !$isWeekend) => 'bg-red-100 text-red-500',
    default => 'bg-slate-100 text-slate-500',
};
$badgeLabel = $isWeekend ? 'Weekly Off' : (ucfirst($h['status'] ?? 'Absent'));
$inClass = $hStatus === 'late' ? 'text-orange-600' : ($hIn ? 'text-slate-800' : 'text-gray-400 italic');
?>
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 font-medium <?= $isWeekend ? 'text-slate-400' : 'text-slate-700' ?>"><?= date('j F Y (D)', strtotime($h['date'])) ?></td>
<td class="px-6 py-4 font-bold <?= $inClass ?>"><?= $hIn ? formatTime($hIn) : '--:-- --' ?></td>
<td class="px-6 py-4 <?= $hOut && $hOut !== '00:00:00' ? 'font-bold text-slate-800' : 'text-gray-400 italic' ?>"><?= $hOut && $hOut !== '00:00:00' ? formatTime($hOut) : '--:-- --' ?></td>
<td class="px-6 py-4 <?= $hMins > 0 ? '' : 'text-gray-400' ?>"><?= formatHours($hMins) ?></td>
<td class="px-6 py-4">
<span class="text-[10px] font-bold px-2.5 py-1 rounded-md <?= $badgeClass ?>"><?= $badgeLabel ?></span>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td class="px-6 py-4 text-gray-400" colspan="5">No attendance records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
<!-- END: DashboardBody -->
</main>
<!-- END: MainContent -->
</div>
<!-- END: MainContainer -->
<script>
function updateClock() {
    const now = new Date();
    let h = now.getHours();
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('liveClock').textContent = h + ':' + m + ' ' + ampm;
    document.getElementById('liveSeconds').textContent = s;
    const deg = (now.getSeconds() / 60) * 360;
    const circle = document.querySelector('.timer-circle');
    if (circle) {
        circle.style.background = 'conic-gradient(#2563eb ' + deg + 'deg, #e2e8f0 ' + deg + 'deg)';
    }
}
setInterval(updateClock, 1000);
updateClock();

async function handleCheckIn() {
    const btn = document.querySelector('#actionButtons button');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
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
            btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Check In';
        }
    } catch (e) {
        alert('Network error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Check In';
    }
}

async function handleCheckOut() {
    const btn = document.querySelector('#actionButtons button');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
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
            btn.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i> Check Out';
        }
    } catch (e) {
        alert('Network error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i> Check Out';
    }
}
</script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const main = document.querySelector('main');
    const isOpen = sidebar.classList.contains('translate-x-0');
    if (isOpen) {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        if (main) main.style.marginLeft = '0';
    } else {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (overlay) overlay.classList.remove('hidden');
        if (main) main.style.marginLeft = '';
    }
}
function setSidebarState() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const main = document.querySelector('main');
    if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (main) main.style.marginLeft = '';
    } else {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        if (main) main.style.marginLeft = '0';
    }
    if (overlay) overlay.classList.add('hidden');
}
setSidebarState();
window.addEventListener('resize', setSidebarState);
</script>
</body></html>