<?php
include '../../../config/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

if (empty($_SESSION['csrf']))
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf'];

$quizzes = $conn->query("SELECT id, title FROM quizzes ORDER BY title ASC")->fetch_all(MYSQLI_ASSOC);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $errors[] = 'Security token mismatch.';
    } else {
        $quiz_id = (int) ($_POST['quiz_id'] ?? 0);
        $question_text = trim($_POST['question_text'] ?? '');
        $question_type = $_POST['question_type'] ?? 'multiple_choice';
        $difficulty = $_POST['difficulty'] ?? 'medium';

        if ($quiz_id <= 0)
            $errors[] = 'اختر الاختبار.';
        if ($question_text === '')
            $errors[] = 'اكتب نص السؤال.';
        if (!in_array($question_type, ['multiple_choice', 'true_false', 'short_answer'], true))
            $errors[] = 'نوع غير صالح.';
        if (!in_array($difficulty, ['easy', 'medium', 'hard'], true))
            $errors[] = 'صعوبة غير صالحة.';

        if (!$errors) {
            $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, question_type, difficulty) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $quiz_id, $question_text, $question_type, $difficulty);
            if ($stmt->execute()) {
                $question_id = $stmt->insert_id;
                $stmt->close();

                if ($question_type === 'multiple_choice') {
                    $answers = $_POST['answers'] ?? []; 
                    $correct = $_POST['correct'] ?? null;
                    if (count($answers) !== 4)
                        $errors[] = 'أدخل 4 إجابات.';
                    if ($correct === null || !in_array((int) $correct, [0, 1, 2, 3], true))
                        $errors[] = 'اختر إجابة صحيحة.';
                    if (count($answers) !== count(array_unique($answers))) {
                        $errors[] = 'لا يمكن أن تكون الإجابات مكررة.';
                    }
                    if (!$errors) {
                        $ins = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                        for ($i = 0; $i < 4; $i++) {
                            $txt = trim($answers[$i]);
                            if ($txt === '') {
                                $errors[] = 'لا تترك حقل الإجابة فارغاً.';
                                break;
                            }
                            $is_correct = ($i == (int) $correct) ? 1 : 0;
                            $ins->bind_param("isi", $question_id, $txt, $is_correct);
                            $ins->execute();
                        }
                        if (isset($ins))
                            $ins->close();
                    }
                } elseif ($question_type === 'true_false') {

                } else {

                }

                if (!$errors) {
                    header("Location: index.php?created=1");
                    exit();
                } else {
                    $conn->query("DELETE FROM questions WHERE id={$question_id}");
                }
            } else {
                $errors[] = 'Database error while creating question.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة سؤال</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include '../sidebar.php'; ?>
        <div class="flex-1 p-6 md:mr-64">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">إضافة سؤال جديد</h2>
                    <a href="index.php" class="text-sm px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">عودة
                        للقائمة</a>
                </div>

                <?php if ($errors): ?>
                    <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 border border-red-200">
                        <ul class="list-disc mr-6 space-y-1">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" class="space-y-4">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

                    <div>
                        <label class="block mb-1 text-sm text-gray-700">الاختبار</label>
                        <select name="quiz_id" class="w-full border rounded-lg p-2 bg-white" required>
                            <option value="">اختر...</option>
                            <?php foreach ($quizzes as $q): ?>
                                <option value="<?= $q['id'] ?>" <?= (($_POST['quiz_id'] ?? '') == $q['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($q['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-700">نص السؤال</label>
                        <textarea name="question_text" rows="3" class="w-full border rounded-lg p-2"
                            required><?= htmlspecialchars($_POST['question_text'] ?? '') ?></textarea>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">النوع</label>
                            <select name="question_type" id="question_type"
                            class="w-full border rounded-lg p-2 bg-white" required>
                            <option value="multiple_choice" <?= (($_POST['question_type'] ?? 'multiple_choice') === 'multiple_choice') ? 'selected' : '' ?>>اختيار من متعدد</option>
                            <option value="true_false" <?= (($_POST['question_type'] ?? '') === 'true_false') ? 'selected' : '' ?>>صح/خطأ</option>
                            <option value="short_answer" <?= (($_POST['question_type'] ?? '') === 'short_answer') ? 'selected' : '' ?>>إجابة قصيرة</option>
                        </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-700">الصعوبة</label>
                            <select name="difficulty" class="w-full border rounded-lg p-2 bg-white" required>
                                <option value="easy" <?= (($_POST['difficulty'] ?? 'medium') === 'easy') ? 'selected' : '' ?>>
                                    سهل</option>
                                <option value="medium" <?= (($_POST['difficulty'] ?? 'medium') === 'medium') ? 'selected' : '' ?>>متوسط</option>
                                <option value="hard" <?= (($_POST['difficulty'] ?? 'medium') === 'hard') ? 'selected' : '' ?>>
                                    صعب</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-red-600">متاح إضافة اسئلة من نوع اختيار متعدد فقط!</p>

                    <div id="mc_section"
                        class="<?= (($_POST['question_type'] ?? 'multiple_choice') === 'multiple_choice') ? '' : 'hidden' ?>">
                        <p class="text-sm text-gray-600 mb-2">أدخل 4 إجابات وحدد الصحيحة</p>
                        <?php for ($i = 0; $i < 4; $i++): ?>
                            <div class="flex items-center gap-2 mb-2">
                                <input type="radio" name="correct" value="<?= $i ?>" <?= ((int) ($_POST['correct'] ?? -1) === $i) ? 'checked' : '' ?> required>
                                <input type="text" name="answers[]" class="flex-1 border rounded-lg p-2"
                                    placeholder="الإجابة <?= $i + 1 ?>"
                                    value="<?= htmlspecialchars($_POST['answers'][$i] ?? '') ?>" required>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div id="tf_section" class="<?= (($_POST['question_type'] ?? '') === 'true_false') ? '' : 'hidden' ?>">
                        <p class="text-sm text-gray-600 mb-2">اختر الإجابة الصحيحة:</p>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tf_correct" value="true" <?= (($_POST['tf_correct'] ?? 'true') === 'true') ? 'checked' : '' ?>>
                                <span>صح</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tf_correct" value="false" <?= (($_POST['tf_correct'] ?? '') === 'false') ? 'checked' : '' ?>>
                                <span>خطأ</span>
                            </label>
                        </div>
                    </div>

                    <div id="short_section"
                        class="<?= (($_POST['question_type'] ?? '') === 'short_answer') ? '' : 'hidden' ?>">
                        <label class="block mb-1 text-sm text-gray-700">الإجابة المتوقعة</label>
                        <input type="text" name="short_answer" class="w-full border rounded-lg p-2"
                            value="<?= htmlspecialchars($_POST['short_answer'] ?? '') ?>">
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">حفظ</button>
                        <a href="index.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const typeSel = document.getElementById('question_type');
        const mc = document.getElementById('mc_section');
        const tf = document.getElementById('tf_section');
        const sh = document.getElementById('short_section');

        typeSel.addEventListener('change', () => {
            mc.classList.add('hidden'); tf.classList.add('hidden'); sh.classList.add('hidden');
            if (typeSel.value === 'multiple_choice') mc.classList.remove('hidden');
            if (typeSel.value === 'true_false') tf.classList.remove('hidden');
            if (typeSel.value === 'short_answer') sh.classList.remove('hidden');
        });
    </script>
</body>

</html>