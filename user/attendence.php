<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Attendance - Smart Attendance System</title>
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

<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary tracking-tight">Smart Attendence</h1>

</div>
<nav class="flex-grow space-y-1">
<!-- Dashboard is Active -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="userdashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="attendence.php">
<span class="material-symbols-outlined">schedule</span>
<span class="font-label-caps text-label-caps">Attendence</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaveform.php">
<span class="material-symbols-outlined">event_note</span>
<span class="font-label-caps text-label-caps">Leave Request</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leavestatus.php">
<span class="material-symbols-outlined">assignment_turned_in</span>
<span class="font-label-caps text-label-caps">Leave Status</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="profile.php">
<span class="material-symbols-outlined">person</span>
<span class="font-label-caps text-label-caps">Profile</span>
</a>
</nav>
<div class="mt-auto border-t border-on-primary-fixed-variant/20 pt-lg space-y-1">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="requestpassword.php">
<span class="material-symbols-outlined">lock</span>
<span class="font-label-caps text-label-caps">Change Password</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="userdashboard.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
</div>
</aside>
<!-- END: Sidebar -->
<!-- BEGIN: MainContent -->
<main class="flex-1 ml-64 flex flex-col min-h-screen">
<!-- BEGIN: Header -->
<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10" data-purpose="header">
<div class="flex items-center gap-4">
<button class="text-gray-500 hover:text-gray-700">
<i class="fa-solid fa-bars text-xl"></i>
</button>
<div>
<h1 class="font-bold text-lg text-slate-800">Welcome back, Kay 👋</h1>
<p class="text-sm text-gray-500">Employee ID :YGN-0005</p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="relative">

</div>
<div class="flex items-center gap-3 pl-4 border-l border-gray-200">
<div class="text-right">
<img src="" alt="">
<p class="text-sm font-semibold text-slate-800">Kay Ko</p>
<p class="text-xs text-gray-500">Employee</p>
</div>
<i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1"></i>
</div>
</div>
</header>
<!-- END: Header -->
<!-- BEGIN: DashboardBody -->
<div class="p-8 space-y-6">
<!-- Breadcrumb and Title -->
<div class="flex justify-between items-end">
<div>
<h2 class="text-2xl font-bold text-slate-800">Attendance</h2>
<nav class="text-sm text-gray-400 mt-1">
</nav>
</div>
<div class="flex items-center bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
<i class="fa-regular fa-calendar-days text-blue-600 mr-3"></i>
<span class="text-sm font-medium text-slate-700">20 June 202-, -</span>
<i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-3"></i>
</div>
</div>
<!-- Attendance Overview Section -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6" data-purpose="attendance-overview">
<!-- Today's Attendance Card -->
<div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
<div class="p-6 flex items-center justify-between">
<h3 class="font-bold text-slate-800">Today's Attendance</h3>
<span class="bg-green-100 text-green-600 text-xs font-bold px-3 py-1 rounded-md">Present</span>
</div>
<div class="px-6 pb-6 flex flex-col items-center">
<!-- Timer Visualization -->
<div class="timer-circle mb-6">
<div class="z-10 text-center">
<p class="text-2xl font-black text-slate-800">09:00 AM</p>
<p class="text-[10px] text-gray-400 font-medium tracking-widest uppercase">Current Time</p>
</div>
</div>
<!-- Check-in Info -->
<div class="w-full space-y-4 mb-8">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-500">
<i class="fa-solid fa-calendar-check text-lg"></i>
</div>
<div>
<p class="text-xs text-gray-400">Check-In</p>
<p class="text-sm font-bold text-slate-800">09:00 AM</p>
</div>
</div>
<p class="text-xs text-slate-400 font-medium">20 June 202-</p>
</div>
<div class="flex items-center justify-between opacity-70">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-400">
<i class="fa-solid fa-calendar-minus text-lg"></i>
</div>
<div>
<p class="text-xs text-gray-400">Check-Out</p>
<p class="text-sm font-bold text-slate-800">--:-- --</p>
</div>
</div>
<p class="text-xs text-slate-400 font-medium italic">Not Checked Out</p>
</div>
</div>
<!-- Actions -->
<div class="flex gap-4 w-full">
<button class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-md shadow-green-200 transition-all flex items-center justify-center gap-2">
<i class="fa-solid fa-right-from-bracket"></i>
                  Check Out
                </button>
