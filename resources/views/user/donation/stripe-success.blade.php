@extends('user.layouts.userLayouts')

@section('title', 'Donation Successful - Kasih Istimewa')

@section('content')
<section class="py-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-5xl text-green-600"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You for Your Donation!</h1>
            
            <p class="text-lg text-gray-600 mb-4">
                Your generosity helps us continue making a difference in our community.
            </p>
            
            @if(session('donation_id'))
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600">Donation Reference</p>
                    <p class="text-lg font-semibold text-primary">{{ session('donation_id') }}</p>
                    <p class="text-sm text-gray-600 mt-2">Amount: <strong>RM {{ number_format(session('amount'), 2) }}</strong></p>
                </div>
            @endif
            
            <!-- Stripe sends the receipt automatically -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-left mb-6">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-envelope mr-2"></i>
                    A receipt has been sent to your email by Stripe.
                </p>
            </div>

            <!-- Receipt Button -->
            <div class="mb-6">
                @if(session('donation_id'))
                    <a href="{{ route('donation.receipt', session('donation_id')) }}" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-file-pdf mr-2"></i> View System Receipt
                    </a>
                @endif
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.donations') }}" 
                   class="px-6 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition">
                    View My Donations
                </a>
                <a href="{{ route('home') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Return Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection