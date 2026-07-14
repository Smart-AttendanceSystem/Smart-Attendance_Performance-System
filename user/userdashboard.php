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

$today = date('Y-m-d');
$currentMonth = (int) date('m');
$currentYear = (int) date('Y');
$monthName = date('F Y');
$dayName = date('l');
$monthDayYear = date('M d, Y');

// --- Today's attendance ---
$todayAtt = null;
$todayRes = $conn->query("SELECT check_in, check_out, status FROM attendance WHERE user_id = $userId AND date = '$today' LIMIT 1");
if ($todayRes && $todayRes->num_rows > 0) {
    $todayAtt = $todayRes->fetch_assoc();
}
$hasCheckIn = $todayAtt && $todayAtt['check_in'] && $todayAtt['check_in'] !== '00:00:00';
$hasCheckOut = $todayAtt && $todayAtt['check_out'] && $todayAtt['check_out'] !== '00:00:00';

// --- Monthly stats ---
$presentDays = 0;
$lateDays = 0;
$leaveDays = 0;

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) = 'present' AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $presentDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM attendance WHERE user_id = $userId AND LOWER(status) = 'late' AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($r) $lateDays = (int) $r->fetch_row()[0];

$r = $conn->query("SELECT COUNT(*) FROM leave_requests WHERE user_id = $userId AND LOWER(status) = 'approved' AND MONTH(start_date) = $currentMonth AND YEAR(start_date) = $currentYear");
if ($r) $leaveDays = (int) $r->fetch_row()[0];

$workingDays = $presentDays + $lateDays;

// --- Notifications ---
$notifications = [];
$notifCount = 0;
$notifTable = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($notifTable && $notifTable->num_rows > 0) {
    $unreadRes = $conn->query("SELECT COUNT(*) FROM notifications WHERE user_id = $userId AND is_read = 0");
    if ($unreadRes) $notifCount = (int) $unreadRes->fetch_row()[0];

    $notifResult = $conn->query("SELECT id, title, message, type, created_at FROM notifications WHERE user_id = $userId OR user_id = 0 ORDER BY created_at DESC LIMIT 5");
    if ($notifResult && $notifResult->num_rows > 0) {
        while ($row = $notifResult->fetch_assoc()) {
            $notifications[] = $row;
        }
    }
}

// --- Leave requests (recent 4) ---
$hasLeaveType = $conn->query("SHOW COLUMNS FROM leave_requests LIKE 'leave_type'")->num_rows > 0;
$typeField = $hasLeaveType ? 'lr.leave_type' : "'Leave'";
$leaveRequests = [];
$lrRes = $conn->query("SELECT $typeField AS leave_type, lr.start_date, lr.end_date, lr.status FROM leave_requests lr WHERE lr.user_id = $userId ORDER BY lr.id DESC LIMIT 4");
if ($lrRes && $lrRes->num_rows > 0) {
    while ($row = $lrRes->fetch_assoc()) {
        $leaveRequests[] = $row;
    }
}

// --- Calendar data (dot colors per day) ---
$calendarData = [];
$calRes = $conn->query("SELECT DAY(date) AS day, status FROM attendance WHERE user_id = $userId AND MONTH(date) = $currentMonth AND YEAR(date) = $currentYear");
if ($calRes && $calRes->num_rows > 0) {
    while ($row = $calRes->fetch_assoc()) {
        $calendarData[(int) $row['day']] = strtolower($row['status']);
    }
}

// --- User display name initial ---
$userInitial = strtoupper(substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Smart Attendance Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
<link rel="stylesheet" href="../config/dashboard.css"/>
<link rel="stylesheet" href="../config/theme.css"/>
<script src="../config/theme.js"></script>
<script>
(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var r=document.documentElement;r.classList.remove('sidebar-open','sidebar-closed');r.classList.add(c?'sidebar-closed':'sidebar-open');})();
</script>
<style>
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
</style>
</head>
<body class="text-on-surface bg-background">
<?php $activePage = 'dashboard'; ?>
<?php include __DIR__ . '/includes/sidebar_user.php'; ?>

