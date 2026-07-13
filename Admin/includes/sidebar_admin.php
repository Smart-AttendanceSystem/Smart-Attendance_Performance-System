<?php
if (!isset($activePage)) $activePage = 'dashboard';
if (!isset($adminName)) $adminName = 'Admin';
if (!isset($adminAvatarDisplay)) $adminAvatarDisplay = 'https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg';

$adminPages = [
    'dashboard' => 'dashboard.php',
    'employees' => 'employee_management.php',
    'departments' => 'department_management.php',
    'attendance' => 'attendenceman.php',
    'leave_requests' => 'leaverequest.php',
    'settings' => 'admin_setting.php',
];

function adminSidebarLink($page, $activePage, $icon, $label)
{
    $href = $GLOBALS['adminPages'][$page] ?? '#';
    $isActive = ($activePage === $page);
    $activeClass = $isActive
        ? 'border-l-4 border-secondary bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-slate-100 font-semibold cursor-pointer'
        : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-100 hover:bg-gray-50 dark:hover:bg-slate-800/30';
    $iconStyle = $isActive ? ' style="font-variation-settings: \'FILL\' 1;"' : '';
    return '<a class="flex items-center gap-md px-md py-sm transition-none ' . $activeClass . '" href="' . $href . '">'
        . '<span class="material-symbols-outlined"' . $iconStyle . '>' . $icon . '</span>'
        . '<span class="font-label-caps text-sm">' . htmlspecialchars($label) . '</span>'
        . '</a>';
}
?>
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>
<aside id="sidebar" class="fixed left-0 top-0 h-full w-[260px] bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-slate-800 shadow-sm flex flex-col py-lg z-50 overflow-y-auto scrollbar-hide">
    <div class="px-md mb-xl">
        <h1 class="font-headline-md text-2xl font-bold text-gray-900 dark:text-white tracking-tight"><?= htmlspecialchars($adminName) ?></h1>
        <p class="font-body-md text-base text-gray-500 dark:text-slate-400 opacity-90">HR Management System</p>
    </div>
    <nav class="flex-grow space-y-1">
        <?= adminSidebarLink('dashboard', $activePage, 'dashboard', 'Dashboard') ?>
        <?= adminSidebarLink('employees', $activePage, 'groups', 'Employees') ?>
        <?= adminSidebarLink('departments', $activePage, 'domain', 'Departments') ?>
        <?= adminSidebarLink('attendance', $activePage, 'fact_check', 'Attendance') ?>
        <?= adminSidebarLink('leave_requests', $activePage, 'event_busy', 'Leave Requests') ?>
    </nav>
    <div class="mt-auto border-t border-gray-200 dark:border-slate-800 pt-lg space-y-1">
        <?= adminSidebarLink('settings', $activePage, 'settings', 'Settings') ?>
        <a class="flex items-center gap-md px-md py-sm transition-none text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-100 cursor-pointer" href="../auth/logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-label-caps text-sm">Logout</span>
        </a>
        <div class="px-md mt-md flex items-center gap-sm">
            <img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" alt="<?= htmlspecialchars($adminName) ?>" src="<?= $adminAvatarDisplay ?>" />
            <div class="flex flex-col">
                <span class="text-gray-900 dark:text-white font-semibold text-base"><?= htmlspecialchars($adminName) ?></span>
            </div>
        </div>
    </div>
</aside>