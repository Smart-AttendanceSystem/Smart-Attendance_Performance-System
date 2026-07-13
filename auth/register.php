<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'smart_attendence');
$departments = [];
$error = '';
$success = '';

if (!$conn->connect_error) {
    $result = $conn->query('SELECT id, name FROM departments');
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $departmentId = (int) ($_POST['department'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $terms = isset($_POST['terms']);

    if ($fullName === '' || $email === '' || $password === '' || $confirmPassword === '' || $departmentId === 0) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!$terms) {
        $error = 'You must agree to the Terms & Conditions.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM `user` WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO `user` (department_id, name, email, password, status, role) VALUES (?, ?, ?, ?, 'Active', 'user')");
            $stmt->bind_param('isss', $departmentId, $fullName, $email, $passwordHash);
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now sign in.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Create Account - Smart Attendance</title>
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
        input:focus, select:focus {
            outline: none !important;
            border-color: #008751 !important;
            box-shadow: 0 0 0 3px rgba(0, 135, 81, 0.12) !important;
        }
        .btn-register {
            background: linear-gradient(135deg, #008751 0%, #006d41 100%);
            transition: all 0.25s ease;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #006d41 0%, #005533 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 135, 81, 0.3);
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
                    Join Our<br/>
                    <span class="text-emerald-400">Platform Today</span>
                </h1>
                <p class="text-white/60 text-sm leading-relaxed mb-6">
                    Create your account to start managing attendance, tracking performance, and collaborating with your team.
                </p>

                <!-- Feature Highlights -->
                <div class="space-y-3 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-400 text-base">speed</span>
                        </div>
                        <span class="text-white/70 text-sm">Quick & easy registration process</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-400 text-base">verified</span>
                        </div>
                        <span class="text-white/70 text-sm">Instant access to your dashboard</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-400 text-base">support_agent</span>
                        </div>
                        <span class="text-white/70 text-sm">Dedicated support for new users</span>
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

        <!-- Right: Registration Form -->
        <div class="flex flex-col justify-center items-center px-6 py-6 lg:w-1/2 bg-white overflow-hidden">
            <div class="w-full max-w-md">
                <!-- Welcome Header -->
                <div class="mb-4">
                    <h2 class="font-display text-2xl font-bold text-brand-navy mb-1">Create Account</h2>
                    <p class="text-brand-gray text-sm">Fill in your details to get started</p>
                </div>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">error</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if (!empty($success)): ?>
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    <?php echo htmlspecialchars($success); ?>
                    <a href="login.php" class="ml-auto text-green-800 font-semibold hover:underline">Sign In</a>
                </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form class="space-y-3" method="post" action="">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-navy mb-1" for="full_name">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">person</span>
                            <input
                                class="w-full h-11 pl-10 pr-4 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all"
                                id="full_name"
                                name="full_name"
                                placeholder="Enter your full name"
                                type="text"
                                required
                            />
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-navy mb-1" for="department">Department</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">business</span>
                            <select
                                class="w-full h-11 pl-10 pr-4 rounded-lg border border-brand-border bg-white text-sm text-brand-navy focus:ring-0 transition-all appearance-none"
                                id="department"
                                name="department"
                                required
                            >
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Email & Phone Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-brand-navy mb-1" for="email">Email Address</label>
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
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-brand-navy mb-1" for="phone">Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">phone</span>
                                <input
                                    class="w-full h-11 pl-10 pr-4 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all"
                                    id="phone"
                                    name="phone"
                                    placeholder="+1 234 567"
                                    type="tel"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Password Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-brand-navy mb-1" for="password">Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">lock</span>
                                <input
                                    class="w-full h-11 pl-10 pr-10 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all"
                                    id="password"
                                    name="password"
                                    placeholder="Create password"
                                    type="password"
                                    required
                                />
                                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-navy transition" type="button" onclick="togglePass('password', this)">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-semibold text-brand-navy mb-1" for="confirm_password">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-xl">lock</span>
                                <input
                                    class="w-full h-11 pl-10 pr-10 rounded-lg border border-brand-border bg-white text-sm text-brand-navy placeholder:text-gray-400 focus:ring-0 transition-all"
                                    id="confirm_password"
                                    name="confirm_password"
                                    placeholder="Confirm password"
                                    type="password"
                                    required
                                />
                                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-navy transition" type="button" onclick="togglePass('confirm_password', this)">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-2 pt-0.5">
                        <input class="w-4 h-4 mt-0.5 rounded border-brand-border text-brand-teal focus:ring-brand-teal/20" id="terms" name="terms" type="checkbox" required/>
                        <label class="text-sm text-brand-gray cursor-pointer select-none" for="terms">
                            I agree to the <a class="text-brand-teal font-medium hover:underline" href="#">Terms &amp; Conditions</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        class="btn-register w-full h-12 text-white font-semibold rounded-lg flex items-center justify-center gap-2 mt-2"
                        type="submit"
                    >
                        <span>Create Account</span>
                        <span class="material-symbols-outlined text-xl">person_add</span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-brand-gray">
                        Already have an account?
                        <a class="text-brand-teal font-semibold hover:underline" href="login.php">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>
