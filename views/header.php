<header class="fixed top-0 inset-x-0 bg-white z-[500] shadow-md">
    <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4 py-8 flex justify-between items-center">
        <div class="flex items-center">
            <div class="md:hidden flex items-center ml-3">
                <button id="hamburger" onclick="toggleMobileMenu()">
                    <svg class="w-8 h-8 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </button>
            </div>

            <div>
                <img src="/QuizLand/assets/images/logo/logo.svg" class="w-24 sm:w-36" alt="شعار QuizLand">
            </div>
        </div>

        <div class="hidden md:flex space-x-8 items-center">
            <a href="/QuizLand/views/home" class="text-gray-700 hover:text-blue-500 ml-8">الرئيسية</a>
            <a href="/QuizLand/views/home/quizzes.php" class="text-gray-700 hover:text-blue-500">الاختبارات</a>
            <a href="/QuizLand/views/home/about.php" class="text-gray-700 hover:text-blue-500">من نحن</a>
            <a href="/QuizLand/views/home/contact.php" class="text-gray-700 hover:text-blue-500">اتصل بنا</a>
        </div>

        <div class="relative">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $userImage = isset($user['image']) && !empty($user['image']) ? '/QuizLand/'+$user['image'] : 'https://dovercourt.org/wp-content/uploads/2019/11/610-6104451_image-placeholder-png-user-profile-placeholder-image-png.jpg';
                echo '
                <div class="flex items-center cursor-pointer" id="user-profile" onclick="toggleDropdown()">
                    <img src="'. $userImage .'" alt="صورة المستخدم" class="w-8 h-8 rounded-full object-cover ml-2">
                    <span id="username" class="text-gray-700">
                        ' . htmlspecialchars($user['username']) . '
                    </span>
                </div>

                <div id="dropdown" class="absolute right-0 hidden bg-white border border-gray-200 shadow-md rounded-md w-36 mt-2">
                    <a href="/QuizLand/views/home/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                    <a href="/QuizLand/views/auth/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">تسجيل الخروج</a>
                </div>';
            } else {
                echo '
                <div class="flex">
                    <a href="/QuizLand/views/auth/signin.php" class="sm:px-4 p-1 text-sm sm:py-2 text-gray-700 bg-blue-500 hover:bg-blue-600 text-white rounded-md ml-1 sm:ml-4">تسجيل الدخول</a>
                    <a href="/QuizLand/views/auth/signup.php" class="sm:px-4 p-1 text-sm sm:py-2 text-gray-700 bg-green-500 hover:bg-green-600 text-white rounded-md">التسجيل</a>
                </div>';
            } ?>
        </div>

    </div>

    <div id="mobile-menu"
        class="md:hidden hidden absolute top-20 left-0 right-0 bg-white shadow-md border border-gray-200 rounded-md w-full">
        <div class="flex flex-col items-center py-4 space-y-4">
            <a href="/QuizLand/views/home" class="text-gray-700 hover:text-blue-500">الرئيسية</a>
            <a href="/QuizLand/views/home/quizzes.php" class="text-gray-700 hover:text-blue-500">الاختبارات</a>
            <a href="/QuizLand/views/home/about.php" class="text-gray-700 hover:text-blue-500">من نحن</a>
            <a href="/QuizLand/views/home/contact.php" class="text-gray-700 hover:text-blue-500">اتصل بنا</a>
        </div>
    </div>
</header>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('hidden');
    }

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    }
</script>