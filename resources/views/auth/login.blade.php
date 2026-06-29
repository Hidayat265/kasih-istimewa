@extends('auth.authLayout')

@section('title', 'Login - Kasih Istimewa')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-80px)] py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sign-in-alt text-2xl text-primary"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
            <p class="text-gray-500 text-sm mt-1">Login to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="your@email.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="Enter your password">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary/80">
                    Forgot password?
                </a>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>

        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-secondary hover:text-secondary/80 font-medium">
                    Sign up
                </a>
            </p>
        </div>
    </div>
</div>

<script>
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
            title: 'Login Failed',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Try Again'
        });
    @endif

    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Signing in...';
    });
</script>
@endsection