<header class="sticky top-0 z-50 bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Mobile Layout - Flexbox for proper alignment -->
        <div class="flex justify-between items-center py-4 md:hidden">
            <!-- Mobile Logo -->
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/KasihIstimewa-KI-Logo.png') }}" alt="Kasih Istimewa" class="h-8 w-auto">
            </a>
            
            <!-- Mobile Hamburger Button - Right aligned -->
            <button id="mobileMenuBtn" class="focus:outline-none">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Desktop Layout - Hidden on mobile -->
        <div class="hidden md:grid md:grid-cols-3 items-center py-4">
            <!-- Desktop Logo -->
            <a href="{{ url('/') }}" class="text-2xl font-extrabold text-primary tracking-tight justify-self-start">
                <span class="text-secondary">Kasih</span> Istimewa
            </a>

            <!-- Desktop Menu -->
            <nav class="flex justify-center space-x-6 lg:space-x-10 whitespace-nowrap">
                <a href="{{ route('dashboard') }}" class="text-base font-medium text-gray-500 hover:text-primary inline-block">Dashboard</a>
                <a href="{{ route('user.donations') }}" class="text-base font-medium text-gray-500 hover:text-primary inline-block">My Donation</a>
                
                <!-- Events Dropdown -->
                <div class="relative group inline-block">
                    <button class="text-base font-medium text-gray-500 hover:text-primary focus:outline-none flex items-center space-x-1 whitespace-nowrap">
                        <span>Events</span>
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="{{ route('user.upcomingevents') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary">
                            <i class="fas fa-calendar-alt mr-2 text-primary"></i> Upcoming Events
                        </a>
                        <a href="{{ route('user.myEvents') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary">
                            <i class="fas fa-calendar-check mr-2 text-secondary"></i> My Events
                        </a>
                        <a href="{{ route('user.events.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary">
                            <i class="fas fa-plus-circle mr-2 text-green-500"></i> Create Event
                        </a>
                    </div>
                </div>
                
                <a href="#" class="text-base font-medium text-gray-500 hover:text-primary inline-block">Volunteers</a>
            </nav>

            <!-- Desktop Auth - Avatar with Dropdown -->
            <div class="flex space-x-4 items-center justify-self-end">
                 @auth
                    <div class="relative">
                        <button id="userMenuBtn" class="focus:outline-none flex items-center space-x-2">
                            <div class="flex items-center space-x-3">
                                <x-avatar :user="Auth::user()" size="40" />
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                            <a href="{{ route('user.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i> My Profile
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-base text-gray-600 hover:text-primary">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-secondary text-white hover:bg-blue-600">
                        Sign Up
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-lg">
        <div class="px-4 py-2 space-y-1">
            <a href="{{ route('dashboard') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <i class="fas fa-tachometer-alt mr-3 text-primary w-5"></i> Dashboard
            </a>
            <a href="{{ route('user.donations') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <i class="fas fa-hand-holding-heart mr-3 text-secondary w-5"></i> My Donation
            </a>
            
            <!-- Mobile Events Section -->
            <div>
                <button id="mobileEventsBtn" class="w-full flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <div>
                        <i class="fas fa-calendar-alt mr-3 text-primary w-5"></i> Events
                    </div>
                    <svg id="mobileEventsIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="mobileEventsSubmenu" class="hidden ml-8 space-y-1 border-l-2 border-gray-200 pl-4">
                    <a href="{{ route('user.upcomingevents') }}" class="block py-2 px-4 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-calendar-alt mr-2 text-primary"></i> Upcoming Events
                    </a>
                    <a href="{{ route('user.myEvents') }}" class="block py-2 px-4 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-calendar-check mr-2 text-secondary"></i> My Events
                    </a>
                    <a href="{{ route('user.events.create') }}" class="block py-2 px-4 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-plus-circle mr-2 text-green-500"></i> Create Event
                    </a>
                </div>
            </div>
            
            <a href="#" class="block py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <i class="fas fa-users mr-3 w-5"></i> Volunteers
            </a>
            
            <hr class="my-2 border-gray-200">
            
            @auth
                <a href="{{ route('user.profile.index') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-user-circle mr-3 w-5"></i> My Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left py-3 px-4 text-red-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-3 w-5"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-sign-in-alt mr-3 w-5"></i> Login
                </a>
                <a href="{{ route('register') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-user-plus mr-3 w-5"></i> Sign Up
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    let isMobileMenuOpen = false;
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            isMobileMenuOpen = !isMobileMenuOpen;
            if (isMobileMenuOpen) {
                mobileMenu.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Mobile events submenu toggle - FIXED: Keeps menu open
    const mobileEventsBtn = document.getElementById('mobileEventsBtn');
    const mobileEventsSubmenu = document.getElementById('mobileEventsSubmenu');
    const mobileEventsIcon = document.getElementById('mobileEventsIcon');
    let isEventsOpen = false;
    
    if (mobileEventsBtn) {
        mobileEventsBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            isEventsOpen = !isEventsOpen;
            if (isEventsOpen) {
                mobileEventsSubmenu.classList.remove('hidden');
                mobileEventsIcon.classList.add('rotate-180');
            } else {
                mobileEventsSubmenu.classList.add('hidden');
                mobileEventsIcon.classList.remove('rotate-180');
            }
        });
    }

    // User dropdown toggle (desktop only)
    const userBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userBtn) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
    }

    // Close dropdown when clicking outside (desktop only)
    window.addEventListener('click', function(event) {
        if (userDropdown && userBtn && !userBtn.contains(event.target)) {
            userDropdown.classList.add('hidden');
        }
    });
    
    // Close mobile menu when clicking a link - FIXED: Doesn't close when clicking events button
    const mobileLinks = document.querySelectorAll('#mobileMenu a:not(#mobileEventsBtn)');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            isMobileMenuOpen = false;
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (isMobileMenuOpen && mobileMenu && mobileMenuBtn) {
            const isClickInside = mobileMenu.contains(event.target) || mobileMenuBtn.contains(event.target);
            if (!isClickInside) {
                mobileMenu.classList.add('hidden');
                isMobileMenuOpen = false;
            }
        }
    });
</script>