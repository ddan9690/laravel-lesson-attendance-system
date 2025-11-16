<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-48 bg-school-green text-white transform md:static md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col h-full">
    <!-- Logo + Close -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-green-700">
        <h1 class="text-lg font-bold">MNGHS</h1>
        <button @click="sidebarOpen = false" class="md:hidden text-white">
            <i class='bx bx-x text-2xl'></i>
        </button>
    </div>

    <!-- Links -->
    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">

        <!-- Dashboard visible to all authenticated users -->
        <a href="{{ dashboard_route() }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition">
            <i class='bx bxs-dashboard mr-3 text-xl'></i> Dashboard
        </a>

        <!-- Teachers link visible only to users with 'manage_users' permission -->
        @can('manage_users')
            <a href="{{ route('teachers.index') }}"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition">
                <i class='bx bx-user mr-3 text-xl'></i> Teachers
            </a>
        @endcan

        @can('manage_classes')
            <a href="{{ route('classes.index') }}"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition">
                <i class='bx bx-buildings mr-3 text-xl'></i> Classes
            </a>
        @endcan

        @can('manage_curricula')
            <a href="{{ route('subjects.index') }}"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition">
                <i class='bx bx-book mr-3 text-xl'></i> Subjects / Learning Areas
            </a>
        @endcan

        @can('payment_capture')
            <a href="{{ route('remedial.index') }}"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition">
                <i class='bx bx-money mr-3 text-xl'></i> Remedial Management
            </a>
        @endcan




    </nav>
</aside>
