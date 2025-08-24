<?php
include '../../config/db.php';

// Get the quiz data from the POST request
$quiz_id = isset($_POST['quiz_id']) ? (int) $_POST['quiz_id'] : 0;
$num_questions = isset($_POST['num_questions']) ? (int) $_POST['num_questions'] : 10;
$timer = isset($_POST['timer']) ? (int) $_POST['timer'] : 0;
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'easy';

// Fetch the quiz details
$quizQuery = "SELECT * FROM quizzes WHERE id = $quiz_id";
$quizResult = $conn->query($quizQuery);
$quiz = $quizResult->fetch_assoc();

// If no quiz found, redirect to the courses page
if (!$quiz) {
    header('Location: /courses');
    exit();
}

// Fetch the questions based on difficulty and number of questions
$questionsQuery = "SELECT * FROM questions WHERE quiz_id = $quiz_id AND difficulty = '$difficulty' LIMIT $num_questions";
$questionsResult = $conn->query($questionsQuery);
$questions = [];
while ($row = $questionsResult->fetch_assoc()) {
    $questions[] = $row;
}

$_SESSION['quiz_questions'] = $questions;

// Fetch quiz creator's name
$creatorQuery = "SELECT username FROM users WHERE id = " . $quiz['created_by'];
$creatorResult = $conn->query($creatorQuery);
$creator = $creatorResult->fetch_assoc()['username'];

// Set the timer based on difficulty
$time_per_question = 0;

switch ($difficulty) {
    case 'easy':
        $time_per_question = 30;
        break;
    case 'medium':
        $time_per_question = 45;
        break;
    case 'hard':
        $time_per_question = 60;
        break;
}

$total_time = $num_questions * $time_per_question;

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ابدأ الاختبار: <?= $quiz['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .quiz-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .question-container {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .question-text {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .answer-option {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            background: #f1f1f1;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .answer-option:hover {
            background: #e2e2e2;
        }

        .answer-option input[type="radio"] {
            margin-right: 15px;
            accent-color: #3182ce;
        }

        .btn-submit {
            background-color: #3182ce;
            color: white;
            padding: 15px;
            width: 100%;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #2b6cb0;
        }

        /* Timer Styles */
        .timer {
            position: fixed;
            top: 10px;
            left: 20px;
            font-size: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <!-- Timer Display -->
    <div id="timer" class="timer">00:00</div>

    <div class="quiz-container">

        <h2 class="text-4xl font-bold text-black mb-6"><?= $quiz['title'] ?></h2>
        <p class="text-lg text-gray-700 mb-6"><?= $quiz['description'] ? $quiz['description'] : 'لا توجد تفاصيل' ?></p>
        <form action="submit-quiz.php" method="POST">
            <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
            <input type="hidden" name="difficulty" value="<?= $difficulty ?>">
            <input type="hidden" name="num_questions" value="<?= $num_questions ?>">

            <div class="grid gap-4">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-container">
                        <p class="question-text"><?= ($index + 1) . '. ' . $question['question_text'] ?></p>
                        <div>
                            <input type="hidden" name="question_<?= $index + 1 ?>" value="<?= $question['id'] ?>">
                            <!-- Store question_id -->
                            <?php
                            // Fetch answers for each question
                            $answersQuery = "SELECT * FROM answers WHERE question_id = " . $question['id'];
                            $answersResult = $conn->query($answersQuery);
                            $answers = [];
                            while ($row = $answersResult->fetch_assoc()) {
                                $answers[] = $row;
                            }
                            ?>
                            <?php foreach ($answers as $answer): ?>
                                <label class="answer-option">
                                    <input type="radio" name="answer_<?= $index + 1 ?>" value="<?= $answer['id'] ?>" required>
                                    <?= $answer['answer_text'] ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>


                <button type="submit" class="btn-submit">إرسال الإجابات</button>
            </div>
        </form>



    </div>

    <script>
        // Initialize timer
        let totalTime = <?= $total_time ?>; // Total time in seconds
        const timerDisplay = document.getElementById('timer');

        // Start countdown
        const countdown = setInterval(function () {
            let minutes = Math.floor(totalTime / 60);
            let seconds = totalTime % 60;

            // Format time
            if (seconds < 10) seconds = '0' + seconds;
            if (minutes < 10) minutes = '0' + minutes;

            timerDisplay.textContent = minutes + ':' + seconds;

            if (totalTime <= 0) {
                clearInterval(countdown);
                alert('لقد انتهت مدة الاختبار!');
                document.querySelector('form').submit();
            }

            totalTime--;
        }, 1000);
    </script>

</body>

</html>