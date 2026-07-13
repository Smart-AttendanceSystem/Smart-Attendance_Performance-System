<?php
if (!isset($activePage)) $activePage = 'dashboard';

$_userAvatar = 'https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg';
$_userName = $_SESSION['user_name'] ?? 'User';
if (isset($_SESSION['user_id'])) {
    $_uid = (int) $_SESSION['user_id'];
    $_q = $conn->prepare("SELECT u.name, ep.avatar FROM `user` u LEFT JOIN employee_profiles ep ON ep.user_id = u.id WHERE u.id = ?");
    $_q->bind_param('i', $_uid);
    $_q->execute();
    $_r = $_q->get_result()->fetch_assoc();
    if ($_r) {
        $_userName = $_r['name'] ?? $_userName;
        if (!empty($_r['avatar'])) {
            $_userAvatar = '../uploads/avatars/' . htmlspecialchars($_r['avatar']);
        }
    }
}

$userPages = [
    'dashboard' => 'userdashboard.php',
    'attendance' => 'attendence.php',
    'leave_request' => 'leaveform.php',
    'leave_status' => 'leavestatus.php',
    'profile' => 'profile.php',
    'change_password' => 'requestpassword.php',
];

function userSidebarLink($page, $activePage, $icon, $label)
{
    $href = $GLOBALS['userPages'][$page] ?? '#';
    $isActive = ($activePage === $page);
    $activeClass = $isActive
        ? 'border-l-4 border-secondary bg-gray-100 text-gray-900 dark:text-slate-100 cursor-pointer font-semibold'
        : 'text-gray-600 dark:text-black cursor-pointer ';
    $iconStyle = $isActive ? ' style="font-variation-settings: \'FILL\' 1;"' : '';
    return '<a class="flex items-center gap-md px-md py-sm transition-none ' . $activeClass . '" href="' . $href . '">'
        . '<span class="material-symbols-outlined"' . $iconStyle . '>' . $icon . '</span>'
        . '<span class="font-label-caps text-sm">' . htmlspecialchars($label) . '</span>'
        . '</a>';
}
?>
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>
<aside id="sidebar" class="fixed left-0 top-0 h-full w-[260px] bg-white dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col py-lg z-50 overflow-y-auto scrollbar-hide">
    <div class="px-md mb-xl">
<h1 class="font-headline-md text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Smart Attendance</h1>
    </div>
    <nav class="flex-grow space-y-1">
        <?= userSidebarLink('dashboard', $activePage, 'dashboard', 'Dashboard') ?>
        <?= userSidebarLink('attendance', $activePage, 'schedule', 'Attendence') ?>
        <?= userSidebarLink('leave_request', $activePage, 'event_note', 'Leave Request') ?>
        <?= userSidebarLink('leave_status', $activePage, 'assignment_turned_in', 'Leave Status') ?>
        <?= userSidebarLink('profile', $activePage, 'person', 'Profile') ?>
    </nav>
    <div class="mt-auto border-t border-on-primary-fixed-variant/20 pt-lg space-y-1">
        <?= userSidebarLink('change_password', $activePage, 'lock', 'Change Password') ?>
        <a class="flex items-center gap-md px-md py-sm transition-none text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-slate-100 cursor-pointer" href="../auth/logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-label-caps text-sm">Logout</span>
        </a>
        <div class="px-md mt-md flex items-center gap-sm">
            <img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" alt="<?= htmlspecialchars($_userName) ?>" src="<?= $_userAvatar ?>" />
            <div class="flex flex-col">
                <span class="text-gray-900 dark:text-white font-semibold text-base"><?= htmlspecialchars($_userName) ?></span>
            </div>
        </div>
    </div>
</aside>