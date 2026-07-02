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

$hasLeaveType = $conn->query("SHOW COLUMNS FROM leave_requests LIKE 'leave_type'")->num_rows > 0;
$typeField = $hasLeaveType ? 'lr.leave_type' : "'Leave'";
$leaveRequests = [];
$pendingCount = 0;
$approvedCount = 0;
$rejectedCount = 0;

$lrRes = $conn->query("SELECT $typeField AS leave_type, lr.start_date, lr.end_date, lr.reason, lr.status, lr.start_date AS applied_on, DATEDIFF(lr.end_date, lr.start_date) + 1 AS total_days FROM leave_requests lr WHERE lr.user_id = $userId ORDER BY lr.id DESC LIMIT 50");
if ($lrRes && $lrRes->num_rows > 0) {
    while ($row = $lrRes->fetch_assoc()) {
        $leaveRequests[] = $row;
        $s = strtolower($row['status'] ?? '');
        if ($s === 'pending') $pendingCount++;
        elseif ($s === 'approved') $approvedCount++;
        elseif ($s === 'rejected') $rejectedCount++;
    }
}
$totalEntries = count($leaveRequests);

$empProfile = [];
$profRes = $conn->query("SELECT position, avatar FROM employee_profiles WHERE user_id = $userId");
if ($profRes && $profRes->num_rows > 0) {
    $empProfile = $profRes->fetch_assoc();
}
$empPosition = $empProfile['position'] ?? 'Employee';
$empAvatar = $empProfile['avatar'] ?? '';
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Leave Status - HR Connect</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendence.php">
<span class="material-symbols-outlined">schedule</span>
<span class="font-label-caps text-label-caps">Attendence</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaveform.php">
<span class="material-symbols-outlined">event_note</span>
<span class="font-label-caps text-label-caps">Leave Request</span>
</a>
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="leavestatus.php">
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
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="../auth/logout.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
</div>
</aside>
<!-- Main Content Area -->
<main class="md:ml-sidebar-width flex-1 flex flex-col min-h-0 bg-background">
<!-- TopNavBar (Authority: JSON) -->
<header class="flex justify-between items-center px-lg py-md bg-surface border-b border-outline-variant transition-colors duration-150 sticky top-0 z-40">
<div class="flex items-center gap-md">
<button onclick="toggleSidebar()" class="text-primary">
<span class="material-symbols-outlined">menu</span>
</button>
<span class="font-headline-sm text-headline-sm text-primary font-bold">HR Connect</span>
</div>
<div class="flex items-center gap-lg">
<!-- Search Bar (on_right) -->
<div class="hidden md:flex items-center bg-surface-container rounded-lg px-md py-xs border border-outline-variant focus-within:border-primary transition-all">
<span class="material-symbols-outlined text-outline">search</span>
<input class="bg-transparent border-none focus:ring-0 text-body-sm w-64 placeholder:text-on-surface-variant" placeholder="Search tasks..." type="text"/>
</div>
<!-- Actions Area -->
<div class="flex items-center gap-md">

<!-- Profile -->
<div class="flex items-center gap-sm border-l border-outline-variant pl-lg ml-sm">
<div class="text-right hidden sm:block">
<p class="font-body-md font-bold text-primary"><?= htmlspecialchars($userName) ?></p>
<p class="text-body-sm text-on-surface-variant"><?= htmlspecialchars($empPosition) ?></p>
</div>
<img src="<?= $empAvatar ? '../uploads/avatars/' . htmlspecialchars($empAvatar) : 'https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg' ?>" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant object-cover" alt="">
</div>
</div>
</div>
</header>
<!-- Content Canvas -->
<div class="flex-1 overflow-y-auto p-lg">
<!-- Page Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
<div>
<h2 class="font-display-lg text-display-lg text-primary">Leave Status</h2>
<p class="text-on-surface-variant font-body-md">Track your leave requests and their current status</p>
</div>
<a href="leaveform.php" class="inline-flex items-center justify-center gap-xs px-xl py-md bg-primary text-on-primary rounded-lg font-headline-sm hover:bg-primary-container transition-colors shadow-sm">
<span class="material-symbols-outlined">add</span>
                        New Leave Request
                    </a>
