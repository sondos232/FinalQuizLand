<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/signin.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$query = "SELECT * FROM users WHERE id = $userId";
$result = $conn->query($query);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $image = $user['image'];

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = '../../assets/images/profiles/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if ($image && file_exists('../../' . $image)) {
            unlink('../../' . $image);
        }

        $image_name = uniqid('user_', true) . '.' . pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $image = 'assets/images/profiles/' . $image_name;
        } else {
            echo "حدث خطأ أثناء رفع الصورة.";
            exit();
        }
    }

    $updateQuery = "UPDATE users SET username = '$username', email = '$email', image = '$image' WHERE id = $userId";
    if ($conn->query($updateQuery)) {
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['image'] = $image;

        header('Location: profile.php');
        exit();
    } else {
        echo "حدث خطأ أثناء تحديث الملف الشخصي!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الملف الشخصي</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8 mt-24">
        <div class="bg-white shadow-lg rounded-xl p-6 max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">تعديل الملف الشخصي</h2>
            <form action="edit-profile.php" method="POST" enctype="multipart/form-data">

                <div class="mb-4">
                    <label for="username" class="block text-gray-700">الاسم</label>
                    <input type="text" name="username" id="username " value="<?= htmlspecialchars($user['username']) ?>"
                        class="w-full px-4 py-2 border rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
                        class="w-full px-4 py-2 border rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="profile_image" class="block text-gray-700">صورة الملف الشخصي</label>
                    <input type="file" name="profile_image" id="profile_image"
                        class="w-full px-4 py-2 border rounded-md">
                    <div class="mt-2 text-sm text-gray-500">يتم استخدام صورة الملف الشخصي الحالية إذا لم يتم تحميل صورة
                        جديدة.</div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md">تحديث الملف
                        الشخصي</button>
                    <a href="profile.php"
                        class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">إلغاء</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>