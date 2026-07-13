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

$message = '';

if (isset($_GET['success']) && $_GET['success'] === '1') {
    $message = 'Employee added successfully.';
}

if (isset($_GET['updated']) && $_GET['updated'] === '1') {
    $message = 'Employee updated successfully.';
}

if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
    $message = 'Employee deleted successfully.';
}

if (isset($_GET['password_reset']) && $_GET['password_reset'] === '1') {
    $message = 'Password reset successfully.';
}

$sort = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_employee_password'])) {
    $employeeId = (int) ($_POST['employee_id'] ?? 0);
    $newPassword = trim($_POST['new_password'] ?? '');

    if ($employeeId <= 0 || strlen($newPassword) < 4) {
        $message = 'Invalid request.';
    } else {
        $empCheck = $conn->prepare("SELECT id, name FROM `user` WHERE id = ? AND role = 'employee'");
        $empCheck->bind_param('i', $employeeId);
        $empCheck->execute();
        $empResult = $empCheck->get_result();
        if ($empResult->num_rows > 0) {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE `user` SET password = ? WHERE id = ?");
            $updateStmt->bind_param('si', $passwordHash, $employeeId);
            if ($updateStmt->execute()) {
                header('Location: employee_management.php?password_reset=1');
                exit;
            } else {
                $message = 'Could not reset password. Please try again.';
            }
        } else {
            $message = 'Employee not found.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_employee'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $departmentId = (int) ($_POST['department_id'] ?? 0);
    $position = trim($_POST['position'] ?? '');
    $employmentType = trim($_POST['employment_type'] ?? 'Full-time');

    if ($name !== '' && $email !== '' && $departmentId > 0) {
        $emailCheck = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
        $emailCheck->bind_param('s', $email);
        $emailCheck->execute();
        if ($emailCheck->get_result()->num_rows > 0) {
            $message = 'An employee with this email already exists.';
        } else {
            $passwordHash = password_hash('123456', PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO `user` (department_id, name, email, password, status, role) VALUES (?, ?, ?, ?, 'Active', 'employee')");
            $stmt->bind_param('isss', $departmentId, $name, $email, $passwordHash);
            if ($stmt->execute()) {
                $userId = $stmt->insert_id;
                $profileStmt = $conn->prepare("INSERT INTO employee_profiles (user_id, department_id, email, position, phone, address) VALUES (?, ?, ?, ?, '', '')");
                $profileStmt->bind_param('iiss', $userId, $departmentId, $email, $position);
                $profileStmt->execute();
                header('Location: employee_management.php?success=1');
                exit;
            } else {
                $message = 'Could not add employee. Please try again.';
            }
        }
    } else {
        $message = 'Please fill out the required fields.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_employee'])) {
    $employeeId = (int) ($_POST['employee_id'] ?? 0);
    if ($employeeId > 0) {
        $conn->query("DELETE FROM leave_requests WHERE user_id = $employeeId");
        $conn->query("DELETE FROM attendance WHERE user_id = $employeeId");
        $conn->query("DELETE FROM employee_profiles WHERE user_id = $employeeId");
        $stmt = $conn->prepare("DELETE FROM `user` WHERE id = ? AND role = 'employee'");
        $stmt->bind_param('i', $employeeId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            header('Location: employee_management.php?deleted=1');
            exit;
        } else {
            $message = 'Could not delete employee.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $employeeId = (int) ($_POST['employee_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $departmentId = (int) ($_POST['department_id'] ?? 0);
    $position = trim($_POST['position'] ?? '');

    if ($employeeId > 0 && $name !== '' && $email !== '' && $departmentId > 0) {
        $stmt = $conn->prepare("UPDATE `user` SET name = ?, email = ?, department_id = ? WHERE id = ? AND role = 'employee'");
        $stmt->bind_param('ssii', $name, $email, $departmentId, $employeeId);
        if ($stmt->execute()) {
            $profileStmt = $conn->prepare("UPDATE employee_profiles SET department_id = ?, email = ?, position = ? WHERE user_id = ?");
            $profileStmt->bind_param('issi', $departmentId, $email, $position, $employeeId);
            $profileStmt->execute();
            header('Location: employee_management.php?updated=1');
            exit;
        } else {
            $message = 'Could not update employee.';
        }
    } else {
        $message = 'Please fill out all required fields.';
    }
}

if (isset($_GET['ajax_employee'])) {
    $ajaxId = (int) $_GET['ajax_employee'];
    $ajaxRes = $conn->query("SELECT u.id, u.name, u.email, u.department_id, ep.position FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = $ajaxId AND u.role = 'employee'");
    if ($ajaxRes && $ajaxRes->num_rows > 0) {
        header('Content-Type: application/json');
        echo json_encode($ajaxRes->fetch_assoc());
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Employee not found.']);
    exit;
}

$currentMonth = date('m');
$currentYear = date('Y');

$totalEmployees = 0;
$newHiresThisMonth = 0;
$employees = [];
$departments = [];
$deptStats = [];
$deptFilter = isset($_GET['dept_filter']) ? (int) $_GET['dept_filter'] : 0;

$empCountRes = $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee'");
if ($empCountRes) $totalEmployees = (int) $empCountRes->fetch_row()[0];

$newRes = $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee' AND MONTH(created_at) = $currentMonth AND YEAR(created_at) = $currentYear");
if ($newRes) $newHiresThisMonth = (int) $newRes->fetch_row()[0];

$filteredCount = 0;
if ($deptFilter > 0) {
    $filteredCountRes = $conn->query("SELECT COUNT(*) FROM `user` WHERE role = 'employee' AND department_id = $deptFilter");
} else {
    $filteredCountRes = $conn->query("SELECT COUNT(*) FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.role = 'employee' AND ep.position LIKE '%Manager%'");
}
if ($filteredCountRes) $filteredCount = (int) $filteredCountRes->fetch_row()[0];

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$totalPages = max(1, ceil($filteredCount / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

if ($deptFilter > 0) {
    $empRes = $conn->query("SELECT u.id, u.name, u.email, u.status, d.name AS department_name, ep.position FROM `user` u LEFT JOIN departments d ON d.id = u.department_id LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.role = 'employee' AND u.department_id = $deptFilter ORDER BY u.id $sort LIMIT $perPage OFFSET $offset");
} else {
    $empRes = $conn->query("SELECT u.id, u.name, u.email, u.status, d.name AS department_name, ep.position FROM `user` u LEFT JOIN departments d ON d.id = u.department_id LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.role = 'employee' AND ep.position LIKE '%Manager%' ORDER BY u.id $sort LIMIT $perPage OFFSET $offset");
}
if ($empRes && $empRes->num_rows > 0) {
    while ($row = $empRes->fetch_assoc()) {
        $employees[] = $row;
    }
}

$deptRes = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
if ($deptRes && $deptRes->num_rows > 0) {
    while ($row = $deptRes->fetch_assoc()) {
        $departments[] = $row;
    }
}

$deptStatsRes = $conn->query("SELECT d.id, d.name, COUNT(u.id) AS emp_count FROM departments d LEFT JOIN `user` u ON u.department_id = d.id AND u.role = 'employee' GROUP BY d.id, d.name ORDER BY d.name ASC");
if ($deptStatsRes && $deptStatsRes->num_rows > 0) {
    while ($row = $deptStatsRes->fetch_assoc()) {
        $deptStats[] = $row;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Employee Management</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Scripts -->
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
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c4c6cd; border-radius: 10px; }
        .zebra-row:nth-child(even) { background-color: #f3f4f5; }
        .hover-row:hover { background-color: rgba(0, 107,88,0.2); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container">
<?php $activePage = 'employees'; ?>
<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>
<!-- Main Content Area -->
<div id="mainContent" class="min-h-screen flex flex-col">
<!-- TopNavBar -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-16 bg-surface border-b border-outline-variant flex justify-between items-center px-lg h-16 z-40 shadow-sm">
<div class="flex items-center gap-lg flex-1">
<button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
<span class="font-headline-sm text-headline-sm font-semibold text-primary">HR Admin</span>
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input id="hrAdminSearch" name="search" class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-secondary text-body-md" placeholder="Search by name, department, or ID..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="bg-secondary text-on-secondary px-md py-2 rounded-lg font-label-caps text-label-caps transition-all duration-200 hover:opacity-90 active:scale-95" onclick="document.getElementById('modal-overlay').classList.remove('hidden')">
                    Add Employee
                </button>

</div>
</header>
<!-- Main Canvas -->
<main class="mt-16 p-lg flex-1">
<div class="max-w-[1600px] mx-auto space-y-lg">
<?php if ($message !== ''): ?>
<div class="bg-secondary/10 border border-secondary/30 text-secondary px-lg py-md rounded-lg font-body-sm flex items-center gap-md" id="success-message">
<span class="material-symbols-outlined text-secondary">check_circle</span>
<span><?= htmlspecialchars($message) ?></span>
<button class="ml-auto text-secondary/60 hover:text-secondary" onclick="this.parentElement.remove()">&times;</button>
</div>
<?php endif; ?>
<!-- Page Title & Stats Bar -->
<div class="flex justify-between items-end">
<div>
<h2 class="font-headline-md text-headline-md text-primary">Employee Directory</h2>
<p class="text-on-surface-variant font-body-md">Manage your workforce, positions, and operational status.</p>
</div>
<div class="flex gap-md">
<div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl shadow-sm flex items-center gap-md">
<div class="bg-secondary/10 p-2 rounded-lg text-secondary">
<span class="material-symbols-outlined">groups</span>
</div>
<div>
<p class="text-label-caps font-label-caps text-on-surface-variant">Total Staff</p>
<p class="text-headline-sm font-headline-sm text-primary"><?= number_format($totalEmployees) ?></p>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl shadow-sm flex items-center gap-md">
<div class="bg-tertiary-container/20 p-2 rounded-lg text-tertiary-container">
<span class="material-symbols-outlined">person_add</span>
</div>
<div>
<p class="text-label-caps font-label-caps text-on-surface-variant">New This Month</p>
<p class="text-headline-sm font-headline-sm text-primary"><?= $newHiresThisMonth ?></p>
</div>
</div>
</div>
</div>
<!-- Department Filter Dropdown -->
<div class="flex items-center gap-sm -mt-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Filter by Department</label>
<select onchange="if(this.value) window.location='employee_management.php?dept_filter='+this.value+'&sort=<?= strtolower($sort) ?>'; else window.location='employee_management.php?sort=<?= strtolower($sort) ?>';" class="border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary text-body-sm py-2 px-md">
<option value="">All Departments (Managers)</option>
<?php foreach ($deptStats as $ds): ?>
<option value="<?= (int) $ds['id'] ?>" <?= $deptFilter === (int) $ds['id'] ? 'selected' : '' ?>><?= htmlspecialchars($ds['name']) ?> </option>
<?php endforeach; ?>
</select>
</div>
<!-- Table Container -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant cursor-pointer select-none hover:text-primary transition-colors" onclick="window.location='employee_management.php?page=<?= $page ?>&sort=<?= $sort === 'ASC' ? 'desc' : 'asc' ?><?= $deptFilter > 0 ? '&amp;dept_filter='.$deptFilter : '' ?>'">ID <span class="material-symbols-outlined text-[14px] align-middle"><?= $sort === 'ASC' ? 'arrow_upward' : 'arrow_downward' ?></span></th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Name</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Department</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Position</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Status</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Email</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant text-right">Actions</th>
</tr>
</thead>
<tbody class="font-body-md text-on-surface">
<?php if (!empty($employees)): ?>
<?php foreach ($employees as $emp): ?>
<tr class=" hover-row border-b border-surface-container transition-colors">
<td class="px-lg py-md font-data-mono text-data-mono">YGN-<?= str_pad($emp['id'], 4, '0', STR_PAD_LEFT) ?></td>
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-dim overflow-hidden flex items-center justify-center text-sm font-semibold text-primary bg-surface-container-high border border-outline-variant">
<?= htmlspecialchars(strtoupper(substr($emp['name'], 0, 1))) ?>
</div>
<span class="font-semibold"><?= htmlspecialchars($emp['name']) ?></span>
</div>
</td>
<td class="px-lg py-md"><?= htmlspecialchars($emp['department_name'] ?? '—') ?></td>
<td class="px-lg py-md text-on-surface-variant"><?= htmlspecialchars($emp['position'] ?? '—') ?></td>
<td class="px-lg py-md">
<?php $status = strtolower($emp['status'] ?? 'active'); ?>
<span class="inline-flex items-center gap-base px-2 py-1 rounded-full text-[11px] font-bold <?= $status === 'active' ? 'bg-secondary-container text-on-secondary-container' : ($status === 'on leave' ? 'bg-tertiary-fixed text-on-tertiary-fixed-variant' : 'bg-error-container/30 text-error') ?>">
<span class="w-2 h-2 rounded-full <?= $status === 'active' ? 'bg-secondary' : ($status === 'on leave' ? 'bg-tertiary' : 'bg-error') ?>"></span> <?= htmlspecialchars($emp['status'] ?? 'Active') ?>
</span>
</td>
<td class="px-lg py-md text-on-surface-variant"><?= htmlspecialchars($emp['email'] ?? '—') ?></td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs">
<button class="p-1 hover:bg-primary-container/10 rounded text-primary transition-colors" title="Edit Profile" onclick="openEditModal(<?= $emp['id'] ?>)"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-1 hover:bg-tertiary-container/10 rounded text-tertiary transition-colors" title="Reset Password" onclick="openPasswordModal(<?= $emp['id'] ?>, '<?= htmlspecialchars(addslashes($emp['name'])) ?>')"><span class="material-symbols-outlined text-[18px]">lock_reset</span></button>
<button class="p-1 hover:bg-error-container/20 rounded text-error transition-colors" title="Delete" onclick="confirmDelete(<?= $emp['id'] ?>, '<?= htmlspecialchars(addslashes($emp['name'])) ?>')"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td class="px-lg py-md text-on-surface-variant" colspan="7">No employees found.</td></tr>
<?php endif; ?>

</tbody>
</table>
</div>
<!-- Pagination Footer -->
<div class="p-lg bg-surface-container-lowest border-t border-outline-variant flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant">Showing <?= count($employees) ?> of <?= number_format($filteredCount) ?> employees</span>
<div class="flex gap-xs">
<?php if ($page > 1): ?>
<a class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container transition-colors text-body-sm" href="employee_management.php?page=<?= $page - 1 ?>&sort=<?= strtolower($sort) ?><?= $deptFilter > 0 ? '&dept_filter='.$deptFilter : '' ?>">Previous</a>
<?php else: ?>
<button class="px-3 py-1 border border-outline-variant rounded text-body-sm opacity-30" disabled>Previous</button>
<?php endif; ?>
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<a class="px-3 py-1 rounded text-body-sm <?= $i === $page ? 'bg-secondary text-on-secondary font-bold' : 'border border-outline-variant hover:bg-surface-container transition-colors' ?>" href="employee_management.php?page=<?= $i ?>&sort=<?= strtolower($sort) ?><?= $deptFilter > 0 ? '&dept_filter='.$deptFilter : '' ?>"><?= $i ?></a>
<?php endfor; ?>
<?php if ($page < $totalPages): ?>
<a class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container transition-colors text-body-sm" href="employee_management.php?page=<?= $page + 1 ?>&sort=<?= strtolower($sort) ?><?= $deptFilter > 0 ? '&dept_filter='.$deptFilter : '' ?>">Next</a>
<?php else: ?>
<button class="px-3 py-1 border border-outline-variant rounded text-body-sm opacity-30" disabled>Next</button>
<?php endif; ?>
</div>
</div>
</div>
</div>
</main>
</div>
<!-- Add Employee Modal Overlay -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="modal-overlay">
<div class="bg-surface-container-lowest w-full max-w-2xl rounded-xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-300">
<!-- Modal Header -->
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Add New Employee</h3>
<p class="text-on-primary-container text-body-sm">Create a new record in the HR database</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('modal-overlay').classList.add('hidden')">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<!-- Modal Body -->
<form class="p-lg grid grid-cols-1 md:grid-cols-2 gap-lg" method="post" action="employee_management.php">
<input type="hidden" name="save_employee" value="1" />
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Full Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="name" placeholder="e.g. John Doe" type="text" required/>
</div>
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Email Address</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="email" placeholder="john.doe@company.com" type="email" required/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Department</label>
<select class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="department_id" required>
<option value="">Select Department</option>
<?php foreach ($departments as $dept): ?>
<option value="<?= (int) $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Position</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="position" placeholder="e.g. Lead Developer" type="text"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Employment Type</label>
<div class="flex gap-md mt-base">
<label class="flex items-center gap-xs cursor-pointer">
<input checked="" class="text-secondary focus:ring-secondary" name="employment_type" type="radio" value="Full-time"/>
<span class="text-body-sm">Full-time</span>
</label>
<label class="flex items-center gap-xs cursor-pointer">
<input class="text-secondary focus:ring-secondary" name="employment_type" type="radio" value="Contract"/>
<span class="text-body-sm">Contract</span>
</label>
</div>
</div>
<div class="flex items-end col-span-2">
<button type="submit" class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps">Save Employee</button>
</div>
</form>
</div>
</div>
<!-- Password Reset Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[110] flex items-center justify-center p-md" id="password-modal">
<div class="bg-surface-container-lowest w-full max-w-md rounded-xl shadow-xl overflow-hidden">
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Reset Password</h3>
<p class="text-on-primary-container text-body-sm" id="password-employee-name">Employee</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('password-modal').classList.add('hidden')"><span class="material-symbols-outlined">close</span></button>
</div>
<form class="p-lg space-y-lg" method="post" action="employee_management.php">
<input type="hidden" name="reset_employee_password" value="1" />
<input type="hidden" name="employee_id" id="password-employee-id" value="0" />
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">New Password</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="new_password" type="password" required minlength="4"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Confirm Password</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="confirm_password" type="password" required/>
</div>
<div class="flex justify-end gap-md pt-md">
<button type="button" class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" onclick="document.getElementById('password-modal').classList.add('hidden')">Cancel</button>
<button type="submit" class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps">Reset Password</button>
</div>
</form>
</div>
</div>
<!-- Delete Confirmation Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[120] flex items-center justify-center p-md" id="delete-modal">
<div class="bg-surface-container-lowest w-full max-w-sm rounded-xl shadow-xl overflow-hidden">
<div class="bg-error p-lg text-on-error flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Delete Employee</h3>
<p class="text-error-container text-body-sm" id="delete-employee-name">Employee</p>
</div>
<button class="hover:bg-error-container/30 p-2 rounded-full transition-colors" onclick="document.getElementById('delete-modal').classList.add('hidden')"><span class="material-symbols-outlined">close</span></button>
</div>
<form class="p-lg space-y-lg" method="post" action="employee_management.php">
<input type="hidden" name="delete_employee" value="1" />
<input type="hidden" name="employee_id" id="delete-employee-id" value="0" />
<p class="text-body-md text-on-surface-variant">Are you sure you want to delete this employee? This action cannot be undone.</p>
<div class="flex justify-end gap-md pt-md">
<button type="button" class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" onclick="document.getElementById('delete-modal').classList.add('hidden')">Cancel</button>
<button type="submit" class="bg-error text-on-error px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps">Delete</button>
</div>
</form>
</div>
</div>
<!-- Edit Employee Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[130] flex items-center justify-center p-md" id="edit-modal">
<div class="bg-surface-container-lowest w-full max-w-2xl rounded-xl shadow-xl overflow-hidden">
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Edit Employee</h3>
<p class="text-on-primary-container text-body-sm">Update employee details</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('edit-modal').classList.add('hidden')">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<form class="p-lg grid grid-cols-1 md:grid-cols-2 gap-lg" method="post" action="employee_management.php">
<input type="hidden" name="update_employee" value="1" />
<input type="hidden" name="employee_id" id="edit-employee-id" value="0" />
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Full Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="name" id="edit-name" type="text" required/>
</div>
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Email Address</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="email" id="edit-email" type="email" required/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Department</label>
<select class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="department_id" id="edit-department-id" required>
<option value="">Select Department</option>
<?php foreach ($departments as $dept): ?>
<option value="<?= (int) $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Position</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="position" id="edit-position" type="text"/>
</div>
<div class="flex items-end col-span-2">
<button type="submit" class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps">Update Employee</button>
</div>
</form>
</div>
</div>
<script>
        function confirmDelete(id, name) {
            document.getElementById('delete-employee-id').value = id;
            document.getElementById('delete-employee-name').textContent = 'Delete employee: ' + name;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function openEditModal(id) {
            fetch('employee_management.php?ajax_employee=' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.error) { alert(data.error); return; }
                    document.getElementById('edit-employee-id').value = data.id;
                    document.getElementById('edit-name').value = data.name;
                    document.getElementById('edit-email').value = data.email;
                    document.getElementById('edit-department-id').value = data.department_id;
                    document.getElementById('edit-position').value = data.position || '';
                    document.getElementById('edit-modal').classList.remove('hidden');
                })
                .catch(() => alert('Could not load employee data.'));
        }

        function openPasswordModal(id, name) {
            document.getElementById('password-employee-id').value = id;
            document.getElementById('password-employee-name').textContent = 'Resetting password for: ' + name;
            document.getElementById('password-modal').classList.remove('hidden');
        }

        // Simple search interaction mockup
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.zebra-row');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }

        // Auto-dismiss success message after 4s
        const msg = document.getElementById('success-message');
        if (msg) setTimeout(() => { msg.style.transition = 'opacity 0.5s'; msg.style.opacity = '0'; setTimeout(() => msg.remove(), 500); }, 4000);

        // Close modals on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('modal-overlay').classList.add('hidden');
                document.getElementById('password-modal').classList.add('hidden');
                document.getElementById('delete-modal').classList.add('hidden');
                document.getElementById('edit-modal').classList.add('hidden');
            }
        });

       
document.getElementById('hrAdminSearch').addEventListener('input', function() {
    // 1. Get the lowercase text typed by the admin user
    const query = this.value.toLowerCase().trim();
    
    // 2. Select all active data rows inside your employee/attendance table body
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        // 3. Scan the column text cells (Name, Department, and Employee ID)
        const nameColumn = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
        const deptColumn = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
        const idColumn = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

        // 4. If the row contains what you searched for, show it; otherwise, hide it
        if (nameColumn.includes(query) || deptColumn.includes(query) || idColumn.includes(query)) {
            row.style.display = ''; // Show row
        } else {
            row.style.display = 'none'; // Hide row
        }
    });
});
    </script>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body></html>