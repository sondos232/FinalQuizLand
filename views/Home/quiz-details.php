<?php
include '../../config/db.php';
include '../header.php';

$quiz_id = isset($_GET['quiz_id']) ? (int) $_GET['quiz_id'] : 0;

$quizQuery = "SELECT * FROM quizzes WHERE id = $quiz_id";
$quizResult = $conn->query($quizQuery);
$quiz = $quizResult->fetch_assoc();

if (!$quiz) {
    header('Location: ./index.php');
    exit();
}

$creatorQuery = "SELECT username FROM users WHERE id = " . $quiz['created_by'];
$creatorResult = $conn->query($creatorQuery);
$creator = $creatorResult->fetch_assoc()['username'];

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الاختبار: <?= $quiz['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-card {
            background-color: #f7fafc;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #3182ce;
            color: #fff;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2b6cb0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            display: none;
            background-color: #f7fafc;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 500px;
            z-index: 9999;
        }

        .form-container.active {
            display: block;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }

        .btn-radio {
            background-color: #f7fafc;
            border-radius: 0.75rem;
            border: 1px solid #ccc;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-radio input[type="radio"] {
            display: none;
        }

        .btn-radio span {
            color: #333;
            padding: 1rem 2rem;
        }

        .btn-radio:hover {
            background-color: #e2e8f0;
            border-color: #ddd;
        }

        .btn-radio input[type="radio"]:checked+span {
            background-color: #3182ce;
            color: white;
            border-color: #3182ce;
            border-radius: 0.75rem;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-6 mt-20">
        <div class="card">
            <h2 class="text-4xl font-bold text-black"><?= $quiz['title'] ?></h2>
            <p class="text-lg text-gray-700 mt-4"><?= $quiz['description'] ? $quiz['description'] : 'لا توجد تفاصيل' ?>
            </p>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="info-card">
                    <h3>عدد الأسئلة</h3>
                    <p class="mt-2">
                        <?php
                        $questionsQuery = "SELECT COUNT(*) AS total_questions FROM questions WHERE quiz_id = $quiz_id";
                        $questionsResult = $conn->query($questionsQuery);
                        $questionsCount = $questionsResult->fetch_assoc()['total_questions'];
                        echo $questionsCount;
                        ?>
                    </p>
                </div>

                <div class="info-card">
                    <h3>منشئ الاختبار</h3>
                    <p class="mt-2"><?= $creator ?></p>
                </div>

                <div class="info-card">
                    <h3>الفئة</h3>
                    <p class="mt-2"><?= $quiz['category'] ?></p>
                </div>
            </div>

            <form action="start-quiz.php" method="POST" class="mt-6 text-center">
                <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                <button type="button" id="startQuizBtn" class="btn-primary">ابدأ الاختبار</button>
            </form>

            <div id="quizSettingsForm" class="form-container mt-6">
                <h3 class="text-2xl font-semibold text-black">إعدادات الاختبار</h3>
                <form action="start-quiz.php" method="POST" class="mt-6">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">

                    <div class="mb-4">
                        <label for="num_questions" class="block text-lg font-medium text-gray-700">عدد الأسئلة</label>
                        <select name="num_questions" id="num_questions"
                            class="w-full p-3 mt-2 border border-gray-300 rounded-md">
                            <?php for ($i = 10; $i <= 30; $i += 5): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="timer" class="block text-lg font-medium text-gray-700">تفعيل المؤقت</label>
                        <select name="timer" id="timer" class="w-full p-3 mt-2 border border-gray-300 rounded-md">
                            <option value="1">نعم</option>
                            <option value="0">لا</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="difficulty" class="block text-lg font-medium text-gray-700">الصعوبة</label>
                        <div class="radio-button-group mt-4">
                            <label class="btn-radio">
                                <input type="radio" name="difficulty" value="easy" checked>
                                <span>سهل</span>
                            </label>
                            <label class="btn-radio">
                                <input type="radio" name="difficulty" value="medium">
                                <span>متوسط</span>
                            </label>
                            <label class="btn-radio">
                                <input type="radio" name="difficulty" value="hard">
                                <span>صعب</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">ابدأ الاختبار</button>
                </form>
            </div>
        </div>
    </div>

    <div id="overlay" class="overlay" style="display: none;"></div>

    <script>
        document.getElementById('startQuizBtn').addEventListener('click', function () {
            document.getElementById('quizSettingsForm').classList.toggle('active');
            document.getElementById('overlay').style.display = 'block';
        });

        document.getElementById('overlay').addEventListener('click', function () {
            document.getElementById('quizSettingsForm').classList.remove('active');
            document.getElementById('overlay').style.display = 'none';
        });
    </script>

    <script>
        document.getElementById('quizSettingsForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var numQuestions = document.getElementById('num_questions').value;
            var timer = document.getElementById('timer').value;
            var difficulty = document.querySelector('input[name="difficulty"]:checked').value;

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'start-quiz.php';

            var inputQuizId = document.createElement('input');
            inputQuizId.type = 'hidden';
            inputQuizId.name = 'quiz_id';
            inputQuizId.value = '<?= $quiz['id'] ?>';

            var inputNumQuestions = document.createElement('input');
            inputNumQuestions.type = 'hidden';
            inputNumQuestions.name = 'num_questions';
            inputNumQuestions.value = numQuestions;

            var inputTimer = document.createElement('input');
            inputTimer.type = 'hidden';
            inputTimer.name = 'timer';
            inputTimer.value = timer;

            var inputDifficulty = document.createElement('input');
            inputDifficulty.type = 'hidden';
            inputDifficulty.name = 'difficulty';
            inputDifficulty.value = difficulty;

            form.appendChild(inputQuizId);
            form.appendChild(inputNumQuestions);
            form.appendChild(inputTimer);
            form.appendChild(inputDifficulty);

            document.body.appendChild(form);
            form.submit();
        });
    </script>


</body>

</html>