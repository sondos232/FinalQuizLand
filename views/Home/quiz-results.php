<?php
include '../../config/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/signin.php');
    exit();
}

// Get the quiz attempt ID from the URL
$quiz_attempt_id = isset($_GET['quiz_attempt_id']) ? (int) $_GET['quiz_attempt_id'] : 0;

// Fetch quiz attempt details
$quizAttemptQuery = "SELECT * FROM quiz_attempts WHERE id = $quiz_attempt_id AND user_id = " . $_SESSION['user']['id'];
$quizAttemptResult = $conn->query($quizAttemptQuery);
$quizAttempt = $quizAttemptResult->fetch_assoc();

// If no quiz attempt found, redirect to courses page
if (!$quizAttempt) {
    header('Location: /courses');
    exit();
}

// Fetch quiz details
$quizQuery = "SELECT * FROM quizzes WHERE id = " . $quizAttempt['quiz_id'];
$quizResult = $conn->query($quizQuery);
$quiz = $quizResult->fetch_assoc();

// Fetch user's score for this attempt
$score = $quizAttempt['score'];
$totalQuestions = $quizAttempt['total_questions'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج الاختبار: <?= $quiz['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Top Bar -->
    <div class="bg-blue-600 py-4 shadow-md">
        <div class="container mx-auto px-4 text-center">
            <span class="text-white text-2xl font-bold">نتائج الاختبار</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8 mt-10">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800"><?= $quiz['title'] ?></h2>
            <p class="text-lg text-gray-600 mt-2"><?= $quiz['description'] ? $quiz['description'] : 'لا توجد تفاصيل' ?></p>

            <!-- Results Section -->
            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-gray-700">النتيجة</h3>
                <p class="text-3xl font-bold text-green-500 mt-4">لقد أجبت على <?= $score ?> من أصل <?= $totalQuestions ?> سؤال بشكل صحيح.</p>
                <p class="text-lg text-gray-600 mt-2">نسبة النجاح: <?= round(($score / $totalQuestions) * 100, 2) ?>%</p>
            </div>

            <!-- Return Button -->
            <div class="mt-8 text-center">
                <a href="./index.php" class="bg-blue-600 text-white py-3 px-8 rounded-full text-lg font-medium hover:bg-blue-700 transition duration-300">العودة إلى الدورات</a>
            </div>
        </div>
    </div>

</body>

</html>