<!-- Top Bar -->
<header id="mainHeader"
    class="fixed top-0 h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center z-40 transition-all duration-200">
    <div class="flex items-center gap-lg flex-1">
        <div class="flex items-center gap-sm bg-surface-container-low rounded-full px-md py-xs border border-outline-variant">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">schedule</span>
            <span id="liveClock" class="font-data-mono text-data-mono text-on-surface font-bold"></span>
        </div>
    </div>
    <div class="flex items-center gap-6 pr-5">
        <div class="relative">
            <button onclick="toggleNotifDropdown()" class="p-2 text-slate-400 hover:bg-slate-50 rounded-full relative">
                <span class="material-symbols-outlined">notifications</span>
                <?php if ($notifCount > 0): ?>
                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full text-[8px] text-white flex items-center justify-center font-bold"><?= $notifCount > 9 ? '9+' : $notifCount ?></span>
                <?php endif; ?>
            </button>
            <div id="notifDropdown" class="hidden absolute right-0 top-12 w-80 bg-white rounded-xl shadow-xl border border-slate-100 z-50 max-h-96 overflow-y-auto">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                    <span class="text-sm font-bold">Notifications</span>
                    <?php if ($notifCount > 0): ?>
                    <span class="text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full font-bold"><?= $notifCount ?> NEW</span>
                    <?php endif; ?>
                </div>
                <div class="divide-y divide-slate-50">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <?php
                            $notifType = $notification['type'] ?? 'info';
                            if ($notifType === 'broadcast' || $notifType === 'success') {
                                $notifIcon = 'check_circle';
                                $notifBg = 'bg-green-50';
                                $notifIconColor = 'text-green-500';
                            } elseif ($notifType === 'warning') {
                                $notifIcon = 'warning';
                                $notifBg = 'bg-orange-50';
                                $notifIconColor = 'text-orange-500';
                            } else {
                                $notifIcon = 'info';
                                $notifBg = 'bg-blue-50';
                                $notifIconColor = 'text-blue-500';
                            }
                            ?>
                            <div class="flex gap-3 p-3 hover:bg-slate-50 transition-colors">
                                <div class="w-8 h-8 <?= $notifBg ?> rounded-full flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined <?= $notifIconColor ?> text-sm"><?= $notifIcon ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate"><?= htmlspecialchars($notification['title']) ?></p>
                                    <p class="text-[11px] text-gray-500 truncate"><?= htmlspecialchars($notification['message']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-xs text-gray-400">No notifications</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-sm font-bold"><?= htmlspecialchars($userInitial) ?></p>
                <p class="text-[10px] text-text-muted uppercase tracking-wider">Employee</p>
            </div>
            <img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
            <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
        </div>
    </div>
</header>

<!-- Main Content -->
<main id="mainContent" class="pt-16 p-10 space-y-8">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Present -->
        <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-green-500 text-3xl font-light">calendar_today</span>
            </div>
            <div>
                <p class="text-text-muted text-xs font-medium">Total Present</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold"><?= $presentDays ?></span>
                    <span class="text-sm font-semibold">Days</span>
                </div>
                <p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
            </div>
        </div>
        <!-- Card 2: Total Leaves -->
        <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-500 text-3xl font-light">event_busy</span>
            </div>
            <div>
                <p class="text-text-muted text-xs font-medium">Total Leaves</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold"><?= $leaveDays ?></span>
                    <span class="text-sm font-semibold">Days</span>
                </div>
                <p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
            </div>
        </div>
        <!-- Card 3: Late Arrivals -->
        <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-500 text-3xl font-light">schedule</span>
            </div>
            <div>
                <p class="text-text-muted text-xs font-medium">Late Arrivals</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold"><?= $lateDays ?></span>
                    <span class="text-sm font-semibold"><?= $lateDays === 1 ? 'Day' : 'Days' ?></span>
                </div>
                <p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
            </div>
        </div>
        <!-- Card 4: Working Days -->
        <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-500 text-3xl font-light">work_history</span>
            </div>
            <div>
                <p class="text-text-muted text-xs font-medium">Working Days</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold"><?= $workingDays ?></span>
                    <span class="text-sm font-semibold">Days</span>
                </div>
                <p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Section -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Today's Attendance -->
        <div class="col-span-12 lg:col-span-7 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="flex justify-between items-start mb-8">
                <h3 class="text-lg font-bold">Today's Attendance</h3>
                <div class="flex items-center gap-2 text-text-muted text-xs bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                    <span class="material-symbols-outlined text-sm">calendar_month</span>
                    <?= $monthName ?> <?= date('d') ?>, <?= $dayName ?>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-12 relative z-10">
                <div class="flex flex-col gap-8 w-full md:w-auto">
                    <div>
                        <?php if ($hasCheckIn): ?>
                            <?php
                            $statusText = 'Present';
                            $statusColor = 'bg-green-100 text-green-700';
                            if ($hasCheckOut) {
                                $statusText = 'Completed';
                                $statusColor = 'bg-blue-100 text-blue-700';
                            } elseif (strtolower($todayAtt['status'] ?? '') === 'late') {
                                $statusText = 'Late';
                                $statusColor = 'bg-orange-100 text-orange-700';
                            }
                            ?>
                            <span class="inline-block px-4 py-1.5 <?= $statusColor ?> text-[10px] font-bold rounded-lg uppercase tracking-wider mb-6"><?= $statusText ?></span>
                        <?php else: ?>
                            <span class="inline-block px-4 py-1.5 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-lg uppercase tracking-wider mb-6">Not Marked</span>
                        <?php endif; ?>
                        <div class="flex gap-16">
                            <div>
                                <p class="text-text-muted text-[10px] font-bold uppercase mb-1">Check-in Time</p>
                                <p class="text-2xl font-bold"><?= $hasCheckIn ? date('h:i A', strtotime($todayAtt['check_in'])) : '- - : - -' ?></p>
                            </div>
                            <div>
                                <p class="text-text-muted text-[10px] font-bold uppercase mb-1">Check-out Time</p>
                                <p class="text-2xl font-bold <?= $hasCheckOut ? '' : 'text-slate-300' ?>"><?= $hasCheckOut ? date('h:i A', strtotime($todayAtt['check_out'])) : '- - : - -' ?></p>
                            </div>
                        </div>
                    </div>
                    <div id="checkInOutArea">
                        <?php if (!$hasCheckIn): ?>
                            <button onclick="doCheckIn()" id="checkinBtn" class="bg-secondary hover:bg-secondary/90 text-white px-10 py-4 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-green-200">
                                <span class="material-symbols-outlined">login</span>
                                Check In
                            </button>
                        <?php elseif ($hasCheckIn && !$hasCheckOut): ?>
                            <button onclick="doCheckOut()" id="checkoutBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-200">
                                <span class="material-symbols-outlined">logout</span>
                                Check Out
                            </button>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-2 bg-green-50 text-green-600 px-10 py-4 rounded-xl font-bold">
                                <span class="material-symbols-outlined">check_circle</span>
                                Completed for today
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="col-span-12 lg:col-span-5 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold">Recent Notifications</h3>
                <span class="text-xs font-semibold text-slate-400"><?= count($notifications) ?> New</span>
            </div>
            <div class="space-y-4">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <?php
                        $notifType = $notification['type'] ?? 'info';
                        if ($notifType === 'broadcast' || $notifType === 'success') {
                            $notifIcon = 'check_circle';
                            $notifBg = 'bg-green-50';
                            $notifIconColor = 'text-green-500';
                        } elseif ($notifType === 'warning') {
                            $notifIcon = 'warning';
                            $notifBg = 'bg-orange-50';
                            $notifIconColor = 'text-orange-500';
                        } else {
                            $notifIcon = 'info';
                            $notifBg = 'bg-blue-50';
                            $notifIconColor = 'text-blue-500';
                        }
                        $timeAgo = '';
                        $created = $notification['created_at'] ?? '';
                        if ($created) {
                            $diff = time() - strtotime($created);
                            if ($diff < 60) $timeAgo = 'Just now';
                            elseif ($diff < 3600) $timeAgo = floor($diff / 60) . 'm ago';
                            elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . 'h ago';
                            else $timeAgo = date('M d', strtotime($created));
                        }
                        ?>
                        <div class="flex gap-4 p-4 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-colors">
                            <div class="w-12 h-12 <?= $notifBg ?> rounded-full flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined <?= $notifIconColor ?>"><?= $notifIcon ?></span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm font-bold"><?= htmlspecialchars($notification['title']) ?></p>
                                    <span class="text-[10px] text-text-muted"><?= $timeAgo ?></span>
                                </div>
                                <p class="text-xs text-text-muted mt-1"><?= htmlspecialchars($notification['message']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-sm text-text-muted">No recent notifications.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Attendance Summary (Calendar View) -->
        <div class="col-span-12 lg:col-span-7 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-lg font-bold">Attendance Summary</h3>
                <span class="flex items-center gap-2 px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium">
                    <?= $monthName ?>
                </span>
            </div>
            <div class="grid grid-cols-7 text-center border-b border-slate-100 pb-4 mb-4">
                <span class="text-[10px] font-bold text-text-muted uppercase">Sun</span>
                <span class="text-[10px] font-bold text-text-muted uppercase">Mon</span>
                <span class="text-[10px] font-bold text-text-muted uppercase">Tue</span>
                <span class="text-[10px] font-bold text-text-muted uppercase">Wed</span>
                <span class="text-[10px] font-bold text-text-muted uppercase">Thu</span>
                <span class="text-[10px] font-bold text-text-muted uppercase">Fri</span>
                <span class="text-[10px] font-bold text-text-muted uppercase">Sat</span>
            </div>
            <div class="grid grid-cols-7 gap-y-6 text-center">
                <?php
                $firstDow = (int) date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
                $totalDays = (int) date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
                $todayDay = (int) date('j');

                // Previous month trailing days
                $prevMonth = $currentMonth === 1 ? 12 : $currentMonth - 1;
                $prevYear = $currentMonth === 1 ? $currentYear - 1 : $currentYear;
                $prevMonthDays = (int) date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
                for ($i = 0; $i < $firstDow; $i++):
                    $pDay = $prevMonthDays - $firstDow + 1 + $i;
                ?>
                    <span class="text-slate-300 text-sm"><?= $pDay ?></span>
                <?php endfor; ?>

                <?php for ($d = 1; $d <= $totalDays; $d++):
                    $status = $calendarData[$d] ?? null;
                    $isToday = ($d === $todayDay);
                ?>
                    <?php if ($isToday): ?>
                        <div class="flex flex-col items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full mx-auto">
                            <span class="text-sm font-bold"><?= $d ?></span>
                        </div>
                    <?php elseif ($status === 'present'): ?>
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-bold"><?= $d ?></span>
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mt-1"></span>
                        </div>
                    <?php elseif ($status === 'late'): ?>
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-bold"><?= $d ?></span>
                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mt-1"></span>
                        </div>
                    <?php elseif ($status === 'absent'): ?>
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-bold"><?= $d ?></span>
                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full mt-1"></span>
                        </div>
                    <?php else: ?>
                        <span class="text-sm font-bold"><?= $d ?></span>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php
                // Next month leading days
                $totalCells = $firstDow + $totalDays;
                $remaining = ($totalCells % 7 === 0) ? 0 : 7 - ($totalCells % 7);
                for ($i = 1; $i <= $remaining; $i++):
                ?>
                    <span class="text-slate-300 text-sm"><?= $i ?></span>
                <?php endfor; ?>
            </div>
            <div class="mt-10 flex gap-6 justify-center">
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span><span class="text-[10px] text-text-muted font-bold">Present</span></div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span><span class="text-[10px] text-text-muted font-bold">Late</span></div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-400"></span><span class="text-[10px] text-text-muted font-bold">Absent</span></div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-slate-300"></span><span class="text-[10px] text-text-muted font-bold">No Data</span></div>
            </div>
        </div>

        <!-- Leave Status Table -->
        <div class="col-span-12 lg:col-span-5 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold">Leave Status</h3>
                <a class="text-blue-500 text-xs font-semibold hover:underline" href="leavestatus.php">View All</a>
            </div>
            <div class="overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-text-muted uppercase font-bold border-b border-slate-50">
                            <th class="py-4 pb-2">Type</th>
                            <th class="py-4 pb-2">Date</th>
                            <th class="py-4 pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (!empty($leaveRequests)): ?>
                            <?php foreach ($leaveRequests as $lr): ?>
                                <?php
                                $lrStatus = strtolower($lr['status'] ?? 'pending');
                                if ($lrStatus === 'approved') {
                                    $lrBadge = 'bg-green-50 text-green-600';
                                    $lrLabel = 'Approved';
                                } elseif ($lrStatus === 'rejected') {
                                    $lrBadge = 'bg-red-50 text-red-500';
                                    $lrLabel = 'Rejected';
                                } else {
                                    $lrBadge = 'bg-orange-50 text-orange-500';
                                    $lrLabel = 'Pending';
                                }
                                $lrDate = $lr['start_date'] ?? '';
                                ?>
                                <tr>
                                    <td class="py-4 text-xs font-bold"><?= htmlspecialchars($lr['leave_type'] ?? 'Leave') ?></td>
                                    <td class="py-4 text-xs text-text-muted"><?= $lrDate ? date('d M Y', strtotime($lrDate)) : '-' ?></td>
                                    <td class="py-4 text-[10px]">
                                        <span class="<?= $lrBadge ?> px-3 py-1 rounded-lg font-bold"><?= $lrLabel ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="py-8 text-xs text-text-muted text-center" colspan="3">No leave requests yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
// Notification dropdown
function toggleNotifDropdown() {
    const dropdown = document.getElementById('notifDropdown');
    dropdown.classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    const btn = e.target.closest('button');
    if (!btn || !btn.onclick || !btn.onclick.toString().includes('toggleNotifDropdown')) {
        if (!e.target.closest('#notifDropdown')) {
            dropdown.classList.add('hidden');
        }
    }
});

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
            btn.innerHTML = '<span class="material-symbols-outlined">login</span> Check In';
        }
    } catch (e) {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">login</span> Check In';
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
            btn.innerHTML = '<span class="material-symbols-outlined">logout</span> Check Out';
        }
    } catch (e) {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">logout</span> Check Out';
    }
}
</script>
</body>
</html>
