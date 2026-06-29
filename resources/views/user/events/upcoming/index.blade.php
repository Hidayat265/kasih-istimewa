@extends('user.layouts.userLayouts')

@section('title', 'Upcoming Events | Kasih Istimewa')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">Upcoming Events</h1>
            <p class="text-base md:text-lg lg:text-xl opacity-90 max-w-2xl mx-auto px-4">
                Discover meaningful events and volunteer opportunities near you
            </p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="py-4 md:py-6 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Buttons - Horizontal scroll on mobile -->
            <div class="overflow-x-auto pb-3 mb-4 md:mb-6 -mx-4 px-4">
                <div class="flex flex-nowrap gap-2 md:gap-4 min-w-max">
                    <button data-filter="all" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-primary text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                        <i class="fas fa-calendar-alt mr-1 md:mr-2"></i> All Events
                    </button>
                    <button data-filter="today" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                        <i class="fas fa-calendar-day mr-1 md:mr-2"></i> Today
                    </button>
                    <button data-filter="tomorrow" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                        <i class="fas fa-sun mr-1 md:mr-2"></i> Tomorrow
                    </button>
                    <button data-filter="this-week" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                        <i class="fas fa-calendar-week mr-1 md:mr-2"></i> This Week
                    </button>
                    <button data-filter="this-month" class="filter-btn px-3 md:px-6 py-1.5 md:py-2 bg-gray-200 text-gray-700 hover:bg-primary hover:text-white rounded-lg font-medium transition text-sm md:text-base whitespace-nowrap">
                        <i class="fas fa-calendar-alt mr-1 md:mr-2"></i> This Month
                    </button>
                </div>
            </div>
            
            <!-- Search and Sort - Side by side -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-3 md:pt-4 border-t">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search by event name, organizer or location..." 
                        class="w-full pl-10 pr-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm md:text-base">
                </div>
                <div class="sm:w-64">
                    <select id="sortSelect" class="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-primary bg-white">
                        <option value="date_asc" selected>Sort by Date (Earliest first)</option>
                        <option value="date_desc">Sort by Date (Latest first)</option>
                        <option value="name_asc">Sort by Name (A-Z)</option>
                        <option value="name_desc">Sort by Name (Z-A)</option>
                        <option value="volunteer_asc">Sort by Slots (Fewest first)</option>
                        <option value="volunteer_desc">Sort by Slots (Most first)</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Grid Section -->
    <section class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Results Count -->
            <div class="mb-4 md:mb-6">
                <p class="text-sm md:text-base text-gray-600"><span id="eventCount">0</span> events found</p>
            </div>

            <!-- Events Grid - Responsive columns -->
            <div id="eventsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                <!-- Events will be loaded here -->
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-12 hidden">
                <i class="fas fa-spinner fa-spin text-3xl md:text-4xl text-primary"></i>
                <p class="mt-2 text-gray-500 text-sm md:text-base">Loading events...</p>
            </div>

            <!-- No Results -->
            <div id="noResults" class="hidden text-center py-12">
                <i class="fas fa-calendar-times text-5xl md:text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg md:text-xl font-semibold text-gray-700">No events found</h3>
                <p class="text-sm md:text-base text-gray-500 mt-2">Try adjusting your search or filter criteria.</p>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let allEvents = [];
    let currentFilter = 'all';
    let currentSearch = '';
    let currentSort = 'date_asc';

    document.addEventListener('DOMContentLoaded', function() {
        fetchEvents();
        setupEventListeners();
        
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#554994'
            });
        @endif
    });

    async function fetchEvents() {
        try {
            const spinner = document.getElementById('loadingSpinner');
            const eventsGrid = document.getElementById('eventsGrid');
            
            if (spinner) spinner.classList.remove('hidden');
            if (eventsGrid) eventsGrid.classList.add('hidden');
            
            @if(isset($events))
                allEvents = @json($events);
                console.log('Events loaded:', allEvents.length);
                filterAndRenderEvents();
            @endif
            
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

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

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

    function filterAndRenderEvents() {
        let filteredEvents = [...allEvents];
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const nextWeek = new Date(today);
        nextWeek.setDate(today.getDate() + 7);
        const nextMonth = new Date(today);
        nextMonth.setMonth(today.getMonth() + 1);
        
        switch(currentFilter) {
            case 'today':
                filteredEvents = filteredEvents.filter(event => {
                    const eventDate = new Date(event.event_date);
                    eventDate.setHours(0, 0, 0, 0);
                    return eventDate.getTime() === today.getTime();
                });
                break;
            case 'tomorrow':
                filteredEvents = filteredEvents.filter(event => {
                    const eventDate = new Date(event.event_date);
                    eventDate.setHours(0, 0, 0, 0);
                    return eventDate.getTime() === tomorrow.getTime();
                });
                break;
            case 'this-week':
                filteredEvents = filteredEvents.filter(event => {
                    const eventDate = new Date(event.event_date);
                    eventDate.setHours(0, 0, 0, 0);
                    return eventDate >= today && eventDate <= nextWeek;
                });
                break;
            case 'this-month':
                filteredEvents = filteredEvents.filter(event => {
                    const eventDate = new Date(event.event_date);
                    eventDate.setHours(0, 0, 0, 0);
                    return eventDate >= today && eventDate <= nextMonth;
                });
                break;
            default:
                break;
        }
        
        if (currentSearch) {
            const searchLower = currentSearch.toLowerCase();
            filteredEvents = filteredEvents.filter(event => 
                (event.title || '').toLowerCase().includes(searchLower) ||
                (event.organizer || '').toLowerCase().includes(searchLower) ||
                (event.location || '').toLowerCase().includes(searchLower)
            );
        }
        
        filteredEvents.sort((a, b) => {
            switch(currentSort) {
                case 'date_asc':
                    return new Date(a.event_date) - new Date(b.event_date);
                case 'date_desc':
                    return new Date(b.event_date) - new Date(a.event_date);
                case 'name_asc':
                    return (a.title || '').localeCompare(b.title || '');
                case 'name_desc':
                    return (b.title || '').localeCompare(a.title || '');
                case 'volunteer_asc':
                    return (a.registered_volunteers || 0) - (b.registered_volunteers || 0);
                case 'volunteer_desc':
                    return (b.registered_volunteers || 0) - (a.registered_volunteers || 0);
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

    function renderEventCard(event) {
        let imageUrl = 'https://placehold.co/600x400/554994/ffffff?text=Upcoming+Event';
        if (event.image) {
            if (event.image.startsWith('http')) {
                imageUrl = event.image;
            } else if (event.image && event.image !== 'null') {
                imageUrl = '/storage/' + event.image;
            }
        }
        
        const isFull = event.registered_volunteers >= event.max_volunteers;
        
        const description = event.description ? 
            (event.description.length > 80 ? event.description.substring(0, 80) + '...' : event.description) : '';
        
        return `
            <div class="event-card bg-white rounded-lg md:rounded-xl shadow-soft overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="relative">
                    <img src="${imageUrl}" alt="${event.title}" class="w-full h-40 md:h-48 object-cover" onerror="this.src='https://placehold.co/600x400/554994/ffffff?text=Event'">
                    ${isFull ? `
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">
                                <i class="fas fa-ban mr-1"></i> Full
                            </span>
                        </div>
                    ` : ''}
                </div>
                <div class="p-4 md:p-5">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 line-clamp-1">${event.title || 'Untitled Event'}</h3>
                    
                    ${description ? `<p class="text-sm text-gray-600 mb-3 line-clamp-2">${description}</p>` : ''}
                    
                    <div class="flex items-start text-xs md:text-sm text-gray-500 mb-2">
                        <i class="fas fa-play-circle mr-1 md:mr-2 text-green-500 text-xs md:text-sm mt-0.5"></i>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700">Start:</span>
                            <span>${formatDate(event.event_date)}</span>
                            <span class="mx-1">•</span>
                            <span class="text-primary font-medium">${getStartTime(event.start_session)}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-start text-xs md:text-sm text-gray-500 mb-3">
                        <i class="fas fa-stop-circle mr-1 md:mr-2 text-red-500 text-xs md:text-sm mt-0.5"></i>
                        <div class="flex-1">
                            <span class="font-medium text-gray-700">End:</span>
                            <span>${formatDate(event.end_date)}</span>
                            <span class="mx-1">•</span>
                            <span class="text-red-500 font-medium">${getEndTime(event.end_session)}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-xs md:text-sm text-gray-500 mb-3">
                        <i class="fas fa-map-marker-alt mr-1 md:mr-2 text-primary text-xs md:text-sm"></i>
                        <span class="truncate">${event.location || 'Location TBD'}</span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-3 md:mb-4">
                        <div class="flex items-center text-xs md:text-sm text-gray-500">
                            <i class="fas fa-users mr-1 md:mr-2 text-green-500 text-xs md:text-sm"></i>
                            <span>${event.registered_volunteers || 0}/${event.max_volunteers} participants</span>
                        </div>
                        <div class="w-16 md:w-24 h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: ${((event.registered_volunteers || 0) / (event.max_volunteers || 1)) * 100}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 mt-3 md:mt-4">
                        <a href="/events/public/${event.id}" 
                           class="flex-1 text-center px-2 md:px-3 py-1.5 md:py-2 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition text-xs md:text-sm">
                            <i class="fas fa-info-circle mr-1 md:mr-2"></i>View Details
                        </a>
                        ${!isFull ? `
                            <button onclick="registerForEvent('${event.id}')" 
                                    class="flex-1 px-2 md:px-3 py-1.5 md:py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition text-xs md:text-sm">
                                <i class="fas fa-hand-peace mr-1 md:mr-2"></i>Register
                            </button>
                        ` : `
                            <button disabled 
                                    class="flex-1 px-2 md:px-3 py-1.5 md:py-2 bg-gray-300 text-gray-500 rounded-lg text-xs md:text-sm cursor-not-allowed">
                                <i class="fas fa-ban mr-1 md:mr-2"></i>Full
                            </button>
                        `}
                    </div>
                </div>
            </div>
        `;
    }

    function registerForEvent(eventId) {
    const event = allEvents.find(e => e.id == eventId);
    if (!event) return;
    
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
            
            // Fix: Use proper URL construction without route helper
            fetch(`/participant/register/${eventId}`, {
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
                console.error('Error:', error);
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
    
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
</style>
@endsection