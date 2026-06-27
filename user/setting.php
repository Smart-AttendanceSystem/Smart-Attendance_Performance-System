<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Profile &amp; Security | HR Connect</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-primary-fixed": "#091d2e",
                    "on-error": "#ffffff",
                    "error-container": "#ffdad6",
                    "tertiary-fixed": "#f8d8ff",
                    "on-tertiary-container": "#d788f6",
                    "secondary-container": "#82f7d8",
                    "on-error-container": "#93000a",
                    "tertiary-fixed-dim": "#ecb2ff",
                    "tertiary": "#43005e",
                    "on-tertiary": "#ffffff",
                    "on-secondary": "#ffffff",
                    "on-secondary-container": "#00725e",
                    "surface-bright": "#f8f9fa",
                    "primary-container": "#2c3e50",
                    "surface-dim": "#d9dadb",
                    "on-primary": "#ffffff",
                    "on-background": "#191c1d",
                    "background": "#f8f9fa",
                    "surface-container-highest": "#e1e3e4",
                    "inverse-on-surface": "#f0f1f2",
                    "on-surface": "#191c1d",
                    "outline-variant": "#c4c6cd",
                    "surface": "#f8f9fa",
                    "secondary-fixed": "#82f7d8",
                    "on-secondary-fixed-variant": "#005142",
                    "surface-variant": "#e1e3e4",
                    "primary-fixed-dim": "#b5c8df",
                    "surface-container-low": "#f3f4f5",
                    "surface-container-high": "#e7e8e9",
                    "on-secondary-fixed": "#002019",
                    "on-primary-fixed-variant": "#36485b",
                    "tertiary-container": "#611381",
                    "surface-container": "#edeeef",
                    "inverse-surface": "#2e3132",
                    "outline": "#74777d",
                    "primary": "#162839",
                    "inverse-primary": "#b5c8df",
                    "surface-container-lowest": "#ffffff",
                    "primary-fixed": "#d1e4fb",
                    "on-tertiary-fixed-variant": "#6c228c",
                    "on-tertiary-fixed": "#320047",
                    "error": "#ba1a1a",
                    "secondary": "#006b58",
                    "on-surface-variant": "#43474c"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "xl": "32px",
                    "gutter": "20px",
                    "sidebar-width": "260px",
                    "md": "16px",
                    "lg": "24px",
                    "sm": "12px",
                    "xs": "8px",
                    "base": "4px"
            },
            "fontFamily": {
                    "headline-md": ["Hanken Grotesk"],
                    "body-sm": ["Inter"],
                    "label-caps": ["Inter"],
                    "display-lg": ["Hanken Grotesk"],
                    "body-lg": ["Inter"],
                    "headline-sm": ["Hanken Grotesk"],
                    "data-mono": ["JetBrains Mono"],
                    "body-md": ["Inter"]
            },
            "fontSize": {
                    "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                    "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                    "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "display-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "data-mono": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                    "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        body { background-color: #F8F9FA; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 24px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid #E9ECEF;
        }
        .sidebar-active-indicator {
            position: absolute;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: #006b58;
        }
    </style>
