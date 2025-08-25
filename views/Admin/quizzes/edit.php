<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$quizId = $_GET['id']; // Get the quiz ID from the URL

// Fetch the quiz data from the database
$query = "SELECT * FROM quizzes WHERE id = '$quizId'";
$result = $conn->query($query);
$quiz = $result->fetch_assoc();

// Handle form submission for editing a quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Update quiz in the database
    $query = "UPDATE quizzes SET title = '$title', category = '$category', description = '$description' WHERE id = '$quizId'";
    if ($conn->query($query)) {
        header('Location: index.php'); // Redirect back to the quizzes list
        exit();
    } else {
        echo "حدث خطأ أثناء تعديل الاختبار!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الاختبار</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include '../sidebar.php'; ?>
        <div class="flex-1 p-6">
            <div class="bg-white shadow-md p-6 rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800">تعديل الاختبار</h2>

                <form action="edit.php?id=<?= $quiz['id'] ?>" method="POST" class="mt-6">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">العنوان</label>
                        <input type="text" name="title" id="title" value="<?= $quiz['title'] ?>"
                            class="w-full px-4 py-2 border rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-gray-700">الفئة</label>
                        <input type="text" name="category" id="category" value="<?= $quiz['category'] ?>"
                            class="w-full px-4 py-2 border rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">الوصف</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full px-4 py-2 border rounded-md" required><?= $quiz['description'] ?></textarea>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md">تحديث
                            الاختبار</button>
                                                    <a href="index.php" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            العودة للقائمة
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>