@extends('user.layouts.userLayouts')

@section('title', 'Edit Event | Kasih Istimewa')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Edit Event</h1>
        <p class="text-lg opacity-90 max-w-2xl mx-auto">
            Update your event details. Your event will be reviewed again after submission.
        </p>
    </div>
</section>

<!-- Edit Event Form Section -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-edit text-primary mr-2"></i> Edit Event Information
                </h2>
                <p class="text-sm text-gray-500 mt-1">Please fill in all required fields marked with <span class="text-red-500">*</span></p>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6 pt-4 overflow-x-auto" aria-label="Tabs">
                    <button type="button" class="tab-btn active mr-8 py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm transition whitespace-nowrap" data-tab="tab1">
                        <i class="fas fa-info-circle mr-2"></i> Basic Information
                    </button>
                    <button type="button" class="tab-btn mr-8 py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition whitespace-nowrap" data-tab="tab2">
                        <i class="fas fa-calendar-alt mr-2"></i> Date & Session
                    </button>
                    <button type="button" class="tab-btn mr-8 py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition whitespace-nowrap" data-tab="tab3">
                        <i class="fas fa-users mr-2"></i> Volunteer Settings
                    </button>
                    <button type="button" class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition whitespace-nowrap" data-tab="tab4">
                        <i class="fas fa-file-upload mr-2"></i> Documents & Media
                    </button>
                </nav>
            </div>

            <!-- Form Body -->
            <form action="{{ route('events.update', $event->event_id) }}" method="POST" enctype="multipart/form-data" class="p-6" id="eventForm">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">
                        <strong class="block font-semibold mb-2">Please fix the following errors:</strong>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Hidden fields -->
                <input type="hidden" name="event_created_by_id" value="{{ $event->event_created_by_id }}">
                <input type="hidden" name="event_current_participant" value="{{ $event->event_current_participant }}">
                <input type="hidden" name="event_approval_status" value="Pending">
                <input type="hidden" name="event_publish" value="0">

                <!-- Tab 1: Basic Information -->
                <div id="tab1" class="tab-content space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event ID</label>
                        <input type="text"
                            value="{{ $event->event_id }}"
                            class="w-full rounded-lg border border-gray-300 bg-gray-100 p-3 text-gray-600 cursor-not-allowed"
                            readonly disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company/Organization Name <span class="text-red-500">*</span></label>
                        <input type="text" name="event_company_name" id="event_company_name"
                            value="{{ old('event_company_name', $event->event_company_name) }}"
                            class="basic-info-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                            placeholder="Enter your company or organization name" required>
                        @error('event_company_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="event_name" id="event_name"
                            value="{{ old('event_name', $event->event_name) }}"
                            class="basic-info-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                            placeholder="Enter event title" required>
                        @error('event_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Location with Map Picker -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Location Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="event_location_name" id="event_location_name"
                            value="{{ old('event_location_name', $event->event_location_name) }}"
                            class="basic-info-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                            placeholder="e.g., Kuala Lumpur Convention Centre" required>
                        <p class="text-xs text-gray-500 mt-1">Enter the name or title of your event location</p>
                        @error('event_location_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pin Location on Map (Optional)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Search for an address or click on the map to pin the exact location.</p>

                        <div class="flex gap-2 mb-3">
                            <input type="text" id="location_search"
                                class="flex-1 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                                placeholder="Search for an address...">
                            <button type="button" id="search_btn"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition flex items-center gap-1 whitespace-nowrap">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <button type="button" id="current_location_btn"
                                class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-secondary/90 transition flex items-center gap-1 whitespace-nowrap">
                                <i class="fas fa-location-dot"></i> My Location
                            </button>
                        </div>

                        <div id="map" style="height: 350px; width: 100%; border-radius: 12px; z-index: 1; border: 1px solid #e5e7eb; margin-bottom: 10px;"></div>

                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mapped Address (Auto-filled)
                            </label>
                            <textarea id="event_location_address" name="event_location_address" rows="2"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 p-3 text-gray-500">{{ old('event_location_address', $event->event_location_address) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">This address will help attendees navigate using GPS</p>
                        </div>

                        <input type="hidden" name="event_location_latitude" id="event_location_latitude" value="{{ old('event_location_latitude', $event->event_location_latitude) }}">
                        <input type="hidden" name="event_location_longitude" id="event_location_longitude" value="{{ old('event_location_longitude', $event->event_location_longitude) }}">

                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                            Click on the map to pin your event location, or use the search / My Location button.
                        </p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="button" id="nextToTab2"
                            class="next-tab px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition opacity-50 cursor-not-allowed"
                            disabled>
                            Next: Date &amp; Session <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Date & Session -->
                <div id="tab2" class="tab-content hidden space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_start_date" id="event_start_date"
                            value="{{ old('event_start_date', $event->event_start_date) }}"
                            min="{{ date('Y-m-d', strtotime('+10 days')) }}"
                            class="date-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none">
                        <p class="text-xs text-gray-400 mt-1">Must be at least 10 days from today</p>
                        @error('event_start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_end_date" id="event_end_date"
                            value="{{ old('event_end_date', $event->event_end_date) }}"
                            class="date-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none">
                        <p class="text-xs text-gray-400 mt-1">Can be same as start date or later</p>
                        @error('event_end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="sameDateSessionPanel">
                        <label id="startSessionLabel" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Session <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-3">
                            Multi-day events include the selected start session through Evening on the first day,
                            all sessions on intermediate days, and Morning through the selected end session on the final day.
                        </p>
                        <div id="startSessionBoxes" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="start-session-box border-2 border-gray-300 bg-white rounded-lg p-4 cursor-pointer transition-all {{ $event->event_start_session == 'Morning' ? 'selected border-blue-500 bg-blue-100' : '' }}" data-session="Morning">
                                <div class="text-center">
                                    <i class="fas fa-sun text-2xl mb-2 text-yellow-500"></i>
                                    <h3 class="font-semibold text-lg">Morning</h3>
                                    <p class="text-sm text-gray-500">8:00 AM</p>
                                </div>
                            </div>
                            <div class="start-session-box border-2 border-gray-300 bg-white rounded-lg p-4 cursor-pointer transition-all {{ $event->event_start_session == 'Afternoon' ? 'selected border-blue-500 bg-blue-100' : '' }}" data-session="Afternoon">
                                <div class="text-center">
                                    <i class="fas fa-cloud-sun text-2xl mb-2 text-orange-400"></i>
                                    <h3 class="font-semibold text-lg">Afternoon</h3>
                                    <p class="text-sm text-gray-500">1:00 PM</p>
                                </div>
                            </div>
                            <div class="start-session-box border-2 border-gray-300 bg-white rounded-lg p-4 cursor-pointer transition-all {{ $event->event_start_session == 'Evening' ? 'selected border-blue-500 bg-blue-100' : '' }}" data-session="Evening">
                                <div class="text-center">
                                    <i class="fas fa-moon text-2xl mb-2 text-indigo-400"></i>
                                    <h3 class="font-semibold text-lg">Evening</h3>
                                    <p class="text-sm text-gray-500">6:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="endSessionPanel">
                        <label id="endSessionLabel" class="block text-sm font-medium text-gray-700 mb-2">
                            End Session <span class="text-red-500">*</span>
                        </label>
                        <div id="endSessionBoxes" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="end-session-box border-2 border-gray-300 bg-white rounded-lg p-4 cursor-pointer transition-all {{ $event->event_end_session == 'Morning' ? 'selected border-blue-500 bg-blue-100' : '' }}" data-session="Morning">
                                <div class="text-center">
                                    <i class="fas fa-sun text-2xl mb-2 text-yellow-500"></i>
                                    <h3 class="font-semibold text-lg">Morning</h3>
                                    <p class="text-sm text-gray-500">12:00 PM</p>
                                </div>
                            </div>
                            <div class="end-session-box border-2 border-gray-300 bg-white rounded-lg p-4 cursor-pointer transition-all {{ $event->event_end_session == 'Afternoon' ? 'selected border-blue-500 bg-blue-100' : '' }}" data-session="Afternoon">
                                <div class="text-center">
                                    <i class="fas fa-cloud-sun text-2xl mb-2 text-orange-400"></i>
                                    <h3 class="font-semibold text-lg">Afternoon</h3>
                                    <p class="text-sm text-gray-500">5:00 PM</p>
                                </div>
                            </div>
                            <div class="end-session-box border-2 border-gray-300 bg-white rounded-lg p-4 cursor-pointer transition-all {{ $event->event_end_session == 'Evening' ? 'selected border-blue-500 bg-blue-100' : '' }}" data-session="Evening">
                                <div class="text-center">
                                    <i class="fas fa-moon text-2xl mb-2 text-indigo-400"></i>
                                    <h3 class="font-semibold text-lg">Evening</h3>
                                    <p class="text-sm text-gray-500">10:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="visualTimeline" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 hidden">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-week text-primary mr-2"></i> Session Timeline Preview
                        </h4>
                        <div id="timelineContent" class="space-y-2"></div>
                    </div>

                    <input type="hidden" name="event_start_session" id="event_start_session" value="{{ $event->event_start_session }}">
                    <input type="hidden" name="event_end_session" id="event_end_session" value="{{ $event->event_end_session }}">

                    <div id="sessionConflictWarning" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mt-0.5"></i>
                            <p class="text-sm text-yellow-700 ml-3" id="conflictMessage"></p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous: Basic Info
                        </button>
                        <button type="button" id="nextToTab3"
                            class="next-tab px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition opacity-50 cursor-not-allowed"
                            disabled>
                            Next: Volunteer Settings <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 3: Volunteer Settings -->
                <div id="tab3" class="tab-content hidden space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Maximum Volunteers <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="event_maximum_participant" id="event_maximum_participant"
                            value="{{ old('event_maximum_participant', $event->event_maximum_participant) }}"
                            min="0" max="1000"
                            class="volunteer-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                            placeholder="Enter maximum number of volunteers" required>
                        <p class="text-xs text-gray-500 mt-1">Set to 0 if you don't need volunteers.</p>
                        @error('event_maximum_participant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <h3 class="font-semibold text-yellow-800 mb-2">
                            <i class="fas fa-users-slash mr-2"></i> Volunteer Information
                        </h3>
                        <div class="text-sm text-yellow-700 space-y-1">
                            <p>• Current registered volunteers: <strong>{{ $event->event_current_participant }}</strong></p>
                            <p>• Available spots: <strong id="availableSpots">{{ $event->event_maximum_participant - $event->event_current_participant }}</strong></p>
                            <p class="text-xs mt-2">Note: You can only increase maximum volunteers, not decrease below current registered count.</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous: Date &amp; Session
                        </button>
                        <button type="button" id="nextToTab4"
                            class="next-tab px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition opacity-50 cursor-not-allowed"
                            disabled>
                            Next: Documents &amp; Media <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 4: Documents & Media -->
                <div id="tab4" class="tab-content hidden space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supporting Document (PDF)</label>
                        <label for="event_document"
                            class="relative cursor-pointer mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                            <input id="event_document" name="event_document" type="file" class="sr-only" accept=".pdf">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="font-medium text-primary">Upload new file</span>
                                    <p class="pl-1">to replace existing</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF up to 10 MB</p>
                            </div>
                        </label>
                        @if($event->event_document)
                            <div class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-file-pdf text-red-500 mr-1"></i> Current: 
                                <a href="{{ $event->event_document }}" target="_blank" class="text-primary hover:underline">View Document</a>
                            </div>
                        @endif
                        <div id="documentFileName" class="text-sm text-gray-600 mt-2 hidden">
                            <i class="fas fa-file-alt text-primary mr-1"></i> <span id="fileName"></span>
                        </div>
                        @error('event_document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Banner / Picture</label>
                        <label for="event_picture"
                            class="relative cursor-pointer mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                            <input id="event_picture" name="event_picture" type="file" class="sr-only" accept="image/*">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="font-medium text-primary">Upload new image</span>
                                    <p class="pl-1">to replace existing</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5 MB</p>
                            </div>
                        </label>
                        @if($event->event_picture)
                            <div class="mt-2">
                                <img src="{{ $event->event_picture }}" alt="Current banner" class="h-24 w-auto object-cover rounded-lg border">
                                <p class="text-xs text-gray-500 mt-1">Current banner</p>
                            </div>
                        @endif
                        <div id="picturePreview" class="mt-3 hidden">
                            <img id="previewImage" src="#" alt="Preview" class="h-32 w-auto object-cover rounded-lg border">
                            <button type="button" id="removeImage" class="text-red-500 text-xs mt-1 hover:underline">Remove new image</button>
                        </div>
                        @error('event_picture')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous: Volunteer Settings
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition shadow-md flex items-center gap-2">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mt-6">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                        <p class="text-sm text-blue-700 ml-3">
                            <strong>Note:</strong> After updating, your event will be reviewed again by our admin team.
                            Events must be scheduled at least 10 days in advance.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 350px;
        width: 100%;
        border-radius: 12px;
        z-index: 1;
    }
    .selected {
        border-color: #3b82f6 !important;
        background-color: #dbeafe !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ─── State ────────────────────────────────────────────────────────────────────
let basicInfoValid = true;
let dateSessionValid = true;
let volunteerValid = true;

let startDate = '{{ $event->event_start_date }}';
let endDate = '{{ $event->event_end_date }}';
let selectedStartSession = '{{ $event->event_start_session }}';
let selectedEndSession = '{{ $event->event_end_session }}';
let selectedSessions = [];
let bookedSessionsByDate = {};
let middleDateConflict = false;

const sessionOrder = ['Morning', 'Afternoon', 'Evening'];
const startTimes = { Morning: '8:00 AM', Afternoon: '1:00 PM', Evening: '6:00 PM' };
const endTimes = { Morning: '12:00 PM', Afternoon: '5:00 PM', Evening: '10:00 PM' };

// Pre-select existing sessions for same date
const isSameDateInit = startDate === endDate;
if (isSameDateInit && selectedStartSession && selectedEndSession) {
    const startIdx = sessionOrder.indexOf(selectedStartSession);
    const endIdx = sessionOrder.indexOf(selectedEndSession);
    for (let i = startIdx; i <= endIdx; i++) {
        selectedSessions.push(sessionOrder[i]);
    }
}

function getBookedSessionsForDate(date) {
    return bookedSessionsByDate[date] || [];
}

function isFullyBookedDate(date) {
    return getBookedSessionsForDate(date).length === sessionOrder.length;
}

// ─── Tab Switching ────────────────────────────────────────────────────────────
const tabs = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

function switchTab(tabId) {
    contents.forEach(c => c.classList.add('hidden'));
    tabs.forEach(t => {
        t.classList.remove('border-primary', 'text-primary');
        t.classList.add('border-transparent', 'text-gray-500');
    });
    const sel = document.getElementById(tabId);
    if (sel) sel.classList.remove('hidden');
    const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
    if (activeTab) {
        activeTab.classList.add('border-primary', 'text-primary');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }
    if (tabId === 'tab1' && map) setTimeout(() => map.invalidateSize(), 100);
}

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const tabId = tab.getAttribute('data-tab');
        if (tabId === 'tab2' && !basicInfoValid) { alert('Please fill in all required fields in Basic Information first.'); return; }
        if (tabId === 'tab3' && !dateSessionValid) { alert('Please complete Date & Session section first.'); return; }
        if (tabId === 'tab4' && !volunteerValid) { alert('Please complete Volunteer Settings first.'); return; }
        switchTab(tabId);
    });
});

document.querySelectorAll('.next-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const cur = btn.closest('.tab-content');
        const next = cur ? cur.nextElementSibling : null;
        if (next && next.classList.contains('tab-content')) switchTab(next.id);
    });
});

document.querySelectorAll('.prev-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const cur = btn.closest('.tab-content');
        const prev = cur ? cur.previousElementSibling : null;
        if (prev && prev.classList.contains('tab-content')) switchTab(prev.id);
    });
});

// ─── Booked Sessions ──────────────────────────────────────────────────────────
async function loadBookedSessionsForRange(start, end) {
    try {
        const res = await fetch('{{ route("events.getBookedSessionsForRange") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ start_date: start, end_date: end })
        });
        const data = await res.json();
        return data.booked_sessions || {};
    } catch (e) {
        console.error('Error loading booked sessions:', e);
        return {};
    }
}

function updateSessionAvailability(bookedMap) {
    bookedSessionsByDate = bookedMap;
    const isSameDate = startDate === endDate;
    const startDateBooked = startDate ? getBookedSessionsForDate(startDate) : [];
    const endDateBooked = endDate ? getBookedSessionsForDate(endDate) : [];
    const startDateFullyBooked = startDate ? isFullyBookedDate(startDate) : false;
    const endDateFullyBooked = endDate ? isFullyBookedDate(endDate) : false;

    // Style start session boxes
    document.querySelectorAll('.start-session-box').forEach(box => {
        const s = box.getAttribute('data-session');
        const booked = startDateBooked.includes(s);
        const isSelected = selectedStartSession === s;
        box.classList.toggle('border-red-500', booked && !isSelected);
        box.classList.toggle('bg-red-50', booked && !isSelected);
        box.classList.toggle('cursor-not-allowed', booked && !isSelected);
        box.style.pointerEvents = (booked && !isSelected) ? 'none' : 'auto';
    });

    const endPanel = document.getElementById('endSessionPanel');
    const startLabel = document.getElementById('startSessionLabel');
    const endLabel = document.getElementById('endSessionLabel');
    if (isSameDate) {
        if (endPanel) endPanel.classList.add('hidden');
        if (startLabel) startLabel.innerHTML = 'Select Sessions <span class="text-red-500">*</span>';
    } else {
        if (endPanel) endPanel.classList.remove('hidden');
        if (startLabel) startLabel.innerHTML = 'Start Session <span class="text-red-500">*</span>';
        if (endLabel) endLabel.innerHTML = 'End Session <span class="text-red-500">*</span>';
    }

    document.querySelectorAll('.end-session-box').forEach(box => {
        const s = box.getAttribute('data-session');
        const booked = endDateBooked.includes(s);
        const isSelected = selectedEndSession === s;
        box.classList.toggle('border-red-500', booked && !isSelected);
        box.classList.toggle('bg-red-50', booked && !isSelected);
        box.classList.toggle('cursor-not-allowed', booked && !isSelected);
        box.style.pointerEvents = (booked && !isSelected) ? 'none' : 'auto';
    });

    middleDateConflict = false;
    if (!isSameDate && startDate && endDate) {
        const cur = new Date(startDate);
        const fin = new Date(endDate);
        cur.setDate(cur.getDate() + 1);
        while (cur < fin) {
            const day = cur.toISOString().split('T')[0];
            if (getBookedSessionsForDate(day).length > 0) { middleDateConflict = true; break; }
            cur.setDate(cur.getDate() + 1);
        }
    }
}

// ─── Visual Timeline ──────────────────────────────────────────────────────────
function updateVisualTimeline() {
    if (!selectedStartSession || !selectedEndSession) {
        document.getElementById('visualTimeline').classList.add('hidden');
        return;
    }
    const startIndex = sessionOrder.indexOf(selectedStartSession);
    const endIndex = sessionOrder.indexOf(selectedEndSession);
    const isSameDate = startDate === endDate;

    if (isSameDate && startIndex > endIndex) {
        document.getElementById('conflictMessage').innerHTML = 'End session must be after start session.';
        document.getElementById('sessionConflictWarning').classList.remove('hidden');
        return;
    }

    document.getElementById('sessionConflictWarning').classList.add('hidden');
    document.getElementById('visualTimeline').classList.remove('hidden');

    let html = '<div class="border rounded-lg overflow-hidden">';
    if (isSameDate) {
        html += `<div class="bg-gray-100 px-3 py-2 font-semibold text-sm"><i class="fas fa-calendar-day mr-1"></i> ${startDate}</div><div class="p-2">`;
        for (let i = startIndex; i <= endIndex; i++) {
            const session = sessionOrder[i];
            html += `<div class="px-3 py-2 bg-green-50 border-l-4 border-green-500 mb-1 rounded flex justify-between"><span class="font-semibold">${session}</span><span class="text-xs text-green-600">✅ Included</span></div>`;
        }
        html += '</div>';
    } else {
        html += `<div class="bg-gray-100 px-3 py-2 font-semibold text-sm"><i class="fas fa-calendar-alt mr-1"></i> ${startDate} – ${endDate}</div>`;
        html += `<div class="p-2 bg-blue-50 border-l-4 border-blue-500 m-2 rounded"><div class="font-semibold mb-1">📅 ${startDate} (Start)</div>`;
        sessionOrder.slice(startIndex).forEach(s => { html += `<div class="px-3 py-1 text-sm">${s}</div>`; });
        html += '</div>';
        const cur = new Date(startDate);
        const fin = new Date(endDate);
        cur.setDate(cur.getDate() + 1);
        while (cur < fin) {
            const ds = cur.toISOString().split('T')[0];
            html += `<div class="p-2 bg-yellow-50 border-l-4 border-yellow-500 m-2 rounded"><div class="font-semibold mb-1">📅 ${ds} (Full day)</div>`;
            sessionOrder.forEach(s => { html += `<div class="px-3 py-1 text-sm">${s}</div>`; });
            html += '</div>';
            cur.setDate(cur.getDate() + 1);
        }
        html += `<div class="p-2 bg-green-50 border-l-4 border-green-500 m-2 rounded"><div class="font-semibold mb-1">📅 ${endDate} (End)</div>`;
        sessionOrder.slice(0, endIndex + 1).forEach(s => { html += `<div class="px-3 py-1 text-sm">${s}</div>`; });
        html += '</div>';
    }
    html += '</div>';
    document.getElementById('timelineContent').innerHTML = html;
}

// ─── Session Selection ────────────────────────────────────────────────────────
document.querySelectorAll('.start-session-box').forEach(box => {
    box.addEventListener('click', () => {
        const session = box.getAttribute('data-session');
        const isSameDate = startDate === endDate;
        
        if (isSameDate) {
            const idx = sessionOrder.indexOf(session);
            const selIdx = selectedSessions.map(s => sessionOrder.indexOf(s));
            if (selectedSessions.includes(session)) {
                if (selectedSessions.length === 1) selectedSessions = [];
                else if (session === selectedSessions[0]) selectedSessions = selectedSessions.slice(1);
                else if (session === selectedSessions[selectedSessions.length - 1]) selectedSessions = selectedSessions.slice(0, -1);
                else selectedSessions = [session];
            } else if (selIdx.length === 0) {
                selectedSessions = [session];
            } else {
                const min = Math.min(...selIdx, idx);
                const max = Math.max(...selIdx, idx);
                selectedSessions = sessionOrder.slice(min, max + 1);
            }
            
            if (selectedSessions.length > 0) {
                selectedStartSession = selectedSessions[0];
                selectedEndSession = selectedSessions[selectedSessions.length - 1];
                document.getElementById('event_start_session').value = selectedStartSession;
                document.getElementById('event_end_session').value = selectedEndSession;
            } else {
                selectedStartSession = selectedEndSession = null;
                document.getElementById('event_start_session').value = '';
                document.getElementById('event_end_session').value = '';
            }
            
            document.querySelectorAll('.start-session-box').forEach(b => {
                const s = b.getAttribute('data-session');
                if (selectedSessions.includes(s)) {
                    b.classList.add('selected', 'border-blue-500', 'bg-blue-100');
                } else {
                    b.classList.remove('selected', 'border-blue-500', 'bg-blue-100');
                }
            });
            updateVisualTimeline();
            return;
        }
        
        document.querySelectorAll('.start-session-box').forEach(b => {
            b.classList.remove('selected', 'border-blue-500', 'bg-blue-100');
        });
        box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
        selectedStartSession = session;
        document.getElementById('event_start_session').value = session;
        if (selectedEndSession) updateVisualTimeline();
    });
});

document.querySelectorAll('.end-session-box').forEach(box => {
    box.addEventListener('click', () => {
        if (startDate === endDate) return;
        document.querySelectorAll('.end-session-box').forEach(b => {
            b.classList.remove('selected', 'border-blue-500', 'bg-blue-100');
        });
        box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
        selectedEndSession = box.getAttribute('data-session');
        document.getElementById('event_end_session').value = selectedEndSession;
        if (selectedStartSession) updateVisualTimeline();
    });
});

// ─── Date Change ──────────────────────────────────────────────────────────────
const startDateInput = document.getElementById('event_start_date');
const endDateInput = document.getElementById('event_end_date');

async function onDateChange() {
    startDate = startDateInput.value;
    endDate = endDateInput.value;
    if (!startDate || !endDate) return;
    if (endDate < startDate) { endDateInput.value = startDate; endDate = startDate; }
    
    // Reset selections for new dates
    const isSameDate = startDate === endDate;
    if (isSameDate) {
        selectedSessions = [];
        selectedStartSession = selectedEndSession = null;
        document.getElementById('event_start_session').value = '';
        document.getElementById('event_end_session').value = '';
    }
    
    const bookedMap = await loadBookedSessionsForRange(startDate, endDate);
    updateSessionAvailability(bookedMap);
    document.getElementById('visualTimeline').classList.add('hidden');
}

if (startDateInput) {
    startDateInput.addEventListener('change', () => {
        if (endDateInput) {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value && endDateInput.value < startDateInput.value)
                endDateInput.value = startDateInput.value;
        }
        onDateChange();
    });
}
if (endDateInput) endDateInput.addEventListener('change', onDateChange);

// ─── Volunteer Validation ─────────────────────────────────────────────────────
const volunteerField = document.getElementById('event_maximum_participant');
const availableSpotsSpan = document.getElementById('availableSpots');
const currentVolunteers = {{ $event->event_current_participant }};

function validateVolunteer() {
    const val = volunteerField ? parseInt(volunteerField.value, 10) : NaN;
    if (!isNaN(val) && val >= currentVolunteers) {
        volunteerValid = true;
        if (availableSpotsSpan) availableSpotsSpan.textContent = val - currentVolunteers;
    } else {
        volunteerValid = false;
        if (availableSpotsSpan) availableSpotsSpan.textContent = 0;
        if (volunteerField && val < currentVolunteers) {
            volunteerField.classList.add('border-red-500');
        } else {
            volunteerField?.classList.remove('border-red-500');
        }
    }
}
if (volunteerField) {
    volunteerField.addEventListener('input', validateVolunteer);
    validateVolunteer();
}

// ─── File Uploads ─────────────────────────────────────────────────────────────
const docInput = document.getElementById('event_document');
const fileNameSpan = document.getElementById('fileName');
const docFileName = document.getElementById('documentFileName');
if (docInput) {
    docInput.addEventListener('change', function() {
        if (this.files[0]) { fileNameSpan.textContent = this.files[0].name; docFileName.classList.remove('hidden'); }
        else { docFileName.classList.add('hidden'); }
    });
}

const pictureInput = document.getElementById('event_picture');
const picturePreview = document.getElementById('picturePreview');
const previewImage = document.getElementById('previewImage');
const removeImageBtn = document.getElementById('removeImage');
if (pictureInput) {
    pictureInput.addEventListener('change', function() {
        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { previewImage.src = e.target.result; picturePreview.classList.remove('hidden'); };
            reader.readAsDataURL(this.files[0]);
        } else { picturePreview.classList.add('hidden'); }
    });
}
if (removeImageBtn) {
    removeImageBtn.addEventListener('click', () => { pictureInput.value = ''; picturePreview.classList.add('hidden'); });
}

// ─── MAP ──────────────────────────────────────────────────────────────────────
let map = null;
let marker = null;

function initMap() {
    if (map) return;
    const existingLat = parseFloat(document.getElementById('event_location_latitude').value) || null;
    const existingLng = parseFloat(document.getElementById('event_location_longitude').value) || null;
    const centerLat = existingLat || 3.1390;
    const centerLng = existingLng || 101.6869;

    map = L.map('map').setView([centerLat, centerLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    if (existingLat && existingLng) {
        marker = L.marker([existingLat, existingLng], { draggable: true }).addTo(map);
        marker.on('dragend', e => setLocation(e.target.getLatLng().lat, e.target.getLatLng().lng));
    }
    map.on('click', e => setLocation(e.latlng.lat, e.latlng.lng));
}

async function setLocation(lat, lng) {
    document.getElementById('event_location_latitude').value = lat;
    document.getElementById('event_location_longitude').value = lng;
    if (marker) marker.setLatLng([lat, lng]);
    else {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', e => setLocation(e.target.getLatLng().lat, e.target.getLatLng().lng));
    }
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=en`);
        const data = await res.json();
        if (data.display_name) document.getElementById('event_location_address').value = data.display_name;
    } catch (err) { console.error('Reverse geocoding error:', err); }
}

async function searchLocation() {
    const q = document.getElementById('location_search').value.trim();
    if (!q) return;
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=5&countrycodes=my`);
        const results = await res.json();
        if (results.length > 0) {
            const r = results[0];
            map.setView([parseFloat(r.lat), parseFloat(r.lon)], 15);
            document.getElementById('event_location_latitude').value = parseFloat(r.lat);
            document.getElementById('event_location_longitude').value = parseFloat(r.lon);
            document.getElementById('event_location_address').value = r.display_name;
            if (marker) marker.setLatLng([parseFloat(r.lat), parseFloat(r.lon)]);
            else marker = L.marker([parseFloat(r.lat), parseFloat(r.lon)], { draggable: true }).addTo(map);
        }
    } catch (err) { console.error('Search error:', err); }
}

function getCurrentLocation() {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        map.setView([lat, lng], 15);
        setLocation(lat, lng);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initMap, 200);
    document.getElementById('search_btn')?.addEventListener('click', searchLocation);
    document.getElementById('current_location_btn')?.addEventListener('click', getCurrentLocation);
    document.getElementById('location_search')?.addEventListener('keypress', e => { if (e.key === 'Enter') searchLocation(); });
    
    // Initialize session selections display
    if (isSameDateInit && selectedSessions.length > 0) {
        document.querySelectorAll('.start-session-box').forEach(box => {
            const s = box.getAttribute('data-session');
            if (selectedSessions.includes(s)) {
                box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
            }
        });
        updateVisualTimeline();
    } else {
        if (selectedStartSession) {
            document.querySelectorAll('.start-session-box').forEach(box => {
                if (box.getAttribute('data-session') === selectedStartSession) {
                    box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
                }
            });
        }
        if (selectedEndSession && !isSameDateInit) {
            document.querySelectorAll('.end-session-box').forEach(box => {
                if (box.getAttribute('data-session') === selectedEndSession) {
                    box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
                }
            });
        }
        updateVisualTimeline();
    }
});
</script>
@endpush