</div>
<!-- Table Container (Level 1 Surface) -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
<!-- Filter Tabs -->
<div class="flex border-b border-outline-variant px-lg overflow-x-auto whitespace-nowrap scrollbar-hide">
<button class="px-xl py-lg text-body-md font-bold text-primary border-b-2 border-primary transition-colors">All (<?= $totalEntries ?>)</button>
<button class="px-xl py-lg text-body-md font-medium text-on-surface-variant hover:text-primary transition-colors">Pending (<?= $pendingCount ?>)</button>
<button class="px-xl py-lg text-body-md font-medium text-on-surface-variant hover:text-primary transition-colors">Approved (<?= $approvedCount ?>)</button>
<button class="px-xl py-lg text-body-md font-medium text-on-surface-variant hover:text-primary transition-colors">Rejected (<?= $rejectedCount ?>)</button>
</div>
<!-- Data Table -->
<div class="overflow-x-auto min-w-full">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low">
<tr>
<th class="px-lg py-md text-label-caps uppercase text-on-surface-variant border-b border-outline-variant">Leave Type</th>
<th class="px-lg py-md text-label-caps uppercase text-on-surface-variant border-b border-outline-variant">Dates</th>
<th class="px-lg py-md text-label-caps uppercase text-on-surface-variant border-b border-outline-variant">Total Days</th>
<th class="px-lg py-md text-label-caps uppercase text-on-surface-variant border-b border-outline-variant">Reason</th>
<th class="px-lg py-md text-label-caps uppercase text-on-surface-variant border-b border-outline-variant">Status</th>
<th class="px-lg py-md text-label-caps uppercase text-on-surface-variant border-b border-outline-variant">Applied On</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<?php if (!empty($leaveRequests)): ?>
<?php foreach ($leaveRequests as $i => $lr): ?>
<?php
$s = strtolower($lr['status'] ?? 'pending');
$badgeClass = match($s) {
    'approved' => 'bg-secondary-fixed text-on-secondary-fixed-variant',
    'rejected' => 'bg-error-container text-on-error-container',
    'pending' => 'bg-tertiary-fixed text-on-tertiary-fixed-variant',
    default => 'bg-surface-container-high text-on-surface-variant',
};
$start = date('j M Y', strtotime($lr['start_date']));
$end = $lr['end_date'] ? date('j M Y', strtotime($lr['end_date'])) : null;
$applied = date('j M Y', strtotime($lr['applied_on'] ?? $lr['start_date']));
$td = (int) ($lr['total_days'] ?? 1);
$rowClass = $i % 2 === 1 ? 'bg-surface-container-low/30' : '';
?>
<tr class="hover:bg-secondary-container/10 transition-colors group <?= $rowClass ?>">
<td class="px-lg py-md font-body-md font-bold text-primary"><?= htmlspecialchars($lr['leave_type'] ?? 'Leave') ?></td>
<td class="px-lg py-md">
<div class="flex flex-col">
<span class="text-body-sm font-medium"><?= htmlspecialchars($start) ?><?= $end ? ' -' : '' ?></span>
<?php if ($end): ?><span class="text-body-sm font-medium"><?= htmlspecialchars($end) ?></span><?php endif; ?>
</div>
</td>
<td class="px-lg py-md text-data-mono"><?= $td ?></td>
<td class="px-lg py-md max-w-xs truncate text-on-surface-variant"><?= htmlspecialchars($lr['reason'] ?? '') ?></td>
<td class="px-lg py-md">
<span class="inline-flex items-center px-sm py-base rounded-full <?= $badgeClass ?> text-label-caps"><?= htmlspecialchars(ucfirst($lr['status'] ?? 'Pending')) ?></span>
</td>
<td class="px-lg py-md text-on-surface-variant"><?= htmlspecialchars($applied) ?></td>
<td class="px-lg py-md text-right">
<button class="text-primary hover:text-secondary p-xs rounded transition-colors" title="View Details">
</button>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td class="px-lg py-md text-on-surface-variant" colspan="7">No leave requests found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Pagination Footer -->
<div class="px-lg py-md border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-md bg-surface-container-low/10">
<p class="text-body-sm text-on-surface-variant">Showing <?= $totalEntries ?> <?= $totalEntries === 1 ? 'entry' : 'entries' ?></p>
<div class="flex items-center gap-xs">
<button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant hover:bg-surface-container transition-colors disabled:opacity-50" disabled="">
<span class="material-symbols-outlined text-body-sm">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-on-primary font-bold text-body-sm">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-body-sm">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
</div>
<!-- Micro-interaction Script -->
<script>
        document.addEventListener('DOMContentLoaded', () => {
            // Tab interaction
            const tabs = document.querySelectorAll('.flex.border-b button');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => {
                        t.classList.remove('text-primary', 'font-bold', 'border-b-2', 'border-primary');
                        t.classList.add('text-on-surface-variant', 'font-medium');
                    });
                    tab.classList.remove('text-on-surface-variant', 'font-medium');
                    tab.classList.add('text-primary', 'font-bold', 'border-b-2', 'border-primary');
                });
            });

            // Ripple effect logic could be added here for buttons
        });
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