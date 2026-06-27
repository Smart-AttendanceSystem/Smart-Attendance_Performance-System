<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>HR Dashboard</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .chart-bar { transition: height 1s ease-in-out; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="text-on-surface bg-background">
<!-- Predicted SideNavBar Component -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-primary dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col py-lg z-50 overflow-y-auto scrollbar-hide">
<div class="px-md mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary tracking-tight">Admin</h1>
<p class="font-body-md text-body-md text-on-primary opacity-80">HR Management System</p>
</div>
<nav class="flex-grow space-y-1">
<!-- Dashboard is Active -->
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="#">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="employee_management.php">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
<span class="font-label-caps text-label-caps">Employees</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="department_management.php">
<span class="material-symbols-outlined" data-icon="domain">domain</span>
<span class="font-label-caps text-label-caps">Departments</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendenceman.php">
<span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
<span class="font-label-caps text-label-caps">Attendance</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaverequest.php">
<span class="material-symbols-outlined" data-icon="event_busy">event_busy</span>
<span class="font-label-caps text-label-caps">Leave Requests</span>
</a>
</nav>
<div class="mt-auto border-t border-on-primary-fixed-variant/20 pt-lg space-y-1">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="admin_setting.php">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-label-caps text-label-caps">Settings</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
<div class="px-md mt-md flex items-center gap-sm">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" data-alt="A professional high-resolution corporate headshot of a middle-aged HR executive with a kind smile, wearing a dark navy blazer over a crisp white shirt. The background is a soft-focus modern office interior with warm wooden accents and bright morning sunlight streaming through glass partitions. The lighting is flattering and high-key, conveying a sense of leadership and institutional trust." src="https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg"/>
<div class="flex flex-col">
<span class="text-on-primary font-semibold text-body-sm">Admin</span>
<span class="text-on-primary text-[10px]">Super Administrator</span>
</div>
</div>
</div>
</aside>
<!-- Predicted TopNavBar Component -->
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg z-40 transition-all duration-200">
<div class="flex items-center gap-lg flex-1">
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-container-low border-none rounded-full py-xs pl-xl pr-md text-body-md focus:ring-2 focus:ring-secondary/50" placeholder="Search employees, files, or reports..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="material-symbols-outlined p-xs rounded-full hover:bg-surface-container-low text-on-surface-variant relative transition-all">
                notifications
                <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
</button>
<div class="h-8 w-[1px] bg-outline-variant mx-xs"></div>
<button class="bg-secondary text-white px-md py-xs rounded-lg font-body-md flex items-center gap-xs hover:bg-secondary/90 active:scale-95 transition-all">
<span class="material-symbols-outlined !text-[18px]">person_add</span>
                Add Employee
            </button>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-[260px] pt-16 min-h-screen p-lg max-w-[1600px]">
<div class="flex flex-col gap-lg">
<!-- Welcome Header -->
<section class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
<div>
<h2 class="font-headline-md text-headline-md text-primary">Dashboard Overview</h2>
<p class="font-body-md text-on-surface-variant">Summary of human resources performance and daily activity.</p>
</div>
<div class="flex items-center gap-sm bg-surface-container p-base rounded-lg border border-outline-variant">
<button class="px-md py-xs rounded text-label-caps bg-surface-container-lowest shadow-sm">TODAY</button>
<button class="px-md py-xs rounded text-label-caps hover:bg-surface-container-high transition-colors">WEEK</button>
<button class="px-md py-xs rounded text-label-caps hover:bg-surface-container-high transition-colors">MONTH</button>
</div>
</section>
<!-- KPI Cards Bento Grid -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- KPI Card: Total Employees -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-primary transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-primary bg-primary-fixed p-xs rounded-lg">groups</span>
</div>
<p class="font-label-caps text-label-caps text-on-surface-variant">TOTAL EMPLOYEES</p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs">1,248</h3>
<p class="text-[10px] text-on-surface-variant mt-xs italic">12 New hires this month</p>
</div>
<!-- KPI Card: Attendance % -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-secondary transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-secondary bg-secondary-container p-xs rounded-lg">calendar_today</span></div>
<p class="font-label-caps text-label-caps text-on-surface-variant">ATTENDANCE RATE</p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs">94.2%</h3>
<p class="text-[10px] text-on-surface-variant mt-xs italic">System average: 95.0%</p>
</div>
<!-- KPI Card: Late Arrivals -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-error transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-error bg-error-container p-xs rounded-lg">schedule</span>
</div>
<p class="font-label-caps text-label-caps text-on-surface-variant">LATE ARRIVALS TODAY</p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs">14</h3>
</div>
<!-- KPI Card: Pending Leave -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm border-t-4 border-tertiary-container transition-transform hover:scale-[1.02]">
<div class="flex justify-between items-start mb-sm">
<span class="material-symbols-outlined text-tertiary-container bg-tertiary-fixed p-xs rounded-lg">pending_actions</span>
<div class="bg-tertiary-fixed text-on-tertiary-fixed px-xs rounded text-[10px] font-bold">URGENT</div>
</div>
<p class="font-label-caps text-label-caps text-on-surface-variant">PENDING LEAVE REQUESTS</p>
<h3 class="font-display-lg text-display-lg text-primary mt-xs">28</h3>
<p class="text-[10px] text-on-surface-variant mt-xs italic">8 awaiting your approval</p>
</div>
</section>
<!-- Charts & Notification Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
<!-- Performance Chart Container -->
<div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm flex flex-col">
<div class="flex justify-between items-center mb-xl">
<div>
<h4 class="font-headline-sm text-headline-sm text-primary">Monthly Performance Report</h4>
<p class="font-body-sm text-on-surface-variant">Attendance vs. Late arrivals trends</p>
</div>
<select class="bg-surface-container border-none rounded-lg text-body-sm px-md py-xs focus:ring-secondary/50">
<option>Last 30 Days</option>
<option>Last Quarter</option>
</select>
</div>
<!-- Custom Visual Chart Implementation -->
<div class="flex-grow flex items-end justify-between h-64 gap-md pb-xs border-b border-outline-variant relative">
<!-- Chart Lines (Simulated Grid) -->
<div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-10">
<div class="border-b border-on-surface w-full"></div>
<div class="border-b border-on-surface w-full"></div>
<div class="border-b border-on-surface w-full"></div>
<div class="border-b border-on-surface w-full"></div>
</div>
<!-- Bar Groups -->
<div class="flex flex-col items-center flex-1 group">
<div class="flex items-end gap-[2px] w-full justify-center h-full">
<div class="bg-primary w-6 chart-bar rounded-t-sm" style="height: 85%;"></div>
<div class="bg-error w-4 chart-bar rounded-t-sm opacity-60" style="height: 15%;"></div>
</div>
<span class="text-[10px] font-label-caps mt-sm text-on-surface-variant">WK 01</span>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="flex items-end gap-[2px] w-full justify-center h-full">
<div class="bg-primary w-6 chart-bar rounded-t-sm" style="height: 92%;"></div>
<div class="bg-error w-4 chart-bar rounded-t-sm opacity-60" style="height: 8%;"></div>
</div>
<span class="text-[10px] font-label-caps mt-sm text-on-surface-variant">WK 02</span>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="flex items-end gap-[2px] w-full justify-center h-full">
<div class="bg-primary w-6 chart-bar rounded-t-sm" style="height: 78%;"></div>
<div class="bg-error w-4 chart-bar rounded-t-sm opacity-60" style="height: 22%;"></div>
</div>
<span class="text-[10px] font-label-caps mt-sm text-on-surface-variant">WK 03</span>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="flex items-end gap-[2px] w-full justify-center h-full">
<div class="bg-primary w-6 chart-bar rounded-t-sm" style="height: 95%;"></div>
<div class="bg-error w-4 chart-bar rounded-t-sm opacity-60" style="height: 5%;"></div>
</div>
<span class="text-[10px] font-label-caps mt-sm text-on-surface-variant">WK 04</span>
</div>
<div class="flex flex-col items-center flex-1 group">
<div class="flex items-end gap-[2px] w-full justify-center h-full">
<div class="bg-primary w-6 chart-bar rounded-t-sm" style="height: 88%;"></div>
<div class="bg-error w-4 chart-bar rounded-t-sm opacity-60" style="height: 12%;"></div>
</div>
<span class="text-[10px] font-label-caps mt-sm text-on-surface-variant">TODAY</span>
</div>
</div>
<div class="flex items-center gap-lg mt-lg">
<div class="flex items-center gap-xs">
<span class="w-3 h-3 bg-primary rounded-full"></span>
<span class="text-body-sm text-on-surface-variant">Attendance (%)</span>
</div>
<div class="flex items-center gap-xs">
<span class="w-3 h-3 bg-error opacity-60 rounded-full"></span>
<span class="text-body-sm text-on-surface-variant">Late Arrivals</span>
</div>
</div>
</div>
<!-- Notifications Panel -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
<div class="flex justify-between items-center mb-lg">
<h4 class="font-headline-sm text-headline-sm text-primary">Recent Notifications</h4>
<span class="bg-error text-white text-[10px] font-bold px-sm py-base rounded-full">1 NEW</span>
</div>
<div class="space-y-sm">
<!-- Notification Item -->
<div class="flex gap-md p-sm bg-surface-container-low rounded-lg border border-outline-variant/30 hover:border-secondary transition-colors cursor-pointer">
<div class="w-10 h-10 rounded-full bg-secondary-container text-secondary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined">assignment_late</span>
</div>
<div>
<p class="text-body-sm font-semibold text-primary">New Leave Request:Kay Ko</p>
<p class="text-[11px] text-on-surface-variant mb-base">Annual Leave Request for Oct 12-15</p>
<span class="text-[10px] text-outline font-data-mono uppercase">2 MINUTES AGO</span>
</div>
</div>
</div>
<button class="w-full mt-lg text-label-caps text-secondary font-bold hover:underline py-sm border border-secondary/20 rounded-lg">VIEW ALL NOTIFICATIONS</button>

</div>
</div>
</div>
<!-- Holidays & Secondary Dashboard Section -->
<section class="grid grid-cols-1 xl:grid-cols-4 gap-lg pt-6">
<!-- Holidays Summary -->
<div class="xl:col-span-1 bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
<h4 class="font-headline-sm text-headline-sm text-primary mb-lg">Upcoming Holidays</h4>
<p class="text-body-sm text-on-surface-variant mb-md">Month: October 2023</p>
<div class="space-y-sm">
<div class="flex items-center gap-md p-xs border-b border-outline-variant/30">
<div class="bg-primary/10 text-primary w-12 h-12 flex flex-col items-center justify-center rounded-lg">
<span class="text-xs font-bold">OCT</span>
<span class="text-lg font-bold">-</span>
</div>
<div>
<p class="text-body-sm font-semibold text-primary">National Day</p>
<p class="text-[10px] text-on-surface-variant">Public Holiday</p>
</div>
</div>
<button class="w-full mt-lg text-label-caps text-secondary font-bold hover:underline py-sm border border-secondary/20 rounded-lg">VIEW ALL</button>

</div>

</div>
</div>
<!-- Recent Activities / Attendance Table -->
<div class="xl:col-span-3 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
<div class="p-lg border-b border-outline-variant flex justify-between items-center">
<div>
<h4 class="font-headline-sm text-headline-sm text-primary">Today's Attendance Detail</h4>
<p class="text-body-sm text-on-surface-variant">Live feed of employee clock-ins</p>
</div>
<button class="material-symbols-outlined text-on-surface-variant hover:bg-surface-container-low p-xs rounded-full">more_vert</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low">
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">EMPLOYEE</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">DEPARTMENT</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">CLOCK-IN</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">STATUS</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<tr class="hover:bg-secondary/5 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
<span class="text-body-sm font-semibold text-primary">Kay Ko</span>
</div>
</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant">Artificial Intelligence & Automation</td>
<td class="px-lg py-md text-body-sm font-data-mono">08:45 AM</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-xs px-sm py-base rounded-full bg-secondary-container/30 text-secondary text-[10px] font-bold">
<span class="w-1.5 h-1.5 bg-secondary rounded-full"></span> PRESENT
                                        </span>
</td>
</tr>
<tr class="hover:bg-secondary/5 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<img src="https://i.pinimg.com/736x/16/a0/34/16a034c977760cd8185e279393265d3a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
<span class="text-body-sm font-semibold text-primary">Sara</span>
</div>
</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant">Infrastructure & Network Operations</td>
<td class="px-lg py-md text-body-sm font-data-mono">09:15 AM</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-xs px-sm py-base rounded-full bg-error-container/30 text-error text-[10px] font-bold">
<span class="w-1.5 h-1.5 bg-error rounded-full"></span> LATE
                                        </span>
</td>
</tr>
<tr class="hover:bg-secondary/5 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<img src="https://i.pinimg.com/736x/b2/22/c9/b222c9b29c5ca95e739e45072f04f715.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
<span class="text-body-sm font-semibold text-primary">Alina</span>
</div>
</td>
<td class="px-lg py-md text-body-sm text-on-surface-variant"> Cyber Security </td>
<td class="px-lg py-md text-body-sm font-data-mono">08:58 AM</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-xs px-sm py-base rounded-full bg-secondary-container/30 text-secondary text-[10px] font-bold">
<span class="w-1.5 h-1.5 bg-secondary rounded-full"></span> PRESENT
                                        </span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</section>
</div>
</main>
<!-- Micro-interaction Scripts -->
<script>
        // Simple entrance animation for chart bars
        document.addEventListener('DOMContentLoaded', () => {
            const bars = document.querySelectorAll('.chart-bar');
            bars.forEach(bar => {
                const finalHeight = bar.style.height;
                bar.style.height = '0';
                setTimeout(() => {
                    bar.style.height = finalHeight;
                }, 300);
            });
        });
    </script>
</body></html>