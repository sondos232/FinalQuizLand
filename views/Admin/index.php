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

$quizzesQuery = "
    SELECT q.category, COUNT(q.id) AS total_quizzes, 
           IFNULL(SUM(qa.total_questions), 0) AS total_attempts
    FROM quizzes q
    LEFT JOIN quiz_attempts qa ON q.id = qa.quiz_id
    GROUP BY q.category";
$quizzesResult = $conn->query($quizzesQuery);
$quizzesData = [];
$categories = [];
$quizCounts = [];
$attemptCounts = [];
while ($row = $quizzesResult->fetch_assoc()) {
    $categories[] = $row['category'];
    $quizCounts[] = $row['total_quizzes'];
    $attemptCounts[] = $row['total_attempts'];
}

$activityQuery = "SELECT MONTH(created_at) AS month, COUNT(*) AS logins FROM users GROUP BY MONTH(created_at)";
$activityResult = $conn->query($activityQuery);
$months = [];
$logins = [];
while ($row = $activityResult->fetch_assoc()) {
    $months[] = $row['month'];
    $logins[] = $row['logins'];
}

$questionsQuery = "
    SELECT 
        COUNT(CASE WHEN a.is_correct = 1 THEN 1 ELSE NULL END) AS correct_answers,
        COUNT(CASE WHEN a.is_correct = 0 THEN 1 ELSE NULL END) AS incorrect_answers
    FROM student_answers sa
    JOIN answers a ON sa.selected_answer = a.id";
$questionsResult = $conn->query($questionsQuery);
$questionsData = $questionsResult->fetch_assoc();

$correctAnswers = $questionsData['correct_answers'];
$incorrectAnswers = $questionsData['incorrect_answers'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <div class="flex">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 md:mr-64">
            <?php include 'topbar.php'; ?>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                        <h3 class="text-lg font-semibold text-gray-700">إجمالي الاختبارات</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-4"><?= $quizzesCount ?></p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                        <h3 class="text-lg font-semibold text-gray-700">عدد المستخدمين</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-4"><?= $usersCount ?></p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                        <h3 class="text-lg font-semibold text-gray-700">عدد الإجابات الصحيحة</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-4">
                            <?= $correctAnswersCount ? $correctAnswersCount : 0 ?>
                        </p>
                    </div>
                </div>

                <div class="container mx-auto p-6">
                    <div class="flex flex-wrap gap-6 justify-between">
                        <div class="w-full lg:w-[30%]">
                            <h3 class="text-xl font-semibold mb-6">إجمالي الاختبارات حسب الفئة</h3>
                            <canvas id="barChart"></canvas>
                        </div>

                        <div class="w-full lg:w-[30%]">
                            <h3 class="text-xl font-semibold my-6">النشاط الشهري للمستخدمين</h3>
                            <canvas id="lineChart"></canvas>
                        </div>

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
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categories); ?>,
                datasets: [
                    {
                        label: 'عدد الاختبارات',
                        data: <?php echo json_encode($quizCounts); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'عدد المحاولات',
                        data: <?php echo json_encode($attemptCounts); ?>,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


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

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['إجابات صحيحة', 'إجابات خاطئة'],
                datasets: [{
                    data: [<?php echo $correctAnswers; ?>, <?php echo $incorrectAnswers; ?>],
                    backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>