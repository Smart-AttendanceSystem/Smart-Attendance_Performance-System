<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Employee Management</title>
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
<!-- Main Content Area -->
<div class="ml-[260px] min-h-screen flex flex-col">
<!-- TopNavBar -->
<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 bg-surface border-b border-outline-variant flex justify-between items-center px-lg h-16 z-40 shadow-sm">
<div class="flex items-center gap-lg flex-1">
<span class="font-headline-sm text-headline-sm font-semibold text-primary">HR Admin</span>
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-secondary text-body-md" placeholder="Search by name, department, or ID..." type="text"/>
</div>
</div>
<div class="flex items-center gap-md">
<button class="bg-secondary text-on-secondary px-md py-2 rounded-lg font-label-caps text-label-caps transition-all duration-200 hover:opacity-90 active:scale-95" onclick="document.getElementById('modal-overlay').classList.remove('hidden')">
                    Add Employee
                </button>
<div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden ml-sm">
<img class="w-10 h-10 rounded-full border-2 border-secondary object-cover" data-alt="A professional high-resolution corporate headshot of a middle-aged HR executive with a kind smile, wearing a dark navy blazer over a crisp white shirt. The background is a soft-focus modern office interior with warm wooden accents and bright morning sunlight streaming through glass partitions. The lighting is flattering and high-key, conveying a sense of leadership and institutional trust." src="https://i.pinimg.com/736x/5f/cb/0a/5fcb0a5578d81bba2917013c511cc247.jpg"/>
</div>
</div>
</header>
<!-- Main Canvas -->
<main class="mt-16 p-lg flex-1">
<div class="max-w-[1600px] mx-auto space-y-lg">
<!-- Page Title & Stats Bar -->
<div class="flex justify-between items-end">
<div>
<h2 class="font-headline-md text-headline-md text-primary">Employee Directory</h2>
<p class="text-on-surface-variant font-body-md">Manage your workforce, positions, and operational status.</p>
</div>
<div class="flex gap-md">
<div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl shadow-sm flex items-center gap-md">
<div class="bg-secondary/10 p-2 rounded-lg text-secondary">
<span class="material-symbols-outlined">groups</span>
</div>
<div>
<p class="text-label-caps font-label-caps text-on-surface-variant">Total Staff</p>
<p class="text-headline-sm font-headline-sm text-primary">1,284</p>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl shadow-sm flex items-center gap-md">
<div class="bg-tertiary-container/20 p-2 rounded-lg text-tertiary-container">
<span class="material-symbols-outlined">person_add</span>
</div>
<div>
<p class="text-label-caps font-label-caps text-on-surface-variant">New This Month</p>
<p class="text-headline-sm font-headline-sm text-primary">24</p>
</div>
</div>
</div>
</div>
<!-- Table Container -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">ID</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Name</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Department</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Position</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Status</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant">Email</th>
<th class="px-lg py-md font-label-caps text-label-caps text-on-surface-variant text-right">Actions</th>
</tr>
</thead>
<tbody class="font-body-md text-on-surface">
<!-- Row 1 -->
<tr class="zebra-row hover-row border-b border-surface-container transition-colors">
<td class="px-lg py-md font-data-mono text-data-mono">YGN-0005</td>
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-dim overflow-hidden">
<img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Kay Ko</span>
</div>
</td>
<td class="px-lg py-md">Artificial Intelligence & Automation</td>
<td class="px-lg py-md text-on-surface-variant">Strategy & Management</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-base px-2 py-1 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-bold">
<span class="w-2 h-2 rounded-full bg-secondary"></span> Active
                                        </span>
</td>
<td class="px-lg py-md text-on-surface-variant">Kay@gmail.com</td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs">
<button class="p-1 hover:bg-primary-container/10 rounded text-primary transition-colors" title="Edit Profile"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-1 hover:bg-tertiary-container/10 rounded text-tertiary transition-colors" title="Reset Password"><span class="material-symbols-outlined text-[18px]">lock_reset</span></button>
<button class="p-1 hover:bg-error-container/20 rounded text-error transition-colors" title="Delete"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="zebra-row hover-row border-b border-surface-container transition-colors">
<td class="px-lg py-md font-data-mono text-data-mono">YGN-0043</td>
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-dim overflow-hidden">
<img src="https://i.pinimg.com/736x/16/a0/34/16a034c977760cd8185e279393265d3a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Sara</span>
</div>
</td>
<td class="px-lg py-md">Infrastructure & Network Operations</td>
<td class="px-lg py-md text-on-surface-variant">Cloud Engineer</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-base px-2 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-bold">
<span class="w-2 h-2 rounded-full bg-tertiary"></span> On Leave
                                        </span>
