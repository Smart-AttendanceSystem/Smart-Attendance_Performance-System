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
<!-- SideNavBar Shell -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-primary dark:bg-surface-container-highest shadow-sm border-r border-outline-variant dark:border-outline flex flex-col py-lg z-50">
<!-- Brand Header -->
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary tracking-tight">Admin</h1>
<p class="font-body-sm text-on-primary opacity-80">HR Management System</p>
</div>
<!-- Navigation Links -->
<nav class="flex-1 space-y-1">
<!-- Dashboard -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95 group" href="dashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<!-- Employees -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="employeement_management.php">
<span class="material-symbols-outlined">groups</span>
<span class="font-label-caps text-label-caps">Employees</span>
</a>
<!-- Departments -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="department_management.php">
<span class="material-symbols-outlined">domain</span>
<span class="font-label-caps text-label-caps">Departments</span>
</a>
<!-- Attendance -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendenceman.php">
<span class="material-symbols-outlined">fact_check</span>
<span class="font-label-caps text-label-caps">Attendance</span>
</a>
<!-- Leave Requests (ACTIVE) -->
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95" href="leaverequest.php">
<span class="material-symbols-outlined">event_busy</span>
<span class="font-label-caps text-label-caps">Leave Requests</span>
</a>
</nav>
<!-- Footer Actions -->
<div class="mt-auto border-t border-on-primary-container/10 pt-lg">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="admin_setting.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-caps text-label-caps">Settings</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
<div class="px-md mt-lg flex items-center gap-sm">
<div class="w-10 h-10 rounded-full overflow-hidden border border-outline-variant/30">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" data-alt="A professional high-resolution corporate headshot of a middle-aged HR executive with a kind smile, wearing a dark navy blazer over a crisp white shirt. The background is a soft-focus modern office interior with warm wooden accents and bright morning sunlight streaming through glass partitions. The lighting is flattering and high-key, conveying a sense of leadership and institutional trust." src="https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg"/>
</div>
<div>
<p class="font-body-sm font-semibold text-on-primary">Admin</p>
<p class="text-[10px] uppercase tracking-widest text-on-primary-container">Admin</p>
</div>
</div>
</div>
</aside>
<!-- Main Content Shell -->
<main class="ml-[260px] min-h-screen">
<!-- TopNavBar Shell -->
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg z-40">
<div class="flex items-center gap-lg">
<h2 class="font-headline-sm text-headline-sm font-semibold text-primary dark:text-inverse-primary">HR Admin</h2>
<div class="relative w-80">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
<input class="w-full h-9 pl-10 pr-4 bg-surface-container-low border-none rounded-lg text-body-sm focus:ring-1 focus:ring-secondary" placeholder="Search employees or departments..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high transition-all duration-200">
<span class="material-symbols-outlined">notifications</span>
</button>
<div class="h-8 w-[1px] bg-outline-variant mx-sm"></div>
<button class="bg-secondary text-on-secondary px-md py-1.5 rounded-lg font-body-sm font-semibold hover:brightness-110 active:scale-95 transition-all">
                    Add Employee
                </button>
</div>
</header>
<!-- Canvas Container -->
<div class="pt-16 p-lg max-w-[1600px] mx-auto">
<!-- Page Header & Tabs -->
<div class="mb-lg">
<div class="flex justify-between items-end mb-lg">
<div>
<nav class="flex gap-xs text-[10px] font-label-caps uppercase tracking-widest text-on-surface-variant mb-base">
<span>Admin</span>
<span>/</span>
<span class="text-secondary">Leave Requests</span>
</nav>
<h3 class="font-display-lg text-display-lg text-primary">Leave Management</h3>
</div>
<div class="flex items-center gap-sm bg-surface-container p-1 rounded-xl">
<button class="flex items-center gap-xs px-md py-2 bg-surface-container-lowest text-primary font-semibold rounded-lg shadow-sm">
<span class="material-symbols-outlined text-[20px]">pending_actions</span>
                            Pending Requests
                        </button>
<button class="flex items-center gap-xs px-md py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">history</span>
                            Leave History
                        </button>
</div>
</div>
<!-- KPI Overview Row -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-md mb-xl">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-t-secondary">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase mb-xs">Active Requests</p>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-primary">12</span>

</div>
</div>

<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-t-error">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase mb-xs">Absent Today</p>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-primary">03</span>
</div>
</div>