</head>
<body class="font-body-md text-on-surface">
<!-- SideNavBar Shell -->
<aside class="fixed left-0 top-0 h-full w-sidebar-width bg-primary dark:bg-primary-container shadow-sm flex flex-col py-lg z-50">
<div class="px-lg mb-xl">
<h1 class="font-headline-sm text-headline-sm text-on-primary font-bold">Employee Portal</h1>
<p class="text-on-primary-container opacity-80 text-body-sm">Precision HR</p>
</div>
<nav class="flex-1 space-y-base">
<a class="group relative flex items-center px-lg py-sm transition-all duration-200 ease-in-out text-on-primary-container opacity-80 hover:bg-on-primary-fixed-variant hover:text-on-primary" href="#">
<span class="material-symbols-outlined mr-md" data-icon="dashboard">dashboard</span>
<span class="font-body-md text-body-md">Dashboard</span>
</a>
<a class="group relative flex items-center px-lg py-sm transition-all duration-200 ease-in-out text-on-primary-container opacity-80 hover:bg-on-primary-fixed-variant hover:text-on-primary" href="#">
<span class="material-symbols-outlined mr-md" data-icon="calendar_today">calendar_today</span>
<span class="font-body-md text-body-md">Attendance</span>
</a>
<a class="group relative flex items-center px-lg py-sm transition-all duration-200 ease-in-out text-on-primary-container opacity-80 hover:bg-on-primary-fixed-variant hover:text-on-primary" href="#">
<span class="material-symbols-outlined mr-md" data-icon="event_busy">event_busy</span>
<span class="font-body-md text-body-md">Leave</span>
</a>
<!-- Active State: Profile -->
<a class="group relative flex items-center px-lg py-sm transition-all duration-200 ease-in-out text-secondary border-l-4 border-secondary bg-on-primary-fixed-variant" href="#">
<span class="material-symbols-outlined mr-md" data-icon="person">person</span>
<span class="font-body-md text-body-md">Profile</span>
</a>
</nav>
<div class="px-lg pt-lg border-t border-on-primary-fixed-variant">
<div class="flex items-center space-x-md">
<div class="w-10 h-10 rounded-full bg-surface-container-high overflow-hidden border border-outline-variant">
<img class="w-full h-full object-cover" data-alt="A professional studio headshot of a middle-aged corporate executive with a friendly expression. The lighting is soft and directional, typical of a high-end office environment. The color palette is clean, featuring neutral grays and blues to match the corporate UI. The background is a slightly out-of-focus modern office interior with warm wooden accents." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBB3IBLk8vm6AzZQzt3ccOWPWmMMogF6_SWU42kSKn5eLHs92UxokDaGUVCjXjy6_uQlsoRCTT5yjHWxGRR0Di0pCr14L5I8aVDGIRJ35m5kDYX7ucAiJr7MJKRYKS9UAsvmr3EUbN6V4VD574VixoRfbwD1s78MoUTM8g8_WcD3AEV0t7lnTrAmqtPYQ3lxWxQzjTfz-TGGGtQfRaFMNRsnYLXWf30EwtX0xdp4xR-iA8TDcWeScTsZNbta3aY8DzNkoeHWMcpN7e2"/>
</div>
<div class="overflow-hidden">
<p class="text-on-primary font-bold truncate text-body-md">Alex Rivera</p>
<p class="text-on-primary-container opacity-70 text-body-sm truncate">Senior Analyst</p>
</div>
</div>
</div>
</aside>
<!-- TopNavBar Shell -->
<header class="fixed top-0 right-0 left-sidebar-width h-16 bg-surface dark:bg-inverse-surface border-b border-outline-variant dark:border-outline flex justify-between items-center px-lg z-40">
<div class="flex items-center">
<span class="font-headline-sm text-headline-sm text-primary dark:text-primary-fixed font-bold">HR Connect</span>
</div>
<div class="flex items-center space-x-lg">
<div class="relative hidden lg:block w-72">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm" data-icon="search">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="Search resources..." type="text"/>
</div>
<div class="flex items-center space-x-md">
<button class="p-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors rounded-full">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
</button>
<button class="p-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors rounded-full">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
</button>
<div class="h-6 w-px bg-outline-variant"></div>
<button class="px-md py-2 bg-secondary text-on-secondary font-bold text-body-sm rounded-lg hover:opacity-90 transition-opacity">
                    Check In/Out
                </button>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-sidebar-width pt-16 min-h-screen">
<div class="max-w-[1200px] mx-auto p-xl">
<!-- Page Header -->
<div class="mb-xl flex justify-between items-end">
<div>
<h2 class="font-display-lg text-display-lg text-primary tracking-tight">Account &amp; Security</h2>
<p class="text-on-surface-variant font-body-md mt-xs">Manage your personal identity and workspace credentials.</p>
</div>
<div class="flex space-x-md">
<button class="px-lg py-2 border border-outline text-primary font-bold text-body-sm rounded-lg hover:bg-surface-container-low transition-colors">Discard</button>
<button class="px-lg py-2 bg-primary text-on-primary font-bold text-body-sm rounded-lg shadow-sm hover:bg-on-primary-fixed-variant transition-colors">Save Changes</button>
</div>
</div>
<!-- Bento Grid Layout -->
<div class="bento-grid">
<!-- Profile Identity Card -->
<section class="col-span-12 lg:col-span-4 space-y-lg">
<div class="glass-card rounded-xl p-lg shadow-sm">
<div class="flex flex-col items-center text-center">
<div class="relative group">
<div class="w-32 h-32 rounded-full overflow-hidden border-4 border-surface-container-highest shadow-inner">
<img class="w-full h-full object-cover" data-alt="A close-up portrait of a professional worker, shot in a high-contrast cinematic style with natural side-lighting. The image focuses on the person's face, conveying reliability and expertise. The aesthetic is modern and corporate, utilizing a color palette of soft neutrals and cool blues to blend seamlessly with the portal's design." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCR5M39ffm4k0B5T-uwtW2GSuPPLPO7Bb1whBUWPoTn6OM2_GHNdYkK0A0GtXH5kI17tZDwVmVHfar9lWMKEKQNO3zxASGcc8C3WZlwbqF2qi9S5rLXD9uLfgLXbdjvLx6n6Cn9YHj1NPyv7BdMwcqHlxFe01v_HLnlDsGAZ8giA9jGplzhQGFwjpliI1FDze9EhhBWQtXLf2lsI6eOzftnFUAQY8yXquy1CdAh9KQYs0dgGvQZtIy769Q_Ay9WrIhgm3LCqWrjhFv-"/>
</div>
<button class="absolute bottom-0 right-0 p-2 bg-secondary text-on-secondary rounded-full shadow-lg hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
</button>
</div>
<h3 class="mt-lg font-headline-sm text-headline-sm text-primary">Alex Rivera</h3>
<p class="text-on-surface-variant font-body-sm">Employee ID: #HR-99210</p>
<div class="mt-md px-md py-1 bg-secondary-container text-on-secondary-container rounded-full text-label-caps font-bold">
                                ACTIVE PERMANENT
                            </div>
