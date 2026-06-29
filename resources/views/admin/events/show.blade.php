@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - Event Details')

@php
use Carbon\Carbon;
@endphp

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Event Details</h1>
    <button onclick="goBack()" 
            class="ml-auto md:mt-0 inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg shadow bg-primary hover:bg-primary/90 transition">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </button>
</div>

<div class="w-full max-w-4xl">
    <!-- Event Details -->
    <div class="bg-white rounded-xl shadow-soft p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-info-circle text-primary mr-2"></i> Event Information
        </h3>
        
        <div class="space-y-4">
            <!-- Event Image (only if exists) -->
            @if($event->event_picture)
                <div class="rounded-lg overflow-hidden">
                    <img src="{{ $event->event_picture }}" alt="{{ $event->event_name }}" class="w-full h-64 object-cover">
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Event ID</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $event->event_id }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Event Name</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $event->event_name }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Company/Organization</label>
                    <p class="text-sm text-gray-900">{{ $event->event_company_name }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Organizer</label>
                    <p class="text-sm text-gray-900">
                        <a href="{{ route('admin.users.profile', $event->event_created_by_id) }}" class="text-primary hover:underline">
                            {{ $event->creator->user_name ?? 'Unknown' }}
                        </a>
                    </p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Location</label>
                    <p class="text-sm text-gray-900">{{ $event->event_location_name ?? 'Not specified' }}</p>
                    @if($event->event_location_address)
                        <p class="text-xs text-gray-500 mt-1">{{ $event->event_location_address }}</p>
                    @endif
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Start Date & Time</label>
                    <p class="text-sm text-gray-900">
                        {{ Carbon::parse($event->event_start_date)->format('d M Y') }}
                        <span class="text-primary font-medium">
                            {{ $event->getStartSessionTimeAttribute() }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">End Date & Time</label>
                    <p class="text-sm text-gray-900">
                        {{ Carbon::parse($event->event_end_date)->format('d M Y') }}
                        <span class="text-primary font-medium">
                            {{ $event->getEndSessionTimeAttribute() }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Participants</label>
                    <p class="text-sm text-gray-900">{{ $event->event_current_participant }} / {{ $event->event_maximum_participant }}</p>
                    <div class="w-full h-1.5 bg-gray-200 rounded-full mt-1">
                        <div class="h-full bg-primary rounded-full" style="width: {{ $event->event_maximum_participant > 0 ? ($event->event_current_participant / $event->event_maximum_participant) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase">Document</label>
                    @if($event->event_document)
                        <a href="{{ route('document.download', $event->event_id) }}" target="_blank" class="text-sm text-primary hover:underline">
                            <i class="fas fa-file-pdf mr-1"></i> View Document
                        </a>
                    @else
                        <p class="text-sm text-gray-500">No document uploaded</p>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4">
                <label class="text-xs font-medium text-gray-500 uppercase">Description</label>
                <p class="text-sm text-gray-700 mt-1">{{ $event->event_description ?? 'No description provided' }}</p>
            </div>

            <hr class="border-gray-200">

            <!-- Status Information -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-clipboard-check text-primary mr-2"></i> Status Information
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Approval Status</label>
                        <div class="mt-1">
                            @if($event->event_approval_status == 'Approved')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                </span>
                            @elseif($event->event_approval_status == 'Pending')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($event->event_approval_status == 'Rejected')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                </span>
                            @elseif($event->event_approval_status == 'NeedsUpdate')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-edit mr-1"></i> Needs Update
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Event Status</label>
                        <div class="mt-1">
                            @if($event->event_status == 'Successful')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Successful
                                </span>
                            @elseif($event->event_status == 'Unsuccessful')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Unsuccessful
                                </span>
                            @elseif($event->event_status == 'Rejected')
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-600">
                                    <i class="fas fa-minus-circle mr-1"></i> Not Set
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Published</label>
                        <div class="mt-1">
                            @if($event->event_publish)
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-globe mr-1"></i> Published
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-600">
                                    <i class="fas fa-eye-slash mr-1"></i> Unpublished
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Approved By</label>
                        <p class="text-sm text-gray-900 mt-1">
                            @if($event->event_approver_id)
                                <a href="{{ route('admin.users.profile', $event->event_approver_id) }}" 
                                class="inline-flex items-center gap-2 text-primary hover:text-white bg-blue-50 hover:bg-primary px-3 py-1 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-user-check text-xs"></i>
                                    {{ $event->approver->user_name ?? 'Unknown' }}
                                </a>
                            @else
                                <span class="text-gray-500 inline-flex items-center gap-2">
                                    <i class="fas fa-clock text-xs"></i>
                                    Not approved yet
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($event->event_remarks)
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-gray-500 uppercase">Remarks</label>
                            <p class="text-sm text-gray-700 mt-1 bg-yellow-50 p-3 rounded-lg border border-yellow-200">{{ $event->event_remarks }}</p>
                        </div>
                    @endif
                    @if($event->event_post_review)
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-gray-500 uppercase">Post Event Review</label>
                            <p class="text-sm text-gray-700 mt-1 bg-blue-50 p-3 rounded-lg border border-blue-200">{{ $event->event_post_review }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Actions -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-cog text-primary mr-2"></i> Actions
                </h4>
                
                <div class="flex flex-wrap gap-2">
                    @if($event->event_approval_status == 'Pending' || $event->event_approval_status == 'NeedsUpdate')
                        <button onclick="approveEvent('{{ $event->event_id }}')" 
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition text-sm font-medium">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        
                        <button onclick="showRejectModal('{{ $event->event_id }}')" 
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition text-sm font-medium">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                        
                        <button onclick="showRequestUpdateModal('{{ $event->event_id }}')" 
                                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i> Request Update
                        </button>
                        
                        <!-- Edit Event Button for Pending/NeedsUpdate -->
                        <a href="{{ route('admin.events.edit', $event->event_id) }}" 
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition text-sm font-medium">
                            <i class="fas fa-pencil-alt mr-1"></i> Edit Event
                        </a>
                    @endif

                    @if($event->event_approval_status == 'Approved')
                        @if($event->event_publish)
                            <button onclick="unpublishEvent('{{ $event->event_id }}')" 
                                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition text-sm font-medium">
                                <i class="fas fa-eye-slash mr-1"></i> Unpublish
                            </button>
                        @else
                            <button onclick="publishEvent('{{ $event->event_id }}')" 
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition text-sm font-medium">
                                <i class="fas fa-globe mr-1"></i> Publish
                            </button>
                        @endif
                        
                        {{-- Show Post Review if event already has a status --}}
                        @if($event->event_status && $event->event_status != 'Rejected')
                            <button onclick="showPostReviewModal('{{ $event->event_id }}')" 
                                    class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition text-sm font-medium">
                                <i class="fas fa-comment mr-1"></i> Add Post Review
                            </button>
                        @endif
                        
                        {{-- Mark as Successful/Unsuccessful for past events --}}
                        @if(Carbon::parse($event->event_end_date)->lt(Carbon::now()))
                            @if($event->event_status != 'Successful')
                                <button onclick="markEventStatus('{{ $event->event_id }}', 'Successful')" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Mark Successful
                                </button>
                            @endif
                            @if($event->event_status != 'Unsuccessful')
                                <button onclick="markEventStatus('{{ $event->event_id }}', 'Unsuccessful')" 
                                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition text-sm font-medium">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Mark Unsuccessful
                                </button>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Location Map -->
    <div class="bg-white rounded-xl shadow-soft p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-map-marked-alt text-primary mr-2"></i> Location Map
        </h3>
        
        @if($event->event_location_latitude && $event->event_location_longitude)
            <div id="eventMap" class="w-full h-80 rounded-lg" 
                 data-lat="{{ $event->event_location_latitude }}" 
                 data-lng="{{ $event->event_location_longitude }}"
                 data-name="{{ $event->event_location_name }}"
                 data-address="{{ $event->event_location_address }}">
            </div>
            <div class="mt-3 text-sm text-gray-600">
                <i class="fas fa-map-pin text-primary mr-1"></i>
                {{ $event->event_location_name }}
                @if($event->event_location_address)
                    <span class="text-gray-400 ml-1">- {{ $event->event_location_address }}</span>
                @endif
            </div>
        @else
            <div class="w-full h-80 bg-gray-100 rounded-lg flex items-center justify-center">
                <div class="text-center text-gray-500">
                    <i class="fas fa-map-pin text-6xl mb-3"></i>
                    <p class="text-lg font-medium">Location coordinates not available</p>
                    <p class="text-sm">{{ $event->event_location_name ?? 'No location specified' }}</p>
                    @if($event->event_location_address)
                        <p class="text-sm mt-1">{{ $event->event_location_address }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Registered Participants -->
    <div class="bg-white rounded-xl shadow-soft p-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-users text-primary mr-2"></i> Registered Participants
            </h3>
            <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium">
                {{ $participants->total() ?? 0 }} Registered
            </span>
        </div>

        <!-- Search Participants -->
        <div class="mb-4">
            <input type="text" id="searchParticipant" 
                   placeholder="Search participants by name or email..." 
                   class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
        </div>

        <!-- Participants Table -->
        <div id="participants-container">
            @include('admin.events.partials.participants-table', ['participants' => $participants])
        </div>
    </div>
</div>

<!-- Post Review Modal -->
<div id="postReviewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Add Post Event Review</h3>
        <p class="text-gray-600 mb-4">Add your review or comments about this event:</p>
        
        <form id="postReviewForm" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Post Event Review</label>
                <textarea id="postReviewText" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Write your review about this event..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closePostReviewModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">Cancel</button>
                <button type="button" onclick="confirmPostReview('{{ $event->event_id }}')" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition">Save Review</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Update Event Status</h3>
        <p class="text-gray-600 mb-4">Set the outcome for this past event:</p>
        
        <form id="statusForm" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Status</label>
                <select id="eventStatusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    <option value="Successful">✅ Successful</option>
                    <option value="Unsuccessful">⚠️ Unsuccessful</option>
                    <option value="Rejected">❌ Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Review / Comments</label>
                <textarea id="eventReview" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Add any comments or review..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">Cancel</button>
                <button type="button" onclick="confirmUpdateStatus('{{ $event->event_id }}')" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition">Update Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Reject Event</h3>
        <p class="text-gray-600 mb-4">Please provide a reason for rejecting this event:</p>
        <textarea id="rejectReason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Enter rejection reason..."></textarea>
        <div class="flex justify-end gap-3 mt-4">
            <button onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">Cancel</button>
            <button onclick="confirmReject('{{ $event->event_id }}')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">Reject</button>
        </div>
    </div>
</div>

<!-- Request Update Modal -->
<div id="updateModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Request Update</h3>
        <p class="text-gray-600 mb-4">Provide feedback for the event creator:</p>
        <textarea id="updateFeedback" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Enter feedback..."></textarea>
        <div class="flex justify-end gap-3 mt-4">
            <button onclick="closeUpdateModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">Cancel</button>
            <button onclick="confirmRequestUpdate('{{ $event->event_id }}')" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">Send Request</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ============================================
    // BACK BUTTON
    // ============================================
    function goBack() {
        if (document.referrer && document.referrer.length > 0) {
            window.location.href = document.referrer;
        } else {
            window.location.href = '{{ route('admin.pendingevent') }}';
        }
    }

    let currentParticipantPage = 1;

    // ========== INITIALIZE MAP ==========
    document.addEventListener('DOMContentLoaded', function() {
        const mapContainer = document.getElementById('eventMap');
        if (mapContainer) {
            const lat = parseFloat(mapContainer.dataset.lat);
            const lng = parseFloat(mapContainer.dataset.lng);
            const name = mapContainer.dataset.name || 'Event Location';
            const address = mapContainer.dataset.address || '';

            // Initialize map
            const map = L.map('eventMap').setView([lat, lng], 15);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add marker
            const popupContent = `<strong>${name}</strong><br>${address}`;
            L.marker([lat, lng]).addTo(map)
                .bindPopup(popupContent)
                .openPopup();
        }
    });

    // ========== PARTICIPANT FUNCTIONS ==========
    function fetchParticipants(page = 1) {
        currentParticipantPage = page;
        const eventId = '{{ $event->event_id }}';
        const search = document.getElementById('searchParticipant')?.value || '';

        const params = new URLSearchParams();
        params.append('page', page);
        if (search) params.append('search', search);

        const url = `{{ route('admin.events.participants', $event->event_id) }}?${params.toString()}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('participants-container').innerHTML = data.html;
                attachParticipantPaginationHandlers();
                
                // Update the participant count
                const countElement = document.querySelector('.bg-primary\\/10.text-primary');
                if (countElement && data.total !== undefined) {
                    countElement.textContent = data.total + ' Registered';
                }
            }
        })
        .catch(error => console.error('Error fetching participants:', error));
    }

    function attachParticipantPaginationHandlers() {
        document.querySelectorAll('#participants-container .pagination-link').forEach(link => {
            link.removeEventListener('click', participantPaginationClickHandler);
            link.addEventListener('click', participantPaginationClickHandler);
        });
    }

    function participantPaginationClickHandler(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) {
            fetchParticipants(parseInt(page));
        }
    }

    // Search participants
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchParticipant');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetchParticipants(1);
                }, 500);
            });
        }
    });

    // ========== EVENT ACTION FUNCTIONS ==========
    // Approve Event with SweetAlert
    function approveEvent(eventId) {
        Swal.fire({
            title: 'Approve Event?',
            text: 'This event will be approved and can be published.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/events/${eventId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to approve event.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    function showRejectModal(eventId) {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
        document.getElementById('rejectReason').value = '';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }

    // Reject Event with SweetAlert
    function confirmReject(eventId) {
        const reason = document.getElementById('rejectReason').value.trim();
        if (!reason) {
            Swal.fire({
                icon: 'warning',
                title: 'Reason Required',
                text: 'Please provide a reason for rejecting this event.',
                confirmButtonColor: '#554994'
            });
            return;
        }

        Swal.fire({
            title: 'Reject Event?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, reject it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/events/${eventId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeRejectModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to reject event.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    function showRequestUpdateModal(eventId) {
        document.getElementById('updateModal').classList.remove('hidden');
        document.getElementById('updateModal').classList.add('flex');
        document.getElementById('updateFeedback').value = '';
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
        document.getElementById('updateModal').classList.remove('flex');
    }

    // Request Update with SweetAlert
    function confirmRequestUpdate(eventId) {
        const feedback = document.getElementById('updateFeedback').value.trim();
        if (!feedback) {
            Swal.fire({
                icon: 'warning',
                title: 'Feedback Required',
                text: 'Please provide feedback for the event creator.',
                confirmButtonColor: '#554994'
            });
            return;
        }

        Swal.fire({
            title: 'Request Update?',
            text: 'The event creator will be notified to make changes.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Yes, send request!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/events/${eventId}/request-update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ feedback: feedback })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeUpdateModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Sent!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to send update request.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // Publish Event with SweetAlert
    function publishEvent(eventId) {
        Swal.fire({
            title: 'Publish Event?',
            text: 'This event will become visible to all users.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'Yes, publish it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/events/${eventId}/publish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Published!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to publish event.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // Unpublish Event with SweetAlert
    function unpublishEvent(eventId) {
        Swal.fire({
            title: 'Unpublish Event?',
            text: 'This event will be hidden from users.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#eab308',
            confirmButtonText: 'Yes, unpublish it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/events/${eventId}/unpublish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Unpublished!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to unpublish event.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // ========== POST REVIEW FUNCTIONS ==========
    function showPostReviewModal(eventId) {
        document.getElementById('postReviewModal').classList.remove('hidden');
        document.getElementById('postReviewModal').classList.add('flex');
        document.getElementById('postReviewText').value = '';
    }

    function closePostReviewModal() {
        document.getElementById('postReviewModal').classList.add('hidden');
        document.getElementById('postReviewModal').classList.remove('flex');
    }

    function confirmPostReview(eventId) {
        const review = document.getElementById('postReviewText').value.trim();
        
        if (!review) {
            Swal.fire({
                icon: 'warning',
                title: 'Review Required',
                text: 'Please write a review for this event.',
                confirmButtonColor: '#554994'
            });
            return;
        }
        
        Swal.fire({
            title: 'Save Post Review?',
            text: 'This review will be saved for this event.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(`/admin/events/${eventId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        event_status: '{{ $event->event_status }}',
                        event_post_review: review
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closePostReviewModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to save review.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // ========== STATUS UPDATE FUNCTIONS ==========
    function showStatusModal(eventId) {
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('statusModal').classList.add('flex');
        document.getElementById('eventStatusSelect').value = '';
        document.getElementById('eventReview').value = '';
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
        document.getElementById('statusModal').classList.remove('flex');
    }

    function confirmUpdateStatus(eventId) {
        const status = document.getElementById('eventStatusSelect').value;
        const review = document.getElementById('eventReview').value.trim();
        
        if (!status) {
            Swal.fire({
                icon: 'warning',
                title: 'Status Required',
                text: 'Please select a status for this event.',
                confirmButtonColor: '#554994'
            });
            return;
        }
        
        Swal.fire({
            title: 'Update Event Status?',
            text: `This will mark the event as "${status}".`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(`/admin/events/${eventId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        event_status: status,
                        event_post_review: review
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeStatusModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to update event status.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // ========== QUICK STATUS UPDATE FUNCTIONS ==========
    function markEventStatus(eventId, status) {
        const statusLabels = {
            'Successful': '✅ Successful',
            'Unsuccessful': '⚠️ Unsuccessful',
            'Rejected': '❌ Rejected'
        };
        
        const statusColors = {
            'Successful': '#22c55e',
            'Unsuccessful': '#eab308',
            'Rejected': '#dc2626'
        };
        
        Swal.fire({
            title: `Mark as ${statusLabels[status]}?`,
            text: `This will mark the event as "${status}". ${status === 'Rejected' ? 'This will also reject the event.' : ''}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: statusColors[status],
            confirmButtonText: `Yes, mark as ${status}!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(`/admin/events/${eventId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        event_status: status,
                        event_post_review: null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to update event status.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }

    // Close modals on outside click
    document.addEventListener('click', function(e) {
        const rejectModal = document.getElementById('rejectModal');
        const updateModal = document.getElementById('updateModal');
        const statusModal = document.getElementById('statusModal');
        const postReviewModal = document.getElementById('postReviewModal');
        
        if (e.target === rejectModal) {
            closeRejectModal();
        }
        if (e.target === updateModal) {
            closeUpdateModal();
        }
        if (e.target === statusModal) {
            closeStatusModal();
        }
        if (e.target === postReviewModal) {
            closePostReviewModal();
        }
    });
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    #participants-container table {
        min-width: 100%;
    }
    #participants-container .pagination-link {
        cursor: pointer;
        transition: all 0.2s;
    }
    #participants-container .pagination-link:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush