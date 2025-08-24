<?php
include("../../config/db.php");

$query = "SELECT id, title, category, created_at FROM quizzes";
$result = $conn->query($query);
$quizzes = [];

while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/swiper@6.8.4/swiper-bundle.min.css" rel="stylesheet" />
</head>

<body>
    <?php include '../header.php'; ?>

    <section id="home-section" class="bg-slate-200 mt-16">
        <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4 pt-20 pb-14">
            <div class="grid grid-cols-1 lg:grid-cols-12 space-x-1 items-center">
                <div class="col-span-6 flex flex-col gap-8">
                    <div class="flex gap-2 mx-auto items-center lg:mx-0">
                        <svg class="w-[20px] h-[20px] fill-green-500" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z"
                                clip-rule="evenodd" />
                        </svg>

                        <p class="text-green-500 text-sm font-semibold text-center lg:text-start">احصل على خصم 30% عند
                            التسجيل الأول في اختباراتنا</p>
                    </div>
                    <h1 class="text-gray-900 text-4xl sm:text-5xl font-semibold pt-5 lg:pt-0">اختبر مهاراتك الثقافية
                        معنا.</h1>
                    <h3 class="text-gray-700 text-lg pt-5 lg:pt-0">ابدأ رحلتك في تعلم أشياء جديدة من خلال اختباراتنا
                        المتنوعة في جميع المجالات.</h3>
                    <div class="relative rounded-full pt-5 lg:pt-0">
                        <input type="email" name="q"
                            class="py-6 lg:py-8 pl-8 pr-20 text-lg w-full text-black rounded-full focus:outline-none shadow-xl  shadow-purple-100/90"
                            placeholder="ابحث عن اختبارات ثقافة عامة..." autocomplete="off" />
                        <button class="bg-blue-500 p-5 rounded-full absolute left-2 top-7 lg:top-2">
                            <svg class="w-[22px] lg:w-[38px] h-[22px] lg:h-[38px] text-gray-800 dark:text-white"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2.3"
                                    d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-around pt-10 lg:pt-4">
                        <div class="flex gap-2">
                            <img src="../../assets/images/banner/check-circle.svg" alt="check-image" width="30"
                                height="30" class="smallImage" />
                            <p class="text-sm sm:text-lg font-normal text-black">مرن</p>
                        </div>
                        <div class="flex gap-2">
                            <img src="../../assets/images/banner/check-circle.svg" alt="check-image" width="30"
                                height="30" class="smallImage" />
                            <p class="text-sm sm:text-lg font-normal text-black">مسار تعليمي متكامل</p>
                        </div>
                        <div class="flex gap-2">
                            <img src="../../assets/images/banner/check-circle.svg" alt="check-image" width="30"
                                height="30" class="smallImage" />
                            <p class="text-sm sm:text-lg font-normal text-black">مجتمع داعم</p>
                        </div>
                    </div>

                </div>
                <div class="col-span-6 flex justify-center">
                    <img src="../../assets/images/banner/mahila.png" alt="nothing" width="1000" height="805" />
                </div>
            </div>

        </div>
    </section>

    <section id="courses" class="mt-14">
        <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4">
            <div class="sm:flex justify-between items-center mb-20">
                <h2 class="text-midnight_text text-4xl lg:text-5xl font-semibold mb-5 sm:mb-0">الدورات الشعبية</h2>
                <a href="/" class="text-primary text-lg font-medium hover:tracking-widest duration-500">استكشاف
                    الدورات&nbsp;&gt;&nbsp;</a>
            </div>

            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($quizzes as $quiz): ?>
                        <div class="swiper-slide">
                            <div class="slick-slider">
                                <div class="course-item shadow-2xl rounded-2xl">
                                    <div class="bg-white m-3 mb-12 px-3 pt-3 pb-12 shadow-course-shadow rounded-2xl h-full">
                                        <div class="relative rounded-3xl">
                                            <img src="../../assets/images/courses/courseone.png" alt="course-image"
                                                width="389" height="262" class="m-auto clipPath">
                                            <div class="absolute right-5 -bottom-2 bg-secondary rounded-full p-6">
                                                <h3 class="text-white uppercase text-center text-sm font-medium">الأفضل
                                                    مبيعًا
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="px-3 pt-6">
                                            <a href="quiz-details.php?quiz_id=<?= $quiz['id'] ?>"
                                                class="text-2xl font-bold text-black max-w-75% inline-block"><?= $quiz['title'] ? $quiz['title'] : 'عنوان الدورة' ?></a>

                                            <h3 class="text-base font-normal pt-6 text-black/75">
                                                <?= $quiz['category'] ? $quiz['category'] : 'الفئة' ?>
                                            </h3>
                                            <div class="flex justify-between items-center py-6 border-b">
                                                <div class="flex items-center gap-4">
                                                    <h3 class="text-red-700 text-2xl font-medium">4.5</h3>
                                                    <div class="flex">
                                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                                            <svg class="w-[33px] h-[33px] fill-yellow-600" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                fill="currentColor" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <h3 class="text-3xl font-medium">$99</h3>
                                            </div>
                                            <div class="flex justify-between pt-6">
                                                <div class="flex gap-4">
                                                    <svg class="w-[33px] h-[33px] fill-blue-300" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="1.1"
                                                            d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023" />
                                                    </svg>
                                                    <h3 class="text-base font-medium text-black opacity-75">12 درسًا</h3>
                                                </div>
                                                <div class="flex gap-4">
                                                    <svg class="w-[33px] h-[33px] fill-blue-300" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd"
                                                            d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <h3 class="text-base font-medium text-black opacity-75">150 طالبًا</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-blue-100 py-16" id="mentor">
        <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4 relative">
            <h2 class="text-midnight_text text-5xl font-semibold">تعرف على مرشدينا.</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <!-- Mentor 1 -->
                <div class="m-3 py-14 text-center">
                    <div class="relative">
                        <img src="../../assets/images/mentor/user1.png" alt="صورة المستخدم" width="306"
                            class="inline-block m-auto" />
                        <div class="absolute right-[84px] bottom-[102px] bg-white rounded-full p-2">
                            <svg class="w-[35px] h-[35px] fill-blue-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                    clip-rule="evenodd" />
                                <path d="M7.2 8.809H4V19.5h3.2V8.809Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="-mt-10">
                        <h3 class="text-2xl font-semibold text-lightblack">أحمد السيد</h3>
                        <h4 class="text-lg font-normal text-lightblack pt-2 opacity-50">مهندس برمجيات</h4>
                    </div>
                </div>

                <!-- Mentor 2 -->
                <div class="m-3 py-14 text-center">
                    <div class="relative">
                        <img src="../../assets/images/mentor/user2.png" alt="صورة المستخدم" width="306"
                            class="inline-block m-auto" />
                        <div class="absolute right-[84px] bottom-[102px] bg-white rounded-full p-2">
                            <svg class="w-[35px] h-[35px] fill-blue-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                    clip-rule="evenodd" />
                                <path d="M7.2 8.809H4V19.5h3.2V8.809Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="-mt-10">
                        <h3 class="text-2xl font-semibold text-lightblack">سارة محمود</h3>
                        <h4 class="text-lg font-normal text-lightblack pt-2 opacity-50">عالمة بيانات</h4>
                    </div>
                </div>

                <!-- Mentor 3 -->
                <div class="m-3 py-14 text-center">
                    <div class="relative">
                        <img src="../../assets/images/mentor/user3.png" alt="صورة المستخدم" width="306"
                            class="inline-block m-auto" />
                        <div class="absolute right-[84px] bottom-[102px] bg-white rounded-full p-2">
                            <svg class="w-[35px] h-[35px] fill-blue-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                    clip-rule="evenodd" />
                                <path d="M7.2 8.809H4V19.5h3.2V8.809Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="-mt-10">
                        <h3 class="text-2xl font-semibold text-lightblack">علي حسن</h3>
                        <h4 class="text-lg font-normal text-lightblack pt-2 opacity-50">مدير منتج</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4">
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 md:grid-cols-12 xl:gap-x-8">
                <div class="col-span-12 bg-newsletter-bg-2 bg-contain bg-no-repeat bg-cover lg:bg-contain"
                    style="background-image: url('../../assets/images/newsletter/bgFile.png'); ">
                    <div class="mb-10 mt-24 lg:mx-64 lg:my-24">
                        <h3 class="text-4xl md:text-5xl text-center font-semibold text-white mb-3">النشرة الإخبارية.
                        </h3>
                        <h3 class="text-base font-normal text-white/75 text-center mb-8">
                            اشترك في نشرتنا الإخبارية للحصول على خصومات، <br /> عروض ترويجية والكثير غيرها.
                        </h3>
                        <div>
                            <div
                                class="relative text-white focus-within:text-white flex flex-row-reverse rounded-full pt-5 lg:pt-0">
                                <input type="email" name="q"
                                    class="py-6 px-4 lg:py-8 text-sm md:text-lg w-full mx-3 text-black rounded-full pl-8 focus:outline-none focus:text-black"
                                    placeholder="أدخل عنوان بريدك الإلكتروني" autocomplete="off" />
                                <div class="absolute inset-y-0 left-5 flex items-center pr-6 pt-5 lg:pt-0">
                                    <button type="submit"
                                        class="p-3 lg:p-5 focus:outline-none bg-gray-200 focus:shadow-outline bg-ultramarine hover:bg-midnightblue duration-150 ease-in-out rounded-full">
                                        <img src="../../assets/images/newsletter/send.svg" alt="send-icon" width="30"
                                            height="30" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../footer.php'; ?>


    <script src="https://unpkg.com/swiper@6.8.4/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            slidesPerView: 3,
            spaceBetween: 30,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    </script>
</body>

</html>