<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$query = "SELECT id, title, category, created_at FROM quizzes";
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

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="bg-white shadow-md p-6 rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800">إدارة الاختبارات</h2>
                <div class="mt-4">
                    <a href="create.php" class="px-4 py-2 bg-blue-600 text-white rounded-md">إضافة اختبار جديد</a>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-gray-700">#</th>
                                <th class="py-2 px-4 text-gray-700">العنوان</th>
                                <th class="py-2 px-4 text-gray-700">الفئة</th>
                                <th class="py-2 px-4 text-gray-700">التاريخ</th>
                                <th class="py-2 px-4 text-gray-700">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="py-2 px-4"><?= $row['id'] ?></td>
                                    <td class="py-2 px-4"><?= $row['title'] ?></td>
                                    <td class="py-2 px-4"><?= $row['category'] ?></td>
                                    <td class="py-2 px-4"><?= $row['created_at'] ?></td>
                                    <td class="py-2 px-4">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600">تعديل</a> |
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600">حذف</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>