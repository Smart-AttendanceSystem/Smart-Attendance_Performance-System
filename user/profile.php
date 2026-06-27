<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Employee Portal - My Profile</title>
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
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leaveform.php">
<span class="material-symbols-outlined">event_note</span>
<span class="font-label-caps text-label-caps">Leave Request</span>
</a>
<a class="flex items-center gap-md px-md py-sm text-on-primary hover:text-on-primary hover:bg-primary-container/50 transition-colors duration-200 cursor-pointer active:scale-95" href="leavestatus.php">
<span class="material-symbols-outlined">assignment_turned_in</span>
<span class="font-label-caps text-label-caps">Leave Status</span>
</a>
<a class="flex items-center gap-md px-md py-sm border-l-4 border-secondary bg-primary-container text-on-primary cursor-pointer active:scale-95 transition-all" href="profile.php">
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
<!-- TopNavBar Anchor -->
<header class="fixed top-0 left-0 right-0 ml-sidebar-width h-16 bg-surface border-b border-outline-variant flex items-center justify-between px-lg z-40 transition-colors duration-150">
<div class="flex items-center space-x-md">
<button class="p-base hover:bg-surface-container-low rounded-lg">
<span class="material-symbols-outlined text-on-surface-variant">menu</span>
</button>
<h2 class="font-headline-sm text-headline-sm text-primary font-bold">HR Connect</h2>
</div>
<div class="flex items-center space-x-lg">
<div class="relative hidden lg:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
<input class="bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-xs focus:ring-2 focus:ring-primary/20 w-64 text-body-sm" placeholder="Search..." type="text"/>
</div>
<div class="flex items-center space-x-md">

<button class="p-base hover:bg-surface-container-low rounded-lg transition-colors">
<span class="material-symbols-outlined text-on-surface-variant">settings</span>
</button>
</div>
<div class="h-8 w-[1px] bg-outline-variant"></div>
<div class="flex items-center space-x-sm cursor-pointer group">
<div class="text-right">
<p class="font-body-md font-semibold text-primary leading-tight">Kay Ko</p>
<p class="text-[11px] text-on-surface-variant">Employee</p>
</div>
<img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant" alt="">
</div>
</div>
</header>
<!-- Main Canvas -->
<main class="ml-sidebar-width pt-16 h-screen overflow-y-auto no-scrollbar bg-background">
<div class="p-lg max-w-[1600px] mx-auto">
<!-- Page Header -->
<div class="mb-xl">
<h3 class="font-headline-md text-headline-md text-primary">My Profile</h3>
<p class="text-on-surface-variant text-body-md">View and update your personal information</p>
</div>
<div class="grid grid-cols-12 gap-lg">
<!-- Left Column: Avatar & Quick Info -->
<div class="col-span-12 lg:col-span-4 space-y-lg">
<!-- Profile Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl shadow-sm flex flex-col items-center text-center">
<div class="relative mb-lg">
<div class="w-32 h-32 rounded-full border-4 border-surface-container overflow-hidden">
<img src="https://i.pinimg.com/736x/e6/41/f7/e641f7816f326ad132ce6ae01543127a.jpg" class="w-32 h-32 rounded-full border-4 border-surface-container overflow-hidden" alt="">
</div>
<button class="absolute bottom-1 right-1 bg-surface border border-outline-variant w-8 h-8 rounded-full flex items-center justify-center hover:bg-surface-container shadow-sm transition-colors">
<span class="material-symbols-outlined text-[18px]">photo_camera</span>
</button>
</div>
<h4 class="font-headline-sm text-headline-sm text-primary"></h4>
<p class="text-on-surface-variant text-body-md mb-md">Artificial Intelligence & Automation</p>
<span class="px-md py-base bg-secondary-container text-on-secondary-container rounded-full text-[12px] font-semibold flex items-center">
<span class="w-2 h-2 bg-secondary rounded-full mr-2"></span>
              Active
            </span>
