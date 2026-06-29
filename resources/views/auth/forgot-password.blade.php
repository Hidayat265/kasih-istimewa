@extends('auth.authLayout')

@section('title', 'Forgot Password - Kasih Istimewa')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-80px)] py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-lock text-3xl text-primary"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">
                Forgot <span class="text-primary">Password</span>
            </h2>
            <p class="text-gray-500 text-sm mt-2">Enter your email address and we'll send you a verification code to reset your password.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" id="forgotForm" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                           placeholder="your@email.com">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i>
                Send Reset Code
            </button>
        </form>

        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">or</span>
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
    // Show SweetAlert for session messages
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

    // Show loading on form submit
    document.getElementById('forgotForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
    });
</script>
@endsection