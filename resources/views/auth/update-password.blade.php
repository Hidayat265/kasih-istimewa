@extends('auth.authLayout')

@section('title', 'Update Password - Kasih Istimewa')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-80px)] py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-key text-3xl text-green-600"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">
                Create <span class="text-primary">New Password</span>
            </h2>
            <p class="text-gray-500 text-sm mt-2">Please enter your new password below.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" id="updateForm" class="space-y-6">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" required
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                           placeholder="Enter new password">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-check-circle text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                           placeholder="Confirm your new password">
                </div>
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

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-save"></i>
                Reset Password
            </button>
        </form>

        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Remembered?</span>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-primary transition duration-150 inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </div>
</div>

<script>
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

    // Form submission with validation
    document.getElementById('updateForm')?.addEventListener('submit', function(e) {
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
                text: 'New password and confirmation password do not match.',
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Resetting password...';
    });

    // Display SweetAlert for session messages
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
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Try Again'
        });
    @endif
</script>
@endsection