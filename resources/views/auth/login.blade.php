@extends('auth.authLayout')

@section('title', 'Welcome Back - Kasih Istimewa')

@push('styles')
<style>
    .login-shell {
        background:
            radial-gradient(circle at 12% 18%, rgba(203, 128, 171, .22), transparent 27rem),
            radial-gradient(circle at 88% 82%, rgba(85, 73, 148, .20), transparent 30rem),
            #f8f7fc;
    }
    .login-grid-pattern {
        background-image: linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
        background-size: 28px 28px;
    }
    .login-card { box-shadow: 0 32px 90px rgba(45, 35, 92, .18); }
    .login-input:focus + .input-icon { color: #554994; }
    @media (prefers-reduced-motion: no-preference) {
        .floating-orb { animation: float 7s ease-in-out infinite; }
        .floating-orb-delayed { animation: float 9s ease-in-out 1.2s infinite reverse; }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-16px) rotate(5deg); } }
    }
</style>
@endpush

@section('content')
<main class="login-shell relative min-h-[calc(100vh-73px)] overflow-hidden px-4 py-6 sm:px-6 sm:py-10 lg:px-10">
    <div aria-hidden="true" class="floating-orb absolute -left-16 top-1/4 h-44 w-44 rounded-full bg-secondary/20 blur-2xl"></div>
    <div aria-hidden="true" class="floating-orb-delayed absolute -right-20 bottom-12 h-56 w-56 rounded-full bg-primary/20 blur-3xl"></div>

    <div class="relative mx-auto flex min-h-[calc(100vh-121px)] max-w-6xl items-center justify-center">
        <section class="login-card grid w-full max-w-5xl overflow-hidden rounded-[1.75rem] border border-white/70 bg-white/95 lg:grid-cols-[1.04fr_.96fr]">
            <div class="login-grid-pattern relative hidden min-h-[650px] overflow-hidden bg-gradient-to-br from-[#392d78] via-primary to-[#8f5791] p-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div aria-hidden="true" class="absolute -right-24 -top-24 h-72 w-72 rounded-full border-[42px] border-white/10"></div>
                <div aria-hidden="true" class="absolute -bottom-24 -left-16 h-64 w-64 rounded-full bg-secondary/30 blur-sm"></div>

                <a href="{{ url('/') }}" class="relative z-10 inline-flex w-fit items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-md transition hover:bg-white/15">
                    <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-white p-1 shadow-sm"><img src="{{ asset('images/KasihIstimewa-KI-Logo.png') }}" alt="Kasih Istimewa logo" class="h-full w-full object-contain"></span>
                    <span class="font-bold tracking-wide">Kasih Istimewa</span>
                </a>

                <div class="relative z-10 max-w-md">
                    <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[.18em] text-purple-50 backdrop-blur">
                        <i class="fas fa-heart text-pink-300"></i> Community with purpose
                    </span>
                    <h1 class="text-5xl font-black leading-[1.08] tracking-tight">Small acts.<br><span class="text-pink-200">Lasting impact.</span></h1>
                    <p class="mt-6 text-base leading-7 text-purple-100">Welcome back to the community turning kindness into meaningful support, one contribution at a time.</p>
                </div>

                <div class="relative z-10 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"><i class="fas fa-shield-heart mb-2 text-pink-200"></i><p class="text-xs font-medium text-purple-50">Secure access</p></div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"><i class="fas fa-hand-holding-heart mb-2 text-pink-200"></i><p class="text-xs font-medium text-purple-50">Real impact</p></div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"><i class="fas fa-people-group mb-2 text-pink-200"></i><p class="text-xs font-medium text-purple-50">One community</p></div>
                </div>
            </div>

            <div class="flex min-h-[600px] flex-col justify-center px-6 py-9 sm:px-12 sm:py-12 lg:px-14">
                <div class="mb-8 lg:hidden">
                    <div class="inline-flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-white p-1 shadow-lg shadow-primary/20"><img src="{{ asset('images/KasihIstimewa-KI-Logo.png') }}" alt="Kasih Istimewa logo" class="h-full w-full object-contain"></span>
                        <div><p class="font-extrabold text-gray-900">Kasih Istimewa</p><p class="text-xs text-gray-500">Community with purpose</p></div>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="mb-2 text-sm font-bold uppercase tracking-[.18em] text-secondary">Welcome back</p>
                    <h2 class="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">Sign in to continue</h2>
                    <p class="mt-3 text-sm leading-6 text-gray-500">Enter your details to access your Kasih Istimewa account.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5" novalidate>
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email address</label>
                        <div class="relative">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                aria-describedby="email-error" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                class="login-input peer h-14 w-full rounded-2xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-12 pr-4 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10"
                                placeholder="name@example.com">
                            <i class="input-icon fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                        </div>
                        @error('email')<p id="email-error" class="mt-2 flex items-start gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                aria-describedby="password-error" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                class="login-input peer h-14 w-full rounded-2xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-12 pr-12 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10"
                                placeholder="Enter your password">
                            <i class="input-icon fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                            <button type="button" id="togglePassword" aria-label="Show password" aria-pressed="false" class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-xl text-gray-400 transition hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')<p id="password-error" class="mt-2 flex items-start gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                        <div class="mt-3 text-right">
                            <a href="{{ route('password.request') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-primary transition hover:text-secondary">
                                <i class="fas fa-key text-xs"></i> Forgot password?
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="group flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-primary to-[#7665c2] px-5 font-bold text-white shadow-lg shadow-primary/25 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/30 focus:outline-none focus:ring-4 focus:ring-primary/25 disabled:cursor-wait disabled:opacity-70">
                        <span>Sign in securely</span><i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>

                    <div class="mt-6 rounded-2xl border border-primary/15 bg-primary/5 p-4">
                        <div class="mb-3 flex items-center gap-2">
                            <i class="fas fa-flask text-primary"></i>
                            <p class="text-sm font-bold text-gray-800">Demo accounts</p>
                        </div>
                        <p class="mb-3 text-xs text-gray-500">Select an account to fill in its login credentials.</p>

                        <div class="space-y-2 text-xs">
                            <button type="button" data-demo-email="admin1@kasihistimewa.my" data-demo-password="11111111"
                                class="demo-account-btn flex w-full items-center justify-between gap-3 rounded-xl border border-purple-200 bg-white p-3 text-left transition hover:border-primary hover:bg-purple-50">
                                <span>
                                    <span class="block font-bold text-primary">Administrator demo</span>
                                    <span class="block break-all text-gray-600">Email: admin1@kasihistimewa.my</span>
                                    <span class="block text-gray-500">Password: 11111111</span>
                                </span>
                                <span class="shrink-0 font-bold text-primary"><i class="fas fa-arrow-pointer mr-1"></i>Use</span>
                            </button>

                            <button type="button" data-demo-email="dieyard.dhr@gmail.com" data-demo-password="11111111"
                                class="demo-account-btn flex w-full items-center justify-between gap-3 rounded-xl border border-pink-200 bg-white p-3 text-left transition hover:border-secondary hover:bg-pink-50">
                                <span>
                                    <span class="block font-bold text-secondary">Regular user demo</span>
                                    <span class="block break-all text-gray-600">Email: dieyard.dhr@gmail.com</span>
                                    <span class="block text-gray-500">Password: 11111111</span>
                                </span>
                                <span class="shrink-0 font-bold text-secondary"><i class="fas fa-arrow-pointer mr-1"></i>Use</span>
                            </button>
                        </div>
                    </div>

                <div class="my-7 flex items-center gap-4"><span class="h-px flex-1 bg-gray-200"></span><span class="text-xs font-medium uppercase tracking-wider text-gray-400">New here?</span><span class="h-px flex-1 bg-gray-200"></span></div>
                <a href="{{ route('register') }}" class="flex h-13 w-full items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3.5 text-sm font-bold text-gray-700 transition hover:border-primary/30 hover:bg-primary/5 hover:text-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    Create your free account <i class="fas fa-sparkles text-secondary"></i>
                </a>
                <p class="mt-7 text-center text-xs leading-5 text-gray-400"><i class="fas fa-lock mr-1"></i>Your credentials are encrypted and handled securely.</p>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword?.addEventListener('click', () => {
        const showing = passwordInput.type === 'text';
        passwordInput.type = showing ? 'password' : 'text';
        togglePassword.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        togglePassword.setAttribute('aria-pressed', String(!showing));
        togglePassword.querySelector('i').className = showing ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    document.querySelectorAll('.demo-account-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('email').value = button.dataset.demoEmail;
            passwordInput.value = button.dataset.demoPassword;
            document.getElementById('email').focus();
        });
    });

    document.getElementById('loginForm')?.addEventListener('submit', function () {
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Signing you in...</span>';
    });

    @if (session('status'))
        Swal.fire({ icon: 'success', title: 'All set!', text: @json(session('status')), confirmButtonColor: '#554994' });
    @endif

    @if (session('error'))
        Swal.fire({ icon: 'error', title: 'Unable to sign in', text: @json(session('error')), confirmButtonColor: '#554994' });
    @endif
</script>
@endpush
