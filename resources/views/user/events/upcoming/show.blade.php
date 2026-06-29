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
                <!-- Event Status Badge -->
                @if($event->isUpcoming())
                    <span class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-calendar-week mr-1"></i> Upcoming
                    </span>
                @elseif($event->isPast())
                    <span class="px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-history mr-1"></i> Past Event
                    </span>
                @endif
                
                <!-- Event Success Status -->
                @if($event->event_status == 'Successful')
                    <span class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-trophy mr-1"></i> Successful
                    </span>
                @elseif($event->event_status == 'Unsuccessful')
                    <span class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-frown mr-1"></i> Unsuccessful
                    </span>
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
                        <!-- Organizer (Company name only) -->
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-gray-500">Organizer</span>
                            <span class="font-semibold text-gray-800">{{ $event->event_company_name ?? 'Unknown' }}</span>
                        </div>
                        
                        <!-- Participants -->
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
                        
                        <!-- Post Review (for past events) -->
                        @if($event->event_post_review)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                                <p class="text-xs text-blue-600">
                                    <i class="fas fa-star mr-1"></i> 
                                    <strong>Event Review:</strong> {{ $event->event_post_review }}
                                </p>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3">
                            <!-- Participant Registration - Only for upcoming approved events and NOT the event creator -->
                            @if($event->isUpcoming() && $event->event_approval_status == 'Approved' && $event->event_publish)
                                @php
                                    $isFull = $event->is_full;
                                    $isCreator = ($event->event_created_by_id == auth()->user()->user_id);
                                    $isRegistered = $event->isUserRegistered(auth()->user()->user_id);
                                @endphp
                                
                                @if(!$isCreator)
                                    @if($isRegistered)
                                        <!-- Show registered message instead of cancel button -->
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            <span class="text-green-700 text-sm font-medium">You are registered for this event!</span>
                                            <p class="text-xs text-green-600 mt-1">Contact organizer for any changes to your registration.</p>
                                        </div>
                                    @elseif(!$isFull)
                                        <button onclick="registerForEvent()" 
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                                            <i class="fas fa-hand-peace"></i> Register as Participant
                                        </button>
                                    @else
                                        <button disabled 
                                                class="w-full bg-gray-400 text-white font-semibold py-3 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                            <i class="fas fa-ban"></i> Event Full
                                        </button>
                                    @endif
                                @endif
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
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
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
        
        const locationName = "{{ $event->event_location_name ?? $event->event_location_address ?? $event->event_name }}";
        const locationAddress = "{{ $event->event_location_address ?? $event->event_location_name ?? '' }}";
        
        L.marker([{{ $event->event_location_latitude }}, {{ $event->event_location_longitude }}])
            .addTo(map)
            .bindPopup(`<strong>${locationName}</strong><br>${locationAddress}`)
            .openPopup();
    @endif
    
    function registerForEvent() {
        // Show Terms and Conditions modal first
        Swal.fire({
            title: 'Terms and Conditions',
            html: `
                <div class="text-left max-h-96 overflow-y-auto">
                    <div class="mb-4">
                        <h3 class="font-semibold text-lg mb-2">Event Participation Agreement</h3>
                        <p class="text-sm text-gray-600 mb-3">By registering for this event, you agree to the following terms:</p>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <h4 class="font-semibold text-primary">1. Punctuality</h4>
                            <p class="text-gray-600">Participants are expected to arrive on time for all event sessions. Late arrivals may not be accommodated.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-primary">2. Code of Conduct</h4>
                            <p class="text-gray-600">All participants must behave respectfully towards organizers, volunteers, and fellow participants. Harassment or disruptive behavior will result in immediate removal.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-primary">3. Cancellation Policy</h4>
                            <p class="text-gray-600">Registration cancellations are at the discretion of event administrators. Please contact the event organizer for any changes.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-primary">4. Liability Waiver</h4>
                            <p class="text-gray-600">Participants assume all risks associated with event participation. The organizer is not liable for any injuries, losses, or damages.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-primary">5. Media Consent</h4>
                            <p class="text-gray-600">By participating, you consent to photography and video recording that may be used for promotional purposes.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-primary">6. Data Privacy</h4>
                            <p class="text-gray-600">Your personal information will be used solely for event coordination and will not be shared with third parties.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t">
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" id="acceptTerms" class="mt-1">
                            <span class="text-sm">I have read and agree to the <span class="text-primary font-semibold">Terms and Conditions</span> above.</span>
                        </label>
                    </div>
                </div>
            `,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: 'Accept & Register',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            preConfirm: () => {
                const acceptTerms = document.getElementById('acceptTerms');
                if (!acceptTerms.checked) {
                    Swal.showValidationMessage('You must accept the Terms and Conditions to register.');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with registration
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we register you for the event.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch('{{ route("participant.register", $event->event_id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ accept_terms: true })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registered!',
                            text: data.message,
                            confirmButtonColor: '#554994'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: data.message,
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                });
            }
        });
    }
</script>
@endpush
@endsection