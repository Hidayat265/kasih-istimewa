@extends('user.layouts.userLayouts')

@section('title', $event->event_name . ' | Kasih Istimewa')

@section('content')
<!-- Hero Section with Event Banner -->
<section class="relative h-64 md:h-96 bg-cover bg-center" 
         style="background-image: url('{{ $event->event_picture ? (str_starts_with($event->event_picture, 'http') ? $event->event_picture : asset('storage/' . $event->event_picture)) : 'https://placehold.co/1200x400/554994/ffffff?text=Event+Banner' }}');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-black/30"></div>
    <div class="relative h-full flex items-end pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto w-full">
            <div class="flex flex-wrap gap-2 mb-3">
                <!-- Approval Status Badge - Formatted nicely -->
                @if($event->event_approval_status == 'Approved')
                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-check-circle mr-1"></i> Approved
                    </span>
                @elseif($event->event_approval_status == 'Pending')
                    <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-clock mr-1"></i> Pending Approval
                    </span>
                @elseif($event->event_approval_status == 'Rejected')
                    <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-times-circle mr-1"></i> Rejected
                    </span>
                @elseif($event->event_approval_status == 'NeedsUpdate')
                    <span class="px-3 py-1 bg-orange-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-edit mr-1"></i> Needs Update
                    </span>
                @endif
                
                <!-- Event Status (for past events) -->
                @if($event->event_status)
                    @if($event->event_status == 'Successful')
                        <span class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-trophy mr-1"></i> Successful
                        </span>
                    @elseif($event->event_status == 'Unsuccessful')
                        <span class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-frown mr-1"></i> Unsuccessful
                        </span>
                    @endif
                @endif
                
                <!-- Upcoming/Past Status (only for approved events) -->
                @if($event->event_approval_status == 'Approved')
                    @if($event->isUpcoming())
                        <span class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-calendar-week mr-1"></i> Upcoming
                        </span>
                    @elseif($event->isPast())
                        <span class="px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-history mr-1"></i> Past Event
                        </span>
                    @endif
                @endif
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2">{{ $event->event_name }}</h1>
            <p class="text-white/90 text-lg">Hosted by {{ $event->event_company_name }}</p>
        </div>
    </div>
</section>

