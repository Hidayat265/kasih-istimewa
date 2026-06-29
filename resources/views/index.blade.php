<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('KasihIstimewa-KI-icon.ico') }}">
    <title>Kasih Istimewa - Making a Difference in Special Needs Lives</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Custom Font and base styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
            color: #1f2937;
        }
        /* Custom utilities */
        .shadow-soft {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .text-gradient {
            background-image: linear-gradient(to right, #554994, #CB80AB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Swiper custom styles */
        .swiper-button-next, .swiper-button-prev {
            color: #554994;
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 2rem !important;
            font-weight: bold;
        }

        /* Remove spinner arrows ONLY for this input */
        input#donor_phone_number::-webkit-inner-spin-button,
        input#donor_phone_number::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input#donor_phone_number {
            -moz-appearance: textfield;
        }

        /* Floating animation for hero image */
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Pulse animation for stat numbers */
        .stat-number {
            transition: all 0.3s ease;
        }
        .stat-number:hover {
            transform: scale(1.05);
        }

        /* Gradient border animation for feature cards */
        .feature-card {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-8px);
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #554994, #CB80AB, #554994);
            background-size: 400% 400%;
            border-radius: 12px;
            z-index: -1;
            animation: gradientMove 3s ease infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-card:hover::before {
            opacity: 1;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Hero section gradient overlay */
        .hero-gradient {
            background: linear-gradient(135deg, #554994 0%, #34495e 100%);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#554994',
                        'secondary': '#CB80AB',
                        'third': '#34495e',
                        'bg-light': '#fefefe',
                        'bg-dark': '#f3f4f6',
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased">

    <!-- Navigation Bar -->
    @include('user.layouts.header')

    <main>
        <!-- Hero Section -->
        <section class="hero-gradient py-12 sm:py-20 lg:py-24 text-white" id="about">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="md:order-1 order-2" data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                        Making a Difference in <span class="text-secondary">Special Needs Lives</span>
                    </h1>
                    <p class="mt-4 text-xl mb-6 opacity-90 max-w-xl">
                        Join us in creating a supportive community and providing essential care for individuals with special needs. Your support helps us create lasting impact.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="#donate" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-green-500 hover:bg-green-600 transition duration-300 ease-in-out transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 7v1m-2.599-4C9.92 10.402 9 11.08 9 12s.92 1.598 2.599 2M12 12V6m0 6H4m8 0h8m-4-4H8m8 0h-4"></path></svg>
                            Donate Now
                        </a>
                        <a href="#events" class="inline-flex items-center justify-center px-8 py-3 border border-white text-base font-medium rounded-full text-white hover:bg-white hover:text-primary transition duration-300 ease-in-out transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            View Events
                        </a>
                    </div>
                </div>

                <!-- Image -->
                <div class="md:order-2 order-1 float-animation" data-aos="fade-left" data-aos-duration="1000">
                    <img src="https://cdn.motherhood.com.my/wp-content/uploads/2022/12/22132138/support.jpg" 
                         alt="A supportive environment for special needs individuals" 
                         class="w-full h-auto max-h-96 object-cover rounded-2xl shadow-2xl" 
                         onerror="this.onerror=null; this.src='https://placehold.co/800x600/6b7280/ffffff?text=Hope+House+Care'"
                    />
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 bg-bg-dark border-b border-gray-200" data-aos="fade-up" data-aos-duration="1000">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Fundraising Goal Progress Bar -->
                <div class="mb-8 bg-white rounded-xl shadow-soft p-6" data-aos="fade-up">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Fundraising Goal</span>
                        <span class="text-sm font-medium text-primary">RM {{ number_format($stats['total_donations'] ?? 0, 0) }} / RM {{ number_format($stats['fundraising_goal'] ?? 500000, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-primary to-secondary h-4 rounded-full transition-all duration-1000" 
                            style="width: {{ $stats['goal_progress'] ?? 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">{{ $stats['goal_progress'] ?? 0 }}% complete</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Funds Raised This Year -->
                    <div class="bg-white rounded-xl shadow-soft p-6 text-center" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-number text-3xl font-extrabold text-primary" 
                            data-count="{{ $stats['funds_raised_this_year'] ?? 0 }}" 
                            data-currency="RM">0</div>
                        <p class="mt-1 text-sm text-gray-500 font-medium">Funds Raised This Year</p>
                    </div>

                    <!-- Active Users -->
                    <div class="bg-white rounded-xl shadow-soft p-6 text-center" data-aos="zoom-in" data-aos-delay="150">
                        <div class="stat-number text-3xl font-extrabold text-primary" 
                            data-count="{{ $stats['active_users'] ?? 0 }}">0</div>
                        <p class="mt-1 text-sm text-gray-500 font-medium">Active Users</p>
                    </div>

                    <!-- Events Hosted -->
                    <div class="bg-white rounded-xl shadow-soft p-6 text-center" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-number text-3xl font-extrabold text-primary" 
                            data-count="{{ $stats['events_hosted'] ?? 0 }}">0</div>
                        <p class="mt-1 text-sm text-gray-500 font-medium">Events Hosted</p>
                    </div>

                    <!-- Total Donations -->
                    <div class="bg-white rounded-xl shadow-soft p-6 text-center" data-aos="zoom-in" data-aos-delay="250">
                        <div class="stat-number text-3xl font-extrabold text-primary" 
                            data-count="{{ $stats['total_donations'] ?? 0 }}" 
                            data-currency="RM">0</div>
                        <p class="mt-1 text-sm text-gray-500 font-medium">Total Funds Raised</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 sm:py-24" id="impact">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-center text-gray-900 sm:text-4xl" data-aos="fade-up">
                    Seamless Support: <span class="text-primary">Donations & Scheduling</span>
                </h2>
                <p class="mt-4 text-xl text-gray-600 text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Our platform makes it easy to support our mission and get involved with our community.
                </p>

                <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="feature-card bg-white p-8 rounded-xl shadow-soft border-t-4 border-primary transition duration-300" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 7v1m-2.599-4C9.92 10.402 9 11.08 9 12s.92 1.598 2.599 2M12 12V6m0 6H4m8 0h8m-4-4H8m8 0h-4"></path></svg>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-gray-900">Secure Donations</h3>
                        <p class="mt-4 text-gray-500">Make one-time or recurring financial contributions instantly. All transactions are secure and tax-deductible.</p>
                        <a href="#donate" class="mt-4 inline-block text-primary hover:text-red-600 font-medium">Learn More &rarr;</a>
                    </div>

                    <div class="feature-card bg-white p-8 rounded-xl shadow-soft border-t-4 border-secondary transition duration-300" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-secondary/10 text-secondary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-gray-900">Be a Volunteer</h3>
                        <p class="mt-4 text-gray-500">View our activities calendar, from holiday parties to therapy sessions. Reserve your spot with a simple click.</p>
                        <a href="#events" class="mt-4 inline-block text-secondary hover:text-blue-600 font-medium">View Events &rarr;</a>
                    </div>

                    <div class="feature-card bg-white p-8 rounded-xl shadow-soft border-t-4 border-green-500 transition duration-300" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-500/10 text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-2m2 0h-2m0 0H9m1.405 0h7.21M17 20v-2m0 0a3 3 0 01-3-3V9a2 2 0 00-2-2m0 0H9m1.405 0H7.385m8.42 2.768l2.122 2.121m0-2.121L14.49 10.51M17 14v4"></path></svg>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-gray-900">Create an Event</h3>
                        <p class="mt-4 text-gray-500">Empower our community by organizing your own activity or fundraiser. Our simple form makes it easy.</p>
                        <a href="{{ url('/events/create') }}" class="mt-4 inline-block text-green-500 hover:text-green-700 font-medium">Create &rarr;</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Donation Form -->
        <section class="bg-primary/5 py-20" id="donate">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-2xl border-t-8 border-primary" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="text-3xl font-extrabold text-gray-900 text-center" data-aos="fade-up">Fuel Our Mission</h2>
                    <p class="mt-4 mb-8 text-lg text-gray-600 text-center" data-aos="fade-up" data-aos-delay="100">
                        100% of your gift goes directly to providing essential care, educational programs, and therapeutic resources for our residents.
                    </p>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pay.bill') }}" method="POST" class="space-y-6">
                        @csrf

                        <div data-aos="fade-up" data-aos-delay="200">
                            <label for="donor_name" class="block text-sm font-medium text-gray-700">Donor's Name</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" name="donor_name" id="donor_name"
                                    class="border focus:ring-primary focus:border-primary block w-full pr-12 sm:text-lg border-gray-300 rounded-lg p-3"
                                    placeholder="Hidayat" required>
                            </div>
                        </div>

                        <div data-aos="fade-up" data-aos-delay="250">
                            <label for="donor_email" class="block text-sm font-medium text-gray-700">Donor's Email</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="email" name="donor_email" id="donor_email"
                                    class="border focus:ring-primary focus:border-primary block w-full pr-12 sm:text-lg border-gray-300 rounded-lg p-3"
                                    placeholder="hidayat@example.com" required>
                            </div>
                        </div>

                        <div data-aos="fade-up" data-aos-delay="300">
                            <label for="donor_phone_number" class="block text-sm font-medium text-gray-700">Donor's Phone Number</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="tel" name="donor_phone_number" id="donor_phone_number"
                                    class="border focus:ring-primary focus:border-primary block w-full pr-12 sm:text-lg border-gray-300 rounded-lg p-3"
                                    placeholder="0123456789" required>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-4" data-aos="fade-up" data-aos-delay="350">
                            <button type="button"
                                class="donation-amount-btn border-2 border-gray-200 text-gray-700 rounded-xl p-4 font-semibold hover:border-primary hover:bg-primary/10 transition duration-150">
                                RM10<span class="block text-sm font-normal">Essentials</span>
                            </button>
                            <button type="button"
                                class="donation-amount-btn border-2 border-gray-200 text-gray-700 rounded-xl p-4 font-semibold hover:border-primary hover:bg-primary/10 transition duration-150">
                                RM50<span class="block text-sm font-normal">Therapy Session</span>
                            </button>
                            <button type="button"
                                class="donation-amount-btn border-2 border-gray-200 text-gray-700 rounded-xl p-4 font-semibold hover:border-primary hover:bg-primary/10 transition duration-150">
                                RM100<span class="block text-sm font-normal">Program Support</span>
                            </button>
                        </div>

                        <div data-aos="fade-up" data-aos-delay="400">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Custom Amount (RM)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">RM</span>
                                </div>
                                <input type="number" name="amount" id="amount"
                                    class="border focus:ring-primary focus:border-primary block w-full pl-10 pr-12 sm:text-lg border-gray-300 rounded-lg p-3"
                                    placeholder="100" min="1" required>
                            </div>
                        </div>

                        <div data-aos="fade-up" data-aos-delay="450">
                            <button type="submit" name="received_by" value="ToyyibPay"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-md text-lg font-medium text-white bg-primary hover:bg-primary transition duration-300 ease-in-out transform hover:scale-[1.01]">
                                Pay with ToyyibPay
                            </button>
                        </div>

                        <div data-aos="fade-up" data-aos-delay="500">
                            <button type="submit" name="received_by" value="Stripe"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-md text-lg font-medium text-white bg-secondary hover:bg-secondary transition duration-300 ease-in-out transform hover:scale-[1.01]">
                                Pay with Stripe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <!-- Events Section -->
        <section id="events" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12" data-aos="fade-up">Upcoming Events & Activities</h2>
                <div class="relative" data-aos="fade-up" data-aos-delay="100">
                    <div class="swiper mySwiper px-10">
                        <div class="swiper-wrapper py-4">
                            @forelse($upcomingEvents ?? [] as $event)
                            <div class="swiper-slide h-full">
                                <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden flex flex-col h-full">
                                    <img src="{{ $event->event_picture ?? 'https://placehold.co/600x400/554994/ffffff?text=Event' }}" 
                                         alt="{{ $event->event_name }}" 
                                         class="w-full h-48 object-cover"
                                         onerror="this.onerror=null; this.src='https://placehold.co/600x400/554994/ffffff?text=Event'">
                                    <div class="p-6 flex flex-col flex-grow">
                                        <p class="text-sm text-gray-500 mb-1">
                                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d, Y') }} 
                                            | {{ $event->event_start_session }}
                                        </p>
                                        <h3 class="text-xl font-bold mb-2">{{ $event->event_name }}</h3>
                                        <p class="text-gray-600 mb-4 flex-grow line-clamp-3">{{ Str::limit($event->event_description ?? 'No description available', 100) }}</p>
                                        <div class="flex items-center justify-between mt-auto">
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-users mr-1"></i> {{ $event->event_current_participant ?? 0 }}/{{ $event->event_maximum_participant ?? 0 }}
                                            </span>
                                            <a href="{{ route('events.public.show', $event->event_id) }}" class="font-semibold text-secondary hover:underline">View Details &rarr;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="swiper-slide h-full">
                                <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden flex flex-col h-full p-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-700">No Upcoming Events</h3>
                                    <p class="text-gray-500 mt-2">Check back later for new events and activities.</p>
                                    <a href="{{ route('user.events.create') }}" class="mt-4 inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                                        Create an Event
                                    </a>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 50
            });

            const amountInput = document.getElementById('amount');
            const donationButtons = document.querySelectorAll('.donation-amount-btn');
            const statNumbers = document.querySelectorAll('.stat-number');

            // Donation amount buttons
            donationButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    donationButtons.forEach(btn => {
                        btn.classList.remove('bg-primary', 'text-white', 'border-primary');
                        btn.classList.add('text-gray-700', 'border-gray-200');
                    });

                    e.currentTarget.classList.add('bg-primary', 'text-white', 'border-primary');
                    e.currentTarget.classList.remove('text-gray-700', 'border-gray-200');

                    const amountText = e.currentTarget.textContent.match(/(\d+)/);
                    if (amountText && amountText[1]) {
                        amountInput.value = amountText[1];
                    }
                });
            });

            // Stat Counter Animation
            function startCounterAnimation(entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const finalValue = parseInt(target.getAttribute('data-count'));
                        const currency = target.getAttribute('data-currency') || '';
                        let start = 0;
                        const duration = 2000;
                        const frameDuration = 1000 / 60;
                        const totalFrames = Math.round(duration / frameDuration);
                        const increment = finalValue / totalFrames;

                        let currentFrame = 0;
                        const counter = setInterval(() => {
                            currentFrame++;
                            start += increment;
                            const displayValue = Math.round(start);
                            let formattedValue = displayValue.toLocaleString('en-US');

                            if (currentFrame >= totalFrames) {
                                clearInterval(counter);
                                formattedValue = finalValue.toLocaleString('en-US');
                            }
                            
                            target.textContent = (currency === 'RM' ? 'RM' : '') + formattedValue;
                            
                        }, frameDuration);

                        observer.unobserve(target);
                    }
                });
            }

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.5
            };

            if (statNumbers.length > 0) {
                const counterObserver = new IntersectionObserver(startCounterAnimation, observerOptions);
                statNumbers.forEach(stat => {
                    counterObserver.observe(stat);
                });
            }

            // Swiper JS Initialization
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                }
            });
        });

        document.querySelectorAll('.donation-amount-btn').forEach(button => {
            button.addEventListener('click', () => {
                const amount = button.textContent.replace(/\D/g, '');
                document.getElementById('amount').value = amount;
            });
        });
    </script>

</body>
</html>