<!-- Header Partial: resources/views/partials/header.blade.php -->
<header
    class="flex items-center justify-between bg-school-green shadow px-4 py-2 border-b border-green-700 relative z-20">
    <!-- Left: Hamburger for mobile -->
    <button @click="sidebarOpen = !sidebarOpen" class="text-white md:hidden p-1 rounded hover:bg-green-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Center: Brand / Logo -->
    <div class="text-white font-bold text-base md:text-lg">
        Remedial Management System
    </div>

    <!-- Right: User Dropdown -->
    <div x-data="{ open: false }" class="relative text-white">
        <!-- User Icon -->
        <button @click="open = !open" class="flex items-center focus:outline-none">
            <i class='bx bx-user-circle text-2xl md:text-3xl'></i>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded shadow-lg py-2 z-50"
            style="display: none;">

            <!-- User Name -->
            <div class="px-4 py-1 border-b border-gray-200 font-bold">
                {{ Auth::user()->name }}
            </div>

            <!-- Menu Items -->
            <a href="{{ route('teacher.changepassword') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                Change Password
            </a>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="block px-4 py-2 text-sm hover:bg-gray-100">Logout</a>

            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</header>
