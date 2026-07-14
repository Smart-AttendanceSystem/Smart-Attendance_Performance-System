<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/company_location.php';

$adminId = $_SESSION['user_id'] ?? 0;
$role = $_SESSION['user_role'] ?? '';
$companyName = COMPANY_NAME;
$companyLocation = COMPANY_LOCATION;

if ($adminId <= 0) {
    header('Location: ../auth/login.php');
    exit;
}

if ($role !== 'admin') {
    header('Location: ../user/userdashboard.php');
    exit;
}

$message = '';
$messageType = '';

$notifPrefs = [
    'leave_request_alerts' => 1,
    'password_reset_alerts' => 1
];
$notifTableCheck = $conn->query("SHOW TABLES LIKE 'notification_preferences'");
if ($notifTableCheck && $notifTableCheck->num_rows > 0) {
    $notifStmt = $conn->prepare("SELECT * FROM notification_preferences WHERE user_id = ?");
    $notifStmt->bind_param('i', $adminId);
    $notifStmt->execute();
    $notifRow = $notifStmt->get_result()->fetch_assoc();
    if ($notifRow) {
        $notifPrefs = $notifRow;
    }
}

$defaultAvatar = 'https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_photo']) && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['avatar']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            $message = 'Only JPG, PNG, GIF & WebP images are allowed.';
            $messageType = 'error';
        } else {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'admin_' . $adminId . '_' . time() . '.' . $ext;
            $uploadDir = __DIR__ . '/../uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                $avatarPath = 'uploads/avatars/' . $filename;
                $profileCheck = $conn->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
                $profileCheck->bind_param('i', $adminId);
                $profileCheck->execute();
                if ($profileCheck->get_result()->num_rows > 0) {
                    $updateAvatar = $conn->prepare("UPDATE employee_profiles SET avatar = ? WHERE user_id = ?");
                    $updateAvatar->bind_param('si', $avatarPath, $adminId);
                    $updateAvatar->execute();
                } else {
                    $insertAvatar = $conn->prepare("INSERT INTO employee_profiles (user_id, avatar) VALUES (?, ?)");
                    $insertAvatar->bind_param('is', $adminId, $avatarPath);
                    $insertAvatar->execute();
                }
                $admin['avatar'] = $avatarPath;
                $_SESSION['user_avatar'] = $avatarPath;
                $message = 'Photo updated successfully.';
                $messageType = 'success';
            } else {
                $message = 'Failed to upload photo.';
                $messageType = 'error';
            }
        }
    } elseif (isset($_POST['save_profile'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $departmentId = (int) ($_POST['department_id'] ?? 0);
        $jobTitle = trim($_POST['job_title'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        if ($name !== '' && $email !== '') {
            $updateStmt = $conn->prepare("UPDATE `user` SET name = ?, email = ?, department_id = ?, job_title = ?, bio = ? WHERE id = ?");
            $updateStmt->bind_param('ssissi', $name, $email, $departmentId, $jobTitle, $bio, $adminId);
            if ($updateStmt->execute()) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $message = 'Profile updated successfully.';
                $messageType = 'success';
                $admin['name'] = $name;
                $admin['email'] = $email;
                $admin['department_id'] = $departmentId;
                $admin['job_title'] = $jobTitle;
                $admin['bio'] = $bio;
            } else {
                $message = 'Could not update profile. Please try again.';
                $messageType = 'error';
            }
        } else {
            $message = 'Name and Email are required.';
            $messageType = 'error';
        }
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $pwStmt = $conn->prepare("SELECT password FROM `user` WHERE id = ?");
        $pwStmt->bind_param('i', $adminId);
        $pwStmt->execute();
        $pwRow = $pwStmt->get_result()->fetch_assoc();

        if (!password_verify($currentPassword, $pwRow['password']) && $currentPassword !== $pwRow['password']) {
            $message = 'Current password is incorrect.';
            $messageType = 'error';
        } elseif (strlen($newPassword) < 4) {
            $message = 'New password must be at least 4 characters.';
            $messageType = 'error';
        } elseif ($newPassword !== $confirmPassword) {
            $message = 'New password and confirmation do not match.';
            $messageType = 'error';
        } else {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePwStmt = $conn->prepare("UPDATE `user` SET password = ? WHERE id = ?");
            $updatePwStmt->bind_param('si', $passwordHash, $adminId);
            if ($updatePwStmt->execute()) {
                $message = 'Password changed successfully.';
                $messageType = 'success';
            } else {
                $message = 'Could not change password. Please try again.';
                $messageType = 'error';
            }
        }
    } elseif (isset($_POST['save_position'])) {
        $positionName = trim($_POST['position_name'] ?? '');
        if ($positionName !== '') {
            $checkPos = $conn->prepare("SELECT id FROM positions WHERE name = ?");
            $checkPos->bind_param('s', $positionName);
            $checkPos->execute();
            if ($checkPos->get_result()->num_rows === 0) {
                $insertPos = $conn->prepare("INSERT INTO positions (name) VALUES (?)");
                $insertPos->bind_param('s', $positionName);
                $insertPos->execute();
                $message = 'Position added successfully.';
                $messageType = 'success';
            } else {
                $message = 'Position already exists.';
                $messageType = 'error';
            }
        }
    } elseif (isset($_POST['delete_position'])) {
        $positionId = (int) ($_POST['position_id'] ?? 0);
        if ($positionId > 0) {
            $delStmt = $conn->prepare("DELETE FROM positions WHERE id = ?");
            $delStmt->bind_param('i', $positionId);
            $delStmt->execute();
            $message = 'Position deleted.';
            $messageType = 'success';
        }
    } elseif (isset($_POST['reset_employee_password'])) {
        $employeeId = (int) ($_POST['employee_id'] ?? 0);
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if ($employeeId <= 0 || $newPassword === '') {
            $message = 'Invalid request.';
            $messageType = 'error';
        } elseif (strlen($newPassword) < 4) {
            $message = 'Password must be at least 4 characters.';
            $messageType = 'error';
        } elseif ($newPassword !== $confirmPassword) {
            $message = 'Passwords do not match.';
            $messageType = 'error';
        } else {
            $empCheck = $conn->prepare("SELECT id, name FROM `user` WHERE id = ? AND role = 'employee'");
            $empCheck->bind_param('i', $employeeId);
            $empCheck->execute();
            $empResult = $empCheck->get_result();
            if ($empResult->num_rows === 0) {
                $message = 'Employee not found.';
                $messageType = 'error';
            } else {
                $employee = $empResult->fetch_assoc();
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare("UPDATE `user` SET password = ? WHERE id = ?");
                $updateStmt->bind_param('si', $passwordHash, $employeeId);
                if ($updateStmt->execute()) {
                    $notifTitle = 'Password Reset';
                    $notifMessage = 'Your password has been reset by admin. Your new password is: ' . $newPassword . '. Please change it after logging in.';
                    $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'password_reset')");
                    $notifStmt->bind_param('iss', $employeeId, $notifTitle, $notifMessage);
                    $notifStmt->execute();
                    $message = 'Password reset successfully. Employee "' . htmlspecialchars($employee['name']) . '" has been notified.';
                    $messageType = 'success';
                } else {
                    $message = 'Could not reset password.';
                    $messageType = 'error';
                }
            }
        }
    } elseif (isset($_POST['edit_position'])) {
        $positionId = (int) ($_POST['position_id'] ?? 0);
        $positionName = trim($_POST['position_name'] ?? '');
        if ($positionId > 0 && $positionName !== '') {
            $updatePos = $conn->prepare("UPDATE positions SET name = ? WHERE id = ?");
            $updatePos->bind_param('si', $positionName, $positionId);
            $updatePos->execute();
            $message = 'Position updated successfully.';
            $messageType = 'success';
        }
    } elseif (isset($_POST['save_notification_prefs'])) {
        if (!$notifTableCheck || $notifTableCheck->num_rows === 0) {
            $message = 'Notification preferences table not available. Run the database migration first.';
            $messageType = 'error';
        } else {
            $leaveAlerts = isset($_POST['leave_request_alerts']) ? 1 : 0;
            $pwdAlerts = isset($_POST['password_reset_alerts']) ? 1 : 0;

            $upsert = $conn->prepare("INSERT INTO notification_preferences (user_id, leave_request_alerts, password_reset_alerts) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE leave_request_alerts = VALUES(leave_request_alerts), password_reset_alerts = VALUES(password_reset_alerts)");
            $upsert->bind_param('iii', $adminId, $leaveAlerts, $pwdAlerts);
            if ($upsert->execute()) {
                $notifPrefs['leave_request_alerts'] = $leaveAlerts;
                $notifPrefs['password_reset_alerts'] = $pwdAlerts;
                $message = 'Notification preferences saved.';
                $messageType = 'success';
            } else {
                $message = 'Could not save preferences.';
                $messageType = 'error';
            }
        }
    }
}

$stmt = $conn->prepare("SELECT u.id, u.name, u.email, u.department_id, u.job_title, u.bio, ep.avatar FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = ?");
$stmt->bind_param('i', $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

$departmentsResult = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
$departments = [];
if ($departmentsResult && $departmentsResult->num_rows > 0) {
    while ($row = $departmentsResult->fetch_assoc()) {
        $departments[] = $row;
    }
}

$positionsResult = $conn->query("SELECT id, name FROM positions ORDER BY name ASC");
$positions = [];
if ($positionsResult && $positionsResult->num_rows > 0) {
    while ($row = $positionsResult->fetch_assoc()) {
        $positions[] = $row;
    }
}

$employeesResult = $conn->query("SELECT u.id, u.name, u.email, u.status, u.role FROM `user` u WHERE u.role = 'employee' ORDER BY u.name ASC");
$employees = [];
if ($employeesResult && $employeesResult->num_rows > 0) {
    while ($row = $employeesResult->fetch_assoc()) {
        $employees[] = $row;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Settings &amp; Security</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
    <link rel="stylesheet" href="../config/dashboard.css"/>
    <link rel="stylesheet" href="../config/theme.css"/>
    <script src="../config/theme.js"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #c4c6cd;
            border-radius: 10px;
        }
    </style>
<!-- Set sidebar state BEFORE body paints (eliminates layout blink) -->
<script>
(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var r=document.documentElement;r.classList.remove('sidebar-open','sidebar-closed');r.classList.add(c?'sidebar-closed':'sidebar-open');})();
</script>
</head>

<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container">
    <?php $activePage = 'settings'; ?>
    <?php include __DIR__ . '/includes/sidebar_admin.php'; ?>
  
    <main id="mainContent" class="pt-3 min-h-screen bg-background">
        <div class="max-w-[1600px] mx-auto p-lg">
            <div class="mb-xl">
                <h2 class="font-display-lg text-display-lg text-primary">Settings &amp; Security</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Manage your account preferences, security credentials, and system roles.</p>
            </div>
            <?php if (!empty($message)): ?>
                <div class="mb-lg px-lg py-md rounded-xl border <?= $messageType === 'success' ? 'bg-secondary-container/20 border-secondary/30 text-secondary' : 'bg-error-container/20 border-error/30 text-error' ?> font-body-md">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-12 gap-lg">
                <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg overflow-hidden relative">
                    <div class="flex items-center gap-lg mb-lg">
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 rounded-full border-4 border-surface-container overflow-hidden">
                                <img src="<?= !empty($admin['avatar']) ? '../' . htmlspecialchars($admin['avatar']) : $defaultAvatar ?>" class="w-full h-full object-cover" alt="Admin Photo" />
                            </div>
                            <button type="button" class="absolute -bottom-1 -right-1 bg-surface border border-outline-variant w-7 h-7 rounded-full flex items-center justify-center hover:bg-surface-container shadow-sm transition-colors cursor-pointer" onclick="document.getElementById('avatar-file-input').click()">
                                <span class="material-symbols-outlined text-sm">photo_camera</span>
                            </button>
                            <form id="avatar-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="save_photo" value="1" />
                                <input type="file" id="avatar-file-input" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" onchange="this.form.submit()" />
                            </form>
                        </div>
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-primary mb-1">Admin Profile</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Update your public information.</p>
                        </div>
                    </div>
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-md" method="post">
                        <input type="hidden" name="save_profile" value="1" />
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">FULL NAME</label>
                            <input class="w-full border border-outline-variant text-black  rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="name" type="text" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" required />
                        </div>
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">EMAIL ADDRESS</label>
                            <input class="w-full border border-outline-variant text-black rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="email" type="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required />
                        </div>
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">JOB TITLE</label>
                            <input class="w-full border border-outline-variant text-black rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="job_title" type="text" value="<?= htmlspecialchars($admin['job_title'] ?? '') ?>" />
                        </div>
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">DEPARTMENT</label>
                            <select class="w-full border border-outline-variant text-black rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="department_id">
                                <option value="0">-- Select --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= (int) $dept['id'] ?>" <?= (int) ($admin['department_id'] ?? 0) === (int) $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-span-full space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">BIO / NOTES</label>
                            <textarea class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="bio" rows="3"><?= htmlspecialchars($admin['bio'] ?? '') ?></textarea>
                        </div>
                        <div class="col-span-full mt-lg flex justify-end">
                            <button class="bg-secondary text-on-secondary px-xl py-sm rounded-lg font-body-md font-semibold hover:opacity-90 shadow-md transition-all active:scale-95" type="submit">Save Changes</button>
                        </div>
                    </form>
                </div>
                <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-1">Security</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-lg">Maintain a strong password.</p>
                    <form class="space-y-md" method="post">
                        <input type="hidden" name="change_password" value="1" />
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">CURRENT PASSWORD</label>
                            <input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="current_password" placeholder="••••••••" type="password" required />
                        </div>
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">NEW PASSWORD</label>
                            <input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="new_password" placeholder="••••••••" type="password" required />
                        </div>
                        <div class="space-y-base">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">CONFIRM PASSWORD</label>
                            <input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" name="confirm_password" placeholder="••••••••" type="password" required />
                        </div>
                        <div class="pt-sm">
                            <button class="w-full bg-surface-container-high text-primary border border-outline-variant px-md py-sm rounded-lg font-body-md font-semibold hover:bg-outline-variant transition-all" type="submit">Update Password</button>
                        </div>
                    </form>
                </div>
                <div class="col-span-12 lg:col-span-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-1">Notifications Settings</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-lg">Define when and how you want to be alerted about staff activities.</p>
                    <form method="post">
                        <input type="hidden" name="save_notification_prefs" value="1" />
                        <div class="space-y-md">
                            <div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded-lg transition-colors group">
                                <div class="flex gap-md">
                                    <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">event_note</span>
                                    <div>
                                        <p class="font-body-md font-semibold">Leave Request Alerts</p>
                                        <p class="font-body-sm text-on-surface-variant">Instant notification for urgent leave submissions.</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input name="leave_request_alerts" type="checkbox" class="sr-only peer" <?= $notifPrefs['leave_request_alerts'] ? 'checked' : '' ?> />
                                    <div class="w-11 h-6 bg-surface-container-high rounded-full peer peer-checked:bg-secondary peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded-lg transition-colors group">
                                <div class="flex gap-md">
                                    <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">lock_reset</span>
                                    <div>
                                        <p class="font-body-md font-semibold">Password Reset Alerts</p>
                                        <p class="font-body-sm text-on-surface-variant">Notify when employee passwords are reset.</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input name="password_reset_alerts" type="checkbox" class="sr-only peer" <?= $notifPrefs['password_reset_alerts'] ? 'checked' : '' ?> />
                                    <div class="w-11 h-6 bg-surface-container-high rounded-full peer peer-checked:bg-secondary peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
                        </div>
                        <div class="mt-lg flex justify-end">
                            <button class="bg-secondary text-on-secondary px-xl py-sm rounded-lg font-body-md font-semibold hover:opacity-90 shadow-md transition-all active:scale-95" type="submit">Save Preferences</button>
                        </div>
                    </form>
                </div>
                <div class="col-span-12 lg:col-span-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
                    <div class="flex justify-between items-center mb-lg">
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-primary mb-1">Positions / Roles</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Manage job positions in the system.</p>
                        </div>
                        <button class="bg-secondary text-on-secondary px-md py-sm rounded-lg font-body-sm font-semibold hover:opacity-90 transition-all active:scale-95" onclick="document.getElementById('add-position-modal').classList.remove('hidden')">+ Add Position</button>
                    </div>
                    <div class="overflow-hidden border border-outline-variant rounded-lg">
                        <table class="w-full text-left font-body-sm">
                            <thead class="bg-surface-container font-label-caps text-on-surface-variant border-b border-outline-variant">
                                <tr>
                                    <th class="px-md py-sm">POSITION</th>
                                    <th class="px-md py-sm text-right">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <?php if (!empty($positions)): ?>
                                    <?php foreach ($positions as $pos): ?>
                                        <tr class="hover:bg-secondary/5 transition-colors group">
                                            <td class="px-md py-sm font-semibold text-primary"><?= htmlspecialchars($pos['name']) ?></td>
                                            <td class="px-md py-sm text-right">
                                                <button class="text-on-surface-variant hover:text-primary inline-flex items-center gap-xs" onclick="editPosition(<?= (int) $pos['id'] ?>, '<?= htmlspecialchars(addslashes($pos['name'])) ?>')">
                                                    <span class="material-symbols-outlined text-sm">edit</span>
                                                </button>
                                                <form method="post" class="inline" onsubmit="return confirm('Delete this position?')">
                                                    <input type="hidden" name="delete_position" value="1" />
                                                    <input type="hidden" name="position_id" value="<?= (int) $pos['id'] ?>" />
                                                    <button class="text-on-surface-variant hover:text-error inline-flex items-center gap-xs" type="submit">
                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="px-md py-sm text-on-surface-variant" colspan="2">No positions defined yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-span-12 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
                    <div class="flex justify-between items-center mb-lg">
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-primary mb-1">Employee Password Reset</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Set a new password for any employee. They will be notified.</p>
                        </div>
                    </div>
                    <div class="overflow-hidden border border-outline-variant rounded-lg">
                        <table class="w-full text-left font-body-sm">
                            <thead class="bg-surface-container font-label-caps text-on-surface-variant border-b border-outline-variant">
                                <tr>
                                    <th class="px-md py-sm">EMPLOYEE</th>
                                    <th class="px-md py-sm">EMAIL</th>
                                    <th class="px-md py-sm text-right">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <?php if (!empty($employees)): ?>
                                    <?php foreach ($employees as $emp): ?>
                                        <tr class="hover:bg-secondary/5 transition-colors group">
                                            <td class="px-md py-sm">
                                                <div class="flex items-center gap-sm">
                                                    <div class="h-8 w-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold"><?= htmlspecialchars(strtoupper(substr($emp['name'], 0, 1))) ?></div>
                                                    <div>
                                                        <p class="font-semibold text-primary"><?= htmlspecialchars($emp['name']) ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-md py-sm text-on-surface-variant"><?= htmlspecialchars($emp['email']) ?></td>
                                            <td class="px-md py-sm text-right">
                                                <button class="text-secondary border border-secondary/30 px-sm py-xs rounded-lg text-[11px] font-bold hover:bg-secondary/5 transition-all" onclick="openResetPassword(<?= (int) $emp['id'] ?>, '<?= htmlspecialchars(addslashes($emp['name'])) ?>')">Reset Password</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="px-md py-sm text-on-surface-variant" colspan="3">No employees found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="add-position-modal">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl shadow-xl overflow-hidden">
            <div class="bg-primary p-lg text-on-primary flex justify-between items-center">
                <div>
                    <h3 class="font-headline-sm text-headline-sm">Add Management Role</h3>
                    <p class="text-on-primary-container text-body-sm">Define a new job position in the system</p>
                </div>
                <button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('add-position-modal').classList.add('hidden')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form class="p-lg space-y-lg" method="post">
                <input type="hidden" name="save_position" value="1" />
                <div class="space-y-base">
                    <label class="font-label-caps text-label-caps text-on-surface-variant">Role / Position Name</label>
                    <input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="position_name" placeholder="e.g. Senior Developer" type="text" required />
                </div>
                <div class="flex justify-end gap-md">
                    <button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="document.getElementById('add-position-modal').classList.add('hidden')">Cancel</button>
                    <button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Save Role</button>
                </div>
            </form>
        </div>
    </div>

    <div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="edit-position-modal">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl shadow-xl overflow-hidden">
            <div class="bg-primary p-lg text-on-primary flex justify-between items-center">
                <div>
                    <h3 class="font-headline-sm text-headline-sm">Edit Management Role</h3>
                    <p class="text-on-primary-container text-body-sm">Update the position name</p>
                </div>
                <button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('edit-position-modal').classList.add('hidden')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form class="p-lg space-y-lg" method="post">
                <input type="hidden" name="edit_position" value="1" />
                <input type="hidden" name="position_id" id="edit-position-id" value="" />
                <div class="space-y-base">
                    <label class="font-label-caps text-label-caps text-on-surface-variant">Role / Position Name</label>
                    <input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="position_name" id="edit-position-name" placeholder="e.g. Senior Developer" type="text" required />
                </div>
                <div class="flex justify-end gap-md">
                    <button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="document.getElementById('edit-position-modal').classList.add('hidden')">Cancel</button>
                    <button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Update Role</button>
                </div>
            </form>
        </div>
    </div>

    <div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="reset-password-modal">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl shadow-xl overflow-hidden">
            <div class="bg-primary p-lg text-on-primary flex justify-between items-center">
                <div>
                    <h3 class="font-headline-sm text-headline-sm">Reset Employee Password</h3>
                    <p id="reset-password-employee-name" class="text-on-primary-container text-body-sm">Set new password for employee</p>
                </div>
                <button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('reset-password-modal').classList.add('hidden')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form class="p-lg space-y-lg" method="post">
                <input type="hidden" name="reset_employee_password" value="1" />
                <input type="hidden" name="employee_id" id="reset-employee-id" value="" />
                <div class="space-y-base">
                    <label class="font-label-caps text-label-caps text-on-surface-variant">New Password</label>
                    <input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="new_password" placeholder="Enter new password" type="text" required />
                </div>
                <div class="space-y-base">
                    <label class="font-label-caps text-label-caps text-on-surface-variant">Confirm Password</label>
                    <input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="confirm_password" placeholder="Confirm new password" type="text" required />
                </div>
                <div class="flex justify-end gap-md">
                    <button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')">Cancel</button>
                    <button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Reset &amp; Notify</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        
        function editPosition(id, name) {
            document.getElementById('edit-position-id').value = id;
            document.getElementById('edit-position-name').value = name;
            document.getElementById('edit-position-modal').classList.remove('hidden');
        }

        function openResetPassword(id, name) {
            document.getElementById('reset-employee-id').value = id;
            document.getElementById('reset-password-employee-name').textContent = 'Set new password for ' + name;
            document.getElementById('reset-password-modal').classList.remove('hidden');
        }

        const searchInput = document.getElementById('global-search');
        const searchResults = document.getElementById('search-results');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const term = this.value.trim();
            if (term.length < 1) {
                searchResults.classList.add('hidden');
                return;
            }
            searchTimeout = setTimeout(() => {
                fetch('dashboard.php?q=' + encodeURIComponent(term) + '&ajax=1')
                    .then(r => r.text())
                    .then(html => {
                        if (html.trim()) {
                            searchResults.innerHTML = html;
                            searchResults.classList.remove('hidden');
                        } else {
                            searchResults.classList.add('hidden');
                        }
                    })
                    .catch(() => {});
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
      
    </script>
</body>

</html>