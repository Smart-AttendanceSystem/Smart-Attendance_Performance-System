<!DOCTYPE html>

<html class="h-full" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Sign In - Smart Attendance</title>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800&amp;family=Inter:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Theme Configuration -->
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary": "#ffffff",
                    "on-primary-fixed-variant": "#36485b",
                    "error": "#ba1a1a",
                    "surface-dim": "#d9dadb",
                    "secondary-fixed-dim": "#65dabc",
                    "background": "#f8f9fa",
                    "on-tertiary-fixed-variant": "#6c228c",
                    "surface-container-low": "#f3f4f5",
                    "on-surface": "#191c1d",
                    "on-primary-container": "#96a9be",
                    "secondary": "#006b58",
                    "primary-fixed": "#d1e4fb",
                    "surface-container-high": "#e7e8e9",
                    "on-tertiary-container": "#d788f6",
                    "tertiary-fixed": "#f8d8ff",
                    "surface-container": "#edeeef",
                    "outline-variant": "#c4c6cd",
                    "surface-container-highest": "#e1e3e4",
                    "on-error-container": "#93000a",
                    "primary-container": "#2c3e50",
                    "secondary-fixed": "#82f7d8",
                    "inverse-surface": "#2e3132",
                    "on-secondary-fixed": "#002019",
                    "on-secondary-fixed-variant": "#005142",
                    "surface-tint": "#4e6073",
                    "surface-container-lowest": "#ffffff",
                    "on-primary": "#ffffff",
                    "error-container": "#ffdad6",
                    "surface-variant": "#e1e3e4",
                    "surface": "#f8f9fa",
                    "tertiary-fixed-dim": "#ecb2ff",
                    "on-surface-variant": "#43474c",
                    "primary": "#162839",
                    "on-primary-fixed": "#091d2e",
                    "tertiary-container": "#611381",
                    "surface-bright": "#f8f9fa",
                    "on-background": "#191c1d",
                    "on-secondary-container": "#00725e",
                    "on-tertiary": "#ffffff",
                    "outline": "#74777d",
                    "primary-fixed-dim": "#b5c8df",
                    "on-error": "#ffffff",
                    "tertiary": "#43005e",
                    "on-tertiary-fixed": "#320047",
                    "inverse-on-surface": "#f0f1f2",
                    "secondary-container": "#82f7d8",
                    "inverse-primary": "#b5c8df"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "md": "16px",
                    "xl": "32px",
                    "base": "4px",
                    "sm": "12px",
                    "gutter": "20px",
                    "sidebar-width": "260px",
                    "xs": "8px",
                    "lg": "24px"
            },
            "fontFamily": {
                    "body-sm": ["Inter"],
                    "headline-sm": ["Hanken Grotesk"],
                    "headline-md": ["Hanken Grotesk"],
                    "data-mono": ["JetBrains Mono"],
                    "label-caps": ["Inter"],
                    "body-md": ["Inter"],
                    "display-lg": ["Hanken Grotesk"],
                    "body-lg": ["Inter"]
            },
            "fontSize": {
                    "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                    "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                    "data-mono": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                    "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "display-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
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
        
        .login-card {
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.95);
        }

       

        input:focus {
            outline: none !important;
            border-color: #006b58 !important;
            box-shadow: 0 0 0 2px rgba(0, 107, 88, 0.1) !important;
        }
    </style>
</head>
<body class="h-full font-body-md text-on-surface selection:bg-secondary-container selection:text-on-secondary-container">
<div class="min-h-screen flex items-center justify-center">
<!-- Main Login Container -->
<main class="w-full max-w-[440px] ">
<div class="login-card shadow-md rounded-xl border border-outline-variant overflow-hidden">
<!-- Brand Header -->
<div class="bg-primary px-xl py-lg text-center flex flex-col items-center gap-xs">
<h1 class="font-headline-md text-headline-md font-bold text-on-primary tracking-tight">
                        Smart Attendance
                    </h1>
<p class="font-body-sm text-body-sm text-on-primary-fixed-variant opacity-80">
                        Enterprise Human Resource Management
                    </p>
