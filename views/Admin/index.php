<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}


$quizzesQuery = "SELECT COUNT(*) AS total_quizzes FROM quizzes";
$quizzesResult = $conn->query($quizzesQuery);
$quizzesCount = $quizzesResult->fetch_assoc()['total_quizzes'];

$usersQuery = "SELECT COUNT(*) AS total_users FROM users";
$usersResult = $conn->query($usersQuery);
$usersCount = $usersResult->fetch_assoc()['total_users'];

$correctAnswersQuery = "SELECT SUM(score) AS total_correct_answers FROM quiz_attempts";
$correctAnswersResult = $conn->query($correctAnswersQuery);
$correctAnswersCount = $correctAnswersResult->fetch_assoc()['total_correct_answers'];

$quizzesQuery = "SELECT category, COUNT(*) AS total_quizzes FROM quizzes GROUP BY category";
$quizzesResult = $conn->query($quizzesQuery);
$quizzesData = [];
$categories = [];
$counts = [];
while ($row = $quizzesResult->fetch_assoc()) {
    $categories[] = $row['category'];
    $counts[] = $row['total_quizzes'];
}

// Query to get user activity (number of logins per month)
$activityQuery = "SELECT MONTH(created_at) AS month, COUNT(*) AS logins FROM users GROUP BY MONTH(created_at)";
$activityResult = $conn->query($activityQuery);
$months = [];
$logins = [];
while ($row = $activityResult->fetch_assoc()) {
    $months[] = $row['month'];
    $logins[] = $row['logins'];
}

// Query to get correct vs incorrect answers
$answersQuery = "SELECT 
                    SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) AS correct,
                    SUM(CASE WHEN is_correct = 0 THEN 1 ELSE 0 END) AS incorrect
                  FROM answers";
$answersResult = $conn->query($answersQuery);
$answersData = $answersResult->fetch_assoc();

$correctAnswers = $answersData['correct'];
$incorrectAnswers = $answersData['incorrect'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <div class="flex">
        <!-- Sidebar -->
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white min-h-screen md:block hidden" id="sidebar">
            <div class="p-6 text-center bg-blue-100">
                <img src="../../assets/images/logo/logo.svg" alt="Logo" width="150" class="mx-auto">
            </div>
            <div class="mt-10">
                <ul>
                    <li class="py-3 px-6 hover:bg-purple-700">
                        <a href="#" class="text-xl flex items-center">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            اللوحة الرئيسية
                        </a>
                    </li>
                    <li class="py-3 px-6 hover:bg-purple-700">
                        <a href="#" class="text-xl flex items-center">
                            <i class="fas fa-chalkboard-teacher mr-3"></i>
                            الاختبارات
                        </a>
                    </li>
                    <li class="py-3 px-6 hover:bg-purple-700">
                        <a href="#" class="text-xl flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            المستخدمين
                        </a>
                    </li>
                    <li class="py-3 px-6 hover:bg-purple-700">
                        <a href="#" class="text-xl flex items-center">
                            <i class="fas fa-cogs mr-3"></i>
                            الإعدادات
                        </a>
                    </li>
                    <li class="py-3 px-6 hover:bg-purple-700">
                        <a href="#" class="text-xl flex items-center">
                            <i class="fas fa-chart-line mr-3"></i>
                            التقارير
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- Top Bar -->
            <div class="bg-blue-700 shadow-md flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <button class="text-white md:hidden ml-4" id="hamburger-btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <span class="text-xl font-semibold text-white">لوحة تحكم المدير</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">تسجيل الخروج</button>
                    </div>
                </div>
            </div>


            <!-- Dashboard Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                        <h3 class="text-lg font-semibold text-gray-700">إجمالي الاختبارات</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-4"><?= $quizzesCount ?></p>
                    </div>

                    <!-- Stats Card 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                        <h3 class="text-lg font-semibold text-gray-700">عدد المستخدمين</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-4"><?= $usersCount ?></p>
                    </div>

                    <!-- Stats Card 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                        <h3 class="text-lg font-semibold text-gray-700">عدد الإجابات الصحيحة</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-4">
                            <?= $correctAnswersCount ? $correctAnswersCount : 0 ?>
                        </p>
                    </div>
                </div>

                <div class="container mx-auto p-6">
                    <div class="flex flex-wrap gap-6 justify-between">
                        <!-- Bar Chart: Number of Quizzes per Category -->
                        <div class="w-full lg:w-[30%]">
                            <h3 class="text-xl font-semibold mb-6">إجمالي الاختبارات حسب الفئة</h3>
                            <canvas id="barChart"></canvas>
                        </div>

                        <!-- Line Chart: User Activity (Logins per Month) -->
                        <div class="w-full lg:w-[30%]">
                            <h3 class="text-xl font-semibold my-6">النشاط الشهري للمستخدمين</h3>
                            <canvas id="lineChart"></canvas>
                        </div>

                        <!-- Pie Chart: Correct vs Incorrect Answers -->
                        <div class="w-full lg:w-[30%]">
                            <h3 class="text-xl font-semibold my-6">الإجابات الصحيحة مقابل الإجابات الخاطئة</h3>
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        document.getElementById('hamburger-btn').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden'); // Toggle the hidden class on the sidebar
        });

        // Bar Chart: Number of Quizzes per Category
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categories); ?>,
                datasets: [{
                    label: 'عدد الاختبارات',
                    data: <?php echo json_encode($counts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Line Chart: User Activity (Logins per Month)
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'عدد تسجيلات الدخول',
                    data: <?php echo json_encode($logins); ?>,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            }
        });

        // Pie Chart: Correct vs Incorrect Answers
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['إجابات صحيحة', 'إجابات خاطئة'],
                datasets: [{
                    data: [<?php echo $correctAnswers; ?>, <?php echo $incorrectAnswers; ?>],
                    backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
                }]
            }
        });
    </script>
</body>

</html>