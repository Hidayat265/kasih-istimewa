<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 flex flex-col bg-primary text-white h-screen w-64 overflow-hidden transform -translate-x-full md:translate-x-0">

    <!-- Logo -->
    <div class="flex items-center justify-between p-7 h-16 shadow-lg flex-shrink-0">
        <a href="#" class="flex items-center space-x-2">
            <span class="logo-icon-small hidden"></span>
            <span class="logo-text-full text-2xl font-bold"><span class="text-white">Navigation</span></span>
        </a>
        <!-- Desktop Toggle Button -->
        <button id="sidebar-toggle" class="hidden md:block text-white hover:text-secondary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <!-- Mobile close button -->
        <button id="close-sidebar-btn" class="md:hidden text-white hover:text-secondary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Nav Links -->
    <nav class="flex-1 flex flex-col mt-4 space-y-2 p-2 overflow-hidden">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('admin.dashboard') ? 'bg-secondary/30 text-white' : 'text-gray-300' }} hover:bg-secondary/20 hover:text-white rounded-md flex-shrink-0">
            <i class="fas fa-tachometer-alt p-1 w-6 text-center"></i>
            <span class="ml-3 nav-link-text">Dashboard</span>
        </a>

        

        <!-- Manage Events Dropdown -->
        <div class="flex flex-col flex-shrink-0">
            <button id="events-dropdown-btn" class="flex items-center justify-between px-4 py-3 w-full text-gray-300 hover:bg-secondary/20 hover:text-white rounded-md focus:outline-none">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt p-1 w-6 text-center"></i>
                    <span class="ml-3 nav-link-text">Events</span><span class="ml-1 nav-link-text">Management</span>
                </div>
                <i id="events-dropdown-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
            </button>
            <div id="events-dropdown-menu" class="ml-10 mt-2 flex flex-col space-y-1 hidden">
                <!-- Pending Events -->
                <a href="{{ route('admin.pendingevent') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary/20 hover:text-white rounded-md {{ request()->routeIs('admin.pendingevent') ? 'bg-secondary/30 text-white' : '' }}">
                    <i class="fas fa-hourglass-half w-5"></i>
                    <span class="ml-2 nav-link-text">Pending Events</span>
                </a>
                <!-- Upcoming Events -->
                <a href="{{ route('admin.upcomingevent') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary/20 hover:text-white rounded-md {{ request()->routeIs('admin.upcomingevent') ? 'bg-secondary/30 text-white' : '' }}">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span class="ml-2 nav-link-text">Upcoming Events</span>
                </a>
                <!-- Past Events -->
                <a href="{{ route('admin.pastevent') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary/20 hover:text-white rounded-md {{ request()->routeIs('admin.pastevent') ? 'bg-secondary/30 text-white' : '' }}">
                    <i class="fas fa-history w-5"></i>
                    <span class="ml-2 nav-link-text">Past Events</span>
                </a>
            </div>
        </div>

        <!-- Donations -->
        <a href="{{ route('admin.donations') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.donations') ? 'bg-secondary/30 text-white' : 'text-gray-300' }} hover:bg-secondary/20 hover:text-white rounded-md flex-shrink-0">
            <i class="fas fa-hand-holding-usd p-1 w-6 text-center"></i>
            <span class="ml-3 nav-link-text">Monetary</span>
        </a>

        <!-- Admins -->
        <a href="{{ route('admin.admins.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.admins.index') ? 'bg-secondary/30 text-white' : 'text-gray-300' }} hover:bg-secondary/20 hover:text-white rounded-md flex-shrink-0">
            <i class="fas fa-user-shield p-1 w-6 text-center"></i>
            <span class="ml-3 nav-link-text">Admins</span>
        </a>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('admin.users.index') ? 'bg-secondary/30 text-white' : 'text-gray-300' }} hover:bg-secondary/20 hover:text-white rounded-md flex-shrink-0">
            <i class="fas fa-users p-1 w-6 text-center"></i>
            <span class="ml-3 nav-link-text">Users</span>
        </a>

        <!-- Spacer to push divider and logout to bottom -->
        <div class="flex-1"></div>

        <!-- Divider Line -->
        <div class="border-t border-white/20 my-2 flex-shrink-0"></div>

        <!-- Profile -->
        <a href="{{ route('admin.profile.index') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('admin.profile.index') ? 'bg-secondary/30 text-white' : 'text-gray-300' }} hover:bg-secondary/20 hover:text-white rounded-md flex-shrink-0">
            <i class="fas fa-user-circle p-1 w-6 text-center"></i>
            <span class="ml-3 nav-link-text">My</span><span class="ml-1 nav-link-text">Profile</span>
        </a>

        <!-- Logout -->
        <div class="flex-shrink-0">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-500/20 hover:text-red-300 rounded-md transition-colors duration-200">
                <i class="fas fa-sign-out-alt p-1 w-6 text-center"></i>
                <span class="ml-3 nav-link-text">Logout</span>
            </a>
        </div>
    </nav>
</aside>

