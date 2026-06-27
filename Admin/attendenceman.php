<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Attendance Management</title>
<!-- Google Fonts -->
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
        }
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c4c6cd; border-radius: 10px; }
    </style>
</head>
<body class="bg-background text-on-background overflow-hidden">
<!-- SideNavBar (Shared Component) -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-primary dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col py-lg z-50">
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary tracking-tight">Admin</h1>
<p class="font-label-caps text-label-caps text-on-primary opacity-80 mt-base uppercase">HR Management System</p>
</div>
<nav class="flex-1 space-y-base px-sm overflow-y-auto custom-scrollbar">
<!-- Dashboard -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<!-- Employees -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="employee_management.php">
<span class="material-symbols-outlined">groups</span>
<span class="font-label-caps text-label-caps">Employees</span>
</a>
<!-- Departments -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="department_management.php">
<span class="material-symbols-outlined">domain</span>
<span class="font-label-caps text-label-caps">Departments</span>
</a>
<!-- Attendance (ACTIVE) -->
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95" href="attendenceman.php">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">fact_check</span>
<span class="font-label-caps text-label-caps">Attendance</span>
</a>
<!-- Leave Requests -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaverequest.php">
<span class="material-symbols-outlined">event_busy</span>
<span class="font-label-caps text-label-caps">Leave Requests</span>
</a>
</nav>
<div class="mt-auto px-sm border-t border-white/10 pt-lg">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95 mb-base" href="admin_setting.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-caps text-label-caps">Settings</span>
</a>
<div class="flex items-center gap-md px-md py-sm mt-md">
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-secondary/30">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" data-alt="A professional high-resolution corporate headshot of a middle-aged HR executive with a kind smile, wearing a dark navy blazer over a crisp white shirt. The background is a soft-focus modern office interior with warm wooden accents and bright morning sunlight streaming through glass partitions. The lighting is flattering and high-key, conveying a sense of leadership and institutional trust." src="https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg"/>
</div>
<div class="flex flex-col">
<span class="font-body-sm text-on-primary font-semibold">Admin</span>
<span class="font-label-caps text-[10px] text-on-primary-container/70">Admin Access</span>
</div>
<button class="ml-auto text-on-primary-fixed-variant hover:text-error transition-colors">
<a class="material-symbols-outlined" href="dashboard.php">logout</a>
</button>
</div>
</div>
</aside>
<!-- TopNavBar (Shared Component) -->
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg h-16 z-40">
<div class="flex items-center gap-md">
<div class="relative group">
<span class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none text-outline">
<span class="material-symbols-outlined text-[20px]">search</span>
</span>
<input class="bg-surface-container-low border-none focus:ring-2 focus:ring-secondary/50 rounded-lg pl-xl pr-md py-xs font-body-sm w-72 transition-all duration-200" placeholder="Search employees, reports..." type="text">
</div>
</div>
<div class="flex items-center gap-lg">
<div class="flex gap-sm">
<button class="p-xs text-on-surface-variant hover:bg-surface-container-low transition-all duration-200 rounded-lg relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full border border-surface"></span>
</button>
</div>
<button class="bg-secondary text-on-secondary px-md py-xs rounded-lg font-body-sm font-semibold hover:opacity-90 active:scale-95 transition-all flex items-center gap-xs">
<span class="material-symbols-outlined text-[18px]">add</span>
                Add Employee
            </button>
