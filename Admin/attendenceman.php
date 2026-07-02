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

// Today's date
$today = date('Y-m-d');

// Present today
$presentStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'present'");
$presentStmt->bind_param('s', $today);
$presentStmt->execute();
$presentToday = (int) $presentStmt->get_result()->fetch_assoc()['cnt'];

// Late today
$lateStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'late'");
$lateStmt->bind_param('s', $today);
$lateStmt->execute();
$lateToday = (int) $lateStmt->get_result()->fetch_assoc()['cnt'];

// Absent today (recorded as absent)
$absentStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'absent'");
$absentStmt->bind_param('s', $today);
$absentStmt->execute();
$absentToday = (int) $absentStmt->get_result()->fetch_assoc()['cnt'];

// Also count users with no attendance record today as absent
$totalUsers = (int) $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee'")->fetch_row()[0];
$totalAttendedToday = $presentToday + $lateToday + $absentToday;
if ($totalUsers > $totalAttendedToday) {
    $absentToday += ($totalUsers - $totalAttendedToday);
}

// Daily attendance log
$logResult = $conn->query("SELECT u.id, u.name, a.date, a.check_in, a.check_out, a.status FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = CURDATE() ORDER BY a.check_in ASC");
$attendanceLog = [];
if ($logResult && $logResult->num_rows > 0) {
    while ($row = $logResult->fetch_assoc()) {
        $attendanceLog[] = $row;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Attendance Management</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c4c6cd; border-radius: 10px; }
    </style>
</head>
<body class="bg-background text-on-background overflow-hidden">
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>
<!-- SideNavBar (Shared Component) -->
<aside id="sidebar" class="fixed left-0 top-0 h-full w-[260px] bg-primary dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col py-lg z-50 -translate-x-full transition-transform duration-300">
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary tracking-tight"><?= htmlspecialchars($adminName) ?></h1>
<p class="font-label-caps text-label-caps text-on-primary opacity-80 mt-base uppercase">HR Management System</p>
</div>
<nav class="flex-1 space-y-base px-sm overflow-y-auto custom-scrollbar">
<!-- Dashboard -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<!-- Employees -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="employee_management.php">
<span class="material-symbols-outlined">groups</span>
<span class="font-label-caps text-label-caps">Employees</span>
</a>
<!-- Departments -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="department_management.php">
<span class="material-symbols-outlined">domain</span>
<span class="font-label-caps text-label-caps">Departments</span>
</a>
<!-- Attendance (ACTIVE) -->
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95" href="attendenceman.php">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">fact_check</span>
<span class="font-label-caps text-label-caps">Attendance</span>
</a>
<!-- Leave Requests -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaverequest.php">
<span class="material-symbols-outlined">event_busy</span>
<span class="font-label-caps text-label-caps">Leave Requests</span>
</a>
</nav>
<div class="mt-auto px-sm border-t border-white/10 pt-lg">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95 mb-base" href="admin_setting.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-caps text-label-caps">Settings</span>
</a>
<div class="flex items-center gap-md px-md py-sm mt-md">
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-secondary/30">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" alt="<?= htmlspecialchars($adminName) ?>" src="<?= $adminAvatarDisplay ?>"/>
</div>
<div class="flex flex-col">
<span class="font-body-sm text-on-primary font-semibold"><?= htmlspecialchars($adminName) ?></span>
<span class="font-label-caps text-[10px] text-on-primary-container/70">Admin Access</span>
</div>
<button class="ml-auto text-on-primary-fixed-variant hover:text-error transition-colors">
<a class="material-symbols-outlined" href="../auth/logout.php">logout</a>
</button>
</div>
</div>
</aside>
<!-- TopNavBar (Shared Component) -->
<header class="fixed top-0 right-0 w-full md:w-[calc(100%-260px)] h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg h-16 z-40">

<div class="flex items-center gap-lg">
<button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
<div class="flex gap-sm">
<div>
<h2 class="font-headline-md text-headline-md text-primary">Attendance Management</h2>
<p class="font-body-md text-on-surface-variant">Real-time monitoring of daily employee presence and punctuality.</p>
</div>
</div>
</div>
</header>
<!-- Main Canvas -->
<main class="md:ml-[260px] pt-16 h-screen overflow-y-auto bg-background">
<div class="p-lg max-w-[1600px] mx-auto space-y-lg">
<!-- Page Header & Filter Controls -->
<section class="flex flex-col md:flex-row md:items-end justify-between gap-md   ">

<div class="flex items-center gap-sm bg-surface-container-lowest p-xs rounded-xl shadow-sm border border-outline-variant"><div class="flex flex-col px-sm border-r border-outline-variant">
<label class="font-label-caps text-label-caps text-outline uppercase">Search</label>
<input id="attendance-search" class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent w-48" placeholder="Username or ID" type="text">
</div>
<div class="flex flex-col px-sm">
<label class="font-label-caps text-label-caps text-outline uppercase">Date Range</label>
<input id="attendance-date" class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent" type="date">
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
<option value="no">No Overtime</option>
</select>
</div>
</div>
</section>
<!-- KPI Cards - Summary Stats -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- Total Present -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-secondary">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Present Today</span>
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
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider text-right">Actions</th>
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
<tr class="hover:bg-secondary/5 transition-colors group">
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
<td class="px-lg py-md font-data-mono text-outline">--:--</td>
<td class="px-lg py-md">
<span class="<?= $badgeClass ?> px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span>
<?= htmlspecialchars(ucfirst($log['status'] ?? 'Present')) ?>
</span>
</td>
<td class="px-lg py-md text-right">
<button class="opacity-0 group-hover:opacity-100 text-outline hover:text-secondary transition-all">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 0;">edit_note</span>
</button>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-lg py-md text-body-sm text-on-surface-variant text-center" colspan="8">No attendance records found for today.</td>
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
            const controls = [
                document.querySelector('#attendance-search'),
                document.querySelector('#attendance-date'),
                document.querySelector('#attendance-department'),
                document.querySelector('#attendance-overtime')
            ];
            controls.forEach(control => {
                if (control) control.addEventListener('input', filterAttendanceRows);
            });
            filterAttendanceRows();
        });
    </script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const main = document.querySelector('main');
    const header = document.querySelector('header');
    const isOpen = sidebar.classList.contains('translate-x-0');
    if (isOpen) {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        if (main) main.style.marginLeft = '0';
        if (header) header.style.width = '100%';
    } else {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (overlay) overlay.classList.remove('hidden');
        if (main) main.style.marginLeft = '';
        if (header) header.style.width = '';
    }
}
function setSidebarState() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const main = document.querySelector('main');
    const header = document.querySelector('header');
    if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (main) main.style.marginLeft = '';
        if (header) header.style.width = '';
    } else {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        if (main) main.style.marginLeft = '0';
        if (header) header.style.width = '100%';
    }
    if (overlay) overlay.classList.add('hidden');
}
setSidebarState();
window.addEventListener('resize', setSidebarState);
</script>
</body></html>