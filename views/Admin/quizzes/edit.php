<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$quizId = $_GET['id'];

$query = "SELECT * FROM quizzes WHERE id = '$quizId'";
$result = $conn->query($query);
$quiz = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $image = $quiz['image'];

    if (isset($_FILES['quiz_image']) && $_FILES['quiz_image']['error'] == 0) {
        $upload_dir = '../../../assets/images/quizzes/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if ($image) {
            $old_image_path = '../../../' . $image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }

        $image_name = uniqid('quiz_', true) . '.' . pathinfo($_FILES['quiz_image']['name'], PATHINFO_EXTENSION);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['quiz_image']['tmp_name'], $target_file)) {
            $image = 'assets/images/quizzes/' . $image_name;
        } else {
            echo "حدث خطأ أثناء رفع الصورة.";
            exit();
        }
    }

    $query = "UPDATE quizzes SET title = '$title', category = '$category', description = '$description', image = '$image' WHERE id = '$quizId'";
    if ($conn->query($query)) {
        header('Location: index.php');
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
        <div class="flex-1 p-6 md:mr-64">
            <div class="bg-white shadow-md p-6 rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800">تعديل الاختبار</h2>

                <form action="edit.php?id=<?= $quiz['id'] ?>" method="POST" enctype="multipart/form-data" class="mt-6">
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

                    <div class="mb-4">
                        <label for="quiz_image" class="block text-gray-700">صورة الاختبار (اختياري)</label>
                        <input type="file" name="quiz_image" id="quiz_image" class="w-full px-4 py-2 border rounded-md"
                            accept="image/*">
                        <?php if ($quiz['image']): ?>
                            <p class="text-sm text-gray-600 mt-2">الصورة الحالية:</p>
                            <img src="../../../<?= $quiz['image'] ?>" alt="Quiz Image"
                                class="w-32 h-32 object-cover mt-2 rounded-md">
                        <?php endif; ?>
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