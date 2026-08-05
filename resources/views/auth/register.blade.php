@extends('auth.authLayout')

@section('title', 'Create Account - Kasih Istimewa')

@push('styles')
<style>
    .register-shell { background: radial-gradient(circle at 10% 15%, rgba(203,128,171,.22), transparent 27rem), radial-gradient(circle at 90% 85%, rgba(85,73,148,.20), transparent 30rem), #f8f7fc; }
    .register-grid-pattern { background-image: linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px); background-size: 28px 28px; }
    .register-card { box-shadow: 0 32px 90px rgba(45,35,92,.18); }
    .register-input:focus + .input-icon { color: #554994; }
    @media (prefers-reduced-motion: no-preference) {
        .register-orb { animation: registerFloat 8s ease-in-out infinite; }
        @keyframes registerFloat { 0%,100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-16px) rotate(5deg); } }
    }
</style>
@endpush

@section('content')
<main class="register-shell relative min-h-[calc(100vh-73px)] overflow-hidden px-4 py-6 sm:px-6 sm:py-10 lg:px-10">
    <div aria-hidden="true" class="register-orb absolute -left-16 top-1/4 h-44 w-44 rounded-full bg-secondary/20 blur-2xl"></div>
    <div aria-hidden="true" class="absolute -right-20 bottom-12 h-56 w-56 rounded-full bg-primary/20 blur-3xl"></div>

    <div class="relative mx-auto flex max-w-6xl items-center justify-center">
        <section class="register-card grid w-full max-w-5xl overflow-hidden rounded-[1.75rem] border border-white/70 bg-white/95 lg:grid-cols-[.86fr_1.14fr]">
            <aside class="register-grid-pattern relative hidden min-h-[790px] overflow-hidden bg-gradient-to-br from-[#392d78] via-primary to-[#8f5791] p-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div aria-hidden="true" class="absolute -right-24 -top-24 h-72 w-72 rounded-full border-[42px] border-white/10"></div>
                <div aria-hidden="true" class="absolute -bottom-24 -left-16 h-64 w-64 rounded-full bg-secondary/30"></div>

                <a href="{{ url('/') }}" class="relative z-10 inline-flex w-fit items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-md transition hover:bg-white/15">
                    <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-white p-1 shadow-sm"><img src="{{ asset('images/KasihIstimewa-KI-Logo.png') }}" alt="Kasih Istimewa logo" class="h-full w-full object-contain"></span>
                    <span class="font-bold tracking-wide">Kasih Istimewa</span>
                </a>

                <div class="relative z-10 max-w-sm">
                    <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[.18em] text-purple-50 backdrop-blur"><i class="fas fa-heart text-pink-300"></i> Join the movement</span>
                    <h1 class="text-4xl font-black leading-[1.1] tracking-tight">Your kindness<br><span class="text-pink-200">starts here.</span></h1>
                    <p class="mt-5 text-sm leading-7 text-purple-100">Create an account to support meaningful causes, join events, and follow the impact we make together.</p>
                </div>

                <div class="relative z-10 space-y-3">
                    <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 p-3.5 backdrop-blur-sm"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10"><i class="fas fa-hand-holding-heart text-pink-200"></i></span><div><p class="text-sm font-bold">Make an impact</p><p class="text-xs text-purple-200">Support causes that matter.</p></div></div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 p-3.5 backdrop-blur-sm"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10"><i class="fas fa-people-group text-pink-200"></i></span><div><p class="text-sm font-bold">Find your community</p><p class="text-xs text-purple-200">Connect through shared purpose.</p></div></div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 p-3.5 backdrop-blur-sm"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10"><i class="fas fa-shield-heart text-pink-200"></i></span><div><p class="text-sm font-bold">Safe and secure</p><p class="text-xs text-purple-200">Your information stays protected.</p></div></div>
                </div>
            </aside>

            <div class="px-6 py-9 sm:px-10 sm:py-11 lg:px-12">
                <div class="mb-7 lg:hidden">
                    <div class="inline-flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-white p-1 shadow-lg shadow-primary/20"><img src="{{ asset('images/KasihIstimewa-KI-Logo.png') }}" alt="Kasih Istimewa logo" class="h-full w-full object-contain"></span>
                        <div><p class="font-extrabold text-gray-900">Kasih Istimewa</p><p class="text-xs text-gray-500">Community with purpose</p></div>
                    </div>
                </div>

                <div class="mb-7">
                    <p class="mb-2 text-sm font-bold uppercase tracking-[.18em] text-secondary">Join our community</p>
                    <h2 class="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">Create your account</h2>
                    <p class="mt-3 text-sm leading-6 text-gray-500">A few details and you’ll be ready to make a difference.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="name" class="mb-2 block text-sm font-bold text-gray-700">Full name</label>
                            <div class="relative">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name" oninput="capitalizeName(this)" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" class="register-input h-14 w-full rounded-2xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-12 pr-4 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10" placeholder="Enter your full name">
                                <i class="input-icon fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                            </div>
                            @error('name')<p class="mt-2 flex gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" class="register-input h-14 w-full rounded-2xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-12 pr-4 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10" placeholder="name@example.com">
                                <i class="input-icon fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                            </div>
                            @error('email')<p class="mt-2 flex gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="dob" class="mb-2 block text-sm font-bold text-gray-700">Date of birth</label>
                            <div class="relative">
                                <input type="date" name="dob" id="dob" value="{{ old('dob') }}" autocomplete="bday" aria-invalid="{{ $errors->has('dob') ? 'true' : 'false' }}" class="register-input h-14 w-full rounded-2xl border {{ $errors->has('dob') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-11 pr-3 text-sm text-gray-700 outline-none transition hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                                <i class="input-icon fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                            </div>
                            @error('dob')<p class="mt-2 flex gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phone_number" class="mb-2 block text-sm font-bold text-gray-700">Phone number</label>
                            <div class="relative">
                                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" inputmode="numeric" pattern="[0-9]*" maxlength="11" minlength="10" autocomplete="tel" oninput="this.value = this.value.replace(/[^0-9]/g, '')" aria-invalid="{{ $errors->has('phone_number') ? 'true' : 'false' }}" class="register-input h-14 w-full rounded-2xl border {{ $errors->has('phone_number') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-11 pr-3 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10" placeholder="0123456789">
                                <i class="input-icon fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                            </div>
                            @error('phone_number')<p class="mt-2 flex gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="password" class="mb-2 block text-sm font-bold text-gray-700">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required autocomplete="new-password" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" class="register-input h-14 w-full rounded-2xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-gray-50/70' }} py-3 pl-12 pr-12 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10" placeholder="Create a password">
                                <i class="input-icon fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                                <button type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false" class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-xl text-gray-400 transition hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30"><i class="fas fa-eye"></i></button>
                            </div>
                            @error('password')<p class="mt-2 flex gap-1.5 text-xs font-medium text-red-600"><i class="fas fa-circle-exclamation mt-0.5"></i>{{ $message }}</p>@enderror
                        </div>

                        <div id="passwordStrengthContainer" class="sm:col-span-2 grid gap-2 rounded-2xl border border-gray-100 bg-gray-50/80 p-4 sm:grid-cols-3">
                            <p class="mb-1 text-xs font-bold text-gray-700 sm:col-span-3">Your password needs:</p>
                            <div class="flex items-center gap-2 text-xs" id="lengthCheck"><i class="fas fa-circle text-[8px] text-gray-300"></i><span class="text-gray-600">At least 8 characters</span></div>
                            <div class="flex items-center gap-2 text-xs" id="caseCheck"><i class="fas fa-circle text-[8px] text-gray-300"></i><span class="text-gray-600">Uppercase & lowercase</span></div>
                            <div class="flex items-center gap-2 text-xs" id="numberCheck"><i class="fas fa-circle text-[8px] text-gray-300"></i><span class="text-gray-600">At least 1 number</span></div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="password_confirmation" class="mb-2 block text-sm font-bold text-gray-700">Confirm password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="register-input h-14 w-full rounded-2xl border border-gray-200 bg-gray-50/70 py-3 pl-12 pr-12 text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10" placeholder="Confirm your password">
                                <i class="input-icon fas fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition"></i>
                                <button type="button" data-password-toggle="password_confirmation" aria-label="Show confirmation password" aria-pressed="false" class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-xl text-gray-400 transition hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="group flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-primary to-[#7665c2] px-5 font-bold text-white shadow-lg shadow-primary/25 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/30 focus:outline-none focus:ring-4 focus:ring-primary/25 disabled:cursor-wait disabled:opacity-70"><span>Create my account</span><i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i></button>
                </form>

                <div class="my-7 flex items-center gap-4"><span class="h-px flex-1 bg-gray-200"></span><span class="text-xs font-medium uppercase tracking-wider text-gray-400">Already a member?</span><span class="h-px flex-1 bg-gray-200"></span></div>
                <a href="{{ route('login') }}" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3.5 text-sm font-bold text-gray-700 transition hover:border-primary/30 hover:bg-primary/5 hover:text-primary focus:outline-none focus:ring-4 focus:ring-primary/10">Sign in to your account <i class="fas fa-arrow-right-to-bracket text-secondary"></i></a>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
function capitalizeName(input) {
    if (!input || !input.value) return;
    const cursorPosition = input.selectionStart;
    const capitalized = input.value.split(' ').map(part => part.split('-').map(hyphenPart => hyphenPart.split("'").map(piece => piece ? piece.charAt(0).toUpperCase() + piece.slice(1).toLowerCase() : piece).join("'")).join('-')).join(' ');
    if (input.value !== capitalized) { input.value = capitalized; input.setSelectionRange(cursorPosition, cursorPosition); }
}

const phoneInput = document.getElementById('phone_number');
phoneInput?.addEventListener('input', function () { this.value = this.value.replace(/[^0-9]/g, ''); });

const passwordInput = document.getElementById('password');
function setRequirement(id, passed, waitingText, passedText, warning = false) {
    const element = document.getElementById(id);
    if (!element) return;
    const icon = passed ? 'fa-check-circle text-green-500' : (warning ? 'fa-exclamation-triangle text-yellow-500' : 'fa-circle text-gray-300 text-[8px]');
    const textColor = passed ? 'text-green-700' : (warning ? 'text-yellow-700' : 'text-gray-600');
    element.innerHTML = `<i class="fas ${icon}"></i><span class="${textColor}">${passed ? passedText : waitingText}</span>`;
}
function checkPasswordStrength(password) {
    const length = password.length >= 8;
    const upper = /[A-Z]/.test(password);
    const lower = /[a-z]/.test(password);
    const number = /\d/.test(password);
    setRequirement('lengthCheck', length, 'At least 8 characters', '8+ characters ✓');
    setRequirement('caseCheck', upper && lower, 'Uppercase & lowercase', 'Both letter cases ✓', password.length > 0 && (upper || lower));
    setRequirement('numberCheck', number, 'At least 1 number', 'Number included ✓', password.length > 0 && !number);
}
passwordInput?.addEventListener('input', function () { checkPasswordStrength(this.value); });

document.querySelectorAll('[data-password-toggle]').forEach(button => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        button.setAttribute('aria-pressed', String(!showing));
        button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        button.querySelector('i').className = showing ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
});

@if (session('status'))
Swal.fire({ icon: 'success', title: 'Success!', text: @json(session('status')), confirmButtonColor: '#554994', timer: 3000 });
@endif
@if (session('error'))
Swal.fire({ icon: 'error', title: 'Error!', text: @json(session('error')), confirmButtonColor: '#d33' });
@endif

document.getElementById('registerForm')?.addEventListener('submit', function (event) {
    const nameInput = document.getElementById('name');
    if (nameInput) capitalizeName(nameInput);
    const password = passwordInput.value;
    const confirmation = document.getElementById('password_confirmation').value;
    let message = null;
    if (password !== confirmation) message = ['Password Mismatch', 'Password and confirmation password do not match.'];
    else if (password.length < 8) message = ['Password Too Short', 'Password must be at least 8 characters long.'];
    else if (!/[A-Z]/.test(password) || !/[a-z]/.test(password)) message = ['Invalid Password Format', 'Password must contain BOTH uppercase and lowercase letters.'];
    else if (!/\d/.test(password)) message = ['Invalid Password Format', 'Password must contain at least 1 number.'];
    if (message) { event.preventDefault(); Swal.fire({ icon: 'error', title: message[0], text: message[1], confirmButtonColor: '#d33' }); return; }
    const button = this.querySelector('button[type="submit"]');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating your account...</span>';
});
</script>
@endpush