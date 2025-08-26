<?php
include '../../config/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

$passing_score = 70; 
$success = round(($score / $totalQuestions) * 100) >= $passing_score;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج الاختبار: <?= $quiz['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1.25rem;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            background: linear-gradient(135deg, #6e7fda, #4b6a8e);
            color: white;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .header-section p {
            font-size: 1.25rem;
        }

        .result-header {
            background-color: #4b6a8e;
            color: white;
            padding: 15px 0;
            border-radius: 0.5rem;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background-color: #3182ce;
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2b6cb0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .answer-feedback {
            background-color: #f7fafc;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
        }

        .correct-answer {
            color: #38a169;
        }

        .incorrect-answer {
            color: #e53e3e;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="header-section py-12 text-center">
        <h1>نتائج الاختبار: <?= $quiz['title'] ?></h1>
        <p class="mt-4"><?= $quiz['description'] ? $quiz['description'] : 'لا توجد تفاصيل.' ?></p>
    </div>

    <div class="container max-w-screen-lg mx-auto py-24">
        <div class="card">
            <h3 class="text-2xl font-semibold text-gray-800">النتيجة</h3>
            <p class="text-3xl font-bold <?= $success ? 'text-green-600' : 'text-red-600' ?> mt-4">
                <?= $success ? "لقد أجبت على $score من أصل $totalQuestions سؤال بشكل صحيح." : "فشلت في اجتياز الاختبار." ?>
            </p>
            <p class="text-lg text-gray-600 mt-2">نسبة النجاح: <?= round(($score / $totalQuestions) * 100, 2) ?>%</p>
        </div>

        <div class="mt-8 max-w-screen-lg mx-auto">
            <h3 class="text-2xl font-semibold text-gray-700">الأسئلة</h3>
            <div class="mt-4 space-y-6">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="answer-feedback">
                        <p class="text-lg font-semibold"><?= ($index + 1) . ". " . $question['question_text'] ?></p>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">إجابتك:</p>
                            <p class="font-semibold <?= $question['is_correct'] ? 'correct-answer' : 'incorrect-answer' ?>">
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
            <a href="./index.php" class="btn-primary">العودة إلى الدورات</a>
        </div>
    </div>

</body>

</html>