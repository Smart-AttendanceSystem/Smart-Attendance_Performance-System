<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$user = [];

if ($userId <= 0) {
    header('Location: ../auth/login.php');
    exit;
}

// Ensure extra columns exist before querying them
$extraCols = ['blood_group' => 'VARCHAR(50) DEFAULT NULL', 'date_of_birth' => 'DATE DEFAULT NULL', 'gender' => 'VARCHAR(20) DEFAULT NULL', 'avatar' => 'VARCHAR(500) DEFAULT NULL'];
foreach ($extraCols as $col => $def) {
    $r = $conn->query("SHOW COLUMNS FROM employee_profiles LIKE '$col'");
    if ($r->num_rows === 0) {
        $conn->query("ALTER TABLE employee_profiles ADD COLUMN $col $def");
    }
}

$stmt = $conn->prepare("
    SELECT u.id, u.name, u.email, u.status, u.department_id, u.created_at, u.role,
           ep.phone, ep.address, ep.position,
           ep.position AS job_title,
           ep.blood_group, ep.date_of_birth, ep.gender, ep.avatar,
           d.name AS department_name
    FROM `user` u
    LEFT JOIN employee_profiles ep ON ep.user_id = u.id
    LEFT JOIN departments d ON d.id = u.department_id
    WHERE u.id = ?
");
if (!$stmt) {
    die('Database error: ' . $conn->error);
}
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

// Ensure upload directory exists
$uploadDir = __DIR__ . '/../uploads/avatars/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $bloodGroup = trim($_POST['blood_group'] ?? '');
    $dob     = trim($_POST['date_of_birth'] ?? '');
    $gender  = trim($_POST['gender'] ?? '');

    if ($name === '' || $email === '') {
        $message = 'Name and email are required.';
        $messageType = 'error';
    } else {
        $updateUser = $conn->prepare("UPDATE `user` SET name = ?, email = ? WHERE id = ?");
        $updateUser->bind_param('ssi', $name, $email, $userId);
        $updateUser->execute();

        $check = $conn->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
        $check->bind_param('i', $userId);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();

        $dobVal = $dob !== '' ? $dob : null;

        if ($exists) {
            $up = $conn->prepare("UPDATE employee_profiles SET phone=?, address=?, position=?, blood_group=?, date_of_birth=?, gender=? WHERE user_id=?");
            $up->bind_param('ssssssi', $phone, $address, $position, $bloodGroup, $dobVal, $gender, $userId);
            $up->execute();
        } else {
            $deptId = (int) ($user['department_id'] ?? 0);
            $ins = $conn->prepare("INSERT INTO employee_profiles (user_id, department_id, phone, address, email, position, blood_group, date_of_birth, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $ins->bind_param('iisssssss', $userId, $deptId, $phone, $address, $email, $position, $bloodGroup, $dobVal, $gender);
            $ins->execute();
        }

        $_SESSION['user_name'] = $name;

        $message = 'Profile updated successfully!';
        $messageType = 'success';

        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_avatar'])) {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $filename = 'user_' . $userId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename);

            $check = $conn->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
            $check->bind_param('i', $userId);
            $check->execute();
            $exists = $check->get_result()->fetch_assoc();

            if ($exists) {
                $up = $conn->prepare("UPDATE employee_profiles SET avatar = ? WHERE user_id = ?");
                $up->bind_param('si', $filename, $userId);
                $up->execute();
            } else {
                $deptId = (int) ($user['department_id'] ?? 0);
                $email = $user['email'] ?? '';
                $ins = $conn->prepare("INSERT INTO employee_profiles (user_id, department_id, email, avatar) VALUES (?, ?, ?, ?)");
                $ins->bind_param('iiss', $userId, $deptId, $email, $filename);
                $ins->execute();
            }

            $message = 'Profile image updated!';
            $messageType = 'success';
        } else {
            $message = 'Allowed types: jpg, jpeg, png, gif, webp';
            $messageType = 'error';
        }
    } else {
        $message = 'No file selected or upload error.';
        $messageType = 'error';
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Employee Portal - My Profile</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
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
<link rel="stylesheet" href="../config/dashboard.css"/>
<link rel="stylesheet" href="../config/theme.css"/>
<script src="../config/theme.js"></script>
<script>(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var root=document.documentElement;root.classList.remove('sidebar-open','sidebar-closed');root.classList.add(c?'sidebar-closed':'sidebar-open');})();</script>
<style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .chart-bar { transition: height 1s ease-in-out; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="text-on-surface bg-background">
<?php $activePage = 'profile'; ?>
<?php include __DIR__ . '/includes/sidebar_user.php'; ?>
<!-- Top Navigation Bar -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-10 bg-surface dark:bg-surface-dim shadow-sm flex justify-between items-center px-lg z-40 transition-all duration-200">
    <div class="flex items-center gap-lg flex-1">
        <button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
    </div>
</header>
<!-- Main Canvas -->
<main id="mainContent" class="pt-10 h-screen overflow-y-auto bg-background p-lg">
<div class="p-lg max-w-[1600px] mx-auto">
<!-- Page Header -->
<div class="mb-xl">
<h3 class="font-headline-md text-headline-md text-primary">My Profile</h3>
<p class="text-on-surface-variant text-body-md">View and update your personal information</p>
</div>
<?php if ($message !== ''): ?>
<div class="mb-lg px-lg py-md rounded-lg font-semibold text-body-sm <?= $messageType === 'success' ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container' ?> flex items-center gap-md">
<span class="material-symbols-outlined"><?= $messageType === 'success' ? 'check_circle' : 'error' ?></span>
<?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>
<div class="grid grid-cols-12 gap-lg">
<!-- Left Column: Avatar & Quick Info -->
<div class="col-span-12 lg:col-span-4 space-y-lg">
<!-- Profile Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl shadow-sm flex flex-col items-center text-center">
<div class="relative mb-lg">
<div class="w-32 h-32 rounded-full border-4 border-surface-container overflow-hidden">
<img src="<?= !empty($user['avatar']) ? '../uploads/avatars/' . htmlspecialchars($user['avatar']) : 'https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg' ?>" class="w-32 h-32 rounded-full border-4 border-surface-container overflow-hidden object-cover" alt="">
</div>
<form method="POST" enctype="multipart/form-data" id="avatarForm">
<input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" onchange="document.getElementById('avatarForm').submit()">
<input type="hidden" name="upload_avatar" value="1">
</form>
<button type="button" onclick="document.getElementById('avatarInput').click()" class="absolute bottom-1 right-1 bg-surface border border-outline-variant w-8 h-8 rounded-full flex items-center justify-center hover:bg-surface-container shadow-sm transition-colors">
<span class="material-symbols-outlined text-[18px]">photo_camera</span>
</button>
</div>
<h4 class="font-headline-sm text-headline-sm text-primary"><?= htmlspecialchars($user['name'] ?? '') ?></h4>
<p class="text-on-surface-variant text-body-md mb-md"><?= htmlspecialchars($user['job_title'] ?? $user['position'] ?? 'Employee') ?></p>
<span class="px-md py-base bg-secondary-container text-on-secondary-container rounded-full text-[12px] font-semibold flex items-center">
<span class="w-2 h-2 bg-secondary rounded-full mr-2"></span>
<?= htmlspecialchars(ucfirst($user['status'] ?? 'Active')) ?>
            </span>
</div>
<!-- Quick Info Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<div class="bg-secondary/5 px-lg py-md border-b border-outline-variant/30">
<h5 class="font-body-md font-bold text-primary">Quick Info</h5>
</div>
<div class="p-lg space-y-md">
<div class="flex justify-between items-center py-xs border-b border-surface-container last:border-0">
<span class="text-on-surface-variant text-body-sm">Blood Group</span>
<span class="font-semibold text-primary"><?= htmlspecialchars($user['blood_group'] ?? '—') ?></span>
</div>
<div class="flex justify-between items-center py-xs border-b border-surface-container last:border-0">
<span class="text-on-surface-variant text-body-sm">Date of Birth</span>
<span class="font-semibold text-primary"><?= htmlspecialchars(!empty($user['date_of_birth']) ? date('d M Y', strtotime($user['date_of_birth'])) : '—') ?></span>
</div>
<div class="flex justify-between items-center py-xs border-b border-surface-container last:border-0">
<span class="text-on-surface-variant text-body-sm">Gender</span>
<span class="font-semibold text-primary"><?= htmlspecialchars(ucfirst($user['gender'] ?? '—')) ?></span>
</div>
</div>
</div>
</div>
<!-- Right Column: Personal Information -->
<div class="col-span-12 lg:col-span-8">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm h-full flex flex-col">
<div class="px-xl py-lg border-b border-outline-variant/30 flex justify-between items-center">
<h5 class="font-headline-sm text-headline-sm text-primary">Personal Information</h5>
<button onclick="openEditModal()" class="flex items-center space-x-base px-md py-xs border border-primary text-primary rounded-lg hover:bg-primary/5 transition-colors font-semibold text-body-sm">
<span class="material-symbols-outlined text-[18px]">edit</span>
<span>Edit Profile</span>
</button>
</div>
<div class="p-xl grid grid-cols-1 md:grid-cols-2 gap-x-xl gap-y-lg flex-1">
<!-- Field Item -->
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Employee ID</label>
                <div class="p-md bg-surface-container-low rounded-lg font-data-mono text-primary">EMP-<?= (int) ($user['id'] ?? 0) ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Full Name</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars($user['name'] ?? '') ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Email Address</label>
                <div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars($user['email'] ?? '') ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Phone Number</label>
                <div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars($user['phone'] ?? '—') ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Department</label>
                <div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars($user['department_name'] ?? '—') ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Designation</label>
                <div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars($user['position'] ?? $user['job_title'] ?? '—') ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Date of Joining</label>
                <div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars(date('d M Y', strtotime($user['created_at'] ?? 'now'))) ?></div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Location</label>
                <div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary"><?= htmlspecialchars($user['address'] ?? '—') ?></div>
</div>
</div>
<!-- Decorative Visual Element -->
<div class="px-xl py-lg mt-auto border-t border-outline-variant/10">
<div class="flex items-center text-on-surface-variant opacity-60 text-body-sm">
<span class="material-symbols-outlined mr-xs text-[18px]">verified_user</span>
<span>Last verification: 20 Oct 202-</span>
</div>
</div>
</div>
</div>
</div>
<!-- Additional Stats or Links (Footer-like) -->
<footer class="mt-xl flex flex-col md:flex-row justify-between items-center text-body-sm text-on-surface-variant opacity-80 border-t border-outline-variant/30 pt-lg pb-xl">
<p>© <?php echo date('Y'); ?> HR Connect. All rights reserved.</p>
<div class="flex space-x-lg mt-md md:mt-0">
<a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
<a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
<a class="hover:text-primary transition-colors" href="#">Support Desk</a>
</div>
</footer>
</div>
</main>
<!-- Edit Profile Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40" style="display:none;">
<div class="bg-surface-container-lowest rounded-xl shadow-xl max-w-2xl w-full mx-lg max-h-[90vh] overflow-y-auto">
<form method="POST" class="p-xl">
<div class="flex items-center justify-between mb-lg">
<h4 class="font-headline-sm text-headline-sm text-primary">Edit Profile</h4>
<button type="button" onclick="closeEditModal()" class="p-base hover:bg-surface-container-low rounded-lg">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-xl gap-y-md">
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Full Name</label>
<input name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20" required/>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Email</label>
<input name="email" type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20" required/>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Phone</label>
<input name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20"/>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Address / Location</label>
<input name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20"/>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Designation</label>
<input name="position" value="<?= htmlspecialchars($user['position'] ?? '') ?>" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20"/>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Blood Group</label>
<select name="blood_group" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20">
<option value="">—</option>
<option value="A+" <?= ($user['blood_group'] ?? '') === 'A+' ? 'selected' : '' ?>>A+</option>
<option value="A-" <?= ($user['blood_group'] ?? '') === 'A-' ? 'selected' : '' ?>>A-</option>
<option value="B+" <?= ($user['blood_group'] ?? '') === 'B+' ? 'selected' : '' ?>>B+</option>
<option value="B-" <?= ($user['blood_group'] ?? '') === 'B-' ? 'selected' : '' ?>>B-</option>
<option value="AB+" <?= ($user['blood_group'] ?? '') === 'AB+' ? 'selected' : '' ?>>AB+</option>
<option value="AB-" <?= ($user['blood_group'] ?? '') === 'AB-' ? 'selected' : '' ?>>AB-</option>
<option value="O+" <?= ($user['blood_group'] ?? '') === 'O+' ? 'selected' : '' ?>>O+</option>
<option value="O-" <?= ($user['blood_group'] ?? '') === 'O-' ? 'selected' : '' ?>>O-</option>
</select>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Date of Birth</label>
<input name="date_of_birth" type="date" value="<?= htmlspecialchars($user['date_of_birth'] ?? '') ?>" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20"/>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Gender</label>
<select name="gender" class="w-full p-md bg-surface-container-low rounded-lg border-none text-body-sm text-primary focus:ring-2 focus:ring-primary/20">
<option value="">—</option>
<option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
<option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
</select>
</div>
</div>
<div class="flex justify-end gap-md mt-xl pt-lg border-t border-outline-variant/30">
<button type="button" onclick="closeEditModal()" class="px-md py-xs border border-outline-variant text-on-surface-variant rounded-lg hover:bg-surface-container-low transition-colors text-body-sm font-semibold">Cancel</button>
<button type="submit" name="update_profile" class="px-md py-xs bg-primary text-on-primary rounded-lg hover:bg-primary-container transition-colors text-body-sm font-semibold">Save Changes</button>
</div>
</form>
</div>
</div>
<script>
function openEditModal() {
document.getElementById('editModal').style.display = 'flex';
document.body.style.overflow = 'hidden';
}
function closeEditModal() {
document.getElementById('editModal').style.display = 'none';
document.body.style.overflow = '';
}
document.getElementById('editModal').addEventListener('click', function(e) {
if (e.target === this) closeEditModal();
});
</script>
<!-- Interactive Ripple Effect Script for Buttons -->
<script>
    document.querySelectorAll('button, a').forEach(elem => {
      elem.addEventListener('mousedown', function(e) {
        let ripple = document.createElement('span');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        let d = Math.max(this.clientWidth, this.clientHeight);
        ripple.style.width = ripple.style.height = d + 'px';
        let rect = this.getBoundingClientRect();
        ripple.style.left = e.clientX - rect.left - d/2 + 'px';
        ripple.style.top = e.clientY - rect.top - d/2 + 'px';
        ripple.classList.add('show');
        setTimeout(() => ripple.remove(), 600);
      });
    });
  </script>
<style>
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: scale(0);
      pointer-events: none;
    }
    .ripple.show {
      animation: ripple-animation 0.6s linear;
    }
    @keyframes ripple-animation {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
    /* Zebra striping for detailed data tables if added */
    .zebra-row:nth-child(even) {
      background-color: rgba(0, 0, 0, 0.02);
    }
    .zebra-row:hover {
      background-color: rgba(130, 247, 216, 0.05);
    }
    </style>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body></html>