</div>
<!-- Table Container -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<!-- Table Toolbar -->
<div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low/30">
<div class="flex items-center gap-md">
<span class="font-body-md font-semibold text-primary">Pending Requests</span>
<span class="bg-primary-container/10 text-primary px-2 py-0.5 rounded text-[10px] font-bold">12 TOTAL</span>
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
<!-- Row 1 -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">JS</div>
<div>
<p class="font-body-md font-semibold text-primary">Leo</p>
<p class="text-[11px] text-on-surface-variant">Senior Developer</p>
</div>
</div>
</td>
<td class="px-lg py-md text-body-sm text-primary">Annual Leave</td>
<td class="px-lg py-md text-body-sm font-data-mono">202-10-24</td>
<td class="px-lg py-md text-body-sm font-data-mono">202-10-31</td>
<td class="px-lg py-md text-body-sm font-data-mono">2</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant italic max-w-[200px] truncate">Family vacation to Italy...</td>
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
<!-- Row 2 -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-9 h-9 rounded-full bg-tertiary-container text-on-tertiary-container flex items-center justify-center font-bold text-xs">MA</div>
<div>
<p class="font-body-md font-semibold text-primary">Maria</p>
<p class="text-[11px] text-on-surface-variant">UX Designer</p>
</div>
</div>
</td>
<td class="px-lg py-md text-body-sm text-primary">Sick Leave</td>
<td class="px-lg py-md text-body-sm font-data-mono">202-10-20</td>
<td class="px-lg py-md text-body-sm font-data-mono">202-10-21</td>
<td class="px-lg py-md text-body-sm font-data-mono"></td>1
<td class="px-lg py-md text-body-sm text-on-surface-variant italic max-w-[200px] truncate">Medical check-up routine</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-secondary-container/20 text-on-secondary-container">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            Pending
                                        </span>
</td>
<td class="px-lg py-md text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-secondary hover:bg-secondary/10 rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">check_circle</span>
</button>
<button class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">cancel</span>
</button>
<button class="p-1.5 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-secondary/5 transition-colors group bg-surface-container-low/20">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-9 h-9 rounded-full bg-error-container text-on-error-container flex items-center justify-center font-bold text-xs">DB</div>
<div>
    <p class="font-body-md font-semibold text-primary">Thandar</p>
<p class="text-[11px] text-on-surface-variant">Database Administrator</p>
</div>
</div>
</td>
<td class="px-lg py-md text-body-sm text-primary">Unpaid Leave</td>
<td class="px-lg py-md text-body-sm font-data-mono">2023-11-05</td>
<td class="px-lg py-md text-body-sm font-data-mono">2023-11-10</td>
<td class="px-lg py-md text-body-sm font-data-mono">1</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant italic max-w-[200px] truncate">Personal development course</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-secondary-container/20 text-on-secondary-container">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            Pending
                                        </span>
</td>
<td class="px-lg py-md text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-secondary hover:bg-secondary/10 rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">check_circle</span>
</button>
<button class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">cancel</span>
</button>
<button class="p-1.5 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-9 h-9 rounded-full bg-surface-dim text-on-surface flex items-center justify-center font-bold text-xs">KL</div>
<div>
<p class="font-body-md font-semibold text-primary">Kelly</p>
<p class="text-[11px] text-on-surface-variant">QA Engineer</p>
</div>
</div>
</td>
<td class="px-lg py-md text-body-sm text-primary">Compassionate</td>
<td class="px-lg py-md text-body-sm font-data-mono">2023-10-18</td>
<td class="px-lg py-md text-body-sm font-data-mono">2023-10-19</td>
<td class="px-lg py-md text-body-sm font-data-mono">3</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant italic max-w-[200px] truncate">Family emergency</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-secondary-container/20 text-on-secondary-container">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            Pending
                                        </span>
</td>
<td class="px-lg py-md text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1.5 text-secondary hover:bg-secondary/10 rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">check_circle</span>
</button>
<button class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">cancel</span>
</button>
<button class="p-1.5 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-lg py-sm border-t border-outline-variant flex justify-between items-center bg-surface-container-low/30">
<p class="text-[11px] font-label-caps text-on-surface-variant uppercase tracking-wider">Showing 4 of 12 Pending Requests</p>
<div class="flex items-center gap-xs">
<button class="p-1 hover:bg-surface-container rounded border border-outline-variant/30 transition-all text-on-surface-variant disabled:opacity-30" disabled="">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-primary text-on-primary text-[11px] font-bold rounded">1</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container text-primary text-[11px] font-bold rounded">2</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container text-primary text-[11px] font-bold rounded">3</button>
<button class="p-1 hover:bg-surface-container rounded border border-outline-variant/30 transition-all text-on-surface-variant">
<span class="material-symbols-outlined">chevron_right</span>
</button>
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
</body></html>