</div>
</header>
<!-- Main Canvas -->
<main class="ml-[260px] pt-16 h-screen overflow-y-auto bg-background">
<div class="p-lg max-w-[1600px] mx-auto space-y-lg">
<!-- Page Header & Filter Controls -->
<section class="flex flex-col md:flex-row md:items-end justify-between gap-md">
<div>
<h2 class="font-headline-md text-headline-md text-primary">Attendance Management</h2>
<p class="font-body-md text-on-surface-variant">Real-time monitoring of daily employee presence and punctuality.</p>
</div>
<div class="flex items-center gap-sm bg-surface-container-lowest p-xs rounded-xl shadow-sm border border-outline-variant"><div class="flex flex-col px-sm border-r border-outline-variant">
<label class="font-label-caps text-label-caps text-outline uppercase">Search</label>
<input class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent w-48" placeholder="Username or ID" type="text">
</div>
<div class="flex flex-col px-sm">
<label class="font-label-caps text-label-caps text-outline uppercase">Date Range</label>
<input class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent" type="date" value="2023-10-27">
</div>
<div class="w-px h-8 bg-outline-variant"></div>
<div class="flex flex-col px-sm min-w-[140px]">
<label class="font-label-caps text-label-caps text-outline uppercase">Department</label>
<select class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent appearance-none">
<option>All Departments</option>
<option>Engineering</option>
<option>Human Resources</option>
<option>Marketing</option>
</select>
</div>
<div class="w-px h-8 bg-outline-variant"></div>
<div class="flex flex-col px-sm min-w-[120px]">
<label class="font-label-caps text-label-caps text-outline uppercase">Overtime</label>
<select class="border-none p-0 focus:ring-0 font-body-sm text-on-surface bg-transparent appearance-none">
<option>All Status</option>
<option>With Overtime</option>
<option>No Overtime</option>
</select>
</div>
</div>
</section>
<!-- KPI Cards - Summary Stats -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- Total Present -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-secondary">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Present Today</span>
<span class="bg-secondary/10 text-secondary p-xs rounded-full">
<span class="material-symbols-outlined text-[18px]">check_circle</span>
</div>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-on-surface">142</span>
</div>
</div>
<!-- Absent -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-error">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Absent</span>
<span class="bg-error/10 text-error p-xs rounded-full">
<span class="material-symbols-outlined text-[18px]">cancel</span>
</span>
</div>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-on-surface">12</span>
</div>
</div>
<!-- Late -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm border-t-4 border-tertiary-container">
<div class="flex justify-between items-start mb-sm">
<span class="font-label-caps text-label-caps text-outline uppercase">Late Arrivals</span>
<span class="bg-tertiary-fixed text-tertiary p-xs rounded-full">
<span class="material-symbols-outlined text-[18px]">schedule</span>
</span>
</div>
<div class="flex items-baseline gap-xs">
<span class="font-display-lg text-display-lg text-on-surface">8</span>
</div>
</div>
<!-- On Leave -->

</section>
<!-- Attendance Data Table -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
<h3 class="font-headline-sm text-headline-sm text-primary">Daily Attendance Log</h3>

</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Employee</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">ID</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider text-center">Date</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Check-in</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Check-out</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Overtime</th><th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider">Status</th>
<th class="px-lg py-sm font-label-caps text-label-caps text-outline uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant font-body-md text-on-surface">
<!-- Row 1: Present -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
<img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Kay Ko</span>
</div>
</td>
<td class="px-lg py-md font-data-mono text-data-mono text-on-surface-variant">YGN-0005</td>
<td class="px-lg py-md text-center">Oct 27, 202-</td>
<td class="px-lg py-md">08:55 AM</td>
<td class="px-lg py-md"><div class="flex items-center gap-xs"><span class="">05:30 PM</span><span class="material-symbols-outlined text-[14px] text-secondary" title="Extended Shift">add_circle</span></div></td>
<td class="px-lg py-md font-data-mono text-secondary">00h 35m</td><td class="px-lg py-md">
<span class="bg-secondary/10 text-on-secondary-container px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        Present
                                    </span>
</td>
<td class="px-lg py-md text-right">
<button class="opacity-0 group-hover:opacity-100 text-outline hover:text-secondary transition-all">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: &quot;FILL&quot; 0;">edit_note</span>
</button>
</td>
</tr>
<!-- Row 2: Late -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
<img src="https://i.pinimg.com/736x/16/a0/34/16a034c977760cd8185e279393265d3a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Sara</span>
</div>
</td>
<td class="px-lg py-md font-data-mono text-data-mono text-on-surface-variant">YGN-0043</td>
<td class="px-lg py-md text-center">Oct 27, 202-</td>
<td class="px-lg py-md text-error font-medium">09:15 AM</td>
<td class="px-lg py-md"><div class="flex items-center gap-xs"><span class="">06:05 PM</span><span class="material-symbols-outlined text-[14px] text-secondary" title="Extended Shift">add_circle</span></div></td>
<td class="px-lg py-md font-data-mono text-secondary">01h 05m</td><td class="px-lg py-md">
<span class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                                        Late
                                    </span>
