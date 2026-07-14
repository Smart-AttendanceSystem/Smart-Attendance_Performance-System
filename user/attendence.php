<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? 'User';

if ($userId <= 0) {
    header('Location: ../auth/login.php');
    exit;
}

$conn->query("UPDATE `user` SET last_activity = NOW() WHERE id = $userId");

$currentMonth = (int) date('m');
$currentYear = (int) date('Y');
$today = date('Y-m-d');
$monthName = date('F Y');

// --- Attendance stats for current month ---
$presentDays = 0;
$lateDays = 0;
$leaveDays = 0;
$totalWorkingHours = 0;
$overtimeHours = 0;

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) = 'present' AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $presentDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) = 'late' AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $lateDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM leave_requests WHERE user_id = $userId AND LOWER(status) = 'approved' AND MONTH(start_date) = $currentMonth AND YEAR(start_date) = $currentYear");
if ($r) $leaveDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COALESCE(SUM(TIMESTAMPDIFF(MINUTE, check_in, check_out)), 0) FROM attendance WHERE user_id = $userId AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear AND check_out != '00:00:00'");
if ($r) $totalWorkingHours = round((float) $r->fetch_row()[0] / 60, 1);

$r = $conn->query("SELECT COALESCE(SUM(hours), 0) FROM overtime WHERE employee_id = $userId AND MONTH(ot_date) = $currentMonth AND YEAR(ot_date) = $currentYear");
if ($r) $overtimeHours = round((float) $r->fetch_row()[0], 1);

$workingDaysInMonth = $presentDays + $lateDays;
$attendanceRate = $workingDaysInMonth > 0 ? round(($presentDays / max($workingDaysInMonth, 1)) * 100, 1) : 0;

// --- Today's attendance ---
$todayAtt = null;
$todayRes = $conn->query("SELECT check_in, check_out, status FROM attendance WHERE user_id = $userId AND date = '$today' LIMIT 1");
if ($todayRes && $todayRes->num_rows > 0) {
    $todayAtt = $todayRes->fetch_assoc();
}
$hasCheckIn = $todayAtt && $todayAtt['check_in'] && $todayAtt['check_in'] !== '00:00:00';
$hasCheckOut = $todayAtt && $todayAtt['check_out'] && $todayAtt['check_out'] !== '00:00:00';

// --- Monthly attendance history (current month) ---
$historyStmt = $conn->prepare("SELECT date, check_in, check_out, status FROM attendance WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ? ORDER BY date DESC");
$historyStmt->bind_param("iii", $userId, $currentMonth, $currentYear);
$historyStmt->execute();
$historyResult = $historyStmt->get_result();
$attendanceHistory = [];
while ($row = $historyResult->fetch_assoc()) {
    $attendanceHistory[] = $row;
}
$historyStmt->close();

// --- Calendar data (dot colors per day) ---
$calendarData = [];
$calRes = $conn->query("SELECT DAY(date) AS day, status FROM attendance WHERE user_id = $userId AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($calRes && $calRes->num_rows > 0) {
    while ($row = $calRes->fetch_assoc()) {
        $calendarData[(int) $row['day']] = strtolower($row['status']);
    }
}

// --- Late arrivals count for badge ---
$lateBadgeClass = $lateDays > 0 ? 'border-error' : 'border-secondary';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>My Attendance</title>
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
    <script>(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var root=document.documentElement;root.classList.remove('sidebar-open','sidebar-closed');root.classList.add(c?'sidebar-closed':'sidebar-open');})();</script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background">
<?php $activePage = 'attendance'; ?>
<?php include __DIR__ . '/includes/sidebar_user.php'; ?>

<!-- Top Navigation Bar -->
<header id="mainHeader" class="fixed top-0 h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center z-40 transition-all duration-200">
    <div class="flex items-center gap-lg flex-1">
        <div class="flex items-center gap-sm bg-surface-container-low rounded-full px-md py-xs border border-outline-variant">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">schedule</span>
            <span id="liveClock" class="font-data-mono text-data-mono text-on-surface font-bold"></span>
        </div>
    </div>
    
</header>

