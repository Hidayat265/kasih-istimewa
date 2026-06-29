@extends('auth.authLayout')

@section('title', 'Verify Email - Kasih Istimewa')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-80px)] py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-envelope text-2xl text-primary"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">
                Verify Your <span class="text-primary">Email</span>
            </h2>
            <p class="text-gray-500 text-sm mt-2">
                We've sent a 6-digit verification code to<br>
                <strong class="text-primary">{{ session('verification_email') }}</strong>
            </p>
        </div>

        <form method="POST" action="{{ route('verification.verify') }}" id="verifyForm" class="space-y-6">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 text-center mb-2">Verification Code</label>
                <input id="code" name="code" type="text" maxlength="6" required
                       class="w-full text-center text-2xl tracking-widest px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                       placeholder="000000">
                <p class="text-xs text-gray-500 text-center mt-2">Code expires in 30 minutes</p>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                Verify Email
            </button>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Didn't receive the code?
                <a href="#" onclick="resendCode()" class="text-secondary hover:text-secondary/80 font-medium">
                    Resend Code
                </a>
            </p>
        </div>

        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                <i class="fas fa-clock mr-1"></i> The account will only be created after successful verification.
            </p>
        </div>
    </div>
</div>

<script>
    // Auto-focus and auto-submit on 6 digits
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            if (this.value.length === 6) {
                document.getElementById('verifyForm').submit();
            }
        });
    }

    function resendCode() {
        Swal.fire({
            title: 'Resend Code?',
            text: 'A new verification code will be sent to your email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, resend it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('verification.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Code Sent!',
                        text: 'A new verification code has been sent to your email.',
                        confirmButtonColor: '#554994'
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to resend code. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                });
            }
        });
    }

    @if (session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}',
            confirmButtonColor: '#554994'
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
</script>
@endsection