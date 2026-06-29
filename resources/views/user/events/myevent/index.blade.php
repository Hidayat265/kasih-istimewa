@extends('user.layouts.userLayouts')

@section('title', 'My Events | Kasih Istimewa')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">My Events</h1>
            <p class="text-base md:text-lg lg:text-xl opacity-90 max-w-2xl mx-auto px-4">
                Manage events you've created and view events you've registered for
            </p>
        </div>
    </section>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button onclick="switchTab('created')" id="tab-created-btn" 
                    class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-primary text-primary">
                    <i class="fas fa-calendar-plus mr-2"></i> Events I Created
                </button>
                <button onclick="switchTab('registered')" id="tab-registered-btn" 
                    class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-calendar-check mr-2"></i> Events I Registered For
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab 1: Events I Created -->
    <div id="created-events" class="tab-content">
        <!-- Filter Buttons - Horizontal scroll on mobile -->
        <section class="py-4 md:py-6 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="overflow-x-auto pb-3 mb-4 md:mb-6 -mx-4 px-4">
                    <div class="flex flex-nowrap gap-2 md:gap-4 min-w-max">
                        <button data-filter="all" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-primary text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                            <i class="fas fa-calendar-alt mr-1 md:mr-2"></i> All Events
                        </button>
                        <button data-filter="Approved" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                            <i class="fas fa-check-circle mr-1 md:mr-2"></i> Approved
                        </button>
                        <button data-filter="Pending" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                            <i class="fas fa-clock mr-1 md:mr-2"></i> Pending
                        </button>
                        <button data-filter="NeedsUpdate" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                            <i class="fas fa-edit mr-1 md:mr-2"></i> Needs Update
                        </button>
                        <button data-filter="past" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                            <i class="fas fa-history mr-1 md:mr-2"></i> Past
                        </button>
                        <button data-filter="Rejected" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                            <i class="fas fa-times-circle mr-1 md:mr-2"></i> Rejected
                        </button>   
                    </div>
                </div>
                
                <!-- Search and Sort -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-3 md:pt-4 border-t">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search by event name, description or location..." 
                            class="w-full pl-10 pr-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm md:text-base">
                    </div>
                    <div class="sm:w-64">
                        <select id="sortSelect" class="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-primary bg-white">
                            <option value="date_desc" selected>Sort by Date (Latest first)</option>
                            <option value="date_asc">Sort by Date (Earliest first)</option>
                            <option value="name_asc">Sort by Name (A-Z)</option>
                            <option value="name_desc">Sort by Name (Z-A)</option>
                            <option value="volunteer_desc">Sort by Participants (Most first)</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <!-- Created Events Grid Section -->
        <section class="py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-4 md:mb-6">
                    <p class="text-sm md:text-base text-gray-600"><span id="eventCount">0</span> events found</p>
                </div>

                <div id="eventsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                    <!-- Events will be loaded here -->
                </div>

                <div id="loadingSpinner" class="text-center py-12 hidden">
                    <i class="fas fa-spinner fa-spin text-3xl md:text-4xl text-primary"></i>
                    <p class="mt-2 text-gray-500 text-sm md:text-base">Loading events...</p>
                </div>

                <div id="noResults" class="hidden text-center py-12">
                    <i class="fas fa-calendar-times text-5xl md:text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-700">No events found</h3>
                    <p class="text-sm md:text-base text-gray-500 mt-2">Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('user.events.create') }}" class="inline-block mt-4 px-4 md:px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition text-sm md:text-base">
                        <i class="fas fa-plus mr-2"></i> Create New Event
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- Tab 2: Events I Registered For -->
    <div id="registered-events" class="tab-content hidden">
        <section class="py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div id="registeredEventsContainer">
                    <!-- Registered events will be loaded here -->
                </div>
            </div>
        </section>
    </div>
</main>

