<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Smart Attendance Dashboard</title>
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
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="userdashboard.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendence.php">
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
<!-- Top Bar -->
<header class="fixed top-0 right-0 left-sidebar-width h-20 bg-white border-b border-slate-100 z-40 flex justify-between items-center px-10">
<div class="flex items-center gap-4">
<button class="p-2 text-slate-400 hover:bg-slate-50 rounded-lg">
<span class="material-symbols-outlined">menu</span>
</button>
<div>
<h2 class="text-xl font-bold">Welcome back, Kay 👋</h2>
<p class="text-xs text-text-muted">Here's what's happening with your attendance today.</p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="relative">
<button class="p-2 text-slate-400 hover:bg-slate-50 rounded-full">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-1 right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full text-[8px] text-white flex items-center justify-center font-bold">3</span>
</button>
</div>
<div class="flex items-center gap-3">
<div class="text-right">
<p class="text-sm font-bold">Kay Ko</p>
<p class="text-[10px] text-text-muted uppercase tracking-wider">Employee</p>
</div>
<img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
<span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
</div>
</div>
</header>
<!-- Main Content -->
<main class="ml-sidebar-width pt-20 p-10 space-y-8">
<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Card 1 -->
<div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
<div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center">
<span class="material-symbols-outlined text-green-500 text-3xl font-light">calendar_today</span>
</div>
<div>
<p class="text-text-muted text-xs font-medium">Total Present</p>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-bold">20</span>
<span class="text-sm font-semibold">Days</span>
</div>
<p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
</div>
</div>
<!-- Card 2 -->
<div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
<div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center">
<span class="material-symbols-outlined text-orange-500 text-3xl font-light">event_busy</span>
</div>
<div>
<p class="text-text-muted text-xs font-medium">Total Leaves</p>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-bold">2</span>
<span class="text-sm font-semibold">Days</span>
</div>
<p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
</div>
</div>
<!-- Card 3 -->
<div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
<div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center">
<span class="material-symbols-outlined text-purple-500 text-3xl font-light">schedule</span>
</div>
<div>
<p class="text-text-muted text-xs font-medium">Late Arrivals</p>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-bold">1</span>
<span class="text-sm font-semibold">Day</span>
</div>
<p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
</div>
</div>
<!-- Card 4 -->
<div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
<div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center">
<span class="material-symbols-outlined text-blue-500 text-3xl font-light">work_history</span>
</div>
<div>
<p class="text-text-muted text-xs font-medium">Working Days</p>
<div class="flex items-baseline gap-1">
<span class="text-2xl font-bold">22</span>
<span class="text-sm font-semibold">Days</span>
</div>
<p class="text-[10px] text-text-muted mt-1 uppercase">This Month</p>
</div>
</div>
</div>
<!-- Main Grid Section -->
<div class="grid grid-cols-12 gap-8">
<!-- Today's Attendance -->
<div class="col-span-12 lg:col-span-7 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
<div class="flex justify-between items-start mb-8">
<h3 class="text-lg font-bold">Today's Attendance</h3>
<div class="flex items-center gap-2 text-text-muted text-xs bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
<span class="material-symbols-outlined text-sm">calendar_month</span>
                    -June 202-, Thursday
                </div>
</div>
<div class="flex flex-col md:flex-row items-center gap-12 relative z-10">
<div class="flex flex-col gap-8 w-full md:w-auto">
<div>
<span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-lg uppercase tracking-wider mb-6">Present</span>
<div class="flex gap-16">
<div>
<p class="text-text-muted text-[10px] font-bold uppercase mb-1">Check-in Time</p>
<p class="text-2xl font-bold">09:00 AM</p>
</div>
<div>
<p class="text-text-muted text-[10px] font-bold uppercase mb-1">Check-out Time</p>
<p class="text-2xl font-bold text-slate-300">- - : - -</p>
</div>
</div>
</div>
<button class="bg-accent-blue hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-200">
<span class="material-symbols-outlined">logout</span>
                        Check Out
                    </button>
</div>

</div>
</div>
<!-- Recent Notifications -->
<div class="col-span-12 lg:col-span-5 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100">
<div class="flex justify-between items-center mb-6">
<h3 class="text-lg font-bold">Recent Notifications</h3>
<a class="text-accent-blue text-xs font-semibold hover:underline" href="#">View All</a>
</div>
<div class="space-y-4">
<!-- Notification 1 -->
<div class="flex gap-4 p-4 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-colors">
<div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-green-500">check_circle</span>
</div>
<div class="flex-1">
<div class="flex justify-between items-start">
<p class="text-sm font-bold">Your leave request has been approved.</p>
<span class="text-[10px] text-text-muted">10:30 AM</span>
</div>
<p class="text-xs text-text-muted mt-1">Annual Leave: 24 Jun 2024</p>
</div>
</div>
<!-- Notification 2 -->
<div class="flex gap-4 p-4 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-colors">
<div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-blue-500">info</span>
</div>
<div class="flex-1">
<div class="flex justify-between items-start">
<p class="text-sm font-bold">Don't forget to check-out after work.</p>
<span class="text-[10px] text-text-muted">Yesterday</span>
</div>
<p class="text-xs text-text-muted mt-1">Ensure to mark your attendance.</p>
</div>
</div>
<!-- Notification 3 -->
<div class="flex gap-4 p-4 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-colors">
<div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-orange-500">warning</span>
</div>
<div class="flex-1">
<div class="flex justify-between items-start">
<p class="text-sm font-bold">You were late by 15 minutes today.</p>
<span class="text-[10px] text-text-muted">Yesterday</span>
</div>
<p class="text-xs text-text-muted mt-1">Check-in time was 09:15 AM</p>
</div>
</div>
</div>
</div>
<!-- Attendance Summary (Calendar View) -->
<div class="col-span-12 lg:col-span-7 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100">
<div class="flex justify-between items-center mb-8">
<h3 class="text-lg font-bold">Attendance Summary</h3>
<button class="flex items-center gap-2 px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium">
                    June 2024
                    <span class="material-symbols-outlined text-sm">expand_more</span>
