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

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Create Your Account - Smart Attendance Management System</title>
<!-- Tailwind CSS CDN with plugins -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Google Fonts: Hanken Grotesk -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Hanken Grotesk', 'sans-serif'],
          },
          colors: {
            primary: {
              DEFAULT: '#008751', // Brand green from image
              dark: '#007043',
            },
            brand: {
              navy: '#1a2b3b', // Header text color
              gray: '#64748b', // Placeholder/Subtext color
              border: '#e2e8f0', // Input border
            }
          },
          borderRadius: {
            'custom': '8px',
          }
        }
      }
    }
  </script>
<style data-purpose="custom-styling">
    body {
      background-color: #f8fafc;
      font-family: 'Hanken Grotesk', sans-serif;
    }
    .registration-card {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .input-field {
      border-color: #e2e8f0;
    }
    .input-field:focus {
      border-color: #008751;
      ring-color: #008751;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<!-- BEGIN: RegistrationCard -->
<main class="w-full max-w-[640px] bg-white rounded-2xl p-8 md:p-12 registration-card" data-purpose="registration-form-container">
<!-- BEGIN: HeaderSection -->
<header class="flex flex-col items-center mb-8 text-center" data-purpose="form-header">
<!-- Logo Container -->
<div class="mb-4" data-purpose="logo">
<svg fill="none" height="64" viewbox="0 0 64 64" width="64" xmlns="http://www.w3.org/2000/svg">
<rect fill="#008751" height="64" rx="12" width="64"></rect>
<path d="M32 30C35.3137 30 38 27.3137 38 24C38 20.6863 35.3137 18 32 18C28.6863 18 26 20.6863 26 24C26 27.3137 28.6863 30 32 30Z" fill="white"></path>
<path d="M44 46V44C44 40.6863 41.3137 38 38 38H26C22.6863 38 20 40.6863 20 44V46C20 47.1046 20.8954 48 22 48H42C43.1046 48 44 47.1046 44 46Z" fill="white"></path>
<circle cx="23" cy="43" fill="white" r="3"></circle>
<circle cx="41" cy="43" fill="white" r="3"></circle>
</svg>
</div>
<h1 class="text-2xl font-bold text-brand-navy">Smart Attendance</h1>
<p class="text-brand-gray text-sm mb-6">Management System</p>
<h2 class="text-2xl font-bold text-brand-navy mb-1">Create Your Account</h2>
<p class="text-brand-gray text-sm">Fill in the details to register</p>
</header>
<!-- END: HeaderSection -->
<?php if (!empty($error)): ?>
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>
<?php if (!empty($success)): ?>
<div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
    <?php echo htmlspecialchars($success); ?>
</div>
<?php endif; ?>
<!-- BEGIN: RegistrationForm -->
<form action="#" class="space-y-5" data-purpose="registration-form" method="POST">
<!-- Grid for Desktop Layout -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
<!-- Full Name -->
<div class="flex flex-col space-y-1.5">
<label class="text-sm font-semibold text-brand-navy" for="full_name">Full Name</label>
<input class="input-field w-full h-11 rounded-lg border-brand-border text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none" id="full_name" name="full_name" placeholder="Enter your full name" type="text"/>
</div>
<!-- Department -->
<div class="flex flex-col space-y-1.5">
<label class="text-sm font-semibold text-brand-navy" for="department">Department</label>
<select class="input-field w-full h-11 rounded-lg border-brand-border text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-white" id="department" name="department">
<option value="">Select Department</option>
<?php foreach ($departments as $dept): ?>
<option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
<?php endforeach; ?>
</select>
</div>
<!-- Email Address -->
<div class="flex flex-col space-y-1.5">
<label class="text-sm font-semibold text-brand-navy" for="email">Email Address</label>
<input class="input-field w-full h-11 rounded-lg border-brand-border text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none" id="email" name="email" placeholder="Enter your email" type="email"/>
</div>
<!-- Phone Number -->
<div class="flex flex-col space-y-1.5">
<label class="text-sm font-semibold text-brand-navy" for="phone">Phone Number</label>
<input class="input-field w-full h-11 rounded-lg border-brand-border text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none" id="phone" name="phone" placeholder="Enter your phone number" type="tel"/>
</div>
<!-- Password -->
<div class="flex flex-col space-y-1.5">
<label class="text-sm font-semibold text-brand-navy" for="password">Password</label>
<div class="input-icon-container relative">
<input class="input-field w-full h-11 rounded-lg border-brand-border text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none pr-10" id="password" name="password" placeholder="Create a password" type="password"/>
<button class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray hover:text-primary" type="button">
<svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
</button>
</div>
</div>
<!-- Confirm Password -->
<div class="flex flex-col space-y-1.5">
<label class="text-sm font-semibold text-brand-navy" for="confirm_password">Confirm Password</label>
<div class="input-icon-container relative">
<input class="input-field w-full h-11 rounded-lg border-brand-border text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none pr-10" id="confirm_password" name="confirm_password" placeholder="Confirm your password" type="password"/>
<button class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray hover:text-primary" type="button">
<svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
</button>
</div>
</div>
</div>
<!-- Terms and Conditions -->
<div class="flex items-center space-x-2 pt-2">
<input class="h-4 w-4 rounded border-brand-border text-primary focus:ring-primary" id="terms" name="terms" type="checkbox"/>
<label class="text-sm text-brand-gray" for="terms">I agree to the <a class="text-primary font-medium hover:underline" href="#">Terms &amp; Conditions</a></label>
</div>
<!-- Submit Button -->
<button class="w-full h-12 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-colors flex items-center justify-center space-x-2 mt-4" data-purpose="submit-button" type="submit">
<span>Register</span>
</button>
<!-- Footer Link -->
<div class="text-center pt-4" data-purpose="form-footer">
<p class="text-sm text-brand-gray">
          Already have an account? <a class="text-primary font-semibold hover:underline" href="login.php">Login here</a>
</p>
</div>
</form>
<!-- END: RegistrationForm -->
</main>
<!-- END: RegistrationCard -->
</body></html>
