<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, name, email, password, role, status FROM `user` WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $passwordMatch = password_verify($password, $user['password']);

            if (!$passwordMatch && $password === $user['password']) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare('UPDATE `user` SET password = ? WHERE id = ?');
                $updateStmt->bind_param('si', $hash, $user['id']);
                $updateStmt->execute();
                $passwordMatch = true;
            }

            if ($passwordMatch) {
                if (strtolower($user['status'] ?? 'active') === 'inactive') {
                    $error = 'Your account has been deactivated. Contact your administrator.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];

                    $actStmt = $conn->prepare('UPDATE `user` SET last_activity = NOW() WHERE id = ?');
                    $actStmt->bind_param('i', $user['id']);
                    $actStmt->execute();

                    $role = strtolower($user['role'] ?? '');
                    if ($role === 'admin') {
                        header('Location: ../Admin/dashboard.php');
                    } else {
                        header('Location: ../user/userdashboard.php');
                    }
                    exit;
                }
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sign In - Smart Attendance</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Hanken+Grotesk:wght@600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
</head>
<body class="h-screen overflow-hidden bg-slate-50">
    <div class="auth-split flex h-screen">
        <!-- Left: Company Branding -->
        <div class="brand-left brand-gradient relative flex flex-col justify-center items-center px-8 lg:px-16 text-white lg:w-1/2 overflow-hidden">
            <div class="relative z-10 text-center lg:text-left max-w-lg">
                <!-- Logo -->
                <div class="flex items-center justify-center lg:justify-start gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">
                        <span class="material-symbols-outlined text-white text-xl">fingerprint</span>
                    </div>
                    <span class="font-display text-xl font-bold tracking-tight">Smart Attendance</span>
                </div>

                <!-- Tagline -->
                <h1 class="font-display text-2xl lg:text-3xl font-bold leading-tight mb-3">
                    Enterprise Workforce<br/>
                    <span class="text-emerald-400">Management System</span>
                </h1>
                <p class="text-white/60 text-sm leading-relaxed mb-6">
                    Streamline attendance tracking, performance analytics, and team collaboration with our intelligent platform.
                </p>

                <!-- Feature Highlights -->
                <div class="space-y-3 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-400 text-base">shield</span>
                        </div>
                        <span class="text-white/70 text-sm">Biometric-secured authentication</span>
                    </div>
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
                <span>&copy; 2026 Smart Attendance Inc.</span>
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
                <?php if (!empty($error)): ?>
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">error</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form class="space-y-4" method="post" action="">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-navy mb-1.5" for="email">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">mail</span>
                            <input
                                class="w-full h-11 pl-10 pr-4 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all"
                                id="email"
                                name="email"
                                placeholder="you@company.com"
                                type="email"
                                required
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="text-sm font-semibold text-brand-navy" for="password">Password</label>
                            <a class="text-xs text-brand-teal hover:underline" href="../user/requestpassword.php">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">lock</span>
                            <input
                                class="w-full h-11 pl-10 pr-11 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                type="password"
                                required
                            />
                            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-navy transition" type="button" id="togglePassword">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2">
                        <input class="w-4 h-4 rounded border-brand-border text-brand-teal focus:ring-brand-teal/20" id="remember" type="checkbox"/>
                        <label class="text-sm text-brand-gray cursor-pointer select-none" for="remember">Remember this device</label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        class="btn-signin w-full h-12 text-white font-semibold rounded-lg flex items-center justify-center gap-2 mt-2"
                        type="submit"
                    >
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
    </script>
</body>
</html>
