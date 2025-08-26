<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $errors[] = 'Security token mismatch.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'student';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $password_raw = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($username === '')
            $errors[] = 'Username is required.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Valid email is required.';
        if (!in_array($role, ['admin', 'student'], true))
            $errors[] = 'Invalid role.';
        if ($password_raw === '')
            $errors[] = 'Password is required.';
        if ($password_raw !== '' && strlen($password_raw) < 6)
            $errors[] = 'Password must be at least 6 characters.';
        if ($password_raw !== $password_confirm)
            $errors[] = 'Passwords do not match.';

        if (!$errors) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0)
                $errors[] = 'Username already exists.';
            $stmt->close();

            if (!$errors) {
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0)
                    $errors[] = 'Email already exists.';
                $stmt->close();
            }
        }

        if (!$errors) {
            $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $username, $email, $password_hash, $role, $is_active);
            if ($stmt->execute()) {
                $success = true;
                header("Location: index.php?created=1");
                exit();
            } else {
                $errors[] = 'Database error while creating user.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة مستخدم</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include '../sidebar.php'; ?>

        <div class="flex-1 p-6 md:mr-64">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">إضافة مستخدم جديد</h2>
                    <a href="index.php" class="text-sm px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">عودة
                        للقائمة</a>
                </div>

                <?php if ($success): ?>
                    <div class="mb-4 p-3 rounded-md bg-green-50 text-green-700 border border-green-200">
                        تم إنشاء المستخدم بنجاح.
                    </div>
                <?php endif; ?>

                <?php if ($errors): ?>
                    <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 border border-red-200">
                        <ul class="list-disc mr-6 space-y-1">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

                    <div>
                        <label class="block mb-1 text-sm text-gray-700">اسم المستخدم</label>
                        <input type="text" name="username"
                            value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                            required>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-700">البريد الإلكتروني</label>
                        <input type="email" name="email"
                            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                            required>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">كلمة المرور</label>
                            <input type="password" name="password"
                                class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                                required>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirm"
                                class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-700">الدور</label>
                        <select name="role"
                            class="w-full border rounded-lg p-2 bg-white focus:outline-none focus:ring focus:ring-purple-200"
                            required>
                            <option value="student" <?= (($_POST['role'] ?? 'student') === 'student') ? 'selected' : '' ?>>
                                طالب</option>
                            <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>مدير
                            </option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" <?= isset($_POST['is_active']) ? 'checked' : 'checked' /* default on */ ?> class="h-4 w-4 border-gray-300 rounded">
                        <label for="is_active" class="text-sm text-gray-700">الحساب نشط</label>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-sm">
                            إضافة
                        </button>
                        <a href="index.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>