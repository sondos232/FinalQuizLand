<?php include("../header.php"); ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عنّا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .icon-hover:hover {
            transform: scale(1.1);
            transition: transform 0.3s ease-in-out;
        }

        .section-image {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
    </style>
</head>

<body class="bg-gray-50">
<section class="pt-32 pb-24 bg-gradient-to-r from-blue-600 to-purple-600 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('../../assets/images/banner/mahila.png');"></div>
    
    <div class="container mx-auto text-center px-4 relative z-10">
        <h2 class="text-5xl font-bold text-white mb-4 tracking-tight leading-tight">من نحن</h2>
        <p class="text-xl mb-6 opacity-90 max-w-2xl mx-auto">نحن هنا لتحفيز التعلم وتقديم اختبارات ثقافية ممتعة وتفاعلية لجميع الأعمار.</p>
        <p class="text-lg max-w-4xl mx-auto">
            مرحبًا بك في منصتنا التعليمية، حيث نقدم لك اختبارات في مختلف المجالات التي يمكنك من خلالها اختبار معرفتك ومهاراتك. 
            نحن فريق من المحترفين الذين يسعون دائمًا لتقديم محتوى تعليمي مميز يساعد في بناء قدرات الطلاب ويوفر بيئة تعليمية مبتكرة.
        </p>
    </div>
    
    <div class="absolute top-0 right-0 w-24 h-24 bg-white rounded-full opacity-20 transform translate-x-24 translate-y-12"></div>
    <div class="absolute bottom-0 left-0 w-36 h-36 bg-white rounded-full opacity-10 transform translate-x-32 translate-y-24"></div>
</section>


    <section class="bg-white py-24">
        <div class="container mx-auto text-center px-4 max-w-screen-xl mx-auto">
            <h2 class="text-4xl font-semibold text-gray-900 mb-8">مهمتنا</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-lg text-gray-700 mb-8 max-w-3xl mx-auto">
                        مهمتنا هي تقديم أفضل تجربة تعليمية للطلاب في جميع أنحاء العالم. نحن نؤمن أن التعلم هو رحلة
                        مستمرة، ونحن هنا لتسهيل هذه الرحلة من خلال اختبارات تعليمية مليئة بالتحديات والفرص. مهمتنا هي
                        تمكين الطلاب من تطوير مهاراتهم واختبار معرفتهم من خلال بيئة تعليمية مبتكرة وآمنة.
                    </p>
                </div>
                <div class="relative">
                    <img src="../../assets/images/banner/mahila.png" alt="Our Mission" class="section-image rounded-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="container mx-auto text-center px-4 max-w-screen-xl mx-auto">
            <h2 class="text-3xl font-semibold text-gray-900 mb-8">قيمنا</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                <div
                    class="flex flex-col items-center bg-white p-8 shadow-lg rounded-xl hover:shadow-2xl transition-transform transform hover:scale-105">
                    <svg class="w-16 h-16 text-blue-600 mb-4 icon-hover" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">الابتكار</h3>
                    <p class="text-gray-600">نحن نسعى دائمًا إلى الابتكار في طرق التعليم، وتقديم طرق جديدة لاختبار
                        وتحفيز المعرفة.</p>
                </div>

                <div
                    class="flex flex-col items-center bg-white p-8 shadow-lg rounded-xl hover:shadow-2xl transition-transform transform hover:scale-105">
                    <svg class="w-16 h-16 text-blue-600 mb-4 icon-hover" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">الشفافية</h3>
                    <p class="text-gray-600">نحن نؤمن بالشفافية في تقديم المعلومات، ونشارك الطلاب بكل ما يحتاجون إليه
                        لتحقيق النجاح.</p>
                </div>

                <div
                    class="flex flex-col items-center bg-white p-8 shadow-lg rounded-xl hover:shadow-2xl transition-transform transform hover:scale-105">
                    <svg class="w-16 h-16 text-blue-600 mb-4 icon-hover" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">الالتزام</h3>
                    <p class="text-gray-600">نلتزم بتقديم محتوى تعليمي عالي الجودة لدعم الطلاب في مسيرتهم التعليمية.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gradient-to-r from-gray-800 to-gray-900 text-white">
        <div class="container mx-auto text-center px-4 max-w-screen-xl mx-auto">
            <h2 class="text-4xl font-semibold text-white mb-8">خدماتنا</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">اختبارات تفاعلية</h3>
                    <p class="text-gray-600">نقدم اختبارات متنوعة في مختلف المجالات من أجل اختبار معرفتك وتنمية مهاراتك.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">تعلم مستمر</h3>
                    <p class="text-gray-600">نحن نساعدك على تحسين مستواك التعليمي باستمرار من خلال التحديات والفرص
                        الجديدة.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">دعم كامل</h3>
                    <p class="text-gray-600">فريقنا هنا دائمًا لدعمك ومساعدتك في تحقيق النجاح على منصتنا التعليمية.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include("../footer.php"); ?>

</body>

</html>