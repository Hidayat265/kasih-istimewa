@extends('user.layouts.userLayouts')

@section('title', 'Dashboard | Kasih Istimewa')

@section('content')

<!-- Hero/Welcome Section -->
<section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-white mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold">Welcome back, {{ auth()->user()->user_name }}!</h1>
                <p class="text-white/80 mt-1">Here's what's happening with your account</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('user.upcomingevents') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i> Find Events
                </a>
                <a href="{{ route('user.donate') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-heart"></i> Donate Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-8 md:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-soft p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Events Registered</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $registeredEventsCount ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('participant.my-registrations') }}" class="text-primary text-sm mt-2 inline-block hover:underline">View All →</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-soft p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Events Created</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $myEventsCount ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-green-600 text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('user.myEvents') }}" class="text-primary text-sm mt-2 inline-block hover:underline">Manage Events →</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-soft p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Donations</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalDonations ?? 0 }}</p>
                        <p class="text-sm text-green-600 font-semibold">RM {{ number_format($totalDonatedAmount ?? 0, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-purple-600 text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('user.donations') }}" class="text-primary text-sm mt-2 inline-block hover:underline">View History →</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Upcoming Registered Events (Left Column - 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar-week text-primary mr-2"></i> Upcoming Events You're Registered For
                        </h2>
                        <a href="{{ route('participant.my-registrations') }}" class="text-primary text-sm hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($upcomingRegisteredEvents ?? [] as $registration)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $registration->event->event_name }}</h3>
                                    <div class="flex flex-wrap gap-3 mt-1 text-sm text-gray-500">
                                        <span><i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($registration->event->event_start_date)->format('d M Y') }}</span>
                                        <span><i class="fas fa-clock mr-1"></i> {{ $registration->event->getStartSessionTimeAttribute() }}</span>
                                        <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $registration->event->event_location_name ?? 'TBD' }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('events.public.show', $registration->event->event_id) }}" 
                                   class="px-3 py-1.5 bg-primary/10 text-primary rounded-lg text-sm font-medium hover:bg-primary/20 transition text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-3xl mb-2 block"></i>
                            <p>You haven't registered for any upcoming events yet.</p>
                            <a href="{{ route('user.upcomingevents') }}" class="inline-block mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition text-sm">
                                Browse Events
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Donations -->
                <div class="bg-white rounded-xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-history text-primary mr-2"></i> Recent Donations
                        </h2>
                        <a href="{{ route('user.donations') }}" class="text-primary text-sm hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentDonations ?? [] as $donation)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">RM {{ number_format($donation->donation_amount ?? $donation->amount ?? 0, 2) }}</p>
                                    <p class="text-sm text-gray-500">{{ $donation->created_at ? \Carbon\Carbon::parse($donation->created_at)->format('d M Y, h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if(in_array($donation->donation_status, ['success', 'completed'])) bg-green-100 text-green-800
                                        @elseif($donation->donation_status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($donation->donation_status ?? 'Completed') }}
                                    </span>
                                    @if(in_array($donation->donation_status, ['success', 'completed']))
                                        <button onclick="viewReceipt('{{ $donation->donation_id ?? $donation->id }}')" 
                                                class="text-primary hover:text-primary/80 text-sm transition" 
                                                title="View Receipt">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-300 text-sm" title="No receipt available for pending donations">
                                            <i class="fas fa-file-pdf"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-hand-holding-heart text-3xl mb-2 block"></i>
                            <p>No donations yet.</p>
                            <a href="{{ route('user.donate') }}" class="inline-block mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                                Make Your First Donation
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions (1/3) -->
            <div class="space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-primary mr-2"></i> Quick Actions
                    </h2>
                    <div class="space-y-3">
                        <a href="{{ route('user.events.create') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-primary/10 transition group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center group-hover:bg-primary/30">
                                    <i class="fas fa-calendar-plus text-primary text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-700">Create New Event</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-primary"></i>
                        </a>
                        <a href="{{ route('user.donate') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-green-50 transition group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200">
                                    <i class="fas fa-heart text-green-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-700">Make a Donation</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-600"></i>
                        </a>
                        <a href="{{ route('user.upcomingevents') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200">
                                    <i class="fas fa-search text-blue-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-700">Find Volunteer Events</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                        </a>
                        <a href="{{ route('user.myEvents') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-purple-50 transition group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200">
                                    <i class="fas fa-list-ul text-purple-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-700">Manage My Events</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-purple-600"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function viewReceipt(donationId) {
        if (!donationId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Donation ID not found.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        // Open receipt in new tab
        window.open(`/receipt/${donationId}/view`, '_blank');
    }
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
</style>
@endpush
@endsection