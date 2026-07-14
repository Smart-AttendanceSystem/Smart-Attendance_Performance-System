<?php
if (!isset($activePage)) $activePage = 'dashboard';

// Ensure avatar column exists on employee_profiles
$_avatarColCheck = $conn->query("SHOW COLUMNS FROM employee_profiles LIKE 'avatar'");
if ($_avatarColCheck && $_avatarColCheck->num_rows === 0) {
    $conn->query("ALTER TABLE employee_profiles ADD COLUMN avatar VARCHAR(500) DEFAULT NULL");
}

// Fetch user details from database with fallbacks
$_userName = 'User';
$_userAvatar = 'https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg';

if (isset($_SESSION['user_id'])) {
    $_userId = (int) $_SESSION['user_id'];
    $_userQuery = $conn->prepare("SELECT u.name, ep.avatar FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = ?");
    $_userQuery->bind_param('i', $_userId);
    $_userQuery->execute();
    $_userResult = $_userQuery->get_result()->fetch_assoc();
    if ($_userResult) {
        $_userName = $_userResult['name'] ?? 'User';
        if (!empty($_userResult['avatar'])) {
            $_userAvatar = '../uploads/avatars/' . htmlspecialchars($_userResult['avatar']);
        }
    }
}

$adminPages = [
    'dashboard' => 'dashboard.php',
    'employees' => 'employee_management.php',
    'departments' => 'department_management.php',
    'attendance' => 'attendenceman.php',
    'leave_requests' => 'leaverequest.php',
    'settings' => 'admin_setting.php',
    
];


function userSidebarLink($page, $activePage, $icon, $label)
{
    global $adminPages;
    $href = $adminPages[$page] ?? '#';
    $isActive = ($activePage === $page);
    $activeClass = $isActive
        ? 'bg-gray-100 text-gray-900 dark:text-slate-100 font-semibold'
        : 'text-gray-600 dark:text-black cursor-pointer';
    $iconStyle = $isActive ? ' style="font-variation-settings: \'FILL\' 1;"' : '';
    return '<a class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors ' . $activeClass . '" href="' . $href . '">'
        . '<span class="material-symbols-outlined shrink-0"' . $iconStyle . '>' . $icon . '</span>'
        . '<span class="sidebar-label text-base whitespace-nowrap">' . htmlspecialchars($label) . '</span>'
        . '</a>';
}
?>
<aside id="sidebar" class="fixed left-0 top-0 h-full w-[280px] bg-white dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col py-6 z-50 overflow-y-auto overflow-x-hidden scrollbar-hide">
    <div class="sidebar-header flex items-center justify-center mb-6">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-secondary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-white text-lg">badge</span>
            </div>
            <div class="sidebar-header-text min-w-0">
                
                <h1 class="font-headline-md text-2xl font-bold text-gray-900 dark:text-white truncate">Smart Attendance</h1>
                <p class="text-lg text-gray-500 dark:text-slate-400 truncate">Admin</p>
            </div>
        </div>
    </div>
    <nav class="flex-grow flex flex-col space-y-6 px-1 ">
    <?= userSidebarLink('dashboard', $activePage, 'dashboard', 'Dashboard') ?>
    <?= userSidebarLink('employees', $activePage, 'groups', 'Employees') ?>
    <?= userSidebarLink('departments', $activePage, 'apartment', 'Departments') ?>
    <?= userSidebarLink('attendance', $activePage, 'schedule', 'Attendance') ?>
    <?= userSidebarLink('leave_requests', $activePage, 'event_note', 'Leave Requests') ?>
    <?= userSidebarLink('settings', $activePage, 'settings', 'Settings') ?>
</nav>
    <div class="mt-auto border-t border-on-primary-fixed-variant/20 pt-4 space-y-0.5 px-1">
        <a class="sidebar-logout-link sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-100 cursor-pointer" href="../auth/logout.php">
            <span class="material-symbols-outlined shrink-0">logout</span>
            <span class="sidebar-logout-label sidebar-label text-base whitespace-nowrap">Logout</span>
        </a>
        <div class="sidebar-profile flex items-center gap-3 px-4 py-3 mt-2">
            <img class="w-9 h-9 rounded-full border-2 border-secondary object-cover shrink-0" alt="<?= htmlspecialchars($_userName) ?>" src="<?= $_userAvatar ?>" />
            <div class="sidebar-profile-name min-w-0">
                <span class="text-gray-900 dark:text-white font-semibold text-base block truncate"><?= htmlspecialchars($_userName) ?></span>
            </div>
        </div>
    </div>
</aside>

<script>
(function() {
    var s = localStorage.getItem('sidebarClosed');
    var c = s === '1' || (s === null && window.innerWidth < 768);
    var r = document.documentElement;
    r.classList.remove('sidebar-open', 'sidebar-closed');
    r.classList.add(c ? 'sidebar-closed' : 'sidebar-open');
})();
</script>
