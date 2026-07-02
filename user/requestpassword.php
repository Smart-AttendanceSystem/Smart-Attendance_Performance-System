<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);

if ($userId <= 0) {
    header('Location: ../auth/login.php');
    exit;
}

$user = [];
$stmt = $conn->prepare("SELECT u.id, u.name, u.email, u.role, u.department_id, ep.position, ep.avatar FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = trim($_POST['reason'] ?? '');
    if ($reason === '') {
        $message = 'Please enter a reason for the password reset.';
        $messageType = 'error';
    } else {
        $notifTitle = 'Password Reset Request from ' . $user['name'];
        $notifMessage = $reason;

        $adminStmt = $conn->prepare("SELECT id FROM `user` WHERE LOWER(role) = 'admin'");
        $adminStmt->execute();
        $adminResult = $adminStmt->get_result();

        $insertStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type, created_at) VALUES (?, ?, ?, 'password_reset', NOW())");

        $inserted = false;
        while ($admin = $adminResult->fetch_assoc()) {
            $insertStmt->bind_param('iss', $admin['id'], $notifTitle, $notifMessage);
            if ($insertStmt->execute()) {
                $inserted = true;
            }
        }
        $insertStmt->bind_param('iss', $userId, $notifTitle, $notifMessage);
        if ($insertStmt->execute()) {
            $inserted = true;
        }

        if ($inserted) {
            $message = 'Your password reset request has been submitted to the admin.';
            $messageType = 'success';
        } else {
            $message = 'Failed to submit request. Please try again.';
            $messageType = 'error';
        }
    }
}

