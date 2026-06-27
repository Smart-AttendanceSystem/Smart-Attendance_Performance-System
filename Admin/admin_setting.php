<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title> &amp;Admin</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
        .hover-row:hover { background-color: rgba(0, 107, 88, 0.05); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container">
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-primary flex flex-col py-lg border-r border-outline-variant shadow-sm z-50 overflow-y-auto">
<div class="px-md mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary">Admin</h1>
<p class="font-body-md text-body-md text-on-primary">HR Management System</p>
</div>
<nav class="flex-1 space-y-base">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary transition-colors duration-200 cursor-pointer active:scale-95" href="employee_management.php">
<span class="material-symbols-outlined">groups</span>
<span class="font-label-caps text-label-caps">Employees</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="department_management.php">
<span class="material-symbols-outlined">domain</span>
<span class="font-label-caps text-label-caps">Departments</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendenceman.php">
<span class="material-symbols-outlined">fact_check</span>
<span class="font-label-caps text-label-caps">Attendance</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaverequest.php">
<span class="material-symbols-outlined">event_busy</span>
<span class="font-label-caps text-label-caps">Leave Requests</span>
</a>
</nav>

</div>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary transition-colors duration-200" href="admin_setting.php">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-caps text-label-caps">Settings</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary transition-colors duration-200" href="dashboard.php">
<span class="material-symbols-outlined">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
</div>
</aside>
<!-- TopNavBar Shell -->
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface flex justify-between items-center px-lg h-16 z-40 border-b border-outline-variant shadow-sm">
<div class="flex items-center gap-md">
<span class="font-headline-sm text-headline-sm font-semibold text-primary">HR Admin</span>
<div class="relative ml-lg w-80">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-full text-body-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 transition-all" placeholder="Search settings..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<div class="flex items-center gap-sm">
<button class="p-2 text-on-surface-variant hover:bg-surface-container-low transition-all duration-200 rounded-full relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface"></span>
</button>
</div>
<div class="h-8 w-[1px] bg-outline-variant mx-sm"></div>
<button class="flex items-center gap-sm bg-primary text-on-primary px-md py-xs rounded-lg font-body-md hover:opacity-90 transition-all shadow-sm">
<span class="material-symbols-outlined text-sm">person_add</span>
                Add Employee
            </button>
<div class="flex items-center gap-sm ml-md">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" data-alt="A professional high-resolution corporate headshot of a middle-aged HR executive with a kind smile, wearing a dark navy blazer over a crisp white shirt. The background is a soft-focus modern office interior with warm wooden accents and bright morning sunlight streaming through glass partitions. The lighting is flattering and high-key, conveying a sense of leadership and institutional trust." src="https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg"/>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-[260px] pt-16 min-h-screen bg-background">
<div class="max-w-[1600px] mx-auto p-lg">
<!-- Page Header -->
<div class="mb-xl">
<h2 class="font-display-lg text-display-lg text-primary">Settings &amp; Security</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Manage your account preferences, security credentials, and system notification rules.</p>
</div>
<!-- Bento Grid Layout -->
<div class="grid grid-cols-12 gap-lg">
<!-- Profile Edit Card -->
<div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg overflow-hidden relative">
<div class="flex justify-between items-start mb-lg">
<div>
<h3 class="font-headline-sm text-headline-sm text-primary mb-1">Admin Profile</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Update your public information and avatar.</p>
</div>
<button class="text-secondary border border-secondary px-md py-xs rounded-lg font-label-caps hover:bg-secondary/5 transition-all">EDIT PHOTO</button>
</div>
<form class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">FULL NAME</label>
<input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" type="text" value="Alice"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">EMAIL ADDRESS</label>
<input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" type="email" value="alice.h@hr-portal.com"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">JOB TITLE</label>
<input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" type="text" value="Senior HR Administrator"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">DEPARTMENT</label>
<select class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0">
<option>Administration</option>
<option>Operations</option>
<option>Finance</option>
</select>
</div>
<div class="col-span-full space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">BIO / NOTES</label>
<textarea class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" rows="3">Responsible for system-wide employee oversight and payroll synchronization across all regional offices.</textarea>
</div>
</form>
<div class="mt-lg flex justify-end">
<button class="bg-secondary text-on-secondary px-xl py-sm rounded-lg font-body-md font-semibold hover:opacity-90 shadow-md transition-all active:scale-95">Save Changes</button>
</div>
</div>
<!-- Security / Password Card -->
<div class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
<h3 class="font-headline-sm text-headline-sm text-primary mb-1">Security</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-lg">Maintain a strong password to protect the HR environment.</p>
<form class="space-y-md">
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">CURRENT PASSWORD</label>
<input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" placeholder="••••••••" type="password"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">NEW PASSWORD</label>
<input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" placeholder="••••••••" type="password"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">CONFIRM PASSWORD</label>
<input class="w-full border border-outline-variant rounded-lg p-sm font-body-md focus:border-secondary focus:ring-0" placeholder="••••••••" type="password"/>
</div>
<div class="pt-sm">
<button class="w-full bg-surface-container-high text-primary border border-outline-variant px-md py-sm rounded-lg font-body-md font-semibold hover:bg-outline-variant transition-all">Update Password</button>
</div>
</form>

</div>
<!-- Notifications Toggle Section -->
<div class="col-span-12 lg:col-span-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
<h3 class="font-headline-sm text-headline-sm text-primary mb-1">Notifications Settings</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-lg">Define when and how you want to be alerted about staff activities.</p>
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
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-surface-container-high rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
</label>
</div>

</div>
</div>
<!-- User Management Controls (Access Levels) -->
<div class="col-span-12 lg:col-span-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
<div class="flex justify-between items-center mb-lg">
<div>
<h3 class="font-headline-sm text-headline-sm text-primary mb-1">User Management</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Active administrators on the system.</p>
</div>
<button class="bg-primary-container text-on-primary-container px-md py-sm rounded-lg font-label-caps flex items-center gap-xs">
<span class="material-symbols-outlined text-sm">person_add</span>
                            MANAGE ROLES
                        </button>
</div>
<div class="overflow-hidden border border-outline-variant rounded-lg">
<table class="w-full text-left font-body-sm">
<thead class="bg-surface-container font-label-caps text-on-surface-variant border-b border-outline-variant">
<tr>
<th class="px-md py-sm">USER</th>
<th class="px-md py-sm">ROLE</th>
<th class="px-md py-sm">STATUS</th>
<th class="px-md py-sm">ACTIONS</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-md py-sm">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold">JD</div>
<div>
<p class="font-semibold text-primary">John </p>
<p class="text-xs text-on-surface-variant">j@gmail.com</p>
</div>
</div>
</td>
<td class="px-md py-sm">Data Architect</td>
<td class="px-md py-sm">
<span class="px-xs py-[2px] bg-secondary/10 text-secondary border border-secondary/20 rounded text-[10px] font-bold">ACTIVE</span>
</td>
<td class="px-md py-sm">
<button class="text-on-surface-variant hover:text-primary"><span class="material-symbols-outlined text-sm">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-md py-sm">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold">SM</div>
<div>
<p class="font-semibold text-primary">Sandi</p>
<p class="text-xs text-on-surface-variant">sandi@gmail.com</p>
</div>
</div>
</td>
<td class="px-md py-sm">Helpdesk Supervisor</td>
<td class="px-md py-sm">
<span class="px-xs py-[2px] bg-secondary/10 text-secondary border border-secondary/20 rounded text-[10px] font-bold">ACTIVE</span>
</td>
<td class="px-md py-sm">
<button class="text-on-surface-variant hover:text-primary"><span class="material-symbols-outlined text-sm">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-secondary/5 transition-colors group">
<td class="px-md py-sm">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant font-bold">RB</div>
<div>
<p class="font-semibold text-primary">Jay</p>
<p class="text-xs text-on-surface-variant">Jay@gmail.com</p>
</div>
</div>
</td>
<td class="px-md py-sm">Viewer</td>
<td class="px-md py-sm">
<span class="px-xs py-[2px] bg-outline-variant/10 text-outline border border-outline/20 rounded text-[10px] font-bold uppercase">Inactive</span>
</td>
<td class="px-md py-sm">
<button class="text-on-surface-variant hover:text-primary"><span class="material-symbols-outlined text-sm">more_vert</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="mt-md text-right">
<button class="text-secondary font-body-sm hover:underline">View all</button>
</div>
</div>
</div>
</div>
</main>
<!-- Micro-interaction Script -->
<script>
        document.querySelectorAll('button, [role="button"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Simple input focus behavior
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('ring-1', 'ring-secondary/20');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('ring-1', 'ring-secondary/20');
            });
        });
    </script>
</body></html>