@extends('auth.authLayout')

@section('title', 'Reset Password - Kasih Istimewa')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-80px)] py-12">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-key text-3xl text-primary"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">
                Enter <span class="text-primary">Reset Code</span>
            </h2>
            <p class="text-gray-500 text-sm mt-2">We sent a 6-digit code to your email</p>
        </div>

        <form method="POST" action="{{ route('password.verify.code') }}" id="resetForm" class="space-y-6">
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
                Verify Code
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
    </div>
</div>

<script>
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            if (this.value.length === 6) {
                document.getElementById('resetForm').submit();
            }
        });
    }

    function resendCode() {
        Swal.fire({
            title: 'Resend Code?',
            text: 'A new reset code will be sent to your email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, resend it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('password.resend') }}', {
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
                        text: data.success, 
                        confirmButtonColor: '#554994' 
                    });
                })
                .catch(() => {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error', 
                        text: 'Failed to resend code.', 
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