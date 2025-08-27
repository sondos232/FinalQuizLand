<?php
session_start();
$errors  = $_SESSION['signup_errors']  ?? [];
$success = $_SESSION['signup_success'] ?? '';
$old     = $_SESSION['signup_old']     ?? ['username' => '', 'email' => ''];
unset($_SESSION['signup_errors'], $_SESSION['signup_success']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>إنشاء حساب</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .weak   { background-color:#f87171; }
    .medium { background-color:#fbbf24; }
    .strong { background-color:#4ade80; }
  </style>
</head>

<body class="bg-white">

  <div class="flex flex-col items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-14">
      <img src="../../assets/images/logo/logo.svg" width="250" alt="Logo">
    </div>

    <?php if (!empty($success)): ?>
      <div class="mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800 p-4 w-full max-w-sm">
        <p class="font-medium">🎉 <?= htmlspecialchars($success) ?></p>
        <a href="signin.php" class="mt-2 inline-block underline text-green-700 text-sm">الانتقال لصفحة تسجيل الدخول</a>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 text-yellow-800 p-4 w-full max-w-sm">
        <p class="font-semibold mb-2">⚠️ يرجى تصحيح الأخطاء التالية:</p>
        <ul class="list-disc mr-6 space-y-1 px-3">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form class="w-full max-w-sm mx-auto" id="signupForm" action="signup_handler.php" method="POST" autocomplete="off">
      <div class="mb-6">
        <input
          type="text"
          name="username"
          placeholder="اسم المستخدم"
          value="<?= htmlspecialchars($old['username']) ?>"
          class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
          required
        >
      </div>

      <div class="mb-6">
        <input
          type="email"
          name="email"
          placeholder="البريد الإلكتروني"
          value="<?= htmlspecialchars($old['email']) ?>"
          class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
          required
        >
      </div>

      <div class="mb-6">
        <input
          type="password"
          id="password"
          name="password"
          placeholder="كلمة المرور"
          class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
          required
        >
        <div id="password-strength-meter" class="mt-2 bg-gray-300 h-2 rounded"></div>
      </div>

      <div class="mb-6">
        <input
          type="password"
          name="confirm_password"
          placeholder="تأكيد كلمة المرور"
          class="w-full px-5 py-3 text-lg text-gray-900 bg-transparent border border-gray-300 rounded-md placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
          required
        >
      </div>

      <div class="mb-6">
        <button
          type="submit"
          class="w-full py-3 px-5 text-lg font-medium text-white bg-blue-600 rounded-lg border-2 border-blue-600 hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
        >
          إنشاء حساب
        </button>
      </div>
    </form>

    <p class="text-base text-gray-900">
      لديك حساب بالفعل؟
      <a href="./signin.php" class="text-blue-600 hover:underline">سجّل الدخول من هنا</a>
    </p>
  </div>

  <script>
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.getElementById('password-strength-meter');

    const checkPasswordStrength = (password) => {
      let strength = 0;

      if (password.length >= 8) strength++;
      if (/\d/.test(password)) strength++;
      if (/[a-z]/.test(password)) strength++;
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

    const form = document.getElementById('signupForm');
    form.addEventListener('submit', function (event) {
      const password = document.querySelector('input[name="password"]');
      const confirmPassword = document.querySelector('input[name="confirm_password"]');

      if (password.value !== confirmPassword.value) {
        event.preventDefault();
        alert('كلمتا المرور غير متطابقتين!');
      }
    });
  </script>

</body>
</html>