<button class="flex-1 bg-white border border-gray-200 hover:bg-gray-50 text-slate-700 font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
<i class="fa-solid fa-mug-hot"></i>
                  Break
                </button>
</div>
</div>
<div class="mt-auto p-4 border-t border-gray-50 bg-gray-50/50 rounded-b-2xl flex justify-between px-6">
<span class="text-sm text-slate-500 font-medium">Working Hours</span>
<span class="text-sm text-slate-800 font-bold">00h 05m</span>
</div>
</div>
<!-- Attendance Summary Calendar Card -->
<div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
<div class="p-6">
<h3 class="font-bold text-slate-800">Attendance Summary <span class="text-gray-400 font-normal">(This Month)</span></h3>
</div>
<div class="px-6">
<!-- Summary Grid -->
<div class="grid grid-cols-4 gap-4 mb-8">
<!-- Present -->
<div class="bg-green-50/50 border border-green-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-calendar-check"></i>
</div>
<p class="text-2xl font-black text-slate-800">20</p>
<p class="text-xs text-green-600 font-bold uppercase">Present</p>
</div>
<!-- Absent -->
<div class="bg-red-50/50 border border-red-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-calendar-xmark"></i>
</div>
<p class="text-2xl font-black text-slate-800">1</p>
<p class="text-xs text-red-500 font-bold uppercase">Absent</p>
</div>
<!-- Late -->
<div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-clock"></i>
</div>
<p class="text-2xl font-black text-slate-800">2</p>
<p class="text-xs text-orange-500 font-bold uppercase">Late</p>
</div>
<!-- Half Day -->
<div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 text-center">
<div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
<i class="fa-regular fa-calendar"></i>
</div>
<p class="text-2xl font-black text-slate-800">0</p>
<p class="text-xs text-blue-500 font-bold uppercase">Half Day</p>
</div>
</div>
<!-- Calendar Representation -->
<div class="border border-gray-100 rounded-xl p-6" data-purpose="monthly-calendar">
<div class="grid grid-cols-7 text-center text-xs font-bold text-gray-400 mb-4 uppercase tracking-wider">
<div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
</div>
<div class="grid grid-cols-7 gap-y-4 text-center">
<!-- Previous month days -->
<div class="text-sm text-gray-300">26</div>
<div class="text-sm text-gray-300">27</div>
<div class="text-sm text-gray-300">28</div>
<div class="text-sm text-gray-300">29</div>
<div class="text-sm text-gray-300">30</div>
<div class="text-sm text-gray-300">31</div>
<div class="text-sm text-slate-800 font-medium">1</div>
<!-- Current month -->
<div class="text-sm text-slate-800 font-medium">2</div>
<div class="text-sm text-slate-800 font-medium">3</div>
<div class="text-sm text-slate-800 font-medium">4</div>
<div class="text-sm text-slate-800 font-medium">5</div>
<div class="text-sm text-slate-800 font-medium">6</div>
<div class="text-sm text-slate-800 font-medium">7</div>
<div class="text-sm text-slate-800 font-medium">8</div>
<div class="text-sm text-slate-800 font-medium">9</div>
<div class="text-sm text-slate-800 font-medium">10</div>
<div class="text-sm text-slate-800 font-medium">11</div>
<div class="text-sm text-slate-800 font-medium">12</div>
<div class="text-sm text-slate-800 font-medium">13</div>
<div class="text-sm text-slate-800 font-medium">14</div>
<div class="text-sm text-slate-800 font-medium">15</div>
<div class="text-sm text-slate-800 font-medium">16</div>
<div class="text-sm text-slate-800 font-medium">17</div>
<div class="text-sm text-slate-800 font-medium">18</div>
<div class="text-sm text-slate-800 font-medium">19</div>
<!-- Today highlight -->
<div class="relative flex items-center justify-center">
<span class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold z-10">20</span>
</div>
<div class="text-sm text-slate-800 font-medium">21</div>
<div class="text-sm text-slate-800 font-medium">22</div>
<div class="text-sm text-slate-800 font-medium">23</div>
<div class="text-sm text-slate-800 font-medium">24</div>
<div class="text-sm text-slate-800 font-medium">25</div>
<div class="text-sm text-slate-800 font-medium">26</div>
<div class="text-sm text-slate-800 font-medium">27</div>
<div class="text-sm text-slate-800 font-medium">28</div>
<div class="text-sm text-slate-800 font-medium">29</div>
<div class="text-sm text-slate-800 font-medium">30</div>
<!-- Next month -->
<div class="text-sm text-gray-300">1</div>
<div class="text-sm text-gray-300">2</div>
<div class="text-sm text-gray-300">3</div>
<div class="text-sm text-gray-300">4</div>
<div class="text-sm text-gray-300">5</div>
<div class="text-sm text-gray-300">6</div>
</div>
</div>
</div>
<!-- Legend -->
<div class="mt-auto flex justify-center gap-6 py-6 border-t border-gray-50">
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-green-600 rounded-full"></span> Present
              </div>
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> Absent
              </div>
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-orange-500 rounded-full"></span> Late
              </div>