</div>
<div class="mt-xl space-y-md border-t border-outline-variant pt-lg">
<div class="flex justify-between items-center">
<span class="text-on-surface-variant text-body-sm">Department</span>
<span class="text-primary font-bold text-body-sm">Business Intelligence</span>
</div>
<div class="flex justify-between items-center">
<span class="text-on-surface-variant text-body-sm">Reporting To</span>
<span class="text-primary font-bold text-body-sm">Sarah Jenkins</span>
</div>
<div class="flex justify-between items-center">
<span class="text-on-surface-variant text-body-sm">Join Date</span>
<span class="text-primary font-bold text-body-sm">Mar 12, 2021</span>
</div>
</div>
</div>
<!-- Contact Card -->
<div class="glass-card rounded-xl p-lg shadow-sm">
<h4 class="font-label-caps text-on-surface-variant mb-md uppercase">Contact Details</h4>
<div class="space-y-md">
<div class="flex items-center p-md bg-surface-container-low rounded-lg">
<span class="material-symbols-outlined text-secondary mr-md" data-icon="mail">mail</span>
<div>
<p class="text-label-caps text-on-surface-variant uppercase">Work Email</p>
<p class="text-primary font-body-md">a.rivera@hrconnect.co</p>
</div>
</div>
<div class="flex items-center p-md bg-surface-container-low rounded-lg">
<span class="material-symbols-outlined text-secondary mr-md" data-icon="phone">phone</span>
<div>
<p class="text-label-caps text-on-surface-variant uppercase">Phone Number</p>
<p class="text-primary font-body-md">+1 (555) 012-3456</p>
</div>
</div>
</div>
</div>
</section>
<!-- Profile Info and Security Sections -->
<section class="col-span-12 lg:col-span-8 space-y-lg">
<!-- Bio Section -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
<div class="flex items-center mb-lg">
<span class="material-symbols-outlined text-primary mr-sm" data-icon="badge">badge</span>
<h4 class="font-headline-sm text-headline-sm text-primary">Professional Summary</h4>
</div>
<div class="space-y-md">
<label class="block text-label-caps text-on-surface-variant uppercase font-bold">Biography</label>
<textarea class="w-full p-md bg-surface-container-low border border-outline-variant rounded-lg text-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="Tell us about your role and expertise..." rows="4">Senior Data Analyst with 5+ years of experience in human resources modeling. Specialized in retention metrics and operational efficiency auditing. Passionate about creating data-driven cultures that empower people.</textarea>
</div>
</div>
<!-- Security Schema Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<div class="bg-primary-container p-lg text-on-primary">
<div class="flex items-center">
<span class="material-symbols-outlined mr-sm" data-icon="security">security</span>
<h4 class="font-headline-sm text-headline-sm">Security &amp; Access</h4>
</div>
<p class="text-on-primary-container text-body-sm mt-xs">Credentials and authorization management</p>
</div>
<div class="p-lg space-y-xl">
<!-- Password Action -->
<div class="space-y-lg">
<div class="flex items-start justify-between">
<div class="max-w-md">
<h5 class="font-body-lg text-primary font-bold">Request Password Reset</h5>
<p class="text-on-surface-variant text-body-sm mt-base">For security, password changes require HR authorization. A request will be sent to your HR Administrator for approval.</p>
</div>
<button class="flex items-center px-lg py-2 bg-primary text-on-primary font-bold text-body-sm rounded-lg hover:bg-on-primary-fixed-variant transition-colors group" id="reset-request-btn">
<span class="material-symbols-outlined mr-xs text-sm group-hover:rotate-180 transition-transform">lock_reset</span>
      Request Password Reset
    </button>
