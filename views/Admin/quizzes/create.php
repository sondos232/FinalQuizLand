<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

// Handle form submission for creating a new quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user']['id'];

    // Insert quiz into the database
    $query = "INSERT INTO quizzes (title, category, description, created_by) VALUES ('$title', '$category', '$description', '$created_by')";
    if ($conn->query($query)) {
        header('Location: index.php'); // Redirect back to the quizzes list
        exit();
    } else {
        echo "حدث خطأ أثناء إضافة الاختبار!";
    }
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
    <!-- Main Content -->
    <div class="flex-1 p-6">
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
                    <textarea name="description" id="description" rows="5" class="w-full px-4 py-2 border rounded-md"
                        required></textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md">إضافة الاختبار</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>