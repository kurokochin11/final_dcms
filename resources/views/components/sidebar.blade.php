<!-- Layout wrapper -->
<div class="flex h-screen bg-gray-100 dark:bg-gray-900">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-md flex-shrink-0">
        <!-- Mobile toggle (optional) -->
        <div class="p-4 border-b flex items-center justify-between lg:hidden">
            <span class="font-semibold text-sm text-gray-800 dark:text-gray-100">Menu</span>
            <!-- Toggle requires Alpine; remove the x-data/x-show if you don't use Alpine -->
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 dark:text-gray-300" aria-label="Toggle sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <!-- Nav (use overflow-y-auto for scrolling) -->
        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]" aria-label="Main navigation">
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                <span class="ml-2">Dashboard</span>
            </a>

            <a href="{{ route('patients.index') }}"
               class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                <span class="ml-2">Patients</span>
            </a>

            <a href="{{ route('extraoral_examinations.index') }}"
               class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                <span class="ml-2">Extraoral Exam</span>
            </a>

            <a href="{{ route('intraoral_examinations.index') }}"
               class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                <span class="ml-2">Intraoral Exam</span>
            </a>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center px-3 py-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition">
                <span class="ml-2">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </nav>
    </aside>

    <!-- PAGE CONTENT -->
    <main class="flex-1 overflow-y-auto p-6">
        {{ $slot ?? '' }}
    </main>
</div>
