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

$today = date('Y-m-d');
$tab = $_GET['tab'] ?? 'pending';
$searchTerm = trim($_GET['q'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Active (pending) requests count
$pendingCount = (int) $conn->query("SELECT COUNT(*) FROM leave_requests lr JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) = 'pending'")->fetch_row()[0];

// Absent today (from attendance where status='absent' + users with no record)
$absentStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) = 'absent'");
$absentStmt->bind_param('s', $today);
$absentStmt->execute();
$absentToday = (int) $absentStmt->get_result()->fetch_assoc()['cnt'];
$totalUsers = (int) $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee'")->fetch_row()[0];
$presentLateStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attendance a JOIN `user` u ON u.id = a.user_id WHERE u.role = 'employee' AND DATE(a.date) = ? AND LOWER(a.status) IN ('present','late')");
$presentLateStmt->bind_param('s', $today);
$presentLateStmt->execute();
$presentLateToday = (int) $presentLateStmt->get_result()->fetch_assoc()['cnt'];
$unaccounted = $totalUsers - $presentLateToday - $absentToday;
if ($unaccounted > 0) {
    $absentToday += $unaccounted;
}

// Total leave requests count per status
$totalApproved = (int) $conn->query("SELECT COUNT(*) FROM leave_requests lr JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) = 'approved'")->fetch_row()[0];
$totalRejected = (int) $conn->query("SELECT COUNT(*) FROM leave_requests lr JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) = 'rejected'")->fetch_row()[0];

// Pending leave requests (count)
$pendingCountQuery = "SELECT COUNT(*) AS cnt FROM leave_requests lr LEFT JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) = 'pending'";
$pendingCountParams = [];
$pendingCountTypes = '';
if ($searchTerm !== '') {
    $pendingCountQuery .= " AND (u.name LIKE ? OR lr.reason LIKE ?)";
    $searchLike = '%' . $searchTerm . '%';
    $pendingCountParams = [$searchLike, $searchLike];
    $pendingCountTypes = 'ss';
}
$pendingCountStmt = $conn->prepare($pendingCountQuery);
if (!empty($pendingCountParams)) {
    $pendingCountStmt->bind_param($pendingCountTypes, ...$pendingCountParams);
}
$pendingCountStmt->execute();
$totalPending = (int) $pendingCountStmt->get_result()->fetch_assoc()['cnt'];
$totalPendingPages = max(1, ceil($totalPending / $perPage));

// Pending leave requests (paginated)
$pendingQuery = "SELECT lr.id, lr.user_id, lr.start_date, lr.end_date, lr.reason, lr.status, u.name FROM leave_requests lr LEFT JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) = 'pending'";
$pendingParams = [];
$pendingTypes = '';
if ($searchTerm !== '') {
    $pendingQuery .= " AND (u.name LIKE ? OR lr.reason LIKE ?)";
    $searchLike = '%' . $searchTerm . '%';
    $pendingParams = [$searchLike, $searchLike];
    $pendingTypes = 'ss';
}
$pendingQuery .= " ORDER BY lr.id DESC LIMIT ? OFFSET ?";
$pendingParams[] = $perPage;
$pendingParams[] = $offset;
$pendingTypes .= 'ii';
$pendingStmt = $conn->prepare($pendingQuery);
if (!empty($pendingParams)) {
    $pendingStmt->bind_param($pendingTypes, ...$pendingParams);
}
$pendingStmt->execute();
$pendingRequests = $pendingStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Leave history (count)
$historyCountQuery = "SELECT COUNT(*) AS cnt FROM leave_requests lr LEFT JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) IN ('approved','rejected')";
$historyCountParams = [];
$historyCountTypes = '';
if ($searchTerm !== '') {
    $historyCountQuery .= " AND (u.name LIKE ? OR lr.reason LIKE ?)";
    $searchLike = '%' . $searchTerm . '%';
    $historyCountParams = [$searchLike, $searchLike];
    $historyCountTypes = 'ss';
}
$historyCountStmt = $conn->prepare($historyCountQuery);
if (!empty($historyCountParams)) {
    $historyCountStmt->bind_param($historyCountTypes, ...$historyCountParams);
}
$historyCountStmt->execute();
$totalHistory = (int) $historyCountStmt->get_result()->fetch_assoc()['cnt'];
$totalHistoryPages = max(1, ceil($totalHistory / $perPage));

// Leave history (paginated)
$historyQuery = "SELECT lr.id, lr.user_id, lr.start_date, lr.end_date, lr.reason, lr.status, u.name FROM leave_requests lr LEFT JOIN `user` u ON u.id = lr.user_id WHERE u.role = 'employee' AND LOWER(lr.status) IN ('approved','rejected')";
$historyParams = [];
$historyTypes = '';
if ($searchTerm !== '') {
    $historyQuery .= " AND (u.name LIKE ? OR lr.reason LIKE ?)";
    $searchLike = '%' . $searchTerm . '%';
    $historyParams = [$searchLike, $searchLike];
    $historyTypes = 'ss';
}
$historyQuery .= " ORDER BY lr.id DESC LIMIT ? OFFSET ?";
$historyParams[] = $perPage;
$historyParams[] = $offset;
$historyTypes .= 'ii';
$historyStmt = $conn->prepare($historyQuery);
if (!empty($historyParams)) {
    $historyStmt->bind_param($historyTypes, ...$historyParams);
}
$historyStmt->execute();
$leaveHistory = $historyStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Search results for header search box
$searchResults = [];
if ($searchTerm !== '') {
    $searchKeyword = '%' . $searchTerm . '%';
    $searchStmt = $conn->prepare("SELECT id, name, email, status FROM `user` WHERE role = 'employee' AND (name LIKE ? OR email LIKE ?) ORDER BY name ASC LIMIT 10");
    $searchStmt->bind_param('ss', $searchKeyword, $searchKeyword);
    $searchStmt->execute();
    $searchResults = $searchStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Leave Management</title>
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
<link rel="stylesheet" href="../config/dashboard.css"/>
<link rel="stylesheet" href="../config/theme.css"/>
<script src="../config/theme.js"></script>
<script>(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var root=document.documentElement;root.classList.remove('sidebar-open','sidebar-closed');root.classList.add(c?'sidebar-closed':'sidebar-open');})();</script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            vertical-align: middle;
        }
        .active-pill {
            position: relative;
        }
        .active-pill::after {
            content: '';
            position: absolute;
            bottom: -16px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #006b58;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container">
<?php $activePage = 'leave_requests'; ?>
<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>
<!-- Main Content Shell -->
<main id="mainContent" class="min-h-screen">
<!-- TopNavBar Shell -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg z-40">
<div class="flex items-center gap-lg">
<button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
<h2 class="font-headline-sm text-headline-sm font-semibold text-primary dark:text-inverse-primary">HR Admin</h2>
<form class="relative w-80" method="get" action="leaverequest.php">
<input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>" />
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
<input class="w-full h-9 pl-10 pr-4 bg-surface-container-low border-none rounded-lg text-body-sm focus:ring-1 focus:ring-secondary" name="q" placeholder="Search employees or departments..." type="text" value="<?= htmlspecialchars($searchTerm) ?>"/>
</form>
</div>
<div class="flex items-center gap-md">
</div>
</header>
<!-- Canvas Container -->
<div class="pt-16 p-lg max-w-[1600px] mx-auto">
<!-- Page Header & Tabs -->
<div class="mb-lg">
<div class="flex justify-between items-end mb-lg">
<div>

<h3 class="font-display-lg text-display-lg text-primary">Leave Management</h3>
</div>
<div class="flex items-center gap-sm bg-surface-container p-1 mt-3 rounded-xl">
<a href="leaverequest.php?tab=pending" class="flex items-center gap-xs px-md py-2 <?= $tab === 'pending' ? 'bg-surface-container-lowest text-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' ?> rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">pending_actions</span>
                            Pending Requests
                        </a>
<a href="leaverequest.php?tab=history" class="flex items-center gap-xs px-md py-2 <?= $tab === 'history' ? 'bg-surface-container-lowest text-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' ?> rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">history</span>
                            Leave History
                        </a>
</div>
</div>
<?php if ($searchTerm !== '' && !empty($searchResults)): ?>
<section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm mb-lg">
<h4 class="font-headline-sm text-headline-sm text-primary mb-md">Employee Search Results</h4>
<div class="space-y-sm">
<?php foreach ($searchResults as $result): ?>
<div class="flex items-center justify-between rounded-lg border border-outline-variant/30 bg-surface-container-low p-sm">
<div>
<p class="font-semibold text-primary"><?= htmlspecialchars($result['name']) ?></p>
<p class="text-sm text-on-surface-variant"><?= htmlspecialchars($result['email']) ?></p>
</div>
<span class="text-[11px] font-bold uppercase text-secondary"><?= htmlspecialchars($result['status'] ?? 'Active') ?></span>
</div>
<?php endforeach; ?>
</div>
</section>
<?php elseif ($searchTerm !== ''): ?>
<section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm mb-lg">
<p class="text-sm text-on-surface-variant">No matching employees found.</p>
</section>
<?php endif; ?>
<!-- KPI Overview Row -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-md mb-xl">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-t-secondary">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase mb-xs">Active Requests</p>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-primary"><?= $pendingCount ?></span>
</div>
</div>

<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-t-error">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase mb-xs">Absent Today</p>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-primary"><?= $absentToday ?></span>
</div>
</div>

<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-t-tertiary-container">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase mb-xs">Pending Requests</p>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-primary"><?= $pendingCount ?></span>
</div>
</div>

</div>
<!-- Table Container -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<!-- Table Toolbar -->
<div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low/30">
<div class="flex items-center gap-md">
<span class="font-body-md font-semibold text-primary"><?= $tab === 'history' ? 'Leave History' : 'Pending Requests' ?></span>
<span class="bg-primary-container/10 text-primary px-2 py-0.5 rounded text-[10px] font-bold"><?= $tab === 'history' ? count($leaveHistory) : count($pendingRequests) ?> TOTAL</span>
</div>

</div>
<!-- Table Execution -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">Employee</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">Type</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">Start Date</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">End Date</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">Leave Days</th>

<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">Reason</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant">Status</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant uppercase border-b border-outline-variant text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<?php if ($tab === 'pending'): ?>
<?php if (!empty($pendingRequests)): ?>
<?php foreach ($pendingRequests as $req): ?>
<?php
$leaveDays = max(1, (strtotime($req['end_date']) - strtotime($req['start_date'])) / 86400 + 1);
$initial = strtoupper(substr($req['name'] ?? '?', 0, 1));
$colors = ['bg-primary-container text-on-primary-container', 'bg-tertiary-container text-on-tertiary-container', 'bg-error-container text-on-error-container', 'bg-surface-dim text-on-surface'];
$colorKey = abs(crc32($req['name'] ?? $req['id'])) % count($colors);
?>
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-9 h-9 rounded-full <?= $colors[$colorKey] ?> flex items-center justify-center font-bold text-xs"><?= $initial ?></div>
<div>
<p class="font-body-md font-semibold text-primary"><?= htmlspecialchars($req['name'] ?? 'Unknown') ?></p>
</div>
</div>
</td>
<td class="px-lg py-md text-body-sm text-primary">Leave Request</td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= htmlspecialchars(date('M d, Y', strtotime($req['start_date']))) ?></td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= htmlspecialchars(date('M d, Y', strtotime($req['end_date']))) ?></td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= $leaveDays ?></td>
<td class="px-lg py-md text-body-sm text-on-surface-variant italic max-w-[200px] truncate"><?= htmlspecialchars($req['reason']) ?></td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-secondary-container/20 text-on-secondary-container">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            Pending
                                        </span>
</td>
<td class="px-lg py-md text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-secondary hover:bg-secondary/10 rounded-lg transition-all" title="Approve">
<span class="material-symbols-outlined text-[20px]">check_circle</span>
</button>
<button class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-all" title="Reject">
<span class="material-symbols-outlined text-[20px]">cancel</span>
</button>
<button class="p-1.5 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-lg py-md text-body-sm text-on-surface-variant text-center" colspan="8">No pending leave requests found.</td>
</tr>
<?php endif; ?>
<?php else: ?>
<?php if (!empty($leaveHistory)): ?>
<?php foreach ($leaveHistory as $req): ?>
<?php
$leaveDays = max(1, (strtotime($req['end_date']) - strtotime($req['start_date'])) / 86400 + 1);
$initial = strtoupper(substr($req['name'] ?? '?', 0, 1));
$statusLower = strtolower($req['status']);
$colors = ['bg-primary-container text-on-primary-container', 'bg-tertiary-container text-on-tertiary-container', 'bg-error-container text-on-error-container', 'bg-surface-dim text-on-surface'];
$colorKey = abs(crc32($req['name'] ?? $req['id'])) % count($colors);
$statusBadge = $statusLower === 'approved' ? 'bg-secondary/20 text-secondary' : 'bg-error/10 text-error';
$statusDot = $statusLower === 'approved' ? 'bg-secondary' : 'bg-error';
?>
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-9 h-9 rounded-full <?= $colors[$colorKey] ?> flex items-center justify-center font-bold text-xs"><?= $initial ?></div>
<div>
<p class="font-body-md font-semibold text-primary"><?= htmlspecialchars($req['name'] ?? 'Unknown') ?></p>
</div>
</div>
</td>
<td class="px-lg py-md text-body-sm text-primary">Leave Request</td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= htmlspecialchars(date('M d, Y', strtotime($req['start_date']))) ?></td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= htmlspecialchars(date('M d, Y', strtotime($req['end_date']))) ?></td>
<td class="px-lg py-md text-body-sm font-data-mono"><?= $leaveDays ?></td>
<td class="px-lg py-md text-body-sm text-on-surface-variant italic max-w-[200px] truncate"><?= htmlspecialchars($req['reason']) ?></td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase <?= $statusBadge ?>">
<span class="w-1.5 h-1.5 rounded-full <?= $statusDot ?>"></span>
<?= htmlspecialchars(ucfirst($req['status'] ?? 'Unknown')) ?>
</span>
</td>
<td class="px-lg py-md text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-lg py-md text-body-sm text-on-surface-variant text-center" colspan="8">No leave history found.</td>
</tr>
<?php endif; ?>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Pagination -->
<?php
$totalPages = $tab === 'history' ? $totalHistoryPages : $totalPendingPages;
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$currentTotal = $tab === 'history' ? count($leaveHistory) : count($pendingRequests);
$grandTotal = $tab === 'history' ? $totalHistory : $totalPending;
$pageParam = $searchTerm !== '' ? '&q=' . urlencode($searchTerm) : '';
$tabParam = 'tab=' . $tab;
?>
<div class="px-lg py-sm border-t border-outline-variant flex justify-between items-center bg-surface-container-low/30">
<p class="text-[11px] font-label-caps text-on-surface-variant uppercase tracking-wider">Showing <?= $currentTotal ?> of <?= $grandTotal ?> Records</p>
<div class="flex items-center gap-xs">
<a class="p-1 hover:bg-surface-container rounded border border-outline-variant/30 transition-all text-on-surface-variant <?= $page <= 1 ? 'pointer-events-none opacity-30' : '' ?>" href="?<?= $tabParam ?>&page=<?= $page - 1 ?><?= $pageParam ?>">
<span class="material-symbols-outlined">chevron_left</span>
</a>
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<a class="w-8 h-8 flex items-center justify-center text-[11px] font-bold rounded <?= $i === $page ? 'bg-primary text-on-primary' : 'hover:bg-surface-container text-primary' ?>" href="?<?= $tabParam ?>&page=<?= $i ?><?= $pageParam ?>"><?= $i ?></a>
<?php endfor; ?>
<a class="p-1 hover:bg-surface-container rounded border border-outline-variant/30 transition-all text-on-surface-variant <?= $page >= $totalPages ? 'pointer-events-none opacity-30' : '' ?>" href="?<?= $tabParam ?>&page=<?= $page + 1 ?><?= $pageParam ?>">
<span class="material-symbols-outlined">chevron_right</span>
</a>
</div>
</div>
</div>
</div>
<!-- Bento Sidebar / Detail Panel (Contextual) -->

</div>
</main>
<!-- Micro-interaction Script -->
<script>
        // Simple row highlight toggle or action simulation
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                // Potential detail sidebar expansion logic could go here
            });
        });

        // Search bar focus effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-secondary/20');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-secondary/20');
        });
    </script>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body></html>