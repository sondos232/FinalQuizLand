<?php include("../header.php"); ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اتصل بنا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body class="bg-gray-100">

    <section class="pt-32 pb-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-white mb-4">اتصل بنا</h2>
            <p class="text-lg mb-6 opacity-80">نحن هنا للمساعدة، لا تتردد في التواصل معنا بأي وقت.</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-white p-8 shadow-lg rounded-xl">
                    <h3 class="text-3xl font-semibold text-gray-900 mb-6">نموذج الاتصال</h3>
                    <form action="send_message.php" method="POST">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">الاسم</label>
                            <input type="text" id="name" name="name" class="w-full p-3 mt-2 border rounded-lg" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" class="w-full p-3 mt-2 border rounded-lg"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block text-gray-700">الموضوع</label>
                            <input type="text" id="subject" name="subject" class="w-full p-3 mt-2 border rounded-lg"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="block text-gray-700">الرسالة</label>
                            <textarea id="message" name="message" rows="4" class="w-full p-3 mt-2 border rounded-lg"
                                required></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                إرسال
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-8 shadow-lg rounded-xl">
                    <h3 class="text-3xl font-semibold text-gray-900 mb-6">معلومات الاتصال</h3>
                    <div class="mb-4">
                        <h4 class="text-xl text-gray-800 mb-2">العنوان</h4>
                        <p class="text-gray-600">شارع صلاح الدين، مدينة غزة، فلسطين</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-xl text-gray-800 mb-2">البريد الإلكتروني</h4>
                        <p class="text-gray-600">contact@quizland.com</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-xl text-gray-800 mb-2">الهاتف</h4>
                        <p class="text-gray-600">+970 8 123 4567</p>
                    </div>

                    <h4 class="text-xl text-gray-800 mb-4">موقعنا</h4>
                    <section class="py-16">
                        <div class="container mx-auto px-4 text-center">
                            <h2 class="text-4xl font-semibold text-gray-900 mb-8">موقعنا في غزة</h2>
                            <div class="relative w-full h-96">
                                <iframe
                                    src="https://www.openstreetmap.org/export/embed.html?bbox=34.4600,31.4900,34.4700,31.5100&layer=mapnik"
                                    style="border: 1px solid black" class="w-full h-full" allowfullscreen=""
                                    loading="lazy"></iframe>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
        </div>
    </section>

    <?php include("../footer.php"); ?>
</body>

</html>