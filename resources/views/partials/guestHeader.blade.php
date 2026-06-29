<header class="sticky top-0 z-50 bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="text-2xl font-extrabold text-primary tracking-tight">
            <span class="text-secondary">Kasih</span> Istimewa
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-10">
            <a href="#about" class="text-base font-medium text-gray-500 hover:text-primary">About Us</a>
            <a href="#events" class="text-base font-medium text-gray-500 hover:text-primary">Events</a>
            <a href="#impact" class="text-base font-medium text-gray-500 hover:text-primary">Our Impact</a>
            <a href="#contact" class="text-base font-medium text-gray-500 hover:text-primary">Contact</a>
        </nav>

        <!-- Desktop Auth -->
        <div class="hidden md:flex space-x-4 items-center">
            <a href="{{ route('login') }}" class="px-4 py-2 text-base text-gray-600 hover:text-primary">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-secondary text-white hover:bg-blue-600">
                Sign Up
            </a>
        </div>

        <!-- Mobile Hamburger -->
        <button id="mobileMenuBtn" class="md:hidden focus:outline-none">
            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Dropdown -->
    <div id="mobileMenu" class="hidden md:hidden flex flex-col bg-white border-t border-gray-200">
        <a href="#about" class="py-3 px-6 text-gray-700 hover:bg-gray-100">About Us</a>
        <a href="#events" class="py-3 px-6 text-gray-700 hover:bg-gray-100">Events</a>
        <a href="#impact" class="py-3 px-6 text-gray-700 hover:bg-gray-100">Our Impact</a>
        <a href="#contact" class="py-3 px-6 text-gray-700 hover:bg-gray-100">Contact</a>
        <a href="{{ route('login') }}" class="py-3 px-6 text-gray-700 hover:bg-gray-100">Login</a>
        <a href="{{ route('register') }}" class="py-3 px-6 text-gray-700 hover:bg-gray-100">Sign Up</a>
    </div>

</header>

<script>
    document.getElementById('mobileMenuBtn').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>