</td>
<td class="px-lg py-md text-on-surface-variant">s@gmail.com</td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs">
<button class="p-1 hover:bg-primary-container/10 rounded text-primary transition-colors" title="Edit Profile"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-1 hover:bg-tertiary-container/10 rounded text-tertiary transition-colors" title="Reset Password"><span class="material-symbols-outlined text-[18px]">lock_reset</span></button>
<button class="p-1 hover:bg-error-container/20 rounded text-error transition-colors" title="Delete"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="zebra-row hover-row border-b border-surface-container transition-colors">
<td class="px-lg py-md font-data-mono text-data-mono">YGN-1001</td>
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-dim overflow-hidden">
<img src="https://i.pinimg.com/736x/b2/22/c9/b222c9b29c5ca95e739e45072f04f715.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
<span class="font-semibold">Alina</span>
</div>
</td>
<td class="px-lg py-md"> Cyber Security</td>
<td class="px-lg py-md text-on-surface-variant">Security Architect</td>
<td class="px-lg py-md">
<span class="inline-flex items-center gap-base px-2 py-1 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-bold">
<span class="w-2 h-2 rounded-full bg-secondary"></span> Active
                                        </span>
</td>
<td class="px-lg py-md text-on-surface-variant">Alina@gmail.com</td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs">
<button class="p-1 hover:bg-primary-container/10 rounded text-primary transition-colors" title="Edit Profile"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-1 hover:bg-tertiary-container/10 rounded text-tertiary transition-colors" title="Reset Password"><span class="material-symbols-outlined text-[18px]">lock_reset</span></button>
<button class="p-1 hover:bg-error-container/20 rounded text-error transition-colors" title="Delete"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Row 4 -->

</tbody>
</table>
</div>
<!-- Pagination Footer -->
<div class="p-lg bg-surface-container-lowest border-t border-outline-variant flex justify-between items-center">
<span class="text-body-sm text-on-surface-variant">Showing 1-4 of 1,200 employees</span>
<div class="flex gap-xs">
<button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container transition-colors text-body-sm disabled:opacity-30" disabled="">Previous</button>
<button class="px-3 py-1 bg-secondary text-on-secondary rounded text-body-sm font-bold">1</button>
<button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container transition-colors text-body-sm">2</button>
<button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container transition-colors text-body-sm">3</button>
<button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container transition-colors text-body-sm">Next</button>
</div>
</div>
</div>
</div>
</main>
</div>
<!-- Add Employee Modal Overlay -->
<div class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-md" id="modal-overlay">
<div class="bg-surface-container-lowest w-full max-w-2xl rounded-xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-300">
<!-- Modal Header -->
<div class="bg-primary p-lg text-on-primary flex justify-between items-center">
<div>
<h3 class="font-headline-sm text-headline-sm">Add New Employee</h3>
<p class="text-on-primary-container text-body-sm">Create a new record in the HR database</p>
</div>
<button class="hover:bg-primary-container p-2 rounded-full transition-colors" onclick="document.getElementById('modal-overlay').classList.add('hidden')">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<!-- Modal Body -->
<form class="p-lg grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Full Name</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" placeholder="e.g. John Doe" type="text"/>
</div>
<div class="space-y-base col-span-2 md:col-span-1">
<label class="font-label-caps text-label-caps text-on-surface-variant">Email Address</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" placeholder="john.doe@company.com" type="email"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Department</label>
<select class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary">
<option>Engineering</option>
<option>Marketing</option>
<option>Sales</option>
<option>HR</option>
<option>Design</option>
</select>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Position</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" placeholder="e.g. Lead Developer" type="text"/>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Employment Type</label>
<div class="flex gap-md mt-base">
<label class="flex items-center gap-xs cursor-pointer">
<input checked="" class="text-secondary focus:ring-secondary" name="emp_type" type="radio"/>
<span class="text-body-sm">Full-time</span>
</label>
<label class="flex items-center gap-xs cursor-pointer">
<input class="text-secondary focus:ring-secondary" name="emp_type" type="radio"/>
<span class="text-body-sm">Contract</span>
</label>
</div>
</div>
<div class="space-y-base">
<label class="font-label-caps text-label-caps text-on-surface-variant">Joining Date</label>
<input class="w-full border-outline-variant rounded-lg focus:ring-secondary focus:border-secondary" type="date"/>
</div>
</form>
<!-- Modal Footer -->
<div class="p-lg bg-surface-container-low flex justify-end gap-md">
<button class="px-lg py-2 rounded-lg text-primary hover:bg-surface-container-high transition-colors font-label-caps text-label-caps" onclick="document.getElementById('modal-overlay').classList.add('hidden')">Cancel</button>
<button class="bg-secondary text-on-secondary px-xl py-2 rounded-lg hover:opacity-90 transition-opacity font-label-caps text-label-caps">Save Employee</button>
</div>
</div>
</div>
<script>
        // Simple search interaction mockup
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.zebra-row');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('modal-overlay').classList.add('hidden');
            }
        });
    </script>
</body></html>