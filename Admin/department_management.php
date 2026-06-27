<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Department Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .zebra-row:nth-child(even) {
            background-color: rgba(0, 107, 88, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c4c6cd;
            border-radius: 10px;
        }
    </style>
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
</head>
<body class="bg-background font-body-md text-on-surface">
<!-- SideNavBar Shell -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-primary dark:bg-surface-container-highest border-r border-outline-variant dark:border-outline shadow-sm flex flex-col h-full py-lg z-50">
<div class="px-md mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary dark:text-inverse-primary">Admin</h1>
<p class="font-body-md text-body-md text-on-primary">HR Management System</p>
</div>
<nav class="flex-1 space-y-base overflow-y-auto custom-scrollbar">
<!-- Dashboard (Inactive) -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-caps text-label-caps">Dashboard</span>
</a>
<!-- Employees (Inactive) -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="employee_management.php">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
<span class="font-label-caps text-label-caps">Employees</span>
</a>
<!-- Departments (Active) -->
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95" href="department_management.php">
<span class="material-symbols-outlined" data-icon="domain">domain</span>
<span class="font-label-caps text-label-caps">Departments</span>
</a>
<!-- Attendance (Inactive) -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="attendenceman.php">
<span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
<span class="font-label-caps text-label-caps">Attendance</span>
</a>
<!-- Leave Requests (Inactive) -->
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaverequest.php">
<span class="material-symbols-outlined" data-icon="event_busy">event_busy</span>
<span class="font-label-caps text-label-caps">Leave Requests</span>
</a>
</nav>
<div class="mt-auto border-t border-on-primary-container/20 pt-md space-y-base">
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="admin_setting.php">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-label-caps text-label-caps">Settings</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="dashboard.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-caps text-label-caps">Logout</span>
</a>
</div>
</aside>
<!-- TopNavBar Shell -->
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface dark:bg-surface-dim border-b border-outline-variant shadow-sm flex justify-between items-center px-lg h-16 z-40">
<div class="flex items-center gap-lg flex-1">
<h2 class="font-headline-sm text-headline-sm font-semibold text-primary dark:text-inverse-primary shrink-0">HR Admin</h2>
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-container-low border-none rounded-full py-xs pl-xl pr-md text-body-md focus:ring-2 focus:ring-secondary" placeholder="Search departments or managers..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="bg-secondary text-on-secondary px-md py-xs rounded-lg font-label-caps text-label-caps hover:bg-secondary-container hover:text-on-secondary-container transition-all flex items-center gap-xs">
<span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
                Add Employee
            </button>
<div class="h-8 w-8 rounded-full overflow-hidden border border-outline-variant ml-xs">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" data-alt="A professional high-resolution corporate headshot of a middle-aged HR executive with a kind smile, wearing a dark navy blazer over a crisp white shirt. The background is a soft-focus modern office interior with warm wooden accents and bright morning sunlight streaming through glass partitions. The lighting is flattering and high-key, conveying a sense of leadership and institutional trust." src="https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg"/>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-[260px] pt-16 min-h-screen">
<div class="max-w-[1600px] mx-auto p-lg">
<!-- Header & KPI Row -->
<div class="flex justify-between items-end mb-xl">
<div>
<h3 class="font-display-lg text-display-lg text-primary">Department Management</h3>
<p class="font-body-md text-on-surface-variant">Monitor and manage institutional structures and leadership.</p>
</div>
<button class="bg-primary text-on-primary px-lg py-md rounded-lg font-headline-sm text-headline-sm font-semibold flex items-center gap-sm shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
<span class="material-symbols-outlined" data-icon="domain_add">domain_add</span>
                    New Department
                </button>
</div>
<!-- Dashboard Style Bento Grid / KPIs -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-lg mb-xl">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm border-t-4 border-secondary">
<p class="font-label-caps text-label-caps text-on-surface-variant mb-xs">TOTAL DEPARTMENTS</p>
<div class="flex items-baseline gap-sm">
<span class="font-display-lg text-display-lg text-primary">9</span>
<span class="text-secondary font-semibold text-body-sm">Active</span>
</div>
</div>



</div>
<!-- Data Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container text-on-surface-variant border-b border-outline-variant">
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Department Name</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Manager</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Employee Count</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider">Description</th>
<th class="px-lg py-md font-label-caps text-label-caps uppercase tracking-wider text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<!-- Engineering Row -->
<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Engineering')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-primary-container text-on-primary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="terminal">terminal</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Software Engineering</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center font-bold text-xs">AS</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Alex</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">150</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">structured application of computer science principles to the entire lifecycle of a software product.</p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<!-- Sales Row -->
<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Sales')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-secondary-container text-on-secondary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="diversity_3">diversity_4</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Cyber Security</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-xs">RM</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Rachel</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">70</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">specialized unit dedicated to defending an organization’s digital ecosystem.</p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<!-- Human Resources Row -->
<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('HR')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-tertiary-container text-on-tertiary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="diversity_3">diversity_3</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Infrastructure & Cloud Operations</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-tertiary-fixed-dim text-on-tertiary-fixed flex items-center justify-center font-bold text-xs">JH</div>
<div>
<p class="font-body-md font-semibold text-on-surface">July</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">110</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">to building, maintaining, and scaling the technology foundations of an enterprise. </p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<!-- Operations Row -->
<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Operations')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-surface-container-highest text-primary rounded-lg">
<span class="material-symbols-outlined" data-icon="build_circle">build_circle</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Data Science & Analytics</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-outline-variant text-primary flex items-center justify-center font-bold text-xs">DW</div>
<div>
<p class="font-body-md font-semibold text-on-surface">David</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">82</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1"> engineering robust data pipelines, running statistical modeling, and building business intelligence infrastructure.</p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>

<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Sales')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-secondary-container text-on-secondary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Quality Assurance & Testing</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-xs">RM</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Smart</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">160</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">establishing product quality gates, managing test automation frameworks, and executing rigorous validation protocols.</p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>

<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Sales')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-secondary-container text-on-secondary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="payments">payments</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Finance & Procurement</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-xs">RM</div>
<div>
<p class="font-body-md font-semibold text-on-surface">May</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">80</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">core operational unit responsible for corporate asset allocation, budget governance, capital management, and technical supply-chain sourcing. .</p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>

<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Sales')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-secondary-container text-on-secondary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Human Resources</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-xs">RM</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Aung Aung</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">90</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">responsible for the systematic verification, validation, and quality governance of all software applications and digital products. </p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr></div>

<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Sales')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-secondary-container text-on-secondary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="support_agent">support_agent</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Technical Support & Helpdesk</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-xs">RM</div>
<div>
<p class="font-body-md font-semibold text-on-surface">John</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">108</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1">The primary point of contact for resolving user hardware, software, and connectivity issues. </p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
</div>

<tr class="zebra-row hover:bg-secondary-container/10 transition-colors group cursor-pointer" onclick="openDetails('Sales')">
<td class="px-lg py-lg">
<div class="flex items-center gap-md">
<div class="p-xs bg-secondary-container text-on-secondary-container rounded-lg">
<span class="material-symbols-outlined" data-icon="design_services">design_services</span>
</div>
<span class="font-headline-sm text-headline-sm text-primary">Product & UI/UX Design</span>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="h-8 w-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-xs">RM</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Liam</p>
</div>
</div>
</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-xs">
<span class="font-data-mono text-data-mono text-on-surface py-base px-sm bg-surface-container rounded-full">150</span>

</div>
</td>
<td class="px-lg py-lg">
<p class="font-body-sm text-on-surface-variant max-w-xs line-clamp-1"> Responsible for the strategic vision, visual architecture, and user experience of the enterprise digital products. </p>
</td>
<td class="px-lg py-lg text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs hover:bg-surface-container-high rounded transition-colors text-on-surface-variant" title="Edit">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
<button class="p-xs hover:bg-error-container hover:text-error rounded transition-colors text-on-surface-variant" title="Delete">
<span class="material-symbols-outlined" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr></div>

</tbody>
</table>
</div>
</div>
<!-- Empty State Illustration Placeholder (Only shown when filtered to zero) -->
<div class="hidden flex-col items-center justify-center py-xl text-center" id="empty-state">
<div class="h-48 w-48 mb-lg">
<div class="w-full h-full bg-surface-container-high rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-outline text-[64px]" data-icon="search_off">search_off</span>
</div>
</div>
<h4 class="font-headline-md text-headline-md text-primary">No departments found</h4>
<p class="font-body-md text-on-surface-variant">Adjust your search or add a new department to get started.</p>
</div>
</div>
</main>
<!-- Side Slide-Over Modal for Employee List -->
<div class="fixed inset-0 z-50 pointer-events-none overflow-hidden translate-x-full transition-transform duration-300 ease-in-out" id="side-panel">
<div class="absolute inset-0 bg-primary/20 backdrop-blur-sm pointer-events-auto" onclick="closeDetails()"></div>
<div class="absolute right-0 top-0 h-full w-full max-w-lg bg-surface border-l border-outline-variant pointer-events-auto shadow-2xl flex flex-col">
<div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
<div>
<h4 class="font-headline-md text-headline-md text-primary" id="panel-title">Department Details</h4>
<p class="font-label-caps text-label-caps text-secondary tracking-widest uppercase">Employees List</p>
</div>
<button class="p-sm hover:bg-surface-container-high rounded-full transition-colors" onclick="closeDetails()">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
<div class="flex-1 overflow-y-auto custom-scrollbar p-lg">
<div class="space-y-md" id="employee-list">
<!-- Dynamic Employee Rows will be injected here -->
<div class="flex items-center justify-between p-md bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-secondary transition-all cursor-pointer">
<div class="flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary">ED</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Ethan Davis</p>
<p class="text-body-sm text-on-surface-variant">Senior Lead Developer</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="chevron_right">chevron_right</span>
</div>
<!-- Repeat placeholders -->
<div class="flex items-center justify-between p-md bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-secondary transition-all cursor-pointer">
<div class="flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary">SC</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Sophia Chen</p>
<p class="text-body-sm text-on-surface-variant">Full Stack Engineer</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="chevron_right">chevron_right</span>
</div>
<div class="flex items-center justify-between p-md bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-secondary transition-all cursor-pointer">
<div class="flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary">MK</div>
<div>
<p class="font-body-md font-semibold text-on-surface">Marcus Knight</p>
<p class="text-body-sm text-on-surface-variant">Systems Architect</p>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant" data-icon="chevron_right">chevron_right</span>
</div>
</div>
</div>
<div class="p-lg bg-surface-container-lowest border-t border-outline-variant">
<button class="w-full bg-secondary text-on-secondary py-md rounded-lg font-headline-sm text-headline-sm font-semibold flex justify-center items-center gap-sm">
<span class="material-symbols-outlined" data-icon="person_add">person_add</span>
                    Assign New Employee
                </button>
</div>
</div>
</div>

</body></html>