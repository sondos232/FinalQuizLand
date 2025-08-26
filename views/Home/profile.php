<?php
include '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userId = $_SESSION['user']['id'];

$query = "SELECT * FROM users WHERE id = '$userId'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $userfromdb = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

$achievementsQuery = "
    SELECT 
        q.title, 
        q.category,
        MAX(qa.score) AS highest_score,
        MAX(qa.attempted_at) AS last_attempt
    FROM quiz_attempts qa
    JOIN quizzes q ON qa.quiz_id = q.id
    WHERE qa.user_id = " . $_SESSION['user']['id'] . "
    GROUP BY q.id
    ORDER BY last_attempt DESC
";
$achievementsResult = $conn->query($achievementsQuery);
$achievements = [];

while ($row = $achievementsResult->fetch_assoc()) {
    $achievements[] = $row;
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-primary {
            background-color: #4F46E5;
        }

        .bg-secondary {
            background-color: #F3F4F6;
        }

        .avatar-image {
            border-radius: 50%;
            object-fit: cover;
            width: 120px;
            height: 120px;
        }

        .profile-header {
            background-color: #4F46E5;
            color: white;
        }

        .card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #4F46E5;
            color: white;
            padding: 0.5rem 2rem;
            font-size: 1rem;
            border-radius: 9999px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4338CA;
        }

        .btn-secondary {
            background-color: #E5E7EB;
            color: #4F46E5;
            padding: 0.5rem 2rem;
            font-size: 1rem;
            border-radius: 9999px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #D1D5DB;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <?php include '../header.php'; ?>

    <div class="profile-header pb-12 pt-36">
        <div class="container mx-auto text-center">
            <img src="<?= !empty($userfromdb['image']) ? '../../' . $userfromdb['image'] : 'https://dovercourt.org/wp-content/uploads/2019/11/610-6104451_image-placeholder-png-user-profile-placeholder-image-png.jpg' ?>"
                alt="Profile Picture" class="avatar-image mx-auto">
            <h1 class="text-3xl font-semibold mt-4"><?= htmlspecialchars($userfromdb['username']) ?></h1>
            <p class="text-lg text-gray-300 mt-2"><?= htmlspecialchars($userfromdb['role']) ?></p>
        </div>
    </div>

    <div class="container max-w-screen-xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="card p-6">
                <h2 class="text-2xl font-semibold mb-4">معلوماتي الشخصية</h2>
                <div class="space-y-4">
                    <p class="text-lg"><span class="font-semibold">الاسم:
                        </span><?= htmlspecialchars($userfromdb['username']) ?></p>
                    <p class="text-lg"><span class="font-semibold">البريد الإلكتروني:
                        </span><?= htmlspecialchars($userfromdb['email']) ?></p>
                </div>
            </div>

            <div class="card p-6">
                <h2 class="text-2xl font-semibold mb-4">معلومات الحساب</h2>
                <div class="space-y-4">
                    <p class="text-lg"><span class="font-semibold">تاريخ التسجيل:
                        </span><?= date("Y-m-d", strtotime($userfromdb['created_at'])) ?></p>
                    <p class="text-lg"><span class="font-semibold">آخر تسجيل دخول:
                        </span><?= date("Y-m-d", strtotime(date('Y-m-d H:i:s'))) ?></p>
                    <p class="text-lg"><span class="font-semibold">حالة الحساب: </span><span
                            class="text-green-500"><?= $userfromdb['is_active'] ? 'مفعل' : 'غير مفعل' ?></span></p>
                </div>
            </div>
        </div>

        <div class="card p-6 mt-8 bg-white shadow-lg rounded-xl">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">إنجازات المستخدم</h2>
            <?php if (!empty($achievements)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($achievements as $achievement): ?>
                        <div
                            class="bg-blue-50 border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out">
                            <div class="flex justify-between items-center">
                                <p class="text-lg font-semibold text-blue-600">
                                    <?= htmlspecialchars($achievement['title']) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <?= date("Y-m-d", strtotime($achievement['last_attempt'])) ?>
                                </p>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">الفئة: <span
                                        class="font-medium text-gray-800"><?= htmlspecialchars($achievement['category']) ?></span>
                                </p>
                                <p class="text-sm text-gray-600 mt-2">أعلى نتيجة:
                                    <span class="font-medium text-green-600"><?= $achievement['highest_score'] ?> من
                                        10</span>
                                </p>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">مستوى الإنجاز:
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-600">
                                        <?= $achievement['highest_score'] >= 8 ? 'متقدم' : 'مبتدئ' ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-lg text-gray-600">لا توجد إنجازات لعرضها.</p>
            <?php endif; ?>
        </div>


        <div class="mt-8 text-center">
            <a href="edit-profile.php" class="btn-primary mx-2">تعديل الملف</a>
            <a href="logout.php" class="btn-secondary mx-2">تسجيل الخروج</a>
        </div>
    </div>

    <footer class="bg-secondary py-6 mt-12">
        <div class="container mx-auto text-center">
            <p class="text-gray-700">حقوق الطبع والنشر محفوظة لدى QuizLand</p>
        </div>
    </footer>

</body>

</html>