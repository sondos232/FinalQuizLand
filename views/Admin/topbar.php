<div class="bg-blue-700 shadow-md flex items-center justify-between px-6 py-4">
    <div class="flex items-center justify-between">
        <button class="text-white md:hidden ml-4" id="hamburger-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
        <span class="md:text-xl font-semibold text-white">لوحة تحكم المدير</span>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <a href="/QuizLand/views/auth/logout.php"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md ml-6 sm:ml-0">تسجيل
                الخروج</a>
        </div>
    </div>
</div>
<script>
    const btn = document.getElementById('hamburger-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    btn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.remove('hidden');
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.add('hidden');
        overlay.classList.add('hidden');
    });
</script>