</td>
<td class="px-lg py-md text-right">
<button class="opacity-0 group-hover:opacity-100 text-outline hover:text-secondary transition-all">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: &quot;FILL&quot; 0;">edit_note</span>
</button>
</td>
</tr>
<!-- Row 3: Absent -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
<img src="https://i.pinimg.com/736x/b2/22/c9/b222c9b29c5ca95e739e45072f04f715.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Alina </span>
</div>
</td>
<td class="px-lg py-md font-data-mono text-data-mono text-on-surface-variant">YGN-1001</td>
<td class="px-lg py-md text-center">Oct 27, 202-</td>
<td class="px-lg py-md text-outline">--:--</td>
<td class="px-lg py-md text-outline">--:--</td>
<td class="px-lg py-md font-data-mono text-outline">--:--</td><td class="px-lg py-md">
<span class="bg-error/10 text-on-error-container px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                        Absent
                                    </span>
</td>
<td class="px-lg py-md text-right">
<button class="opacity-0 group-hover:opacity-100 text-outline hover:text-secondary transition-all">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: &quot;FILL&quot; 0;">edit_note</span>
</button>
</td>
</tr>
<!-- Row 4: Present -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
<img src="https://i.pinimg.com/1200x/04/82/f4/0482f447e372b130624d4e986f49a39e.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">A</span>
</div>
</td>
<td class="px-lg py-md font-data-mono text-data-mono text-on-surface-variant">YGN-0201</td>
<td class="px-lg py-md text-center">Oct 27, 202-</td>
<td class="px-lg py-md">08:42 AM</td>
<td class="px-lg py-md"><div class="flex items-center gap-xs"><span class="">05:15 PM</span><span class="material-symbols-outlined text-[14px] text-secondary" title="Extended Shift">add_circle</span></div></td>
<td class="px-lg py-md font-data-mono text-secondary">00h 15m</td><td class="px-lg py-md">
<span class="bg-secondary/10 text-on-secondary-container px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        Present
                                    </span>
</td>
<td class="px-lg py-md text-right">
<button class="opacity-0 group-hover:opacity-100 text-outline hover:text-secondary transition-all">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: &quot;FILL&quot; 0;">edit_note</span>
</button>
</td>
</tr>
<!-- Row 5: On Leave -->
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full overflow-hidden bg-surface-container">
<img src="https://i.pinimg.com/736x/5a/57/7a/5a577a7564b82106d6815739c673fbd7.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Liam </span>
</div>
</td>
<td class="px-lg py-md font-data-mono text-data-mono text-on-surface-variant">EMP-0229</td>
<td class="px-lg py-md text-center">Oct 27, 2023</td>
<td class="px-lg py-md text-outline">--:--</td>
<td class="px-lg py-md text-outline">--:--</td>
<td class="px-lg py-md font-data-mono text-outline">--:--</td><td class="px-lg py-md">
<span class="bg-surface-variant text-on-surface-variant px-sm py-base rounded-full text-[12px] font-semibold flex items-center w-fit gap-xs">
<span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                        On Leave
                                    </span>
</td>
<td class="px-lg py-md text-right">
<button class="opacity-0 group-hover:opacity-100 text-outline hover:text-secondary transition-all">
<span class="material-symbols-outlined text-[20px]">edit_note</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="px-lg py-md border-t border-outline-variant flex justify-between items-center bg-surface-container-low/30">
<span class="font-body-sm text-on-surface-variant">Showing 5 of 159 employees</span>
<div class="flex gap-xs">
<button class="px-md py-xs border border-outline-variant rounded-lg font-body-sm hover:bg-surface-container transition-all">Previous</button>
<button class="px-md py-xs bg-primary text-on-primary rounded-lg font-body-sm hover:opacity-90 transition-all">Next</button>
</div>
</div>
</section>
</div>
</main>
<!-- Micro-interaction Script -->
<script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.querySelector('.material-symbols-outlined').style.fontVariationSettings = "'FILL' 1";
                });
                row.addEventListener('mouseleave', () => {
                    row.querySelector('.material-symbols-outlined').style.fontVariationSettings = "'FILL' 0";
                });
            });
        });
    </script>


</body></html>