<!-- Event Details Section -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Event Description -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-align-left text-primary mr-3"></i> About This Event
                    </h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $event->event_description ?? 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-primary mr-3"></i> Date & Time
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-play-circle text-green-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-semibold text-gray-700">Start</p>
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($event->event_start_date)->format('l, F j, Y') }}</p>
                                <p class="text-primary font-medium">{{ $event->getStartSessionTimeAttribute() }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-stop-circle text-red-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-semibold text-gray-700">End</p>
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($event->event_end_date)->format('l, F j, Y') }}</p>
                                <p class="text-red-500 font-medium">{{ $event->getEndSessionTimeAttribute() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Location Details -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-primary mr-3"></i> Location
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-700">Venue</h3>
                            <p class="text-gray-600 mt-1">{{ $event->event_location_name ?? 'Location not specified' }}</p>
                        </div>
                        @if($event->event_location_address)
                            <div>
                                <h3 class="font-semibold text-gray-700">Full Address</h3>
                                <p class="text-gray-600 mt-1">{{ $event->event_location_address }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Map -->
                @if($event->event_location_latitude && $event->event_location_longitude)
                    <div class="bg-white rounded-2xl shadow-soft p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-map text-primary mr-3"></i> Location Map
                        </h2>
                        <div id="eventMap" style="height: 350px; border-radius: 12px; z-index: 1;" class="mb-4"></div>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://www.google.com/maps?q={{ $event->event_location_latitude }},{{ $event->event_location_longitude }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                <i class="fab fa-google mr-2"></i> Open in Google Maps
                            </a>
                            <a href="https://www.waze.com/ul?ll={{ $event->event_location_latitude }},{{ $event->event_location_longitude }}&navigate=yes" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                <i class="fab fa-waze mr-2"></i> Navigate with Waze
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info Card -->
                <div class="bg-white rounded-2xl shadow-soft p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-simple text-primary mr-3"></i> Quick Info
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Event ID -->
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-500">Event ID</span>
                            <span class="font-mono font-semibold text-gray-800">{{ $event->event_id }}</span>
                        </div>
                        
                        <!-- Organizer -->
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-500">Organizer</span>
                            <span class="font-semibold text-gray-800">{{ $event->creator->user_name ?? 'Unknown' }}</span>
                        </div>
                        
                        <!-- Volunteers -->
                        <div class="pb-2 border-b border-gray-100">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-500">Participants</span>
                                <span class="font-semibold text-gray-800">{{ $event->event_current_participant }}/{{ $event->event_maximum_participant }}</span>
                            </div>
                            @if($event->event_maximum_participant > 0)
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary rounded-full h-2" style="width: {{ ($event->event_current_participant / max($event->event_maximum_participant, 1)) * 100 }}%"></div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Admin Remarks (for rejected/Needs Update) -->
                        @if($event->event_remarks)
                            <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded">
                                <p class="text-xs text-red-600">
                                    <i class="fas fa-comment mr-1"></i> 
                                    <strong>Admin Note:</strong> {{ $event->event_remarks }}
                                </p>
                            </div>
                        @endif
                        
                        <!-- Post Review (for past events) -->
                        @if($event->event_post_review)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                                <p class="text-xs text-blue-600">
                                    <i class="fas fa-star mr-1"></i> 
                                    <strong>Event Review:</strong> {{ $event->event_post_review }}
                                </p>
                            </div>
                        @endif
                        
                        <!-- Created At -->
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-500">Created</span>
                            <span class="text-sm text-gray-600">{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Supporting Document - Only visible to event owner -->
                                                <!-- Supporting Document - Only visible to event owner -->
                        @if($event->event_created_by_id == auth()->user()->user_id && $event->event_document)
                            <div class="pt-2">
                                <a href="{{ route('document.download', $event->event_id) }}" target="_blank" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition text-sm font-medium">
                                    <i class="fas fa-file-pdf"></i> View Supporting Document
                                </a>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3">
                            <!-- Volunteer Registration - Only for approved upcoming events not created by current user -->
                            @if($event->isUpcoming() && $event->event_approval_status == 'Approved' && $event->event_publish && $event->event_created_by_id != auth()->user()->user_id)
                                <button onclick="registerForEvent()" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-hand-peace"></i> Register as Volunteer
                                </button>
                            @endif
                            
                            <!-- Edit Button - Only for creator when status is Pending or NeedsUpdate -->
                            @if($event->event_created_by_id == auth()->user()->user_id && in_array($event->event_approval_status, ['Pending', 'NeedsUpdate']))
                                <a href="{{ route('events.edit', $event->event_id) }}" 
                                   class="w-full block text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-xl transition">
                                    <i class="fas fa-edit mr-2"></i> Edit Event
                                </a>
                            @endif
                            
                            <!-- Back Button -->
                            <a href="{{ url()->previous() }}" 
                               class="w-full block text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition">
                                <i class="fas fa-arrow-left mr-2"></i> Go Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #eventMap {
        height: 350px;
        width: 100%;
        border-radius: 12px;
        z-index: 1;
    }
    .prose {
        line-height: 1.6;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map if coordinates exist
    @if($event->event_location_latitude && $event->event_location_longitude)
        const map = L.map('eventMap').setView([{{ $event->event_location_latitude }}, {{ $event->event_location_longitude }}], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Get location name for popup
        const locationName = "{{ $event->event_location_name ?? $event->event_location_address ?? $event->event_name }}";
        const locationAddress = "{{ $event->event_location_address ?? $event->event_location_name ?? '' }}";
        
        L.marker([{{ $event->event_location_latitude }}, {{ $event->event_location_longitude }}])
            .addTo(map)
            .bindPopup(`<strong>${locationName}</strong><br>${locationAddress}`)
            .openPopup();
    @endif
    
    // Register for event function
    function registerForEvent() {
        Swal.fire({
            title: 'Register as Volunteer?',
            text: 'Are you interested in volunteering for this event?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, register me!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add your registration logic here
                Swal.fire({
                    icon: 'info',
                    title: 'Coming Soon',
                    text: 'Volunteer registration will be available soon!',
                    confirmButtonColor: '#554994'
                });
            }
        });
    }
</script>
@endpush
@endsection
