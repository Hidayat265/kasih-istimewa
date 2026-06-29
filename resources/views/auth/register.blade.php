@extends('auth.authLayout')

@section('title', 'Register - Kasih Istimewa')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-80px)] py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-2xl text-primary"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Create Account</h2>
            <p class="text-gray-500 text-sm mt-1">Join our community</p>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="Enter your full name"
                       oninput="capitalizeName(this)">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="your@email.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="{{ old('dob') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                @error('dob')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                       inputmode="numeric" pattern="[0-9]*" maxlength="11" minlength="10"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="0123456789"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                @error('phone_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="Create a password">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password strength indicator -->
            <div class="space-y-2 p-3 bg-gray-50 rounded-lg" id="passwordStrengthContainer">
                <p class="text-xs font-medium text-gray-700 mb-2">Password requirements:</p>
                <div class="flex items-center gap-2 text-xs" id="lengthCheck">
                    <i class="fas fa-circle text-gray-300 text-[8px]"></i>
                    <span class="text-gray-600">At least 8 characters</span>
                </div>
                <div class="flex items-center gap-2 text-xs" id="caseCheck">
                    <i class="fas fa-circle text-gray-300 text-[8px]"></i>
                    <span class="text-gray-600">Contains BOTH uppercase & lowercase letters</span>
                </div>
                <div class="flex items-center gap-2 text-xs" id="numberCheck">
                    <i class="fas fa-circle text-gray-300 text-[8px]"></i>
                    <span class="text-gray-600">Contains at least 1 number</span>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="Confirm your password">
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i>
                Create Account
            </button>
        </form>

        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-secondary hover:text-secondary/80 font-medium">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    /**
     * Capitalize each word in a name (e.g., "john doe" → "John Doe")
     * Handles hyphenated names, apostrophes, and multiple spaces
     */
    function capitalizeName(input) {
        if (!input || !input.value) return;
        
        let value = input.value;
        let parts = value.split(' ');
        let capitalizedParts = parts.map(function(part) {
            if (part.length === 0) return part;
            
            // Handle hyphenated names (e.g., "Jane-Doe")
            if (part.includes('-')) {
                let hyphenParts = part.split('-');
                let capitalizedHyphenParts = hyphenParts.map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                return capitalizedHyphenParts.join('-');
            }
            
            // Handle apostrophe names (e.g., "O'Brien")
            if (part.includes("'")) {
                let apostropheParts = part.split("'");
                let capitalizedApostropheParts = apostropheParts.map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                return capitalizedApostropheParts.join("'");
            }
            
            // Regular name
            return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
        });
        
        let capitalized = capitalizedParts.join(' ');
        
        // Only update if changed to avoid cursor jumping
        if (input.value !== capitalized) {
            const cursorPosition = input.selectionStart;
            input.value = capitalized;
            // Restore cursor position
            input.setSelectionRange(cursorPosition, cursorPosition);
        }
    }

    // Phone number - remove non-numeric characters
    const phoneInput = document.getElementById('phone_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Password strength checker with complete validation
    const passwordInput = document.getElementById('password');
    
    function checkPasswordStrength(password) {
        const checks = {
            length: password.length >= 8,
            hasUppercase: /[A-Z]/.test(password),
            hasLowercase: /[a-z]/.test(password),
            hasNumber: /\d/.test(password)
        };
        
        // Update length check
        const lengthCheck = document.getElementById('lengthCheck');
        if (lengthCheck) {
            if (checks.length) {
                lengthCheck.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xs"></i><span class="text-green-700 ml-2">At least 8 characters ✓</span>';
            } else {
                lengthCheck.innerHTML = '<i class="fas fa-circle text-gray-300 text-[8px]"></i><span class="text-gray-600 ml-2">At least 8 characters</span>';
            }
        }
        
        // Update case check - requires BOTH uppercase AND lowercase
        const caseCheck = document.getElementById('caseCheck');
        if (caseCheck) {
            if (checks.hasUppercase && checks.hasLowercase) {
                caseCheck.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xs"></i><span class="text-green-700 ml-2">Contains BOTH uppercase & lowercase letters ✓</span>';
            } else if (checks.hasUppercase || checks.hasLowercase) {
                caseCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 text-xs"></i><span class="text-yellow-700 ml-2">Must contain BOTH uppercase AND lowercase letters</span>';
            } else {
                caseCheck.innerHTML = '<i class="fas fa-circle text-gray-300 text-[8px]"></i><span class="text-gray-600 ml-2">Contains BOTH uppercase & lowercase letters</span>';
            }
        }
        
        // Update number check
        const numberCheck = document.getElementById('numberCheck');
        if (numberCheck) {
            if (checks.hasNumber) {
                numberCheck.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xs"></i><span class="text-green-700 ml-2">Contains at least 1 number ✓</span>';
            } else {
                numberCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 text-xs"></i><span class="text-yellow-700 ml-2">Must contain at least 1 number</span>';
            }
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }

    @if (session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}',
            confirmButtonColor: '#554994',
            timer: 3000
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `
                <ul class="text-left list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#d33',
        });
    @endif

    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
        // Capitalize name before submit
        const nameInput = document.getElementById('name');
        if (nameInput) {
            capitalizeName(nameInput);
        }
        
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        // Check password requirements
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and confirmation password do not match.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Too Short',
                text: 'Password must be at least 8 characters long.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (!hasUppercase || !hasLowercase) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Password Format',
                text: 'Password must contain BOTH uppercase and lowercase letters.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (!hasNumber) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Password Format',
                text: 'Password must contain at least 1 number.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating account...';
    });
</script>
@endsection