<div class="flex items-center gap-2 text-xs font-medium text-slate-500">
<span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Half Day
              </div>
</div>
</div>
</div>
<!-- Attendance History Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-purpose="attendance-history">
<div class="p-6 flex items-center justify-between border-b border-gray-50">
<h3 class="font-bold text-slate-800 text-lg">Attendance History</h3>
<button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
<i class="fa-solid fa-sliders text-blue-600"></i>
              Filters
            </button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead>
<tr class="bg-gray-50/50 text-[11px] font-black text-gray-400 uppercase tracking-widest">
<th class="px-6 py-4">Date</th>
<th class="px-6 py-4">Check-In</th>
<th class="px-6 py-4">Check-Out</th>
<th class="px-6 py-4">Working Hours</th>
<th class="px-6 py-4">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-50 text-sm">
<!-- Row 1 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 font-medium text-slate-700">20 June 202- (Thu)</td>
<td class="px-6 py-4 font-bold text-green-600">09:00 AM</td>
<td class="px-6 py-4 text-gray-400">--:-- --</td>
<td class="px-6 py-4">00h 05m</td>
<td class="px-6 py-4">
<span class="bg-green-100 text-green-600 text-[10px] font-bold px-2.5 py-1 rounded-md">Present</span>
</td>

</tr>
<!-- Row 2 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 font-medium text-slate-700">19 June 202- (Wed)</td>
<td class="px-6 py-4 font-bold text-slate-800">08:55 AM</td>
<td class="px-6 py-4 font-bold text-red-500">05:10 PM</td>
<td class="px-6 py-4">08h 15m</td>
<td class="px-6 py-4">
<span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2.5 py-1 rounded-md">Late</span>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 font-medium text-slate-700">18 June 202- (Tue)</td>
<td class="px-6 py-4 font-bold text-slate-800">09:05 AM</td>
<td class="px-6 py-4 font-bold text-slate-800">05:00 PM</td>
<td class="px-6 py-4">07h 55m</td>
<td class="px-6 py-4">
<span class="bg-green-100 text-green-600 text-[10px] font-bold px-2.5 py-1 rounded-md">Present</span>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-gray-50/50 transition-colors text-gray-400">
<td class="px-6 py-4 font-medium">17 June 202- (Mon)</td>
<td class="px-6 py-4 italic">--:-- --</td>
<td class="px-6 py-4 italic">--:-- --</td>
<td class="px-6 py-4">00h 00m</td>
<td class="px-6 py-4">
<span class="bg-red-100 text-red-500 text-[10px] font-bold px-2.5 py-1 rounded-md">Absent</span>
</td>
</tr>
<!-- Row 5 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 font-medium text-slate-400">16 June 202- (Sun)</td>
<td class="px-6 py-4 italic text-gray-300">--:-- --</td>
<td class="px-6 py-4 italic text-gray-300">--:-- --</td>
<td class="px-6 py-4 text-gray-400">00h 00m</td>
<td class="px-6 py-4">
<span class="bg-blue-50 text-blue-500 text-[10px] font-bold px-2.5 py-1 rounded-md border border-blue-100">Weekly Off</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<!-- END: DashboardBody -->
</main>
<!-- END: MainContent -->
</div>
<!-- END: MainContainer -->
</body></html>