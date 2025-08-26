<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $created_by = $_SESSION['user']['id'];

    $image = NULL;
    if (isset($_FILES['quiz_image']) && $_FILES['quiz_image']['error'] == 0) {
        $upload_dir = '../../../assets/images/quizzes/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = uniqid('quiz_', true) . '.' . pathinfo($_FILES['quiz_image']['name'], PATHINFO_EXTENSION);
        $target_file = $upload_dir . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['quiz_image']['tmp_name'], $target_file)) {
                $image = 'assets/images/quizzes/' . $image_name;
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file type.";
            exit();
        }
    }

    $query = "INSERT INTO quizzes (title, category, created_by, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssis", $title, $category, $created_by, $image);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة اختبار جديد</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex">
        <?php include '../sidebar.php'; ?>
        <div class="flex-1 p-6 md:mr-64">
            <div class="bg-white shadow-md p-6 rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800">إضافة اختبار جديد</h2>

                <form action="create.php" method="POST" class="mt-6">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">العنوان</label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-gray-700">الفئة</label>
                        <input type="text" name="category" id="category" class="w-full px-4 py-2 border rounded-md"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">الوصف</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full px-4 py-2 border rounded-md" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="quiz_image" class="block text-lg font-medium text-gray-700">صورة الاختبار</label>
                        <input type="file" name="quiz_image" id="quiz_image"
                            class="w-full p-3 mt-2 border border-gray-300 rounded-md" accept="image/*">
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md">إضافة
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