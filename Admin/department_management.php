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
$messageType = '';

if (isset($_GET['success']) && $_GET['success'] === '1') {
    $message = 'Saved successfully.';
    $messageType = 'text-secondary';
}

if (isset($_GET['updated']) && $_GET['updated'] === '1') {
    $message = 'Department updated successfully.';
    $messageType = 'text-secondary';
}

if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
    $message = 'Department deleted successfully.';
    $messageType = 'text-secondary';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_department'])) {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name !== '') {
            $checkStmt = $conn->prepare("SELECT id FROM departments WHERE LOWER(name) = LOWER(?)");
            $checkStmt->bind_param('s', $name);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                $message = 'A department with this name already exists.';
                $messageType = 'text-error';
            } else {
                $stmt = $conn->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
                $stmt->bind_param('ss', $name, $description);
                if ($stmt->execute()) {
                    header('Location: department_management.php?success=1');
                    exit;
                }
                $message = 'Could not save department. Please try again.';
                $messageType = 'text-error';
            }
        } else {
            $message = 'Please enter a department name.';
            $messageType = 'text-error';
        }
    }

    if (isset($_POST['update_department'])) {
        $deptId = (int) ($_POST['department_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $managerName = trim($_POST['manager_name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($deptId > 0 && $name !== '') {
            $stmt = $conn->prepare("UPDATE departments SET name = ?, manager_name = ?, description = ? WHERE id = ?");
            $stmt->bind_param('sssi', $name, $managerName, $description, $deptId);
            if ($stmt->execute()) {
                header('Location: department_management.php?updated=1');
                exit;
            }
            $message = 'Could not update department.';
        } else {
            $message = 'Please enter a department name.';
        }
    }

    if (isset($_POST['delete_department'])) {
        $deptId = (int) ($_POST['department_id'] ?? 0);
        if ($deptId > 0) {
            $conn->query("UPDATE `user` SET department_id = NULL WHERE department_id = $deptId");
            $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
            $stmt->bind_param('i', $deptId);
            if ($stmt->execute()) {
                header('Location: department_management.php?deleted=1');
                exit;
            }
            $message = 'Could not delete department.';
        }
    }

    if (isset($_POST['save_employee'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $departmentId = (int) ($_POST['department_id'] ?? 0);
        $position = trim($_POST['position'] ?? '');

        if ($name !== '' && $email !== '' && $departmentId > 0) {
            $emailCheck = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
            $emailCheck->bind_param('s', $email);
            $emailCheck->execute();
            if ($emailCheck->get_result()->num_rows > 0) {
                $message = 'An employee with this email already exists.';
                $messageType = 'text-error';
            } else {
                $passwordHash = password_hash('123456', PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO `user` (department_id, name, email, password, status, role) VALUES (?, ?, ?, ?, 'Active', 'employee')");
                $stmt->bind_param('isss', $departmentId, $name, $email, $passwordHash);
                if ($stmt->execute()) {
                    $userId = $stmt->insert_id;
                    $profileStmt = $conn->prepare("INSERT INTO employee_profiles (user_id, department_id, email, position, phone, address) VALUES (?, ?, ?, ?, '', '')");
                    $profileStmt->bind_param('iiss', $userId, $departmentId, $email, $position);
                    if ($profileStmt->execute()) {
                        header('Location: department_management.php?success=1');
                        exit;
                    }
                    $message = 'Could not save employee profile. Please try again.';
                    $messageType = 'text-error';
                } else {
                    $message = 'Could not add employee. Please try again.';
                    $messageType = 'text-error';
                }
            }
        } else {
            $message = 'Please fill out the required fields.';
            $messageType = 'text-error';
        }
    }
}

$departmentsQuery = $conn->query("SELECT d.id, d.name, d.manager_name, d.description, COUNT(u.id) AS employee_count FROM departments d LEFT JOIN `user` u ON u.department_id = d.id GROUP BY d.id ORDER BY d.name ASC");
$departments = [];
if ($departmentsQuery && $departmentsQuery->num_rows > 0) {
    while ($row = $departmentsQuery->fetch_assoc()) {
        $departments[] = $row;
    }
}

$totalDepartments = count($departments);
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Department Management</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .zebra-row:nth-child(even) {
            background-color: rgba(0, 107, 88, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c4c6cd;
            border-radius: 10px;
        }
    </style>
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
<link rel="stylesheet" href="../config/dashboard.css"/>    <link rel="stylesheet" href="../config/theme.css"/>
    <script src="../config/theme.js"></script><script>(function(){var s=localStorage.getItem('sidebarClosed');var c=s==='1'||(s===null&&window.innerWidth<768);var root=document.documentElement;root.classList.remove('sidebar-open','sidebar-closed');root.classList.add(c?'sidebar-closed':'sidebar-open');})();</script>
</head>
<body class="bg-background font-body-md text-on-surface">
<?php $activePage = 'departments'; ?>
<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>
<!-- TopNavBar Shell -->
<header id="mainHeader" class="fixed top-0 right-0 w-full h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg h-16 z-40">
<div class="flex items-center gap-lg flex-1">
<button onclick="toggleSidebar()" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-lg transition-colors">menu</button>
<h2 class="font-headline-sm text-headline-sm font-semibold text-primary dark:text-inverse-primary shrink-0"><?= htmlspecialchars($adminName) ?></h2>
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-container-low border-none rounded-full py-xs pl-xl pr-md text-body-md focus:ring-2 focus:ring-secondary" placeholder="Search departments or managers..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="bg-secondary text-on-secondary px-md py-xs rounded-lg font-label-caps text-label-caps hover:bg-secondary-container hover:text-on-secondary-container transition-all flex items-center gap-xs" onclick="document.getElementById('employee-modal').classList.remove('hidden')">
<span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
                Add Employee
            </button>

</div>
</header>
<!-- Main Content Canvas -->
<main id="mainContent" class="pt-16 min-h-screen">
<div class="max-w-[1600px] mx-auto p-lg">
<!-- Header & KPI Row -->
<div class="flex justify-between items-end mb-xl">
<div>
<h3 class="font-display-lg text-display-lg text-primary">Department Management</h3>
<p class="font-body-md text-on-surface-variant">Monitor and manage institutional structures and leadership.</p>
</div>
<button class="bg-primary text-on-primary px-lg py-md rounded-lg font-headline-sm text-headline-sm font-semibold flex items-center gap-sm shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-95 transition-all" onclick="document.getElementById('department-modal').classList.remove('hidden')">
<span class="material-symbols-outlined" data-icon="domain_add">domain_add</span>
                    New Department
                </button>
</div>
<?php if ($message !== ''): ?>
<div class="mb-lg rounded-lg border border-outline-variant bg-surface-container-lowest p-md <?= $messageType ?>">
<?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>
<!-- Dashboard Style Bento Grid / KPIs -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-lg mb-xl">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-secondary">
<p class="font-label-caps text-label-caps text-on-surface-variant mb-xs">TOTAL DEPARTMENTS</p>
<div class="flex items-baseline gap-sm">
<span class="font-display-lg text-display-lg text-primary"><?= $totalDepartments ?></span>
<span class="text-secondary font-semibold text-body-sm">Active</span>
</div>
</div>
</div>
<!-- Data Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container text-on-surface-variant border-b border-outline-variant">
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Department Name</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Manager</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Employee Count</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Description</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<?php if (!empty($departments)): ?>
<?php foreach ($departments as $department): ?>
<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('<?= htmlspecialchars($department['name'], ENT_QUOTES) ?>')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-primary-container text-on-primary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="domain">domain</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary"><?= htmlspecialchars($department['name']) ?></span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center font-bold text-xs"><?= strtoupper(substr(htmlspecialchars($department['manager_name'] ?: '—'), 0, 2)) ?></div>
<div>
<p class="font-body-md font-semibold text-on-surface"><?= htmlspecialchars($department['manager_name'] ?: 'Not assigned') ?></p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full"><?= (int) $department['employee_count'] ?></span>
</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1"><?= htmlspecialchars($department['description'] ?: 'No description available.') ?></p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit" onclick="event.stopPropagation();openEditModal(this)" data-id="<?= (int) $department['id'] ?>" data-name="<?= htmlspecialchars($department['name'], ENT_QUOTES) ?>" data-manager="<?= htmlspecialchars($department['manager_name'] ?? '', ENT_QUOTES) ?>" data-description="<?= htmlspecialchars($department['description'] ?? '', ENT_QUOTES) ?>">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete" onclick="event.stopPropagation();openDeleteModal(this)" data-id="<?= (int) $department['id'] ?>" data-name="<?= htmlspecialchars($department['name'], ENT_QUOTES) ?>">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td class="px-lg py-md" colspan="5">No departments found yet.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
<!-- Empty State Illustration Placeholder (Only shown when filtered to zero) -->
<div class="hidden flex-col items-center justify-center py-xl text-center" id="empty-state">
<div class="h-48 w-48 mb-lg">
<div class="w-full h-full bg-surface-container-high rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-outline text-[64px]" data-icon="search_off">search_off</span>
</div>
</div>
<h4 class="font-headline-md text-headline-md text-primary">No departments found</h4>
<p class="font-body-md text-on-surface-variant">Adjust your search or add a new department to get started.</p>
</div>
</div>
</main>
<!-- Side Slide-Over Modal for Employee List -->
<div class="fixed inset-0 z-50 pointer-events-none overflow-hidden translate-x-full transition-transform duration-300 ease-in-out" id="side-panel">
<div class="absolute inset-0 bg-primary/20 backdrop-blur-sm pointer-events-auto" onclick="closeDetails()"></div>
<div class="absolute right-0 top-0 h-full w-full max-w-lg bg-surface border-l border-outline-variant pointer-events-auto shadow-2xl flex flex-col">
<div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
<div>
<h4 class="font-headline-md text-headline-md text-primary" id="panel-title">Department Details</h4>
<p class="font-label-caps text-label-caps text-secondary tracking-widest uppercase">Employees List</p>
</div>
<button class="p-sm hover:bg-surface-container-high rounded-full transition-colors" onclick="closeDetails()">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
<div class="flex-1 overflow-y-auto custom-scrollbar p-lg">
<div class="space-y-md" id="employee-list">
<!-- Dynamic Employee Rows will be injected here -->
<div class="flex items-center justify-between p-md bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-secondary transition-all cursor-pointer">
<div class="flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary">ED</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Ethan Davis</p>
<p class="text-body-sm text-on-surface-variant">Senior Lead Developer</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="chevron_right">chevron_right</span>
</div>
<!-- Repeat placeholders -->
<div class="flex items-center justify-between p-md bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-secondary transition-all cursor-pointer">
<div class="flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary">SC</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Sophia Chen</p>
<p class="text-body-sm text-on-surface-variant">Full Stack Engineer</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="chevron_right">chevron_right</span>
</div>
<div class="flex items-center justify-between p-md bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-secondary transition-all cursor-pointer">
<div class="flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary">MK</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Marcus Knight</p>
<p class="text-body-sm text-on-surface-variant">Systems Architect</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="chevron_right">chevron_right</span>
</div>
</div>
</div>
<div class="p-lg bg-surface-container-lowest border-t border-outline-variant">
<button class="w-full bg-secondary text-on-secondary py-md rounded-lg font-headline-sm text-headline-sm font-semibold flex justify-center items-center gap-sm">
<span class="material-symbols-outlined" data-icon="person_add">person_add</span>
                    Assign New Employee
                </button>
</div>
</div>
</div>
<!-- Department Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="department-modal">
<div class="bg-surface-container-lowest w-full max-w-xl rounded-xl shadow-xl overflow-hidden">
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">New Department</h3>
<p class="text-on-primary-container text-body-sm">Create a department record in the database</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" type="button" onclick="document.getElementById('department-modal').classList.add('hidden')">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<form class="p-lg space-y-lg" method="post" action="department_management.php">
<input type="hidden" name="save_department" value="1" />
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Department Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="name" required type="text" />
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Description</label>
<textarea class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="description" rows="4"></textarea>
</div>
<div class="flex justify-end gap-md pt-md">
<button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="document.getElementById('department-modal').classList.add('hidden')">Cancel</button>
<button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Save Department</button>
</div>
</form>
</div>
</div>
<!-- Edit Department Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="edit-department-modal">
<div class="bg-surface-container-lowest w-full max-w-xl rounded-xl shadow-xl overflow-hidden">
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Edit Department</h3>
<p class="text-on-primary-container text-body-sm">Update department information</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" type="button" onclick="closeEditModal()">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<form class="p-lg space-y-lg" method="post" action="department_management.php">
<input type="hidden" name="update_department" value="1" />
<input type="hidden" name="department_id" id="edit-department-id" value="" />
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Department Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="name" id="edit-department-name" required type="text" />
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Manager Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="manager_name" id="edit-department-manager" type="text" />
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Description</label>
<textarea class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="description" id="edit-department-description" rows="4"></textarea>
</div>
<div class="flex justify-end gap-md pt-md">
<button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="closeEditModal()">Cancel</button>
<button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Update Department</button>
</div>
</form>
</div>
</div>
<!-- Delete Confirmation Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="delete-department-modal">
<div class="bg-surface-container-lowest w-full max-w-md rounded-xl shadow-xl overflow-hidden">
<div class="bg-error-container p-lg text-error flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Delete Department</h3>
<p class="text-on-error-container text-body-sm">This action cannot be undone.</p>
</div>
<button class="hover:bg-error-container p-2 rounded-full transition-colors" type="button" onclick="closeDeleteModal()">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<form class="p-lg space-y-lg" method="post" action="department_management.php">
<input type="hidden" name="delete_department" value="1" />
<input type="hidden" name="department_id" id="delete-department-id" value="" />
<div class="space-y-base">
<p class="font-body-md text-on-surface">Are you sure you want to delete <strong id="delete-department-name"></strong>?</p>
<p class="font-body-sm text-on-surface-variant">Employees in this department will be unassigned.</p>
</div>
<div class="flex justify-end gap-md pt-md">
<button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="closeDeleteModal()">Cancel</button>
<button class="bg-error text-on-error px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Delete</button>
</div>
</form>
</div>
</div>
<!-- Employee Modal -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="employee-modal">
<div class="bg-surface-container-lowest w-full max-w-2xl rounded-xl shadow-xl overflow-hidden">
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Add New Employee</h3>
<p class="text-on-primary-container text-body-sm">Create a new employee record in the database</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" type="button" onclick="document.getElementById('employee-modal').classList.add('hidden')">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<form class="p-lg grid grid-cols-1 md:grid-cols-2 gap-lg" method="post" action="department_management.php">
<input type="hidden" name="save_employee" value="1" />
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Full Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="name" required type="text" />
</div>
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Email Address</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="email" required type="email" />
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Department</label>
<select class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="department_id" required>
<?php foreach ($departments as $department): ?>
<option value="<?= (int) $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Position</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" name="position" placeholder="e.g. Lead Developer" type="text" />
</div>
<div class="col-span-2 flex justify-end gap-md pt-md">
<button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" type="button" onclick="document.getElementById('employee-modal').classList.add('hidden')">Cancel</button>
<button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps" type="submit">Save Employee</button>
</div>
</form>
</div>
</div>
<script>
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

        function openEditModal(btn) {
            document.getElementById('edit-department-id').value = btn.dataset.id;
            document.getElementById('edit-department-name').value = btn.dataset.name;
            document.getElementById('edit-department-manager').value = btn.dataset.manager;
            document.getElementById('edit-department-description').value = btn.dataset.description;
            document.getElementById('edit-department-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-department-modal').classList.add('hidden');
        }

        function openDeleteModal(btn) {
            document.getElementById('delete-department-id').value = btn.dataset.id;
            document.getElementById('delete-department-name').textContent = btn.dataset.name;
            document.getElementById('delete-department-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-department-modal').classList.add('hidden');
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('department-modal')?.classList.add('hidden');
                document.getElementById('edit-department-modal')?.classList.add('hidden');
                document.getElementById('delete-department-modal')?.classList.add('hidden');
                document.getElementById('employee-modal')?.classList.add('hidden');
            }
        });
    </script>
<?php include __DIR__ . '/../config/sidebar_js.php'; ?>
</body></html>