@push('scripts')
<script>
    let currentTab = 'created';
    let allEvents = [];
    let currentFilter = 'all';
    let currentSearch = '';
    let currentSort = 'date_desc';

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#554994'
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif
        
        setupEventListeners();
        fetchEvents();
    });

    function switchTab(tab) {
        currentTab = tab;
        
        const createdBtn = document.getElementById('tab-created-btn');
        const registeredBtn = document.getElementById('tab-registered-btn');
        const createdContent = document.getElementById('created-events');
        const registeredContent = document.getElementById('registered-events');
        
        if (tab === 'created') {
            createdBtn.classList.add('border-primary', 'text-primary');
            createdBtn.classList.remove('border-transparent', 'text-gray-500');
            registeredBtn.classList.remove('border-primary', 'text-primary');
            registeredBtn.classList.add('border-transparent', 'text-gray-500');
            createdContent.classList.remove('hidden');
            registeredContent.classList.add('hidden');
        } else {
            registeredBtn.classList.add('border-primary', 'text-primary');
            registeredBtn.classList.remove('border-transparent', 'text-gray-500');
            createdBtn.classList.remove('border-primary', 'text-primary');
            createdBtn.classList.add('border-transparent', 'text-gray-500');
            registeredContent.classList.remove('hidden');
            createdContent.classList.add('hidden');
            
            loadRegisteredEvents();
        }
    }

    async function fetchEvents() {
        try {
            const spinner = document.getElementById('loadingSpinner');
            const eventsGrid = document.getElementById('eventsGrid');
            
            if (spinner) spinner.classList.remove('hidden');
            if (eventsGrid) eventsGrid.classList.add('hidden');
            
            const response = await fetch('{{ route("user.myEvents.data") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            
            if (data.success) {
                allEvents = data.events;
                console.log('Events loaded:', allEvents.length);
                filterAndRenderEvents();
            }
            
            if (spinner) spinner.classList.add('hidden');
            if (eventsGrid) eventsGrid.classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching events:', error);
            const spinner = document.getElementById('loadingSpinner');
            const eventsGrid = document.getElementById('eventsGrid');
            
            if (spinner) spinner.classList.add('hidden');
            if (eventsGrid) {
                eventsGrid.innerHTML = `
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-12">
                        <i class="fas fa-exclamation-triangle text-5xl md:text-6xl text-red-400 mb-4"></i>
                        <h3 class="text-lg md:text-xl font-semibold text-gray-700">Error loading events</h3>
                        <p class="text-sm md:text-base text-gray-500 mt-2">Please refresh the page and try again.</p>
                    </div>
                `;
                eventsGrid.classList.remove('hidden');
            }
        }
    }

    function loadRegisteredEvents() {
        const container = document.getElementById('registeredEventsContainer');
        
        container.innerHTML = `
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
                <p class="mt-2 text-gray-500">Loading your registered events...</p>
            </div>
        `;
        
        fetch('{{ route("participant.my-registrations.data") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.registrations.length > 0) {
                renderRegisteredEvents(data.registrations);
            } else {
                container.innerHTML = `
                    <div class="text-center py-12 bg-white rounded-xl shadow-soft">
                        <i class="fas fa-calendar-times text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500">You haven't registered for any events yet.</p>
                        <a href="{{ route('user.upcomingevents') }}" class="inline-block mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                            Browse Events
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-3"></i>
                    <p class="text-gray-500">Failed to load registered events. Please refresh the page.</p>
                </div>
            `;
        });
    }

    function renderRegisteredEvents(registrations) {
        const container = document.getElementById('registeredEventsContainer');
        
        let html = `
            <div class="mb-4 md:mb-6">
                <p class="text-sm md:text-base text-gray-600"><span id="registeredCount">${registrations.length}</span> events registered</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
        `;
        
        registrations.forEach(reg => {
            const event = reg.event;
            const startDate = new Date(event.event_start_date);
            const endDate = new Date(event.event_end_date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const isPast = endDate < today;
            const isFull = event.event_current_participant >= event.event_maximum_participant;
            
            let imageUrl = 'https://placehold.co/600x400/554994/ffffff?text=Event';
            if (event.event_picture && event.event_picture !== 'null') {
                if (event.event_picture.startsWith('http')) {
                    imageUrl = event.event_picture;
                } else {
                    imageUrl = '/storage/' + event.event_picture;
                }
            }
            
            function getStartTime(session) {
                const times = { 'Morning': '8:00 AM', 'Afternoon': '1:00 PM', 'Evening': '6:00 PM' };
                return times[session] || session;
            }
            
            function getEndTime(session) {
                const times = { 'Morning': '12:00 PM', 'Afternoon': '5:00 PM', 'Evening': '10:00 PM' };
                return times[session] || session;
            }
            
            html += `
                <div class="bg-white rounded-lg md:rounded-xl shadow-soft overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="relative">
                        <img src="${imageUrl}" alt="${event.event_name}" class="w-full h-40 md:h-48 object-cover" onerror="this.src='https://placehold.co/600x400/554994/ffffff?text=Event'">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${isPast ? 'bg-gray-500' : 'bg-green-500'} text-white">
                                ${isPast ? 'Past' : 'Upcoming'}
                            </span>
                        </div>
                        ${isFull && !isPast ? `
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">
                                    <i class="fas fa-ban mr-1"></i> Full
                                </span>
                            </div>
                        ` : ''}
                    </div>
                    <div class="p-4 md:p-5">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 line-clamp-1">${escapeHtml(event.event_name)}</h3>
                        
                        <div class="flex items-start text-xs md:text-sm text-gray-500 mb-2">
                            <i class="fas fa-play-circle mr-1 md:mr-2 text-green-500 text-xs md:text-sm mt-0.5"></i>
                            <div class="flex-1">
                                <span class="font-medium text-gray-700">Start:</span>
                                <span>${formatDate(startDate)}</span>
                                <span class="mx-1">•</span>
                                <span class="text-primary font-medium">${getStartTime(event.event_start_session)}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-start text-xs md:text-sm text-gray-500 mb-3">
                            <i class="fas fa-stop-circle mr-1 md:mr-2 text-red-500 text-xs md:text-sm mt-0.5"></i>
                            <div class="flex-1">
                                <span class="font-medium text-gray-700">End:</span>
                                <span>${formatDate(endDate)}</span>
                                <span class="mx-1">•</span>
                                <span class="text-red-500 font-medium">${getEndTime(event.event_end_session)}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-xs md:text-sm text-gray-500 mb-3">
                            <i class="fas fa-map-marker-alt mr-1 md:mr-2 text-primary text-xs md:text-sm"></i>
                            <span class="truncate">${event.event_location_name || 'No location'}</span>
                        </div>
                        
                        <div class="flex items-center justify-between mb-3 md:mb-4">
                            <div class="flex items-center text-xs md:text-sm text-gray-500">
                                <i class="fas fa-users mr-1 md:mr-2 text-green-500 text-xs md:text-sm"></i>
                                <span>${event.event_current_participant || 0}/${event.event_maximum_participant || 0} participants</span>
                            </div>
                            <div class="w-16 md:w-24 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: ${((event.event_current_participant || 0) / (event.event_maximum_participant || 1)) * 100}%"></div>
                            </div>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            <i class="fas fa-calendar-check mr-1 text-primary"></i> Registered on: ${new Date(reg.participant_registered_at).toLocaleDateString()}
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="/events/public/${event.event_id}" class="flex-1 text-center px-2 md:px-3 py-1.5 md:py-2 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition text-xs md:text-sm">
                                <i class="fas fa-info-circle mr-1"></i>View Details
                            </a>
                            ${!isPast && !isFull ? `
                                <button onclick="cancelRegistration('${event.event_id}')" class="px-2 md:px-3 py-1.5 md:py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition text-xs md:text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `</div>`;
        container.innerHTML = html;
    }

    function formatDate(date) {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function cancelRegistration(eventId) {
        Swal.fire({
            title: 'Cancel Registration?',
            text: 'Please provide a reason for cancelling your registration:',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Enter your reason for cancelling...',
            inputAttributes: {
                'aria-label': 'Cancellation reason',
                'maxlength': '255'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#554994',
            confirmButtonText: 'Yes, cancel it',
            cancelButtonText: 'No',
            inputValidator: (value) => {
                if (!value || value.trim().length < 5) {
                    return 'Please provide a valid reason (at least 5 characters)';
                }
                return null;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value.trim();
                
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch(`/participant/cancel-own/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: 'Your registration has been cancelled.',
                            confirmButtonColor: '#554994'
                        }).then(() => {
                            loadRegisteredEvents();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to cancel registration.',
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to cancel registration. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                });
            }
        });
    }

    function getStatusBadge(event) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const isPast = new Date(event.event_end_date) < today;
        
        if (event.event_approval_status === 'Rejected') {
            return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-red-100 text-red-800 text-xs rounded-full"><i class="fas fa-times-circle mr-1"></i> Rejected</span>';
        }
        
        if (event.event_approval_status === 'Pending') {
            return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full"><i class="fas fa-clock mr-1"></i> Pending</span>';
        }
        
        if (event.event_approval_status === 'NeedsUpdate') {
            return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-orange-100 text-orange-800 text-xs rounded-full"><i class="fas fa-edit mr-1"></i> Needs Update</span>';
        }
        
        if (event.event_approval_status === 'Approved') {
            if (isPast && event.event_status) {
                if (event.event_status === 'Successful') {
                    return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-green-100 text-green-800 text-xs rounded-full"><i class="fas fa-trophy mr-1"></i> Successful</span>';
                } else if (event.event_status === 'Unsuccessful') {
                    return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-red-100 text-red-800 text-xs rounded-full"><i class="fas fa-frown mr-1"></i> Unsuccessful</span>';
                }
            }
            return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-green-100 text-green-800 text-xs rounded-full"><i class="fas fa-check-circle mr-1"></i> Approved</span>';
        }
        
        return '<span class="px-2 py-0.5 md:px-2 md:py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Unknown</span>';
    }

    function filterAndRenderEvents() {
        let filteredEvents = [...allEvents];
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        switch(currentFilter) {
            case 'Approved':
                filteredEvents = filteredEvents.filter(event => 
                    event.event_approval_status === 'Approved'
                );
                break;
            case 'past':
                filteredEvents = filteredEvents.filter(event => 
                    event.event_approval_status === 'Approved' && 
                    new Date(event.event_end_date) < today
                );
                break;
            case 'Pending':
                filteredEvents = filteredEvents.filter(event => event.event_approval_status === 'Pending');
                break;
            case 'Rejected':
                filteredEvents = filteredEvents.filter(event => event.event_approval_status === 'Rejected');
                break;
            case 'NeedsUpdate':
                filteredEvents = filteredEvents.filter(event => event.event_approval_status === 'NeedsUpdate');
                break;
            default:
                break;
        }
        
        if (currentSearch) {
            const searchLower = currentSearch.toLowerCase();
            filteredEvents = filteredEvents.filter(event => 
                (event.event_name || '').toLowerCase().includes(searchLower) ||
                (event.event_description || '').toLowerCase().includes(searchLower) ||
                (event.event_location_name || '').toLowerCase().includes(searchLower)
            );
        }
        
        filteredEvents.sort((a, b) => {
            switch(currentSort) {
                case 'date_asc':
                    return new Date(a.event_start_date) - new Date(b.event_start_date);
                case 'date_desc':
                    return new Date(b.event_start_date) - new Date(a.event_start_date);
                case 'name_asc':
                    return (a.event_name || '').localeCompare(b.event_name || '');
                case 'name_desc':
                    return (b.event_name || '').localeCompare(a.event_name || '');
                case 'volunteer_desc':
                    return (b.event_current_participant || 0) - (a.event_current_participant || 0);
                default:
                    return 0;
            }
        });
        
        const eventCount = document.getElementById('eventCount');
        const eventsGrid = document.getElementById('eventsGrid');
        const noResults = document.getElementById('noResults');
        
        if (eventCount) eventCount.textContent = filteredEvents.length;
        
        if (filteredEvents.length === 0) {
            if (eventsGrid) eventsGrid.classList.add('hidden');
            if (noResults) noResults.classList.remove('hidden');
        } else {
            if (eventsGrid) {
                eventsGrid.classList.remove('hidden');
                eventsGrid.innerHTML = filteredEvents.map(event => renderEventCard(event)).join('');
            }
            if (noResults) noResults.classList.add('hidden');
        }
    }

    function updateActiveFilter(activeFilter) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.dataset.filter === activeFilter) {
                btn.classList.remove('bg-gray-200', 'text-gray-700');
                btn.classList.add('bg-primary', 'text-white');
            } else {
                btn.classList.remove('bg-primary', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            }
        });
    }

    function truncateDescription(description, maxLength = 80) {
        if (!description) return '';
        if (description.length <= maxLength) return description;
        return description.substring(0, maxLength) + '...';
    }

    function renderEventCard(event) {
        const startDate = new Date(event.event_start_date);
        const endDate = new Date(event.event_end_date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const isPast = new Date(event.event_end_date) < today;
        
        const formatDate = (date) => {
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        };
        
        function getStartTime(session) {
            const times = {
                'Morning': '8:00 AM',
                'Afternoon': '1:00 PM',
                'Evening': '6:00 PM'
            };
            return times[session] || session;
        }
        
        function getEndTime(session) {
            const times = {
                'Morning': '12:00 PM',
                'Afternoon': '5:00 PM',
                'Evening': '10:00 PM'
            };
            return times[session] || session;
        }
        
        let imageUrl = 'https://placehold.co/600x400/554994/ffffff?text=My+Event';
        if (event.event_picture && event.event_picture !== 'null' && event.event_picture !== null) {
            if (event.event_picture.startsWith('http')) {
                imageUrl = event.event_picture;
            } else {
                imageUrl = '/storage/' + event.event_picture;
            }
        }
        
        const showEdit = event.event_approval_status === 'Pending' || event.event_approval_status === 'NeedsUpdate';
        const description = truncateDescription(event.event_description);
        
        return `
            <div class="event-card bg-white rounded-lg md:rounded-xl shadow-soft overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="relative">
                    <img src="${imageUrl}" alt="${event.event_name}" class="w-full h-40 md:h-48 object-cover" onerror="this.src='https://placehold.co/600x400/554994/ffffff?text=Event'">
                    <div class="absolute top-2 right-2 md:top-4 md:right-4">
                        ${getStatusBadge(event)}
                    </div>
                </div>
                <div class="p-4 md:p-5">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 line-clamp-1">${escapeHtml(event.event_name) || 'Untitled Event'}</h3>
                    
                    ${description ? `<p class="text-sm text-gray-600 mb-3 line-clamp-2">${escapeHtml(description)}</p>` : ''}
                    
                    <div class="flex items-start text-xs md:text-sm text-gray-500 mb-2">
                        <i class="fas fa-play-circle mr-1 md:mr-2 text-green-500 text-xs md:text-sm mt-0.5"></i>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700">Start:</span>
                            <span>${formatDate(startDate)}</span>
                            <span class="mx-1">•</span>
                            <span class="text-primary font-medium">${getStartTime(event.event_start_session)}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-start text-xs md:text-sm text-gray-500 mb-3">
                        <i class="fas fa-stop-circle mr-1 md:mr-2 text-red-500 text-xs md:text-sm mt-0.5"></i>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700">End:</span>
                            <span>${formatDate(endDate)}</span>
                            <span class="mx-1">•</span>
                            <span class="text-red-500 font-medium">${getEndTime(event.event_end_session)}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-xs md:text-sm text-gray-500 mb-3">
                        <i class="fas fa-map-marker-alt mr-1 md:mr-2 text-primary text-xs md:text-sm"></i>
                        <span class="truncate">${escapeHtml(event.event_location_name) || 'No location'}</span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-3 md:mb-4">
                        <div class="flex items-center text-xs md:text-sm text-gray-500">
                            <i class="fas fa-users mr-1 md:mr-2 text-green-500 text-xs md:text-sm"></i>
                            <span>${event.event_current_participant || 0}/${event.event_maximum_participant || 0} participants</span>
                        </div>
                        <div class="w-16 md:w-24 h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: ${((event.event_current_participant || 0) / (event.event_maximum_participant || 1)) * 100}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 mt-3 md:mt-4">
                        <a href="/events/${event.event_id}" class="flex-1 text-center px-2 md:px-3 py-1.5 md:py-2 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition text-xs md:text-sm"><i class="fas fa-info-circle mr-1 md:mr-2"></i>View Details</a>
                        ${showEdit ? 
                            `<a href="/events/${event.event_id}/edit" class="px-2 md:px-3 py-1.5 md:py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition text-xs md:text-sm"><i class="fas fa-edit"></i></a>` : ''}
                    </div>
                    
                    ${event.event_remarks ? `<div class="mt-2 md:mt-3 p-2 bg-red-50 border border-red-200 rounded-lg"><p class="text-xs text-red-600"><i class="fas fa-comment mr-1"></i> ${escapeHtml(event.event_remarks.substring(0, 80))}${event.event_remarks.length > 80 ? '...' : ''}</p></div>` : ''}
                    
                    ${isPast && event.event_approval_status === 'Approved' && event.event_post_review ? 
                        `<div class="mt-2 md:mt-3 p-2 bg-blue-50 border border-blue-200 rounded-lg"><p class="text-xs text-blue-600"><i class="fas fa-star mr-1"></i> Review: ${escapeHtml(event.event_post_review.substring(0, 80))}${event.event_post_review.length > 80 ? '...' : ''}</p></div>` : ''}
                </div>
            </div>
        `;
    }

    function setupEventListeners() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                currentFilter = btn.dataset.filter;
                updateActiveFilter(currentFilter);
                filterAndRenderEvents();
            });
        });
        
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                currentSearch = e.target.value;
                filterAndRenderEvents();
            });
        }
        
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                currentSort = e.target.value;
                filterAndRenderEvents();
            });
        }
    }
</script>
@endpush

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .event-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    
    @media (max-width: 640px) {
        .filter-btn {
            font-size: 0.75rem;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
    }
</style>
@endsection