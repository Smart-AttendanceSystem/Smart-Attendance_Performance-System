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

$message = '';
$messageType = '';

if (isset($_GET['success']) && $_GET['success'] === '1') {
    $message = 'Leave request submitted successfully!';
    $messageType = 'success';
}

$colCheck = $conn->query("SHOW COLUMNS FROM leave_requests LIKE 'leave_type'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE leave_requests ADD COLUMN leave_type VARCHAR(255) DEFAULT '' AFTER reason");
}

$allocations = ['Annual Leave' => 12, 'Sick Leave' => 8, 'Paid Leave' => 8, 'Personal Leave' => 5];
$usedDays = [];
$hasTypeCol = $conn->query("SHOW COLUMNS FROM leave_requests LIKE 'leave_type'")->num_rows > 0;
if ($hasTypeCol) {
    $balRes = $conn->query("SELECT leave_type, SUM(DATEDIFF(end_date, start_date) + 1) AS used FROM leave_requests WHERE user_id = $userId AND LOWER(status) = 'approved' AND YEAR(start_date) = YEAR(CURDATE()) GROUP BY leave_type");
    if ($balRes && $balRes->num_rows > 0) {
        while ($row = $balRes->fetch_assoc()) {
            $usedDays[$row['leave_type']] = (int) $row['used'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_leave'])) {
    $leaveType = trim($_POST['leave_type'] ?? '');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $startDate)) {
        $parts = explode('/', $startDate);
        $startDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }
    if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $endDate)) {
        $parts = explode('/', $endDate);
        $endDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }

    $startTS = strtotime($startDate);
    $endTS = strtotime($endDate);

    if ($startDate === '' || $endDate === '' || $reason === '' || $leaveType === '') {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } elseif ($startTS === false || $endTS === false) {
        $message = 'Invalid date format.';
        $messageType = 'error';
    } elseif ($endTS < $startTS) {
        $message = 'End date must be after start date.';
        $messageType = 'error';
    } else {
        $requestedDays = (int) (($endTS - $startTS) / (60 * 60 * 24)) + 1;
        $alloc = (int) ($allocations[$leaveType] ?? 0);
        $used = (int) ($usedDays[$leaveType] ?? 0);
        $remaining = $alloc - $used;

        if ($remaining <= 0) {
            $message = "Insufficient $leaveType balance. You have 0 days remaining.";
            $messageType = 'error';
        } elseif ($requestedDays > $remaining) {
            $message = "Insufficient $leaveType balance. You have $remaining day(s) remaining but requested $requestedDays day(s).";
            $messageType = 'error';
        } else {
            $hasTypeCol = $conn->query("SHOW COLUMNS FROM leave_requests LIKE 'leave_type'")->num_rows > 0;
            if ($hasTypeCol) {
                $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, start_date, end_date, reason, leave_type, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
                $stmt->bind_param('issss', $userId, $startDate, $endDate, $reason, $leaveType);
            } else {
                $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, 'Pending')");
                $stmt->bind_param('isss', $userId, $startDate, $endDate, $reason);
            }
            if ($stmt->execute()) {
                $notifMsg = $userName . ' requested ' . $leaveType . ' from ' . $startDate . ' to ' . $endDate;
                $conn->query("INSERT INTO notifications (user_id, title, message, type) VALUES (1, 'New Leave Request', '$notifMsg', 'leave_request')");
                header('Location: leaveform.php?success=1');
                exit;
            } else {
                $message = 'Could not submit leave request. Please try again.';
                $messageType = 'error';
            }
        }
    }
}

$employeeId = 'YGN-' . str_pad($userId, 4, '0', STR_PAD_LEFT);

