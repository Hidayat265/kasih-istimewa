@extends('user.layouts.userLayouts')

@section('title', 'Make a Donation | Kasih Istimewa')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Support Our Cause</h1>
        <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
            Your generosity helps us continue making a difference in our community
        </p>
    </div>
</section>

<!-- Donation Form Section -->
<section class="bg-primary/5 py-20" id="donate">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-2xl border-t-8 border-primary">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">Fuel Our Mission</h2>
            <p class="mt-4 mb-8 text-lg text-gray-600 text-center">
                100% of your gift goes directly to providing essential care, educational programs, and therapeutic resources for our community.
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

            <form action="{{ route('pay.bill') }}" method="POST" class="space-y-6" id="donationForm">
                @csrf

                <div>
                    <label for="donor_name" class="block text-sm font-medium text-gray-700">Donor's Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="donor_name" id="donor_name"
                            value="{{ auth()->user()->user_name ?? '' }}"
                            class="border focus:ring-primary focus:border-primary block w-full pr-12 sm:text-lg border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed"
                            readonly required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">This field is auto-filled from your profile</p>
                </div>

                <div>
                    <label for="donor_email" class="block text-sm font-medium text-gray-700">Donor's Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="email" name="donor_email" id="donor_email"
                            value="{{ auth()->user()->user_email ?? '' }}"
                            class="border focus:ring-primary focus:border-primary block w-full pr-12 sm:text-lg border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed"
                            readonly required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">This field is auto-filled from your profile</p>
                </div>

                <div>
                    <label for="donor_phone_number" class="block text-sm font-medium text-gray-700">Donor's Phone Number</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="tel" name="donor_phone_number" id="donor_phone_number"
                            value="{{ auth()->user()->user_phone_number ?? '' }}"
                            class="border focus:ring-primary focus:border-primary block w-full pr-12 sm:text-lg border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed"
                            readonly required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">This field is auto-filled from your profile</p>
                </div>

                <div class="grid sm:grid-cols-3 gap-4">
                    <button type="button"
                        class="donation-amount-btn border-2 border-gray-200 text-gray-700 rounded-xl p-4 font-semibold hover:border-primary hover:bg-primary/10 transition duration-150"
                        data-amount="10">
                        RM10<span class="block text-sm font-normal">Essentials</span>
                    </button>

                    <button type="button"
                        class="donation-amount-btn border-2 border-gray-200 text-gray-700 rounded-xl p-4 font-semibold hover:border-primary hover:bg-primary/10 transition duration-150"
                        data-amount="50">
                        RM50<span class="block text-sm font-normal">Therapy Session</span>
                    </button>

                    <button type="button"
                        class="donation-amount-btn border-2 border-gray-200 text-gray-700 rounded-xl p-4 font-semibold hover:border-primary hover:bg-primary/10 transition duration-150"
                        data-amount="100">
                        RM100<span class="block text-sm font-normal">Program Support</span>
                    </button>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Custom Amount (RM)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">RM</span>
                        </div>
                        <input type="number" name="amount" id="amount"
                            class="border focus:ring-primary focus:border-primary block w-full pl-10 pr-12 sm:text-lg border-gray-300 rounded-lg p-3"
                            placeholder="Enter custom amount" min="1" step="0.01">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum donation: RM 1.00</p>
                </div>

                <!-- Hidden field to store the amount from preset buttons -->
                <input type="hidden" name="bill_amount" id="bill_amount" value="">

                <div class="space-y-3">
                    <button type="button" id="toyyibpay-btn"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-md text-lg font-medium text-white bg-primary hover:bg-primary/90 transition duration-300 ease-in-out transform hover:scale-[1.01]">
                        <i class="fas fa-credit-card mr-2"></i> Pay with ToyyibPay
                    </button>

                    <button type="button" id="stripe-btn"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-md text-lg font-medium text-white bg-[#635bff] hover:bg-[#4a43d4] transition duration-300 ease-in-out transform hover:scale-[1.01]">
                        <i class="fab fa-stripe mr-2"></i> Pay with Stripe
                    </button>
                </div>

                <p class="text-xs text-gray-500 text-center mt-4">
                    <i class="fas fa-lock mr-1"></i> Your payment information is secure and encrypted
                </p>
            </form>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Frequently Asked Questions</h2>
            <p class="mt-4 text-lg text-gray-600">Everything you need to know about donating</p>
        </div>
        
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-soft p-6">
                <details class="group">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <span class="font-semibold text-gray-800">Is my donation tax-deductible?</span>
                        <span class="transition group-open:rotate-180 text-primary">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-3 pl-4">Yes, all donations are tax-exempt. You will receive an official receipt via email for your records.</p>
                </details>
            </div>
            
            <div class="bg-white rounded-xl shadow-soft p-6">
                <details class="group">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <span class="font-semibold text-gray-800">Is my donation secure?</span>
                        <span class="transition group-open:rotate-180 text-primary">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-3 pl-4">Absolutely! We use ToyyibPay and Stripe, both secure payment gateways that encrypt your information.</p>
                </details>
            </div>
            
            <div class="bg-white rounded-xl shadow-soft p-6">
                <details class="group">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <span class="font-semibold text-gray-800">How will my donation be used?</span>
                        <span class="transition group-open:rotate-180 text-primary">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-3 pl-4">100% of your donation goes directly to our programs. We maintain transparency in all our operations.</p>
                </details>
            </div>
            
            <div class="bg-white rounded-xl shadow-soft p-6">
                <details class="group">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <span class="font-semibold text-gray-800">Can I make a monthly donation?</span>
                        <span class="transition group-open:rotate-180 text-primary">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-3 pl-4">Yes! You can set up recurring donations through our payment partners. Contact our support team for assistance.</p>
                </details>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<!-- SweetAlert2 - Fixed CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Donation page loaded');
        
        // Check if Swal is loaded
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 (Swal) is not loaded!');
            alert('SweetAlert2 is not loaded. Please refresh the page or check your internet connection.');
            return;
        }
        
        // ─── PRESET AMOUNT BUTTONS ──────────────────────────────────────────
        document.querySelectorAll('.donation-amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.donation-amount-btn').forEach(b => {
                    b.classList.remove('border-primary', 'bg-primary/10');
                    b.classList.add('border-gray-200');
                });
                
                this.classList.add('border-primary', 'bg-primary/10');
                this.classList.remove('border-gray-200');
                
                const amount = this.getAttribute('data-amount');
                document.getElementById('amount').value = amount;
                document.getElementById('bill_amount').value = amount;
            });
        });
        
        // ─── CUSTOM AMOUNT INPUT ────────────────────────────────────────────
        const customAmountInput = document.getElementById('amount');
        if (customAmountInput) {
            customAmountInput.addEventListener('input', function() {
                document.querySelectorAll('.donation-amount-btn').forEach(b => {
                    b.classList.remove('border-primary', 'bg-primary/10');
                    b.classList.add('border-gray-200');
                });
                document.getElementById('bill_amount').value = this.value;
            });
        }
        
        // ─── TOYYIBPAY HANDLER ──────────────────────────────────────────────
        document.getElementById('toyyibpay-btn').addEventListener('click', function(e) {
            e.preventDefault();
            console.log('ToyyibPay button clicked');
            
            const amount = document.getElementById('amount').value;
            const donorName = document.getElementById('donor_name').value;
            const donorEmail = document.getElementById('donor_email').value;
            const donorPhone = document.getElementById('donor_phone_number').value;
            
            console.log('Amount:', amount);
            console.log('Donor:', donorName, donorEmail);
            console.log('Phone:', donorPhone);
            
            if (!amount || amount < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Please select or enter a valid donation amount (minimum RM 1.00)',
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we redirect you to ToyyibPay',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create form data
            const formData = new FormData();
            formData.append('donor_name', donorName);
            formData.append('donor_email', donorEmail);
            formData.append('donor_phone_number', donorPhone);
            formData.append('amount', amount);
            formData.append('bill_amount', amount);
            formData.append('received_by', 'ToyyibPay');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            
            // Create a new form and submit
            const newForm = document.createElement('form');
            newForm.method = 'POST';
            newForm.action = '{{ route("pay.bill") }}';
            
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                newForm.appendChild(input);
            }
            
            document.body.appendChild(newForm);
            console.log('Submitting form to ToyyibPay');
            newForm.submit();
        });
        
        // ─── STRIPE HANDLER ──────────────────────────────────────────────────
        document.getElementById('stripe-btn').addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Stripe button clicked');
            
            const amount = document.getElementById('amount').value;
            const donorName = document.getElementById('donor_name').value;
            const donorEmail = document.getElementById('donor_email').value;
            const donorPhone = document.getElementById('donor_phone_number').value;
            
            console.log('Amount:', amount);
            console.log('Donor:', donorName, donorEmail);
            console.log('Phone:', donorPhone);
            
            if (!amount || amount < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Please select or enter a valid donation amount (minimum RM 1.00)',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we prepare your payment',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            console.log('CSRF Token:', csrfToken);

            // Create Stripe Checkout Session
            fetch('{{ route("stripe.create-checkout-session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    amount: amount,
                    donor_name: donorName,
                    donor_email: donorEmail,
                    donor_phone_number: donorPhone
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                Swal.close();
                
                if (data.success) {
                    console.log('Redirecting to Stripe:', data.url);
                    window.location.href = data.url;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Error',
                        text: data.message || 'Failed to create payment session. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Something went wrong. Please try again.',
                    confirmButtonColor: '#d33'
                });
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    
    details summary::-webkit-details-marker {
        display: none;
    }
    
    .donation-amount-btn.active {
        border-color: #554994;
        background-color: rgba(85, 73, 148, 0.1);
    }
    
    input:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
</style>
@endpush
@endsection