</button>
</div>
<div class="grid grid-cols-7 text-center border-b border-slate-100 pb-4 mb-4">
<span class="text-[10px] font-bold text-text-muted uppercase">Sun</span>
<span class="text-[10px] font-bold text-text-muted uppercase">Mon</span>
<span class="text-[10px] font-bold text-text-muted uppercase">Tue</span>
<span class="text-[10px] font-bold text-text-muted uppercase">Wed</span>
<span class="text-[10px] font-bold text-text-muted uppercase">Thu</span>
<span class="text-[10px] font-bold text-text-muted uppercase">Fri</span>
<span class="text-[10px] font-bold text-text-muted uppercase">Sat</span>
</div>
<div class="grid grid-cols-7 gap-y-6 text-center">
<!-- Sample Calendar Row (Simplified) -->
<span class="text-slate-300 text-sm">26</span><span class="text-slate-300 text-sm">27</span><span class="text-slate-300 text-sm">28</span><span class="text-slate-300 text-sm">29</span><span class="text-slate-300 text-sm">30</span><span class="text-slate-300 text-sm">31</span><span class="text-sm font-bold">1</span>
<span class="text-sm font-bold">2</span>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">3</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">4</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">5</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">6</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">7</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<span class="text-sm font-bold">8</span>
<span class="text-sm font-bold">9</span>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">10</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">11</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">12</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">13</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">14</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">15</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<span class="text-sm font-bold">16</span>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">17</span>
<span class="w-1.5 h-1.5 bg-status-late rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">18</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center">
<span class="text-sm font-bold">19</span>
<span class="w-1.5 h-1.5 bg-status-present rounded-full mt-1"></span>
</div>
<div class="flex flex-col items-center justify-center w-8 h-8 bg-accent-blue text-white rounded-full mx-auto">
<span class="text-sm font-bold">20</span>
</div>
<span class="text-sm font-bold">21</span>
<span class="text-sm font-bold">22</span>
</div>
<div class="mt-10 flex gap-6 justify-center">
<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-status-present"></span><span class="text-[10px] text-text-muted font-bold">Present</span></div>
<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-status-late"></span><span class="text-[10px] text-text-muted font-bold">Late</span></div>
<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-status-absent"></span><span class="text-[10px] text-text-muted font-bold">Absent</span></div>
<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-status-leave"></span><span class="text-[10px] text-text-muted font-bold">Leave</span></div>
</div>
</div>
<!-- Leave Status Table -->
<div class="col-span-12 lg:col-span-5 bg-card-bg rounded-2xl p-8 shadow-sm border border-slate-100">
<div class="flex justify-between items-center mb-6">
<h3 class="text-lg font-bold">Leave Status</h3>
<a class="text-accent-blue text-xs font-semibold hover:underline" href="#">View All</a>
</div>
<div class="overflow-hidden">
<table class="w-full text-left">
<thead>
<tr class="text-[10px] text-text-muted uppercase font-bold border-b border-slate-50">
<th class="py-4 pb-2">Type</th>
<th class="py-4 pb-2">Date</th>
<th class="py-4 pb-2">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
<tr>
<td class="py-4 text-xs font-bold">Annual Leave</td>
<td class="py-4 text-xs text-text-muted">24 Jun 2024</td>
<td class="py-4 text-[10px]">
<span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg font-bold">Approved</span>
</td>
</tr>
<tr>
<td class="py-4 text-xs font-bold">Paid Leave</td>
<td class="py-4 text-xs text-text-muted">10 Jun 2024</td>
<td class="py-4 text-[10px]">
<span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg font-bold">Approved</span>
</td>
</tr>
<tr>
<td class="py-4 text-xs font-bold">Paid Leave</td>
<td class="py-4 text-xs text-text-muted">05 Jun 2024</td>
<td class="py-4 text-[10px]">
<span class="bg-red-50 text-red-500 px-3 py-1 rounded-lg font-bold">Rejected</span>
</td>
</tr>
<tr>
<td class="py-4 text-xs font-bold">Annual Leave</td>
<td class="py-4 text-xs text-text-muted">01 Jun 2024</td>
<td class="py-4 text-[10px]">
<span class="bg-orange-50 text-orange-500 px-3 py-1 rounded-lg font-bold">Pending</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</main>
</body></html>