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

            <form action="{{ route('donation.process') }}" method="POST" class="space-y-6" id="donationForm">
                @csrf

                <!-- Donor Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="donor_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="donor_name" id="donor_name"
                                value="{{ auth()->check() ? auth()->user()->user_name : old('donor_name') }}"
                                class="border focus:ring-primary focus:border-primary block w-full sm:text-lg border-gray-300 rounded-lg p-3 @if(auth()->check()) bg-gray-100 cursor-not-allowed @endif"
                                @if(auth()->check()) readonly @endif
                                required>
                        </div>
                        @if(auth()->check())
                            <p class="text-xs text-gray-500 mt-1">Auto-filled from your profile</p>
                        @endif
                    </div>

                    <div>
                        <label for="donor_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="email" name="donor_email" id="donor_email"
                                value="{{ auth()->check() ? auth()->user()->user_email : old('donor_email') }}"
                                class="border focus:ring-primary focus:border-primary block w-full sm:text-lg border-gray-300 rounded-lg p-3 @if(auth()->check()) bg-gray-100 cursor-not-allowed @endif"
                                @if(auth()->check()) readonly @endif
                                required>
                        </div>
                        @if(auth()->check())
                            <p class="text-xs text-gray-500 mt-1">Auto-filled from your profile</p>
                        @endif
                    </div>

                    <div class="md:col-span-2">
                        <label for="donor_phone" class="block text-sm font-medium text-gray-700">Phone Number (Optional)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="tel" name="donor_phone" id="donor_phone"
                                value="{{ auth()->check() ? auth()->user()->user_phone_number : old('donor_phone') }}"
                                class="border focus:ring-primary focus:border-primary block w-full sm:text-lg border-gray-300 rounded-lg p-3 @if(auth()->check()) bg-gray-100 cursor-not-allowed @endif"
                                @if(auth()->check()) readonly @endif>
                            <!-- Remove 'required' attribute -->
                        </div>
                        @if(auth()->check())
                            <p class="text-xs text-gray-500 mt-1">Auto-filled from your profile</p>
                        @endif
                    </div>
                </div>

                <!-- Donation Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Donation Amount</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
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
                </div>

                <div>
                    <label for="donation_amount" class="block text-sm font-medium text-gray-700">Custom Amount (RM)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">RM</span>
                        </div>
                        <input type="number" name="donation_amount" id="donation_amount"
                            class="border focus:ring-primary focus:border-primary block w-full pl-10 pr-12 sm:text-lg border-gray-300 rounded-lg p-3"
                            placeholder="Enter custom amount" min="1" step="0.01">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum donation: RM 1.00</p>
                </div>

                <!-- Payment Method Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Method</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- ToyyibPay Option -->
                        <label class="payment-method-card relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-primary hover:bg-primary/5 transition duration-150">
                            <input type="radio" name="payment_method" value="toyyibpay" class="sr-only payment-method-radio" checked>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 flex items-center justify-center bg-primary/10 rounded-lg">
                                    <i class="fas fa-credit-card text-primary text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">ToyyibPay</p>
                                    <p class="text-xs text-gray-500">Pay via online banking</p>
                                </div>
                            </div>
                            <div class="absolute top-3 right-3">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div class="w-3.5 h-3.5 rounded-full bg-primary opacity-0 transition-opacity duration-200"></div>
                                </div>
                            </div>
                        </label>

                        <!-- Stripe Option -->
                        <label class="payment-method-card relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-primary hover:bg-primary/5 transition duration-150">
                            <input type="radio" name="payment_method" value="stripe" class="sr-only payment-method-radio">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 flex items-center justify-center bg-[#635bff]/10 rounded-lg">
                                    <i class="fab fa-stripe text-[#635bff] text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Stripe</p>
                                    <p class="text-xs text-gray-500">Pay with credit/debit card</p>
                                </div>
                            </div>
                            <div class="absolute top-3 right-3">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div class="w-3.5 h-3.5 rounded-full bg-primary opacity-0 transition-opacity duration-200"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="donate-submit-btn"
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-full shadow-md text-lg font-medium text-white bg-gradient-to-r from-primary to-secondary hover:opacity-90 transition duration-300 ease-in-out transform hover:scale-[1.01]">
                    <i class="fas fa-heart mr-2"></i> Donate Now
                </button>

                <p class="text-xs text-gray-500 text-center mt-4">
                    <i class="fas fa-lock mr-1"></i> Your payment information is secure and encrypted
                </p>

                @guest
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            You are donating as a guest. 
                            <a href="{{ route('login') }}" class="font-medium text-blue-700 underline hover:text-blue-900">Login</a> or 
                            <a href="{{ route('register') }}" class="font-medium text-blue-700 underline hover:text-blue-900">Register</a> to track your donations.
                        </p>
                    </div>
                @endguest
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
<!-- SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Donation page loaded');
        
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 not loaded!');
            return;
        }

        // ─── PAYMENT METHOD CARD SELECTION ──────────────────────────────
        const paymentCards = document.querySelectorAll('.payment-method-card');
        const paymentRadios = document.querySelectorAll('.payment-method-radio');
        
        function updatePaymentSelection(selectedRadio) {
            paymentCards.forEach(card => {
                card.classList.remove('border-primary', 'bg-primary/5');
                card.classList.add('border-gray-200');
                const dot = card.querySelector('.w-3\\.5.h-3\\.5.rounded-full');
                if (dot) dot.classList.add('opacity-0');
            });
            
            const parentCard = selectedRadio.closest('.payment-method-card');
            if (parentCard) {
                parentCard.classList.remove('border-gray-200');
                parentCard.classList.add('border-primary', 'bg-primary/5');
                const dot = parentCard.querySelector('.w-3\\.5.h-3\\.5.rounded-full');
                if (dot) dot.classList.remove('opacity-0');
            }
        }
        
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    updatePaymentSelection(this);
                }
            });
        });
        
        // Set initial state
        const checkedRadio = document.querySelector('.payment-method-radio:checked');
        if (checkedRadio) {
            updatePaymentSelection(checkedRadio);
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
                document.getElementById('donation_amount').value = amount;
            });
        });
        
        // ─── FORM SUBMISSION HANDLER ──────────────────────────────────────
        const form = document.getElementById('donationForm');
        const submitBtn = document.getElementById('donate-submit-btn');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate amount
            const amount = document.getElementById('donation_amount').value;
            if (!amount || amount < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Please select or enter a valid donation amount (minimum RM 1.00)',
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            // Get selected payment method
            const selectedPayment = document.querySelector('.payment-method-radio:checked');
            if (!selectedPayment) {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Method Required',
                    text: 'Please select a payment method (ToyyibPay or Stripe)',
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            const paymentMethod = selectedPayment.value;
            
            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we prepare your payment',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Get form data
            const formData = new FormData(this);
            formData.append('payment_method', paymentMethod);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // Endpoint: send to unified process route which creates donation and forwards to gateway
            let endpoint = '{{ route("donation.process") }}';
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.close();
                
                // Accept several possible redirect fields returned by different controllers
                const redirectUrl = data.redirect_url || data.payment_url || data.url || data.checkout_url || data.checkout_session_url;
                if (data.success && redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Error',
                        text: data.message || 'Failed to process payment. Please try again.',
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
    
    .payment-method-card {
        transition: all 0.2s ease;
    }
    
    .payment-method-card .w-3\\.5.h-3\\.5.rounded-full {
        transition: opacity 0.2s ease;
    }
    
    input:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
</style>
@endpush

@endsection