</div>
<div class="flex items-center p-md bg-surface-container-low border border-outline-variant rounded-lg">
<span class="material-symbols-outlined text-secondary mr-md">info</span>
<p class="text-body-sm text-on-surface-variant">
<span class="font-bold text-primary">Status:</span> Password reset request pending approval (Sent Oct 27, 2023)
    </p>
</div>
</div>
<div class="h-px bg-outline-variant"></div><div class="space-y-md opacity-50 pointer-events-none">
<h5 class="font-label-caps text-on-surface-variant uppercase">Password Update (Available after approval)</h5>
<div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<div>
<label class="block text-label-caps text-on-surface-variant mb-xs">Current Password</label>
<input class="w-full p-2 bg-surface-container-low border border-outline-variant rounded-lg" disabled="" type="password" value="********"/>
</div>
<div>
<label class="block text-label-caps text-on-surface-variant mb-xs">New Password</label>
<input class="w-full p-2 bg-surface-container-low border border-outline-variant rounded-lg" disabled="" type="password"/>
</div>
<div>
<label class="block text-label-caps text-on-surface-variant mb-xs">Confirm New Password</label>
<input class="w-full p-2 bg-surface-container-low border border-outline-variant rounded-lg" disabled="" type="password"/>
</div>
</div>
</div>
<div class="h-px bg-outline-variant"></div>
<!-- 2FA Toggle -->
<div class="flex items-center justify-between">
<div>
<h5 class="font-body-lg text-primary font-bold">Two-Factor Authentication (2FA)</h5>
<p class="text-on-surface-variant text-body-sm mt-base">Currently active via Google Authenticator.</p>
</div>
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
</label>
</div>
<div class="h-px bg-outline-variant"></div>
<!-- Login History -->
<div>
<h5 class="font-body-lg text-primary font-bold mb-md">Recent Security Activity</h5>
<div class="space-y-sm">
<div class="flex items-center justify-between p-sm border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors">
<div class="flex items-center">
<div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container mr-md">
<span class="material-symbols-outlined text-sm" data-icon="laptop_mac">laptop_mac</span>
</div>
<div>
<p class="font-bold text-body-sm text-primary">MacBook Pro - Chrome</p>
<p class="text-body-sm text-on-surface-variant">San Francisco, USA • 192.168.1.1</p>
</div>
</div>
<span class="text-label-caps text-on-surface-variant">2 HOURS AGO</span>
</div>
<div class="flex items-center justify-between p-sm border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors">
<div class="flex items-center">
<div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed-variant mr-md">
<span class="material-symbols-outlined text-sm" data-icon="smartphone">smartphone</span>
</div>
<div>
<p class="font-bold text-body-sm text-primary">iPhone 13 - Safari</p>
<p class="text-body-sm text-on-surface-variant">San Francisco, USA • 192.168.1.4</p>
</div>
</div>
<span class="text-label-caps text-on-surface-variant">YESTERDAY</span>
</div>
</div>
</div>
</div>
</div>
<!-- Toast Notification (Hidden by default) -->
<div class="fixed bottom-lg right-lg translate-y-20 opacity-0 transition-all duration-300 pointer-events-none bg-primary-container text-on-primary px-xl py-md rounded-xl shadow-lg flex items-center z-50" id="toast">
<span class="material-symbols-outlined mr-md text-secondary" data-icon="check_circle">check_circle</span>
<div>
<p class="font-bold text-body-md">Reset Request Sent</p>
<p class="text-on-primary-container text-body-sm">HR Administration has been notified.</p>
</div>
</div>
</section>
</div>
</div>
</main>
<script>
        // Micro-interaction for Reset Password Request
        const resetBtn = document.getElementById('reset-request-btn');
        const toast = document.getElementById('toast');

        resetBtn.addEventListener('click', () => {
            // Disable button temporarily
            resetBtn.disabled = true;
            resetBtn.classList.add('opacity-50', 'cursor-not-allowed');
            resetBtn.innerHTML = `
                <span class="material-symbols-outlined mr-xs text-sm animate-spin" data-icon="progress_activity">progress_activity</span>
                Processing...
            `;

            // Simulate server/HR logic delay
            setTimeout(() => {
                // Show Toast
                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                
                // Reset button after success
                setTimeout(() => {
                    resetBtn.disabled = false;
                    resetBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    resetBtn.innerHTML = `
                        <span class="material-symbols-outlined mr-xs text-sm" data-icon="lock_reset">lock_reset</span>
                        Request Reset
                    `;
                    // Hide toast
                    setTimeout(() => {
                        toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                    }, 3000);
                }, 500);
            }, 1200);
        });

        // Sticky Header effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 10) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    </script>
</body></html>