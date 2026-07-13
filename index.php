<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
?>
<!DOCTYPE html>
<html lang="en" class="h-full"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Sign In - Smart Attendance</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Hanken+Grotesk:wght@600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#162839',
                            navyLight: '#1e3347',
                            navyDark: '#0f1c2a',
                            teal: '#008751',
                            tealDark: '#006d41',
                            gray: '#64748b',
                            border: '#e2e8f0',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Hanken Grotesk', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-gradient {
            background: linear-gradient(135deg, #162839 0%, #1e3347 40%, #2c3e50 100%);
        }
        .brand-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 60%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 135, 81, 0.15);
            filter: blur(60px);
        }
        .brand-gradient::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 50%;
            height: 80%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            filter: blur(60px);
        }
        .form-card {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }
        input:focus {
            outline: none !important;
            border-color: #008751 !important;
            box-shadow: 0 0 0 3px rgba(0, 135, 81, 0.12) !important;
        }
        .btn-signin {
            background: linear-gradient(135deg, #162839 0%, #1e3347 100%);
            transition: all 0.25s ease;
        }
        .btn-signin:hover {
            background: linear-gradient(135deg, #1e3347 0%, #2c3e50 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(22, 40, 57, 0.3);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        @media (max-width: 1023px) {
            .auth-split { flex-direction: column; }
            .brand-left { min-height: 240px; }
        }
    </style>
<style>*, ::before, ::after{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-gradient-from-position: ;--tw-gradient-via-position: ;--tw-gradient-to-position: ;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: ;--tw-contain-size: ;--tw-contain-layout: ;--tw-contain-paint: ;--tw-contain-style: }::backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-gradient-from-position: ;--tw-gradient-via-position: ;--tw-gradient-to-position: ;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: ;--tw-contain-size: ;--tw-contain-layout: ;--tw-contain-paint: ;--tw-contain-style: }/* ! tailwindcss v3.4.17 | MIT License | https://tailwindcss.com */*,::after,::before{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}::after,::before{--tw-content:''}:host,html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:Inter, sans-serif;font-feature-settings:normal;font-variation-settings:normal;-webkit-tap-highlight-color:transparent}body{margin:0;line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;font-feature-settings:normal;font-variation-settings:normal;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,optgroup,select,textarea{font-family:inherit;font-feature-settings:inherit;font-variation-settings:inherit;font-size:100%;font-weight:inherit;line-height:inherit;letter-spacing:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}button,input:where([type=button]),input:where([type=reset]),input:where([type=submit]){-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dd,dl,figure,h1,h2,h3,h4,h5,h6,hr,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}menu,ol,ul{list-style:none;margin:0;padding:0}dialog{padding:0}textarea{resize:vertical}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}[role=button],button{cursor:pointer}:disabled{cursor:default}audio,canvas,embed,iframe,img,object,svg,video{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]:where(:not([hidden=until-found])){display:none}[type='text'],input:where(:not([type])),[type='email'],[type='url'],[type='password'],[type='number'],[type='date'],[type='datetime-local'],[type='month'],[type='search'],[type='tel'],[type='time'],[type='week'],[multiple],textarea,select{-webkit-appearance:none;appearance:none;background-color:#fff;border-color:#6b7280;border-width:1px;border-radius:0px;padding-top:0.5rem;padding-right:0.75rem;padding-bottom:0.5rem;padding-left:0.75rem;font-size:1rem;line-height:1.5rem;--tw-shadow:0 0 #0000;}[type='text']:focus, input:where(:not([type])):focus, [type='email']:focus, [type='url']:focus, [type='password']:focus, [type='number']:focus, [type='date']:focus, [type='datetime-local']:focus, [type='month']:focus, [type='search']:focus, [type='tel']:focus, [type='time']:focus, [type='week']:focus, [multiple]:focus, textarea:focus, select:focus{outline:2px solid transparent;outline-offset:2px;--tw-ring-inset:var(--tw-empty,/*!*/ /*!*/);--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:#2563eb;--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow);border-color:#2563eb}input::placeholder,textarea::placeholder{color:#6b7280;opacity:1}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-date-and-time-value{min-height:1.5em;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit,::-webkit-datetime-edit-year-field,::-webkit-datetime-edit-month-field,::-webkit-datetime-edit-day-field,::-webkit-datetime-edit-hour-field,::-webkit-datetime-edit-minute-field,::-webkit-datetime-edit-second-field,::-webkit-datetime-edit-millisecond-field,::-webkit-datetime-edit-meridiem-field{padding-top:0;padding-bottom:0}select{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");background-position:right 0.5rem center;background-repeat:no-repeat;background-size:1.5em 1.5em;padding-right:2.5rem;print-color-adjust:exact}[multiple],[size]:where(select:not([size="1"])){background-image:initial;background-position:initial;background-repeat:unset;background-size:initial;padding-right:0.75rem;print-color-adjust:unset}[type='checkbox'],[type='radio']{-webkit-appearance:none;appearance:none;padding:0;print-color-adjust:exact;display:inline-block;vertical-align:middle;background-origin:border-box;-webkit-user-select:none;user-select:none;flex-shrink:0;height:1rem;width:1rem;color:#2563eb;background-color:#fff;border-color:#6b7280;border-width:1px;--tw-shadow:0 0 #0000}[type='checkbox']{border-radius:0px}[type='radio']{border-radius:100%}[type='checkbox']:focus,[type='radio']:focus{outline:2px solid transparent;outline-offset:2px;--tw-ring-inset:var(--tw-empty,/*!*/ /*!*/);--tw-ring-offset-width:2px;--tw-ring-offset-color:#fff;--tw-ring-color:#2563eb;--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)}[type='checkbox']:checked,[type='radio']:checked{border-color:transparent;background-color:currentColor;background-size:100% 100%;background-position:center;background-repeat:no-repeat}[type='checkbox']:checked{background-image:url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");}@media (forced-colors: active) {[type='checkbox']:checked{-webkit-appearance:auto;appearance:auto}}[type='radio']:checked{background-image:url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");}@media (forced-colors: active) {[type='radio']:checked{-webkit-appearance:auto;appearance:auto}}[type='checkbox']:checked:hover,[type='checkbox']:checked:focus,[type='radio']:checked:hover,[type='radio']:checked:focus{border-color:transparent;background-color:currentColor}[type='checkbox']:indeterminate{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 16'%3e%3cpath stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 8h8'/%3e%3c/svg%3e");border-color:transparent;background-color:currentColor;background-size:100% 100%;background-position:center;background-repeat:no-repeat;}@media (forced-colors: active) {[type='checkbox']:indeterminate{-webkit-appearance:auto;appearance:auto}}[type='checkbox']:indeterminate:hover,[type='checkbox']:indeterminate:focus{border-color:transparent;background-color:currentColor}[type='file']{background:unset;border-color:inherit;border-width:0;border-radius:0;padding:0;font-size:unset;line-height:inherit}[type='file']:focus{outline:1px solid ButtonText;outline:1px auto -webkit-focus-ring-color}.absolute{position:absolute}.relative{position:relative}.bottom-8{bottom:2rem}.left-3{left:0.75rem}.left-8{left:2rem}.right-3{right:0.75rem}.right-8{right:2rem}.top-1\/2{top:50%}.z-10{z-index:10}.mb-1{margin-bottom:0.25rem}.mb-1\.5{margin-bottom:0.375rem}.mb-3{margin-bottom:0.75rem}.mb-4{margin-bottom:1rem}.mb-6{margin-bottom:1.5rem}.mt-2{margin-top:0.5rem}.mt-6{margin-top:1.5rem}.block{display:block}.flex{display:flex}.h-10{height:2.5rem}.h-11{height:2.75rem}.h-12{height:3rem}.h-4{height:1rem}.h-7{height:1.75rem}.h-full{height:100%}.h-screen{height:100vh}.w-10{width:2.5rem}.w-4{width:1rem}.w-7{width:1.75rem}.w-full{width:100%}.max-w-lg{max-width:32rem}.max-w-md{max-width:28rem}.flex-shrink-0{flex-shrink:0}.-translate-y-1\/2{--tw-translate-y:-50%;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.cursor-pointer{cursor:pointer}.select-none{-webkit-user-select:none;user-select:none}.flex-col{flex-direction:column}.items-center{align-items:center}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-2{gap:0.5rem}.gap-3{gap:0.75rem}.gap-4{gap:1rem}.space-y-3 > :not([hidden]) ~ :not([hidden]){--tw-space-y-reverse:0;margin-top:calc(0.75rem * calc(1 - var(--tw-space-y-reverse)));margin-bottom:calc(0.75rem * var(--tw-space-y-reverse))}.space-y-4 > :not([hidden]) ~ :not([hidden]){--tw-space-y-reverse:0;margin-top:calc(1rem * calc(1 - var(--tw-space-y-reverse)));margin-bottom:calc(1rem * var(--tw-space-y-reverse))}.overflow-hidden{overflow:hidden}.rounded{border-radius:0.25rem}.rounded-lg{border-radius:0.5rem}.rounded-xl{border-radius:0.75rem}.border{border-width:1px}.border-brand-border{--tw-border-opacity:1;border-color:rgb(226 232 240 / var(--tw-border-opacity, 1))}.border-white\/20{border-color:rgb(255 255 255 / 0.2)}.bg-emerald-500\/20{background-color:rgb(16 185 129 / 0.2)}.bg-slate-50{--tw-bg-opacity:1;background-color:rgb(248 250 252 / var(--tw-bg-opacity, 1))}.bg-white{--tw-bg-opacity:1;background-color:rgb(255 255 255 / var(--tw-bg-opacity, 1))}.bg-white\/10{background-color:rgb(255 255 255 / 0.1)}.px-6{padding-left:1.5rem;padding-right:1.5rem}.px-8{padding-left:2rem;padding-right:2rem}.py-8{padding-top:2rem;padding-bottom:2rem}.pl-10{padding-left:2.5rem}.pr-11{padding-right:2.75rem}.pr-4{padding-right:1rem}.text-left{text-align:left}.text-center{text-align:center}.font-display{font-family:Hanken Grotesk, sans-serif}.text-2xl{font-size:1.5rem;line-height:2rem}.text-base{font-size:1rem;line-height:1.5rem}.text-sm{font-size:0.875rem;line-height:1.25rem}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-xs{font-size:0.75rem;line-height:1rem}.font-bold{font-weight:700}.font-semibold{font-weight:600}.leading-relaxed{line-height:1.625}.leading-tight{line-height:1.25}.tracking-tight{letter-spacing:-0.025em}.text-brand-gray{--tw-text-opacity:1;color:rgb(100 116 139 / var(--tw-text-opacity, 1))}.text-brand-navy{--tw-text-opacity:1;color:rgb(22 40 57 / var(--tw-text-opacity, 1))}.text-brand-teal{--tw-text-opacity:1;color:rgb(0 135 81 / var(--tw-text-opacity, 1))}.text-emerald-400{--tw-text-opacity:1;color:rgb(52 211 153 / var(--tw-text-opacity, 1))}.text-gray-400{--tw-text-opacity:1;color:rgb(156 163 175 / var(--tw-text-opacity, 1))}.text-white{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity, 1))}.text-white\/30{color:rgb(255 255 255 / 0.3)}.text-white\/60{color:rgb(255 255 255 / 0.6)}.text-white\/70{color:rgb(255 255 255 / 0.7)}.backdrop-blur-sm{--tw-backdrop-blur:blur(4px);-webkit-backdrop-filter:var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia);backdrop-filter:var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia)}.transition{transition-property:color, background-color, border-color, fill, stroke, opacity, box-shadow, transform, filter, -webkit-text-decoration-color, -webkit-backdrop-filter;transition-property:color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;transition-property:color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter, -webkit-text-decoration-color, -webkit-backdrop-filter;transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1);transition-duration:150ms}.transition-all{transition-property:all;transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1);transition-duration:150ms}.placeholder\:text-gray-400::placeholder{--tw-text-opacity:1;color:rgb(156 163 175 / var(--tw-text-opacity, 1))}.hover\:text-brand-navy:hover{--tw-text-opacity:1;color:rgb(22 40 57 / var(--tw-text-opacity, 1))}.hover\:text-white\/60:hover{color:rgb(255 255 255 / 0.6)}.hover\:underline:hover{-webkit-text-decoration-line:underline;text-decoration-line:underline}.focus\:ring-0:focus{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(0px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000)}.focus\:ring-brand-teal\/20:focus{--tw-ring-color:rgb(0 135 81 / 0.2)}@media (min-width: 1024px){.lg\:w-1\/2{width:50%}.lg\:justify-start{justify-content:flex-start}.lg\:px-16{padding-left:4rem;padding-right:4rem}.lg\:text-left{text-align:left}.lg\:text-3xl{font-size:1.875rem;line-height:2.25rem}}</style></head>
<body class="h-screen overflow-hidden bg-slate-50">
    <div class="auth-split flex h-screen">
        <!-- Left: Company Branding -->
        <div class="brand-left brand-gradient relative flex flex-col justify-center items-center px-8 lg:px-16 text-white lg:w-1/2 overflow-hidden">
            <div class="relative z-10 text-center lg:text-left max-w-lg">
                <!-- Logo -->
                <div class="flex items-center justify-center lg:justify-start gap-3 mb-4">
                    <span class="font-display text-xl font-bold tracking-tight">Smart Attendance</span>
                </div>

                <!-- Tagline -->
                <h1 class="font-display text-2xl lg:text-3xl font-bold leading-tight mb-3">
                    Enterprise Workforce<br>
                    <span class="text-emerald-400">Management System</span>
                </h1>
                <p class="text-white/60 text-sm leading-relaxed mb-6">
                    Streamline attendance tracking, performance analytics, and team collaboration with our intelligent platform.
                </p>

                <!-- Feature Highlights -->
                <div class="space-y-3 text-left">

                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-400 text-base">analytics</span>
                        </div>
                        <span class="text-white/70 text-sm">Real-time attendance insights</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-400 text-base">groups</span>
                        </div>
                        <span class="text-white/70 text-sm">Multi-department management</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Decoration -->
            <div class="absolute bottom-8 left-8 right-8 flex items-center justify-between text-white/30 text-xs">
                <span>© 2026 Smart Attendance Inc.</span>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white/60 transition">Privacy</a>
                    <a href="#" class="hover:text-white/60 transition">Terms</a>
                </div>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="flex flex-col justify-center items-center px-6 py-8 lg:w-1/2 bg-white overflow-hidden">
            <div class="w-full max-w-md">
                <!-- Welcome Header -->
                <div class="mb-6">
                    <h2 class="font-display text-2xl font-bold text-brand-navy mb-1">Welcome back</h2>
                    <p class="text-brand-gray text-sm">Sign in to access your dashboard</p>
                </div>

                <!-- Error Message -->
                
                <!-- Login Form -->
                <form class="space-y-4" method="post" action="auth/login.php">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-navy mb-1.5" for="email">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">mail</span>
                            <input class="w-full h-11 pl-10 pr-4 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all" id="email" name="email" placeholder="you@company.com" type="email" required="">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="text-sm font-semibold text-brand-navy" for="password">Password</label>
                            <a class="text-xs text-brand-teal hover:underline" href="user/requestpassword.php" id="forgotPasswordLink">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">lock</span>
                            <input class="w-full h-11 pl-10 pr-11 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all" id="password" name="password" placeholder="Enter your password" type="password" required="">
                            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-navy transition" type="button" id="togglePassword">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2">
                        <input class="w-4 h-4 rounded border-brand-border text-brand-teal focus:ring-brand-teal/20" id="remember" type="checkbox">
                        <label class="text-sm text-brand-gray cursor-pointer select-none" for="remember">Remember this device</label>
                    </div>

                    <!-- Submit Button -->
                    <button class="btn-signin w-full h-12 text-white font-semibold rounded-lg flex items-center justify-center gap-2 mt-2" type="submit">
                        <span>Sign In</span>
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-brand-gray">
                        Don't have an account?
                        <a class="text-brand-teal font-semibold hover:underline" href="register.php">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        document.getElementById('togglePassword')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        });

        // Forgot Password link - pass email as query parameter
        document.getElementById('forgotPasswordLink')?.addEventListener('click', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value.trim();
            if (email) {
                window.location.href = 'user/requestpassword.php?email=' + encodeURIComponent(email);
            } else {
                window.location.href = 'user/requestpassword.php';
            }
        });
    </script>


</body></html>