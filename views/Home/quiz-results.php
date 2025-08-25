<?php
include '../../config/db.php';
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/signin.php');
    exit();
}

$quiz_attempt_id = isset($_GET['quiz_attempt_id']) ? (int) $_GET['quiz_attempt_id'] : 0;

$quizAttemptQuery = "SELECT * FROM quiz_attempts WHERE id = $quiz_attempt_id AND user_id = " . $_SESSION['user']['id'];
$quizAttemptResult = $conn->query($quizAttemptQuery);
$quizAttempt = $quizAttemptResult->fetch_assoc();

if (!$quizAttempt) {
    header('Location: ./index.php');
    exit();
}

$quizQuery = "SELECT * FROM quizzes WHERE id = " . $quizAttempt['quiz_id'];
$quizResult = $conn->query($quizQuery);
$quiz = $quizResult->fetch_assoc();

$score = $quizAttempt['score'];
$totalQuestions = $quizAttempt['total_questions'];

$questionsQuery = "SELECT q.id AS question_id, q.question_text, sa.selected_answer, a.answer_text AS selected_answer_text, a.is_correct, ca.answer_text AS correct_answer_text 
                   FROM questions q
                   LEFT JOIN student_answers sa ON sa.question_id = q.id AND sa.quiz_attempt_id = $quiz_attempt_id
                   LEFT JOIN answers a ON a.id = sa.selected_answer
                   LEFT JOIN answers ca ON ca.question_id = q.id AND ca.is_correct = 1
                   WHERE sa.selected_answer IS NOT NULL";

$questionsResult = $conn->query($questionsQuery);
$questions = [];

while ($row = $questionsResult->fetch_assoc()) {
    $questions[] = $row;
}
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

    <div class="bg-blue-600 py-4 shadow-md">
        <div class="container mx-auto px-4 text-center">
            <span class="text-white text-2xl font-bold">نتائج الاختبار</span>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 mt-10">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800"><?= $quiz['title'] ?></h2>
            <p class="text-lg text-gray-600 mt-2"><?= $quiz['description'] ? $quiz['description'] : 'لا توجد تفاصيل' ?>
            </p>

            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-gray-700">النتيجة</h3>
                <p class="text-3xl font-bold text-green-500 mt-4">لقد أجبت على <?= $score ?> من أصل
                    <?= $totalQuestions ?> سؤال بشكل صحيح.
                </p>
                <p class="text-lg text-gray-600 mt-2">نسبة النجاح: <?= round(($score / $totalQuestions) * 100, 2) ?>%
                </p>
            </div>

            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-gray-700">الأسئلة</h3>
                <div class="mt-4">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="border-b pb-4 mb-4">
                            <p class="text-lg font-semibold"><?= ($index + 1) . ". " . $question['question_text'] ?></p>

                            <div class="mt-2">
                                <p class="text-sm text-gray-600">إجابتك:</p>
                                <p class="font-semibold <?= $question['is_correct'] ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $question['selected_answer_text'] ?>
                                </p>
                                <p class="text-sm text-gray-500 mt-2">الإجابة الصحيحة:</p>
                                <p class="font-semibold text-green-600"><?= $question['correct_answer_text'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="./index.php"
                    class="bg-blue-600 text-white py-3 px-8 rounded-full text-lg font-medium hover:bg-blue-700 transition duration-300">العودة
                    إلى الدورات</a>
            </div>
        </div>
    </div>

</body>

</html>