</div>
<!-- Quick Info Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<div class="bg-secondary/5 px-lg py-md border-b border-outline-variant/30">
<h5 class="font-body-md font-bold text-primary">Quick Info</h5>
</div>
<div class="p-lg space-y-md">
<div class="flex justify-between items-center py-xs border-b border-surface-container last:border-0">
<span class="text-on-surface-variant text-body-sm">Blood Group</span>
<span class="font-semibold text-primary">O+</span>
</div>
<div class="flex justify-between items-center py-xs border-b border-surface-container last:border-0">
<span class="text-on-surface-variant text-body-sm">Date of Birth</span>
<span class="font-semibold text-primary">15 May 200-</span>
</div>
<div class="flex justify-between items-center py-xs border-b border-surface-container last:border-0">
<span class="text-on-surface-variant text-body-sm">Gender</span>
<span class="font-semibold text-primary">Female</span>
</div>
</div>
</div>
</div>
<!-- Right Column: Personal Information -->
<div class="col-span-12 lg:col-span-8">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm h-full flex flex-col">
<div class="px-xl py-lg border-b border-outline-variant/30 flex justify-between items-center">
<h5 class="font-headline-sm text-headline-sm text-primary">Personal Information</h5>
<button class="flex items-center space-x-base px-md py-xs border border-primary text-primary rounded-lg hover:bg-primary/5 transition-colors font-semibold text-body-sm">
<span class="material-symbols-outlined text-[18px]">edit</span>
<span>Edit Profile</span>
</button>
</div>
<div class="p-xl grid grid-cols-1 md:grid-cols-2 gap-x-xl gap-y-lg flex-1">
<!-- Field Item -->
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Employee ID</label>
<div class="p-md bg-surface-container-low rounded-lg font-data-mono text-primary">YGN-0005</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Full Name</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">Kay Ko</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Email Address</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">kay@gmail.com</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Phone Number</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">+959 773254321</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Department</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">Data Science & Analytics</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Designation</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">Artificial Intelligence & Automation</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Date of Joining</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">01 Jan 2024</div>
</div>
<div class="space-y-base">
<label class="text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Location</label>
<div class="p-md bg-surface-container-low rounded-lg font-body-md text-primary">Yangon</div>
</div>
</div>
<!-- Decorative Visual Element -->
<div class="px-xl py-lg mt-auto border-t border-outline-variant/10">
<div class="flex items-center text-on-surface-variant opacity-60 text-body-sm">
<span class="material-symbols-outlined mr-xs text-[18px]">verified_user</span>
<span>Last verification: 20 Oct 202-</span>
</div>
</div>
</div>
</div>
</div>
<!-- Additional Stats or Links (Footer-like) -->
<footer class="mt-xl flex flex-col md:flex-row justify-between items-center text-body-sm text-on-surface-variant opacity-80 border-t border-outline-variant/30 pt-lg pb-xl">
<p>© 202- HR Connect . All rights reserved.</p>
<div class="flex space-x-lg mt-md md:mt-0">
<a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
<a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
<a class="hover:text-primary transition-colors" href="#">Support Desk</a>
</div>
</footer>
</div>
</main>
<!-- Interactive Ripple Effect Script for Buttons -->
<script>
    document.querySelectorAll('button, a').forEach(elem => {
      elem.addEventListener('mousedown', function(e) {
        let ripple = document.createElement('span');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        let d = Math.max(this.clientWidth, this.clientHeight);
        ripple.style.width = ripple.style.height = d + 'px';
        let rect = this.getBoundingClientRect();
        ripple.style.left = e.clientX - rect.left - d/2 + 'px';
        ripple.style.top = e.clientY - rect.top - d/2 + 'px';
        ripple.classList.add('show');
        setTimeout(() => ripple.remove(), 600);
      });
    });
  </script>
<style>
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: scale(0);
      pointer-events: none;
    }
    .ripple.show {
      animation: ripple-animation 0.6s linear;
    }
    @keyframes ripple-animation {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
    /* Zebra striping for detailed data tables if added */
    .zebra-row:nth-child(even) {
      background-color: rgba(0, 0, 0, 0.02);
    }
    .zebra-row:hover {
      background-color: rgba(130, 247, 216, 0.05);
    }
  </style>
</body></html>