<!-- Main Canvas -->
<main id="mainContent" class="pt-16 h-screen overflow-y-auto bg-background">
    <div class="p-lg max-w-[1600px] mx-auto space-y-lg">

        <!-- Page Header + Check In/Out -->
        <section class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h1 class="font-headline-md text-headline-md text-primary">My Attendance</h1>
                <p class="text-body-sm text-on-surface-variant mt-xs"><?= $monthName ?> &mdash; <?= date('l, M d, Y') ?></p>
            </div>
            <div id="checkInOutArea">
                <?php if (!$hasCheckIn): ?>
                    <button onclick="doCheckIn()" id="checkinBtn" class="bg-secondary text-on-secondary px-lg py-sm rounded-xl font-label-caps text-label-caps hover:brightness-110 transition-all flex items-center gap-xs shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        Check In
                    </button>
                <?php elseif ($hasCheckIn && !$hasCheckOut): ?>
                    <button onclick="doCheckOut()" id="checkoutBtn" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-caps text-label-caps hover:brightness-110 transition-all flex items-center gap-xs shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Check Out
                    </button>
                <?php else: ?>
                    <span class="inline-flex items-center gap-xs px-lg py-sm rounded-xl bg-surface-container-high text-on-surface-variant font-label-caps text-label-caps">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Completed for today
                    </span>
                <?php endif; ?>
            </div>
        </section>

        <!-- KPI Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Today's Status -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 <?= $lateBadgeClass ?>">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Today's Status</span>
                    <span class="bg-secondary/10 text-secondary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">today</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <?php
                    $statusText = 'Not Marked';
                    $statusColor = 'text-on-surface-variant';
                    if ($hasCheckIn && $hasCheckOut) { $statusText = 'Completed'; $statusColor = 'text-secondary'; }
                    elseif ($hasCheckIn) { $statusText = $todayAtt['status'] ?? 'Present'; $statusColor = strtolower($todayAtt['status'] ?? '') === 'late' ? 'text-error' : 'text-secondary'; }
                    ?>
                    <span class="font-display-lg text-display-lg <?= $statusColor ?>"><?= htmlspecialchars($statusText) ?></span>
                </div>
                <?php if ($hasCheckIn): ?>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">
                        In: <?= date('h:i A', strtotime($todayAtt['check_in'])) ?>
                        <?php if ($hasCheckOut): ?> &middot; Out: <?= date('h:i A', strtotime($todayAtt['check_out'])) ?><?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Attendance Rate -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-secondary">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Attendance Rate</span>
                    <span class="bg-secondary/10 text-secondary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-primary"><?= $attendanceRate ?>%</span>
                </div>
                <div class="w-full bg-surface-container-high h-1.5 rounded-full mt-sm">
                    <div class="bg-secondary h-full rounded-full transition-all duration-500" style="width: <?= min($attendanceRate, 100) ?>%"></div>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs"><?= $presentDays ?> present / <?= $workingDaysInMonth ?> working days</p>
            </div>

            <!-- Working Hours -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-primary">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-surface-variant">Working Hours</span>
                    <span class="bg-primary/10 text-primary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-primary"><?= number_format($totalWorkingHours, 1) ?></span>
                    <span class="text-on-surface-variant font-data-mono text-data-mono">hrs</span>
                </div>
                <?php if ($overtimeHours > 0): ?>
                    <p class="font-body-sm text-body-sm text-error mt-sm font-semibold">+<?= number_format($overtimeHours, 1) ?>h overtime</p>
                <?php else: ?>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">This month</p>
                <?php endif; ?>
            </div>

            <!-- Late & Leaves -->
            <div class="bento-card bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-tertiary-container">
                <div class="flex justify-between items-start mb-xs">
                    <span class="font-label-caps text-label-caps text-on-tertiary-container">Late / Leave</span>
                    <span class="bg-tertiary-fixed text-tertiary p-xs rounded-full">
                        <span class="material-symbols-outlined text-[18px]">event_busy</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-xs">
                    <span class="font-display-lg text-display-lg text-on-tertiary-container"><?= $lateDays ?></span>
                    <span class="text-on-tertiary-container font-body-sm text-body-sm">late</span>
                    <span class="text-on-tertiary-container font-body-sm text-body-sm mx-xs">/</span>
                    <span class="font-display-lg text-display-lg text-on-tertiary-container"><?= $leaveDays ?></span>
                    <span class="text-on-tertiary-container font-body-sm text-body-sm">leave</span>
                </div>
            </div>
        </section>

        <!-- Detailed Records Layout -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
            <!-- Attendance History Table -->
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
                    <h3 class="font-headline-sm text-headline-sm text-primary">Daily Logs &mdash; <?= $monthName ?></h3>
                    <span class="text-body-sm text-on-surface-variant"><?= count($attendanceHistory) ?> records</span>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left zebra-table">
                        <thead class="bg-primary">
                            <tr>
                                <th class="px-lg py-sm font-label-caps text-label-caps uppercase text-on-primary">Date</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps uppercase text-on-primary">Check In</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps uppercase text-on-primary">Check Out</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps uppercase text-on-primary">Hours</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps uppercase text-on-primary">Status</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-sm text-body-sm">
                            <?php if (!empty($attendanceHistory)): ?>
                                <?php foreach ($attendanceHistory as $log): ?>
                                    <?php
                                    $st = strtolower($log['status'] ?? 'present');
                                    if ($st === 'late') {
                                        $badgeCls = 'bg-error-container text-on-error-container';
                                        $dotCls = 'bg-error';
                                        $label = 'Late';
                                    } elseif ($st === 'absent') {
                                        $badgeCls = 'bg-surface-container-highest text-on-surface-variant';
                                        $dotCls = 'bg-outline';
                                        $label = 'Absent';
                                    } else {
                                        $badgeCls = 'bg-secondary-container text-on-secondary-container';
                                        $dotCls = 'bg-secondary';
                                        $label = 'On Time';
                                    }
                                    $ci = $log['check_in'] && $log['check_in'] !== '00:00:00' ? date('h:i A', strtotime($log['check_in'])) : '--:--';
                                    $co = $log['check_out'] && $log['check_out'] !== '00:00:00' ? date('h:i A', strtotime($log['check_out'])) : '--:--';
                                    $hrs = '--';
                                    if ($ci !== '--:--' && $co !== '--:--') {
                                        $mins = strtotime($log['check_out']) - strtotime($log['check_in']);
                                        $hrs = round($mins / 3600, 1) . 'h';
                                    }
                                    ?>
                                    <tr>
                                        <td class="px-lg py-md font-data-mono"><?= date('M d, Y', strtotime($log['date'])) ?></td>
                                        <td class="px-lg py-md"><?= $ci ?></td>
                                        <td class="px-lg py-md"><?= $co ?></td>
                                        <td class="px-lg py-md font-data-mono"><?= $hrs ?></td>
                                        <td class="px-lg py-md">
                                            <span class="inline-flex items-center gap-xs px-sm py-0.5 rounded-full <?= $badgeCls ?> text-[11px] font-bold">
                                                <span class="w-2 h-2 rounded-full <?= $dotCls ?>"></span>
                                                <?= $label ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="px-lg py-md text-on-surface-variant text-center" colspan="5">No records yet this month.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Sidebar: Calendar + Quick Stats -->
            <div class="space-y-lg">
                <!-- Calendar Mini View -->
                <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm">
                    <div class="flex justify-between items-center mb-md">
                        <h3 class="font-headline-sm text-headline-sm text-primary"><?= $monthName ?></h3>
                    </div>
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
                            $status = $calendarData[$d] ?? null;
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

                <!-- Quick Summary Card -->
                <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-5 -translate-y-1/2 translate-x-1/2 rounded-full"></div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-md">Monthly Summary</h3>
                    <div class="space-y-md relative z-10">
                        <div>
                            <div class="flex justify-between items-center mb-xs">
                                <span class="text-body-sm font-medium">Present Days</span>
                                <span class="text-data-mono font-bold text-secondary"><?= $presentDays ?> / <?= $totalDays ?></span>
                            </div>
                            <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                <div class="bg-secondary h-full rounded-full" style="width: <?= round(($presentDays / max($totalDays, 1)) * 100) ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-xs">
                                <span class="text-body-sm font-medium">Late Arrivals</span>
                                <span class="text-data-mono font-bold text-error"><?= $lateDays ?></span>
                            </div>
                            <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                <div class="bg-error h-full rounded-full" style="width: <?= round(($lateDays / max($totalDays, 1)) * 100) ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-xs">
                                <span class="text-body-sm font-medium">Approved Leaves</span>
                                <span class="text-data-mono font-bold text-tertiary-container"><?= $leaveDays ?></span>
                            </div>
                            <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                <div class="bg-tertiary-container h-full rounded-full" style="width: <?= round(($leaveDays / max($totalDays, 1)) * 100) ?>%"></div>
                            </div>
                        </div>
                        <?php if ($overtimeHours > 0): ?>
                        <div>
                            <div class="flex justify-between items-center mb-xs">
                                <span class="text-body-sm font-medium">Overtime</span>
                                <span class="text-data-mono font-bold text-primary"><?= number_format($overtimeHours, 1) ?>h</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
// Live clock
function updateClock() {
    const now = new Date();
    const el = document.getElementById('liveClock');
    if (el) el.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
}
updateClock();
setInterval(updateClock, 1000);

// Check In
async function doCheckIn() {
    const btn = document.getElementById('checkinBtn');
    if (!btn) return;
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
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">login</span> Check In';
    }
}

// Check Out
async function doCheckOut() {
    const btn = document.getElementById('checkoutBtn');
    if (!btn) return;
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
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">logout</span> Check Out';
    }
}

// Table row hover micro-interaction
document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.zebra-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', () => { row.style.backgroundColor = '#82f7d815'; });
        row.addEventListener('mouseleave', () => { row.style.backgroundColor = ''; });
    });
});
</script>
</body>
</html>
