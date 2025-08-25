<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$query = "SELECT id, username, email, role, is_active, created_at FROM users ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include '../sidebar.php'; ?>
        <div class="flex-1 md:mr-64">
            <?php include '../topbar.php'; ?>
            <div class="p-6">
                <div class="bg-white shadow-md p-6 rounded-lg">
                    <h2 class="text-2xl font-semibold text-gray-800">إدارة المستخدمين</h2>
                    <div class="mt-4">
                        <a href="create.php" class="px-4 py-2 bg-blue-600 text-white rounded-md">إضافة مستخدم جديد</a>
                    </div>
                    <div class="mt-6">
                        <!-- جدول لسطح المكتب والأجهزة الكبيرة -->
                        <div class="overflow-x-auto hidden lg:block">
                            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                                    <tr class="text-right">
                                        <th class="py-3 px-4">#</th>
                                        <th class="py-3 px-4">الاسم</th>
                                        <th class="py-3 px-4">البريد الإلكتروني</th>
                                        <th class="py-3 px-4">الدور</th>
                                        <th class="py-3 px-4">الحالة</th>
                                        <th class="py-3 px-4">تاريخ الإنشاء</th>
                                        <th class="py-3 px-4">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                                    <?php foreach ($result as $row): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4"><?= $row['id'] ?></td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <?= htmlspecialchars($row['username']) ?>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="block max-w-[220px] truncate"
                                                    title="<?= htmlspecialchars($row['email']) ?>">
                                                    <?= htmlspecialchars($row['email']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4"><?= htmlspecialchars($row['role']) ?></td>
                                            <td class="py-3 px-4">
                                                <?php if (!empty($row['is_active'])): ?>
                                                    <span class="px-2 py-1 text-green-700 bg-green-100 rounded">نشط</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 text-red-700 bg-red-100 rounded">معطل</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <?= date("Y-m-d", strtotime($row['created_at'])) ?>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">
                                                    <a href="edit.php?id=<?= $row['id'] ?>"
                                                        class="text-blue-600 hover:text-blue-800">✏️
                                                        تعديل</a>
                                                    <a href="toggle_status.php?id=<?= $row['id'] ?>"
                                                        class="<?= !empty($row['is_active']) ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' ?>">
                                                        <?= !empty($row['is_active']) ? '🚫 تعطيل' : '✅ تفعيل' ?>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- كروت للجوال والأجهزة الصغيرة -->
                        <div class="space-y-3 lg:hidden">
                            <?php foreach ($result as $row): ?>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-500">#<?= $row['id'] ?></span>
                                                <?php if (!empty($row['is_active'])): ?>
                                                    <span
                                                        class="px-2 py-0.5 text-xs text-green-700 bg-green-100 rounded-full">نشط</span>
                                                <?php else: ?>
                                                    <span
                                                        class="px-2 py-0.5 text-xs text-red-700 bg-red-100 rounded-full">معطل</span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="text-base font-semibold text-gray-800 mt-1 truncate">
                                                <?= htmlspecialchars($row['username']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1 truncate"
                                                title="<?= htmlspecialchars($row['email']) ?>">
                                                <?= htmlspecialchars($row['email']) ?>
                                            </p>
                                            <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100"><?= htmlspecialchars($row['role']) ?></span>
                                                <span
                                                    class="whitespace-nowrap"><?= date("Y-m-d", strtotime($row['created_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center gap-3">
                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg bg-blue-600 text-white">
                                            تعديل
                                        </a>
                                        <a href="toggle_status.php?id=<?= $row['id'] ?>"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg <?= !empty($row['is_active']) ? 'bg-red-600 text-white' : 'bg-green-600 text-white' ?>">
                                            <?= !empty($row['is_active']) ? 'تعطيل' : 'تفعيل' ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>