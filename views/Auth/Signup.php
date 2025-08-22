<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- Tailwind CSS CDN Link -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .weak {
            background-color: #f87171;
        }

        .medium {
            background-color: #fbbf24;
        }

        .strong {
            background-color: #4ade80;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Container for centering content -->
    <div class="flex flex-col items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">

        <!-- Logo Section -->
        <div class="text-center mb-14">
            <!-- Logo (Replace with your logo) -->
            <img src="../../assets/images/logo/logo.svg" width="250" alt="Logo">
        </div>

        <!-- Sign Up Form -->
        <form class="w-full max-w-sm mx-auto" id="signupForm" action="signup_handler.php" method="POST"
            autocomplete="off">
            <!-- Username Input -->
            <div class="mb-6">
                <input type="text" name="username" placeholder="Username"
                    class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    required>
            </div>

            <!-- Email Input -->
            <div class="mb-6">
                <input type="email" name="email" placeholder="Email"
                    class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    required>
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <input type="password" id="password" name="password" placeholder="Password"
                    class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    required>
                <!-- Password Strength Meter -->
                <div id="password-strength-meter" class="mt-2 bg-gray-300 h-2 rounded"></div>
            </div>

            <!-- Confirm Password Input -->
            <div class="mb-6">
                <input type="password" name="confirm_password" placeholder="Confirm Password"
                    class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    required>
            </div>

            <!-- Sign Up Button -->
            <div class="mb-6">
                <button type="submit"
                    class="w-full py-3 px-5 text-lg font-medium text-white bg-blue-600 rounded-lg border-2 border-blue-600 hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    Sign Up
                </button>
            </div>
        </form>

        <!-- Already have an account? Link -->
        <p class="text-base text-gray-900">
            Already have an account? <a href="./signin.php" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </div>

    <!-- JavaScript Validation (Optional) -->
    <script>
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.getElementById('password-strength-meter');

        const checkPasswordStrength = (password) => {
            let strength = 0;

            // Check length (at least 8 characters)
            if (password.length >= 8) strength++;

            // Check if password contains numbers
            if (/\d/.test(password)) strength++;

            // Check if password contains lowercase letters
            if (/[a-z]/.test(password)) strength++;

            // Check if password contains uppercase letters
            if (/[A-Z]/.test(password)) strength++;

            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;

            if (strength === 0) {
                strengthMeter.className = 'h-2 mt-2 rounded bg-gray-300';
                strengthMeter.style.width = '0%';
            } else if (strength === 1) {
                strengthMeter.className = 'h-2 mt-2 rounded bg-red-500';
                strengthMeter.style.width = '33.3333%';
            } else if (strength === 2) {
                strengthMeter.className = 'h-2 mt-2 rounded bg-yellow-500';
                strengthMeter.style.width = '66.666%';
            } else if (strength === 3 || strength === 4) {
                strengthMeter.className = 'h-2 mt-2 rounded bg-green-500';
                strengthMeter.style.width = '100%';
            } else {
                strengthMeter.className = 'h-2 mt-2 rounded bg-green-500';
                strengthMeter.style.width = '100%';
            }
        };

        passwordInput.addEventListener('input', (e) => {
            checkPasswordStrength(e.target.value);
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            const password = document.querySelector('input[name="password"]');
            const confirmPassword = document.querySelector('input[name="confirm_password"]');

            // Client-side check if password and confirm password match
            if (password.value !== confirmPassword.value) {
                event.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>

</body>

</html>