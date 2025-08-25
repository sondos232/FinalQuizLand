<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$quiz_id = isset($_GET['quiz_id']) && ctype_digit($_GET['quiz_id']) ? (int) $_GET['quiz_id'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// اختبارات للقائمة
$quizzes = $conn->query("SELECT id, title FROM quizzes ORDER BY title ASC")->fetch_all(MYSQLI_ASSOC);

// جلب الأسئلة مع دعم البحث
$sql = "SELECT q.id, q.question_text, q.question_type, q.difficulty, q.created_at,
               qz.title AS quiz_title, qz.id AS quiz_id
        FROM questions q
        JOIN quizzes qz ON q.quiz_id = qz.id";

// إضافة البحث
$params = [];
if ($search) {
    $sql .= " WHERE q.question_text LIKE ? OR qz.title LIKE ? OR q.difficulty LIKE ?";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if ($quiz_id) {
    $sql .= " AND q.quiz_id = ?";
    $params[] = $quiz_id;
}

$sql .= " ORDER BY q.id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
$questions = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة الأسئلة</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include '../sidebar.php'; ?>

        <div class="flex-1 md:mr-64" style="width: -webkit-fill-available;">
            <?php include '../topbar.php'; ?>
            <div class="p-6">
                <div class="bg-white shadow-md p-6 rounded-lg">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <h2 class="text-2xl font-semibold text-gray-800">إدارة الأسئلة</h2>
                        <div class="flex items-center gap-2">
                            <a href="create.php" class="px-4 py-2 bg-blue-600 text-white rounded-md">إضافة سؤال جديد</a>
                        </div>
                    </div>

                    <!-- فلترة حسب الاختبار + حقل بحث -->
                    <form class="mt-4 flex items-center gap-2 flex-wrap" method="get">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                            placeholder="بحث عن سؤال" class="border rounded-md p-2 bg-white ml-6">
                        <select name="quiz_id" class="border rounded-md p-2 bg-white">
                            <option value="">كل الاختبارات</option>
                            <?php foreach ($quizzes as $q): ?>
                                <option value="<?= $q['id'] ?>" <?= $quiz_id === $q['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($q['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button class="px-4 py-2 bg-gray-800 text-white rounded-md">تطبيق</button>

                        <?php if ($quiz_id || $search): ?>
                            <a href="index.php" class="px-3 py-2 text-gray-700">إزالة الفلتر</a>
                        <?php endif; ?>
                    </form>

                    <!-- عرض متجاوب -->
                    <?php if (count($questions) === 0): ?>
                        <p class="mt-6 text-gray-600">لا توجد أسئلة.</p>
                    <?php else: ?>
                        <!-- جدول لأجهزة md وأكبر -->
                        <div class="mt-6 overflow-x-auto hidden lg:block">
                            <table class="border border-gray-200 rounded-lg shadow-sm" style="width: 100% !important;">
                                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                                    <tr>
                                        <th class="py-3 px-4 text-right border-b">#</th>
                                        <th class="py-3 px-4 text-right border-b">الاختبار</th>
                                        <th class="py-3 px-4 text-right border-b">السؤال</th>
                                        <th class="py-3 px-4 text-right border-b hidden xl:table-cell">النوع</th>
                                        <th class="py-3 px-4 text-right border-b">الصعوبة</th>
                                        <th class="py-3 px-4 text-right border-b">التاريخ</th>
                                        <th class="py-3 px-4 text-right border-b">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                                    <?php foreach ($questions as $row): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4 font-medium text-gray-600"><?= $row['id'] ?></td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <a href="index.php?quiz_id=<?= $row['quiz_id'] ?>"
                                                    class="text-blue-700 hover:underline">
                                                    <?= htmlspecialchars($row['quiz_title']) ?>
                                                </a>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="block max-w-[250px] truncate"
                                                    title="<?= htmlspecialchars($row['question_text']) ?>">
                                                    <?= htmlspecialchars($row['question_text']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 hidden xl:table-cell">
                                                <?= htmlspecialchars($row['question_type']) ?>
                                            </td>
                                            <td class="py-3 px-4"><?= htmlspecialchars($row['difficulty']) ?></td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <?= date("Y-m-d", strtotime($row['created_at'])) ?>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="edit.php?id=<?= $row['id'] ?>"
                                                        class="inline-flex items-center text-white hover:opacity-90 transition bg-yellow-500 px-3 py-2 rounded-md">
                                                        ✏️ تعديل
                                                    </a>
                                                    <a href="delete.php?id=<?= $row['id'] ?>"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟');"
                                                        class="inline-flex items-center text-white hover:opacity-90 transition bg-red-500 px-3 py-2 rounded-md">
                                                        🗑️ حذف
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- كروت للجوال -->
                        <div class="space-y-3 lg:hidden mt-6">
                            <?php foreach ($questions as $row): ?>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span>#<?= $row['id'] ?></span>
                                                <span
                                                    class="whitespace-nowrap"><?= date("Y-m-d", strtotime($row['created_at'])) ?></span>
                                            </div>
                                            <h3 class="text-sm text-gray-700 mt-1">
                                                <span
                                                    class="px-2 py-0.5 bg-gray-100 rounded"><?= htmlspecialchars($row['question_type']) ?></span>
                                                <span
                                                    class="px-2 py-0.5 bg-gray-100 rounded"><?= htmlspecialchars($row['difficulty']) ?></span>
                                            </h3>
                                            <p class="text-base font-semibold text-gray-800 mt-1 truncate"
                                                title="<?= htmlspecialchars($row['question_text']) ?>">
                                                <?= htmlspecialchars($row['question_text']) ?>
                                            </p>
                                            <a class="text-blue-700 text-sm hover:underline"
                                                href="index.php?quiz_id=<?= $row['quiz_id'] ?>">
                                                <?= htmlspecialchars($row['quiz_title']) ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-2 gap-3">
                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-yellow-500 text-white">تعديل</a>
                                        <a href="delete.php?id=<?= $row['id'] ?>"
                                            onclick="return confirm('هل أنت متأكد من الحذف؟');"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-500 text-white">حذف</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>