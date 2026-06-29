<header class="sticky top-0 z-30 flex items-center justify-between p-4 bg-bg-light shadow-md h-16">
    <div class="flex items-center space-x-4">
        <!-- Mobile Hamburger -->
        <button id="hamburger" class="md:hidden text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <!-- Kasih Istimewa Logo -->
        <span class="logo-text-full text-2xl font-bold hidden md:block">
            <img src="{{ asset('images/Kasih_Istimewa_Text.png') }}" alt="Kasih Istimewa" style="height:20px; display:inline-block; vertical-align:baseline;">
        </span>
    </div>
    
    <div class="flex-1"></div>

    

         <!-- User Profile -->
        <div class="flex items-center space-x-3">
            <span class="hidden sm:inline text-gray-700">
                Welcome, {{ Auth::user()->user_name ?? 'Admin' }}
            </span>
            <x-avatar :user="Auth::user()" size="40" />
        </div>



</header>