</div>
<!-- Form Section -->
<div class="p-xl space-y-lg">
<div class="space-y-base">
<h2 class="font-headline-sm text-headline-sm text-on-surface">Welcome Back</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant">Please enter your credentials to access .</p>
</div>
<form class="space-y-md" onsubmit="event.preventDefault();">
<!-- Email Field -->
<div class="space-y-xs">
<label class="font-label-caps text-label-caps text-on-surface-variant uppercase" for="email">Email Address</label>
<div class="relative group">
<input class="w-full pl-xl pr-md py-sm bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-on-surface placeholder:text-outline focus:ring-0 transition-all" id="email" placeholder="" required="" type="email"/>
</div>
</div>
<!-- Password Field -->
<div class="space-y-xs">
<div class="flex justify-between items-end">
<label class="font-label-caps text-label-caps text-on-surface-variant uppercase" for="password">Password</label>
<a class="font-body-sm text-body-sm text-secondary hover:underline transition-all" href="#">Forgot Password?</a>
</div>
<div class="relative group">
<input class="w-full pl-xl pr-md py-sm bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-on-surface placeholder:text-outline focus:ring-0 transition-all" id="password" placeholder="••••••••" required="" type="password"/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
</div>
<!-- Utilities -->
<div class="flex items-center gap-xs">
<input class="w-4 h-4 rounded-base border-outline text-secondary focus:ring-secondary/20" id="remember" type="checkbox"/>
<label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer select-none" for="remember">Remember this device for 30 days</label>
</div>
<!-- Action Button -->
<div class="pt-md">
<button class="w-full bg-primary text-on-primary font-headline-sm text-headline-sm py-sm rounded-lg hover:bg-primary-container transition-all active:scale-[0.98] shadow-sm flex items-center justify-center gap-xs group" type="submit">
<span>Sign In</span>
<span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</div>
</form>
</div>


</div>
</div>
</div>
<!-- Trust Badges / Social Proof -->
<div class="mt-xl flex flex-col items-center gap-md opacity-60">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Secured by Enterprise Shield</p>
<div class="flex gap-lg items-center grayscale opacity-50">
<div class="h-6 w-20 bg-primary-container rounded opacity-20"></div>
<div class="h-6 w-24 bg-primary-container rounded opacity-20"></div>
<div class="h-6 w-16 bg-primary-container rounded opacity-20"></div>
</div>
</div>
</main>
</div>
<!-- Decorative Elements (Subtle corporate identity) -->
<div class="fixed bottom-gutter left-gutter hidden lg:block">
<div class="flex items-center gap-sm">
<div class="w-2 h-8 bg-secondary rounded-full"></div>
<div class="flex flex-col">
<span class="font-headline-sm text-headline-sm text-primary">Precision</span>
<span class="font-body-sm text-body-sm text-on-surface-variant -mt-1">In Attendance Tracking</span>
</div>
</div>
</div>
<!-- Background Illustration - Absolute Positioned to avoid layout shift -->
<div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
<div class="absolute -top-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[120px]"></div>
<div class="absolute -bottom-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-secondary/10 blur-[120px]"></div>
</div>
<script>
        // Micro-interaction: Form input shake on error (placeholder logic)
        const form = document.querySelector('form');
        const card = document.querySelector('.login-card');

        form.addEventListener('submit', (e) => {
            // Simulated login success
            const btn = form.querySelector('button[type="submit"]');
            const originalContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin material-symbols-outlined">progress_activity</span>`;
            
            setTimeout(() => {
                // Return to normal or redirect
                btn.innerHTML = originalContent;
                btn.disabled = false;
                console.log('Login attempt recorded');
            }, 1500);
        });

        // Hover effect for the card using mouse movement disabled to keep the form fixed.
        // document.addEventListener('mousemove', (e) => {
        //     const x = (window.innerWidth / 2 - e.pageX) / 50;
        //     const y = (window.innerHeight / 2 - e.pageY) / 50;
        //     card.style.transform = `rotateY(${x}deg) rotateX(${y}deg)`;
        // });
    </script>
</body></html>