$requests = [];
$reqStmt = $conn->prepare("SELECT title, message, created_at FROM notifications WHERE user_id = ? AND type = 'password_reset' ORDER BY created_at DESC LIMIT 5");
$reqStmt->bind_param('i', $userId);
$reqStmt->execute();
$reqResult = $reqStmt->get_result();
while ($row = $reqResult->fetch_assoc()) {
    $requests[] = $row;
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Smart Attendance - Forgot Password Request</title>
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
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="requestpassword.php">
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
<main class="flex-1 md:ml-[260px] flex flex-col h-screen overflow-y-auto">
<!-- TopNavBar Component -->
<header class="docked full-width top-0 z-40 bg-surface border-b border-outline-variant flex justify-between items-center w-full px-lg h-16 sticky top-0">
<div class="flex items-center gap-md">
<button onclick="toggleSidebar()" class="p-base hover:bg-surface-container rounded-lg transition-colors">
<span class="material-symbols-outlined text-on-surface-variant">menu</span>
</button>
<div class="flex flex-col">
<h2 class="font-headline-sm text-headline-sm font-bold text-primary">Welcome, <?= htmlspecialchars($user['name'] ?? '') ?> 👋</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant">Employee ID : EMP-<?= (int) ($user['id'] ?? 0) ?></p>
</div>
</div>
<div class="flex items-center gap-xl">
<div class="flex items-center gap-sm cursor-pointer hover:bg-surface-container p-xs rounded-lg transition-colors">
<div class="w-10 h-10 rounded-full border border-outline-variant overflow-hidden">
<img src="<?= !empty($user['avatar']) ? '../uploads/avatars/' . htmlspecialchars($user['avatar']) : 'https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg' ?>" class="w-22 h-22 rounded-full border-4 border-surface-container overflow-hidden object-cover" alt="">
</div>
<div class="hidden sm:flex flex-col">
<span class="font-body-md font-semibold text-on-surface"><?= htmlspecialchars($user['name'] ?? '') ?></span>
<span class="font-body-sm text-[11px] text-on-surface-variant"><?= htmlspecialchars($user['position'] ?? 'Employee') ?></span>
</div>
<span class="material-symbols-outlined text-on-surface-variant">keyboard_arrow_down</span>
</div>
</div>
</header>
<!-- Canvas -->
<div class="flex-1 p-lg max-w-[1600px] w-full mx-auto">
<div class="mb-xl">
<h3 class="font-display-lg text-display-lg text-primary">Forgot Password Request</h3>
<p class="font-body-lg text-body-lg text-on-surface-variant mt-1">Submit a request to reset your password.</p>
</div>
<div class="grid grid-cols-12 gap-gutter">
<!-- Form Section (Bento Style) -->
<div class="col-span-12 lg:col-span-8">
<div class="bg-white rounded-xl border border-outline-variant p-xl shadow-sm space-y-lg">
<?php if ($message !== ''): ?>
<div class="px-md py-sm rounded-lg border <?= $messageType === 'error' ? 'border-red-200 bg-red-50 text-red-700' : 'border-secondary/20 bg-secondary-container/30 text-on-secondary-container' ?> font-body-md">
<?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>
<form method="post">
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="space-y-xs">
<label class="font-label-caps text-label-caps text-on-surface-variant uppercase">Employee ID</label>
<input class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-lg font-body-md text-on-surface focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none" disabled="" type="text" value="EMP-<?= (int) ($user['id'] ?? 0) ?>"/>
</div>
<div class="space-y-xs">
<label class="font-label-caps text-label-caps text-on-surface-variant uppercase">Email</label>
<input class="w-full px-md py-sm bg-surface-container-low border border-outline-variant rounded-lg font-body-md text-on-surface focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none" disabled="" type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"/>
</div>
</div>
<div class="space-y-xs">
<label class="font-label-caps text-label-caps text-on-surface-variant uppercase flex items-center gap-1">
                                Reason for Password Reset <span class="text-error">*</span>
</label>
<textarea class="w-full px-md py-sm border border-outline-variant rounded-lg font-body-md text-on-surface focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all outline-none resize-none" placeholder="Enter reason here..." rows="6" name="reason" maxlength="250"><?= htmlspecialchars($_POST['reason'] ?? '') ?></textarea>
<div class="flex justify-end">
<span class="font-body-sm text-[11px] text-on-surface-variant"><?= strlen($_POST['reason'] ?? '') ?>/250</span>
</div>
</div>
<div class="pt-sm">
<button type="submit" class="bg-[#0061ff] hover:bg-[#0052d9] text-white px-xl py-sm rounded-lg font-label-caps text-label-caps tracking-wide flex items-center gap-sm transition-all transform active:scale-95 shadow-lg shadow-blue-500/20">
<span class="font-bold">SUBMIT REQUEST</span>
<span class="material-symbols-outlined text-sm">send</span>
</button>
</div>
</form>
</div>
</div>
<!-- Guidance/Info Section (Side Column) -->

</div>
<!-- Recent Requests Table (Subtle Preview) -->
<div class="mt-xl">
<div class="flex items-center justify-between mb-md">
<h4 class="font-headline-sm text-headline-sm text-on-surface">Recent Reset History</h4>
<button class="text-secondary font-label-caps text-label-caps hover:underline">View All Requests</button>
</div>
<div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<table class="w-full text-left">
<thead class="bg-surface-container border-b border-outline-variant">
<tr>
<th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">REQUEST DATE</th>
<th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">REASON</th>
<th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">STATUS</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<?php if (!empty($requests)): ?>
<?php foreach ($requests as $req): ?>
<tr class="hover:bg-surface-container/30 transition-colors">
<td class="px-md py-md font-body-sm text-on-surface"><?= htmlspecialchars(date('d M Y, h:i A', strtotime($req['created_at']))) ?></td>
<td class="px-md py-md font-body-sm text-on-surface"><?= htmlspecialchars($req['message']) ?></td>
<td class="px-md py-md">
<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-orange-50 text-orange-600 font-label-caps text-[10px]">PENDING</span>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-md py-md font-body-sm text-on-surface-variant" colspan="3">No previous requests.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</main>
<script>
        const form = document.querySelector('form');
        const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="animate-spin material-symbols-outlined text-sm">refresh</span> <span class="font-bold">PROCESSING...</span>';
            });
        }

        const textarea = document.querySelector('textarea[name="reason"]');
        const charCount = document.querySelector('.space-y-xs + .flex.justify-end span');
        if (textarea && charCount) {
            textarea.addEventListener('input', function() {
                charCount.textContent = this.value.length + '/250';
            });
        }

        setInterval(() => {
            const indicator = document.querySelector('.absolute.top-1.right-1');
            if (indicator) indicator.classList.toggle('animate-pulse');
        }, 3000);
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