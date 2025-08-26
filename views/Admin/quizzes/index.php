<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$query = "SELECT id, title, category, created_at, image FROM quizzes";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الاختبارات</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex">
        <?php include '../sidebar.php'; ?>

        <div class="flex-1 md:mr-64">
            <?php include '../topbar.php'; ?>
            <div class="p-6">
                <div class="bg-white shadow-md p-6 rounded-lg">
                    <h2 class="text-2xl font-semibold text-gray-800">إدارة الاختبارات</h2>
                    <div class="mt-4">
                        <a href="create.php" class="px-4 py-2 bg-blue-600 text-white rounded-md">إضافة اختبار جديد</a>
                    </div>

                    <div class="mt-6">
                        <div class="overflow-x-auto hidden lg:block">
                            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                                    <tr>
                                        <th class="py-3 px-4 text-right border-b">#</th>
                                        <th class="py-3 px-4 text-right border-b">العنوان</th>
                                        <th class="py-3 px-4 text-right border-b">الفئة</th>
                                        <th class="py-3 px-4 text-right border-b">التاريخ</th>
                                        <th class="py-3 px-4 text-right border-b">الصورة</th>
                                        <th class="py-3 px-4 text-right border-b">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                                    <?php foreach ($result as $row): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4 font-medium text-gray-600"><?= $row['id'] ?></td>
                                            <td class="py-3 px-4">
                                                <span class="block max-w-[320px] truncate"
                                                    title="<?= htmlspecialchars($row['title']) ?>">
                                                    <?= htmlspecialchars($row['title']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <?= htmlspecialchars($row['category']) ?></td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <?= date("Y-m-d", strtotime($row['created_at'])) ?></td>
                                            <td class="py-3 px-4">
                                                    <img src="../../../<?= $row['image'] ?>" alt="صورة الاختبار"
                                                        class="w-16 h-16 object-cover rounded-md">
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="edit.php?id=<?= $row['id'] ?>"
                                                        class="inline-flex items-center text-white hover:opacity-90 transition bg-yellow-500 px-3 py-2 rounded-md">
                                                        ✏️ <span class="ml-1">تعديل</span>
                                                    </a>
                                                    <a href="delete.php?id=<?= $row['id'] ?>"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟');"
                                                        class="inline-flex items-center text-white hover:opacity-90 transition bg-red-500 px-3 py-2 rounded-md">
                                                        🗑️ <span class="ml-1">حذف</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="space-y-3 lg:hidden">
                            <?php foreach ($result as $row): ?>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span>#<?= $row['id'] ?></span>
                                                <span
                                                    class="whitespace-nowrap"><?= date("Y-m-d", strtotime($row['created_at'])) ?></span>
                                            </div>
                                            <h3 class="text-base font-semibold text-gray-800 mt-1 truncate"
                                                title="<?= htmlspecialchars($row['title']) ?>">
                                                <?= htmlspecialchars($row['title']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100">
                                                    <?= htmlspecialchars($row['category']) ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-2 gap-3">
                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-yellow-500 text-white">
                                            تعديل
                                        </a>
                                        <a href="delete.php?id=<?= $row['id'] ?>"
                                            onclick="return confirm('هل أنت متأكد من الحذف؟');"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-500 text-white">
                                            حذف
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