$empProfile = [];
$profRes = $conn->query("SELECT position, avatar FROM employee_profiles WHERE user_id = $userId");
if ($profRes && $profRes->num_rows > 0) {
    $empProfile = $profRes->fetch_assoc();
}
$empPosition = $empProfile['position'] ?? 'Employee';
$empAvatar = $empProfile['avatar'] ?? '';
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Leave Request</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "fontWeight": "600"
                        }],
                        "body-md": ["14px", {
                            "lineHeight": "20px",
                            "fontWeight": "400"
                        }],
                        "headline-sm": ["20px", {
                            "lineHeight": "28px",
                            "fontWeight": "600"
                        }],
                        "display-lg": ["32px", {
                            "lineHeight": "40px",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "label-caps": ["11px", {
                            "lineHeight": "16px",
                            "letterSpacing": "0.05em",
                            "fontWeight": "600"
                        }],
                        "data-mono": ["12px", {
                            "lineHeight": "16px",
                            "fontWeight": "500"
                        }],
                        "body-sm": ["13px", {
                            "lineHeight": "18px",
                            "fontWeight": "400"
                        }],
                        "body-lg": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }]
                    }
                },
            },
        }
    </script>
    <link rel="stylesheet" href="../config/dashboard.css" />
    <link rel="stylesheet" href="../config/theme.css" />
    <script src="../config/theme.js"></script>
    <script>
        (function() {
            var s = localStorage.getItem('sidebarClosed');
            var c = s === '1' || (s === null && window.innerWidth < 768);
            var root = document.documentElement;
            root.classList.remove('sidebar-open', 'sidebar-closed');
            root.classList.add(c ? 'sidebar-closed' : 'sidebar-open');
        })();
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .chart-bar {
            transition: height 1s ease-in-out;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="text-on-surface bg-background">
    <?php $activePage = 'leave_request'; ?>
    <?php include __DIR__ . '/includes/sidebar_user.php'; ?>
    <!-- Top Navigation Bar -->
    <header id="mainHeader" class="fixed top-0 right-0 w-full h-10 bg-surface dark:bg-surface-dim  shadow-sm flex justify-between items-center px-lg z-40 transition-all duration-200">
        <div class="flex items-center gap-lg flex-1">
            <button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
        </div>
       
    </header>
    <!-- Main Content -->
    <main id="mainContent" class="pt-10 h-screen overflow-y-auto bg-background p-lg">
        <?php if ($message !== ''): ?>
            <div class="max-w-6xl mx-auto mb-lg">
                <div class="<?= $messageType === 'success' ? 'bg-green-100 border-green-300 text-green-700' : 'bg-red-100 border-red-300 text-red-700' ?> border px-lg py-md rounded-lg font-body-sm"><?= htmlspecialchars($message) ?></div>
            </div>
        <?php endif; ?>
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-lg">
            <!-- Leave Request Form Area -->
            <div class="lg:col-span-8 mt-8 bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                <div class="p-lg">
                    <h3 class="text-xl font-bold text-gray-800">Leave Request</h3>
                    <p class="text-sm text-gray-400 mt-1">Fill in the details below to request for leave</p>
                    <form class="mt-lg space-y-lg" method="post" action="leaveform.php">
                        <!-- Leave Type -->
                        <div class="space-y-xs">
                            <label class="text-sm font-medium text-gray-700">Leave Type <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue appearance-none" name="leave_type" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="Annual Leave">Annual Leave</option>
                                    <option value="Paid Leave">Paid Leave</option>
                                    <option value="Sick Leave">Sick Leave</option>
                                     <option value="Sick Leave">Unpaid Leave</option>
                                    <option value="Personal Leave">Personal Leave</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" data-icon="expand_more">expand_more</span>
                            </div>
                        </div>
                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                            <div class="space-y-xs">
                                <label class="text-sm font-medium text-gray-700">Start Date <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue" type="date" name="start_date" required />
                                    <span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl" data-icon="calendar_today">calendar_today</span>
                                </div>
                            </div>
                            <div class="space-y-xs">
                                <label class="text-sm font-medium text-gray-700">End Date <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue" type="date" name="end_date" required />
                                    <span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl" data-icon="calendar_today">calendar_today</span>
                                </div>
                            </div>
                        </div>
                        <!-- Total Days -->
                        <div class="space-y-xs">
                            <label class="text-sm font-medium text-gray-700">Total Days</label>
                            <input class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:outline-none" readonly="" type="text" id="total-days" value="0 Days" />
                        </div>
                        <!-- Reason -->
                        <div class="space-y-xs">
                            <label class="text-sm font-medium text-gray-700">Reason <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <textarea class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue resize-none" placeholder="Enter reason here..." name="reason" rows="4" required></textarea>
                                <span class="absolute bottom-2 right-2 text-[10px] text-gray-400" id="char-count">0/500</span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- Form Actions -->
        <div class="flex justify-end gap-md pt-lg border-t border-gray-50">
            <button class="px-8 py-2.5 border border-gray-200 text-gray-600 font-semibold rounded-lg hover:bg-gray-50 transition-colors text-sm" type="reset">Reset</button>
            <button class="px-8 py-2.5 bg-brand-blue text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center gap-xs text-sm" type="submit" name="submit_leave">
                Submit Request
                <span class="material-symbols-outlined text-sm" data-icon="send">send</span>
            </button>
        </div>
        <p class="text-[10px] text-red-500">* Indicates required field</p>
        </form>
        </div>
        </div>
        <!-- Sidebar Components -->
        <div class="lg:col-span-4 space-y-lg">
            <!-- Leave Balance Card -->
            <div class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] border border-gray-100 p-lg">
                <div class="flex items-center gap-md mb-lg">
                    <span class="material-symbols-outlined text-brand-blue" data-icon="calendar_month">calendar_month</span>
                    <h4 class="text-md font-bold text-gray-800">Leave Balance</h4>
                </div>
                <div class="space-y-lg">
                    <?php foreach (['Annual Leave' => ['brand-blue', 'calendar_today'], 'Sick Leave' => ['green-500', 'medical_services'], 'Unpaid Leave' => ['green-500', 'calendar_today']] as $type => [$color, $icon]):
                        $alloc = (int) ($allocations[$type] ?? 10);
                        $used = (int) ($usedDays[$type] ?? 0);
                        $remaining = max(0, $alloc - $used);
                    ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="bg-<?= $color === 'green-500' ? 'green' : 'blue' ?>-50 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-<?= $color ?> text-lg" data-icon="<?= $icon ?>"><?= $icon ?></span>
                                </div>
                                <span class="text-sm font-medium text-gray-600"><?= htmlspecialchars($type) ?></span>
                            </div>
                            <span class="text-sm font-bold text-gray-800"><span class="text-<?= $color ?>"><?= $remaining ?></span> Days Left</span>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>
        </div>
    </main>
    <script>
        // Calculate total days
        const startInput = document.querySelector('input[name="start_date"]');
        const endInput = document.querySelector('input[name="end_date"]');
        const totalDaysInput = document.getElementById('total-days');

        function calcDays() {
            if (startInput.value && endInput.value) {
                const start = new Date(startInput.value);
                const end = new Date(endInput.value);
                const diff = Math.max(0, Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1);
                totalDaysInput.value = diff + ' Day' + (diff !== 1 ? 's' : '');
            }
        }
        startInput.addEventListener('change', calcDays);
        endInput.addEventListener('change', calcDays);

        // Character count
        const reasonInput = document.querySelector('textarea[name="reason"]');
        const charCount = document.getElementById('char-count');
        reasonInput.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = len + '/500';
            if (len > 500) this.value = this.value.substring(0, 500);
        });
    </script>
    <?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body>

</html>