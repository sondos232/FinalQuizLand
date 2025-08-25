<?php
// /views/admin/users/edit.php
include '../../../config/db.php';
session_start();

// 1) AuthZ: only admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

// 2) Basic helpers
function redirect_with($params = []) {
    $query = http_build_query($params);
    header("Location: index.php" . ($query ? "?$query" : ""));
    exit();
}

// 3) CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// 4) Validate ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    redirect_with(['error' => 'invalid_id']);
}
$user_id = (int) $_GET['id'];

// 5) Fetch current user row (username بدل name) + is_active
$stmt = $conn->prepare("SELECT id, username, email, role, is_active, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    redirect_with(['error' => 'not_found']);
}

// 6) Handle POST (update)
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $errors[] = 'Security token mismatch.';
    } else {
        $username        = trim($_POST['username'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $role            = $_POST['role'] ?? $user['role'];                 // admin / student
        $is_active       = isset($_POST['is_active']) ? 1 : 0;              // checkbox
        $password_raw    = $_POST['password'] ?? '';
        $password_confirm= $_POST['password_confirm'] ?? '';

        // Validation
        if ($username === '') $errors[] = 'Username is required.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if (!in_array($role, ['admin','student'], true)) $errors[] = 'Invalid role.';
        if ($password_raw !== '' && $password_raw !== $password_confirm) $errors[] = 'Passwords do not match.';
        if (strlen($password_raw) > 0 && strlen($password_raw) < 6) $errors[] = 'Password must be at least 6 characters.';

        // Ensure username unique (excluding this user)
        if (!$errors) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id <> ?");
            $stmt->bind_param("si", $username, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) $errors[] = 'Username already in use by another account.';
            $stmt->close();
        }

        // Ensure email unique (excluding this user)
        if (!$errors) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ?");
            $stmt->bind_param("si", $email, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) $errors[] = 'Email already in use by another account.';
            $stmt->close();
        }

        // Update
        if (!$errors) {
            if ($password_raw !== '') {
                $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);
                $stmt = $conn->prepare(
                    "UPDATE users SET username = ?, email = ?, role = ?, is_active = ?, password = ? WHERE id = ?"
                );
                $stmt->bind_param("sssisi", $username, $email, $role, $is_active, $password_hash, $user_id);
            } else {
                $stmt = $conn->prepare(
                    "UPDATE users SET username = ?, email = ?, role = ?, is_active = ? WHERE id = ?"
                );
                $stmt->bind_param("sssii", $username, $email, $role, $is_active, $user_id);
            }

            if ($stmt->execute()) {
                $success = true;
                // refresh $user to reflect changes
                $user['username']  = $username;
                $user['email']     = $email;
                $user['role']      = $role;
                $user['is_active'] = $is_active;
            } else {
                $errors[] = 'Database error while updating user.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>تعديل مستخدم</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex">
    <?php include '../sidebar.php'; ?>

    <div class="flex-1 p-6">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-semibold text-gray-800">تعديل المستخدم</h1>
                <a href="index.php" class="text-sm px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">عودة للقائمة</a>
            </div>

            <?php if ($success): ?>
                <div class="mb-4 p-3 rounded-md bg-green-50 text-green-700 border border-green-200">
                    تم تحديث بيانات المستخدم بنجاح.
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
                           value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                           required>
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-700">البريد الإلكتروني</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                           required>
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-700">الدور</label>
                    <select name="role"
                            class="w-full border rounded-lg p-2 bg-white focus:outline-none focus:ring focus:ring-purple-200"
                            required>
                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>طالب</option>
                        <option value="admin"   <?= $user['role'] === 'admin'   ? 'selected' : '' ?>>مدير</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           <?= $user['is_active'] ? 'checked' : '' ?>
                           class="h-4 w-4 border-gray-300 rounded">
                    <label for="is_active" class="text-sm text-gray-700">الحساب نشط</label>
                </div>

                <div class="border-t pt-4 mt-6">
                    <p class="text-sm text-gray-600 mb-2">تغيير كلمة المرور (اختياري)</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">كلمة المرور الجديدة</label>
                            <input type="password" name="password"
                                   class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                                   placeholder="اتركها فارغة إن لم ترغب بالتغيير">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirm"
                                   class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-purple-200"
                                   placeholder="كرر كلمة المرور الجديدة">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-sm">
                        حفظ التعديلات
                    </button>
                    <a href="index.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">إلغاء</a>
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    تم إنشاء الحساب في:
                    <?= htmlspecialchars(date("Y-m-d", strtotime($user['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
