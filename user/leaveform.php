<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Leave Request</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendence.php">
<span class="material-symbols-outlined">schedule</span>
<span class="font-label-caps text-label-caps">Attendence</span>
</a>
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="leaveform.php">
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
<!-- Top Navigation Bar -->
<header class="fixed top-0 right-0 left-sidebar-width bg-white border-b border-gray-100 z-40 flex justify-between items-center px-lg py-sm">
<div class="flex items-center gap-md">
<button class="material-symbols-outlined text-gray-400" data-icon="menu">menu</button>
<div>
<h2 class="text-body-lg font-bold text-gray-800">Welcome back, Kay 👋</h2>
<p class="text-xs text-gray-400">Employee ID :YGN-0005</p>
</div>
</div>
<div class="flex items-center gap-lg">
<div class="flex items-center gap-md pl-lg border-l border-gray-100">
<img src="" alt="">
<div class="hidden sm:block">
<p class="text-sm font-bold text-gray-800 leading-none">Kay Ko</p>
<p class="text-xs text-gray-400">Employee</p>
</div>
<span class="material-symbols-outlined text-gray-400" data-icon="expand_more">expand_more</span>
</div>
</div>
</header>
<!-- Main Content -->
<main class="ml-sidebar-width pt-[80px] min-h-screen p-lg">
<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-lg">
<!-- Leave Request Form Area -->
<div class="lg:col-span-8 bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
<div class="p-lg">
<h3 class="text-xl font-bold text-gray-800">Leave Request</h3>
<p class="text-sm text-gray-400 mt-1">Fill in the details below to request for leave</p>
<form class="mt-lg space-y-lg">
<!-- Leave Type -->
<div class="space-y-xs">
<label class="text-sm font-medium text-gray-700">Leave Type <span class="text-red-500">*</span></label>
<div class="relative">
<select class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue appearance-none">
<option>Annual Leave</option>
<option>Paid Leave</option>

</select>
<span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" data-icon="expand_more">expand_more</span>
</div>
</div>
<!-- Dates -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="space-y-xs">
<label class="text-sm font-medium text-gray-700">Start Date <span class="text-red-500">*</span></label>
<div class="relative">
<input class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue" type="text" value="20/06/2024"/>
<span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl" data-icon="calendar_today">calendar_today</span>
</div>
</div>
<div class="space-y-xs">
<label class="text-sm font-medium text-gray-700">End Date <span class="text-red-500">*</span></label>
<div class="relative">
<input class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue" type="text" value="22/06/2024"/>
<span class="material-symbols-outlined absolute right-md top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl" data-icon="calendar_today">calendar_today</span>
</div>
</div>
</div>
<!-- Total Days -->
<div class="space-y-xs">
<label class="text-sm font-medium text-gray-700">Total Days</label>
<input class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:outline-none" readonly="" type="text" value="3 Days"/>
</div>
<!-- Reason -->
<div class="space-y-xs">
<label class="text-sm font-medium text-gray-700">Reason <span class="text-red-500">*</span></label>
<div class="relative">
<textarea class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-md text-sm text-gray-800 focus:ring-brand-blue focus:border-brand-blue resize-none" placeholder="Enter reason here..." rows="4">Family vacation and personal work.</textarea>
<span class="absolute bottom-2 right-2 text-[10px] text-gray-400">34/500</span>
</div>
</div>
<!-- Attachment -->


</div>
</div>
</div>
<!-- Form Actions -->
<div class="flex justify-end gap-md pt-lg border-t border-gray-50">
<button class="px-8 py-2.5 border border-gray-200 text-gray-600 font-semibold rounded-lg hover:bg-gray-50 transition-colors text-sm" type="reset">Reset</button>
<button class="px-8 py-2.5 bg-brand-blue text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center gap-xs text-sm" type="submit">
                            Submit Request
                            <span class="material-symbols-outlined text-sm" data-icon="send">send</span>
</button>
</div>
<p class="text-[10px] text-red-500">* Indicates required field</p>
</form>
</div>
</div>
<!-- Sidebar Components -->
<div class="lg:col-span-4 space-y-lg">
<!-- Leave Balance Card -->
<div class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] border border-gray-100 p-lg">
<div class="flex items-center gap-md mb-lg">
<span class="material-symbols-outlined text-brand-blue" data-icon="calendar_month">calendar_month</span>
<h4 class="text-md font-bold text-gray-800">Leave Balance</h4>
</div>
<div class="space-y-lg">
<!-- Annual Leave -->
<div class="flex items-center justify-between">
<div class="flex items-center gap-md">
<div class="bg-blue-50 p-2 rounded-lg">
<span class="material-symbols-outlined text-brand-blue text-lg" data-icon="calendar_today">calendar_today</span>
</div>
<span class="text-sm font-medium text-gray-600">Annual Leave</span>
</div>
<span class="text-sm font-bold text-gray-800"><span class="text-brand-blue">12</span> Days Left</span>
</div>
<!-- Sick Leave -->
<div class="flex items-center justify-between">
<div class="flex items-center gap-md">
<div class="bg-green-50 p-2 rounded-lg">
<span class="material-symbols-outlined text-green-500 text-lg" data-icon="medical_services">medical_services</span>
</div>
<span class="text-sm font-medium text-gray-600">Sick Leave</span>
</div>
<span class="text-sm font-bold text-gray-800"><span class="text-green-500">8</span> Days Left</span>
</div>
<div class="flex items-center justify-between">
<div class="flex items-center gap-md">
<div class="bg-green-50 p-2 rounded-lg">
<span class="material-symbols-outlined text-green-500 text-lg" data-icon="medical_services">calendar_today</span>
</div>
<span class="text-sm font-medium text-gray-600">Paid Leave</span>
</div>
<span class="text-sm font-bold text-gray-800"><span class="text-green-500">8</span> Days Left</span>
</div>

</div>
</div>

</div>
</div>
</main>
<script>
    // Minimal interaction logic
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = e.submitter;
        const originalContent = btn.innerHTML;
        btn.innerHTML = `<span class="material-symbols-outlined animate-spin" data-icon="progress_activity">progress_activity</span> Sending...`;
        btn.disabled = true;
        
        setTimeout(() => {
            alert('Leave request submitted successfully!');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }, 1500);
    });
</script>
</body></html>