<div class="flex h-screen bg-gray-100 dark:bg-gray-900">

    <!-- SIDEBAR -->
    <aside class="w-60 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="p-4 text-center border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">Clinic Dashboard</h1>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                🏠 <span class="ml-2">Dashboard</span>
            </a>

            <a href="{{ route('patients.index') }}"
                class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                👤 <span class="ml-2">Patients</span>
            </a>

            <a href="{{ route('extraoral_examinations.index') }}"
                class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                🦷 <span class="ml-2">Extraoral Exam</span>
            </a>

            <a href="{{ route('intraoral_examinations.index') }}"
                class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                😬 <span class="ml-2">Intraoral Exam</span>
            </a>

            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center px-3 py-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition">
                🚪 <span class="ml-2">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

        </nav>
    </aside>

    <!-- PAGE CONTENT -->
    <main class="flex-1 overflow-y-auto p-6">
        {{ $slot ?? '' }}
    </main>
</div>
