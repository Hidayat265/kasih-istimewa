@extends('user.layouts.userLayouts')

@section('title', 'Donation Failed - Kasih Istimewa')

@section('content')
<section class="py-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-times-circle text-5xl text-red-600"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Donation Failed</h1>
            
            <p class="text-lg text-gray-600 mb-6">
                We're sorry, but your donation could not be processed at this time.
            </p>
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 text-left mb-6">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-left mb-6">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Please try again or contact our support team if the problem persists.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.donate') }}" 
                   class="px-6 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition">
                    Try Again
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