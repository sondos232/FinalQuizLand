<?php
include("../../config/db.php");

$search_input = "";
$quizzes = [];

if (isset($_GET['search'])) {
    $search_input = $_GET['search'];
    $search_query = '%' . $search_input . '%';
    $stmt = $conn->prepare("SELECT id, title, category, created_at FROM quizzes WHERE title LIKE ? OR category LIKE ?");
    $stmt->bind_param("ss", $search_query, $search_query);
} else {
    $stmt = $conn->prepare("SELECT id, title, category, created_at FROM quizzes");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث في الاختبارات</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include '../header.php'; ?>

    <section id="search-section" class="bg-slate-200 mt-16">
        <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4 pt-20 pb-14">
            <div class="flex flex-col gap-8 items-center">
                <h2 class="text-gray-900 text-3xl sm:text-4xl font-semibold">ابحث في اختباراتنا</h2>
                <div class="relative rounded-full pt-5 lg:pt-0 w-full max-w-md">
                    <form method="get">
                        <input type="text" name="search"
                            class="py-6 lg:py-8 pl-8 pr-20 text-lg w-full text-black rounded-full focus:outline-none shadow-xl shadow-purple-100/90"
                            placeholder="ابحث عن اختبارات..." value="<?= htmlspecialchars($search_input) ?>"
                            autocomplete="off">
                        <button type="submit" class="bg-blue-500 p-5 rounded-full absolute left-2 top-7 lg:top-2">
                            <svg class="w-[22px] lg:w-[38px] h-[22px] lg:h-[38px] text-gray-800 dark:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2.3"
                                    d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <?php if (count($quizzes) > 0): ?>
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($quizzes as $quiz): ?>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($quiz['title']) ?></h3>
                                <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($quiz['category']) ?></p>
                                <p class="text-xs text-gray-500 mt-2"><?= date("Y-m-d", strtotime($quiz['created_at'])) ?></p>
                                <div class="mt-4">
                                    <a href="quiz-details.php?quiz_id=<?= $quiz['id'] ?>"
                                        class="text-blue-600 hover:underline">عرض التفاصيل</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="mt-6 text-gray-600">لا توجد اختبارات متاحة لهذا البحث.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include '../footer.php'; ?>
</body>

</html>