@extends('admin.layouts.adminLayouts')

@section('title', 'Edit Event | Admin')

@section('content')

<!-- Edit Event Form Section -->
<section class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gray-50 px-4 md:px-6 py-4 border-b">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">
                            <i class="fas fa-edit text-primary mr-2"></i> Edit Event (Admin)
                        </h2>
                        <p class="text-xs md:text-sm text-gray-500 mt-1">
                            Event ID: <span class="font-semibold">{{ $event->event_id }}</span> | 
                            Status: <span class="font-semibold {{ $event->event_approval_status === 'Approved' ? 'text-green-600' : ($event->event_approval_status === 'Rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                {{ $event->event_approval_status }}
                            </span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.events.show', $event->event_id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                            <i class="fas fa-eye mr-1"></i> View Event
                        </a>
                        <a href="{{ route('admin.pendingevent') }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium rounded-lg transition">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <nav class="flex px-4 md:px-6 pt-4" aria-label="Tabs">
                    <button type="button" class="tab-btn active mr-4 md:mr-8 py-3 px-1 border-b-2 border-primary text-primary font-medium text-xs md:text-sm transition whitespace-nowrap" data-tab="tab1">
                        <i class="fas fa-info-circle mr-1 md:mr-2"></i> Basic Info
                    </button>
                    <button type="button" class="tab-btn mr-4 md:mr-8 py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-xs md:text-sm transition whitespace-nowrap" data-tab="tab2">
                        <i class="fas fa-calendar-alt mr-1 md:mr-2"></i> Date & Session
                    </button>
                    <button type="button" class="tab-btn mr-4 md:mr-8 py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-xs md:text-sm transition whitespace-nowrap" data-tab="tab3">
                        <i class="fas fa-users mr-1 md:mr-2"></i> Volunteers
                    </button>
                    <button type="button" class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-xs md:text-sm transition whitespace-nowrap" data-tab="tab4">
                        <i class="fas fa-file-upload mr-1 md:mr-2"></i> Documents
                    </button>
                </nav>
            </div>

            <!-- Form Body -->
            <form action="{{ route('admin.events.update', $event->event_id) }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-6" id="eventForm">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded">
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
                <input type="hidden" name="event_approval_status" value="{{ $event->event_approval_status }}">
                <input type="hidden" name="event_publish" value="{{ $event->event_publish }}">

                <!-- Tab 1: Basic Information -->
                <div id="tab1" class="tab-content space-y-4 md:space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event ID</label>
                            <input type="text"
                                value="{{ $event->event_id }}"
                                class="w-full rounded-lg border border-gray-300 bg-gray-100 p-3 text-gray-600 cursor-not-allowed"
                                readonly disabled>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                            <input type="text"
                                value="{{ $event->creator ? $event->creator->user_name : 'Unknown' }}"
                                class="w-full rounded-lg border border-gray-300 bg-gray-100 p-3 text-gray-600 cursor-not-allowed"
                                readonly disabled>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Company/Organization Name <span class="text-red-500">*</span>
                        </label>
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

                    <!-- Event Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="event_description" id="event_description" rows="4"
                            class="basic-info-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                            placeholder="Describe your event - what attendees can expect, agenda, special notes, etc." required>{{ old('event_description', $event->event_description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Provide a detailed description of your event</p>
                        @error('event_description')
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
                            placeholder="e.g., Kuala Lumpur Convention Centre, Taman Tasik Titiwangsa" required>
                        <p class="text-xs text-gray-500 mt-1">Enter the name or title of your event location</p>
                        @error('event_location_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pin Location on Map <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Search for an address or click on the map to pin the exact location. This helps attendees navigate to your event.</p>

                        <!-- Search row -->
                        <div class="flex flex-col sm:flex-row gap-2 mb-3">
                            <input type="text" id="location_search"
                                class="flex-1 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                                placeholder="Search for an address...">
                            <div class="flex gap-2">
                                <button type="button" id="search_btn"
                                    class="flex-1 sm:flex-none bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition flex items-center justify-center gap-1 whitespace-nowrap">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <button type="button" id="current_location_btn"
                                    class="flex-1 sm:flex-none bg-secondary text-white px-4 py-2 rounded-lg hover:bg-secondary/90 transition flex items-center justify-center gap-1 whitespace-nowrap">
                                    <i class="fas fa-location-dot"></i> My Location
                                </button>
                            </div>
                        </div>

                        <!-- Map Container -->
                        <div id="map" style="height: 300px; width: 100%; border-radius: 12px; z-index: 1; border: 1px solid #e5e7eb; margin-bottom: 10px;"></div>

                        <!-- Address from map (readonly, auto-filled) -->
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mapped Address (Auto-filled) <span class="text-red-500">*</span>
                            </label>
                            <textarea id="event_location_address_display" rows="2" readonly
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 p-3 text-gray-500 cursor-not-allowed
                                    {{ $errors->has('event_location_address') ? 'border-red-500' : '' }}"
                                placeholder="Click on the map or search to auto-fill address">{{ old('event_location_address', $event->event_location_address) }}</textarea>
                            <p class="text-xs text-red-500 mt-1 hidden" id="locationError">Please pin a location on the map.</p>
                            <p class="text-xs text-gray-500 mt-1">Click on the map or search to auto-fill the address</p>
                        </div>

                        <!-- Hidden geolocation fields -->
                        <input type="hidden" name="event_location_latitude" id="event_location_latitude" value="{{ old('event_location_latitude', $event->event_location_latitude) }}">
                        <input type="hidden" name="event_location_longitude" id="event_location_longitude" value="{{ old('event_location_longitude', $event->event_location_longitude) }}">
                        <input type="hidden" name="place_id" id="place_id" value="{{ old('place_id') }}">
                        <input type="hidden" name="event_location_address" id="event_location_address" value="{{ old('event_location_address', $event->event_location_address) }}">

                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                            Click on the map to pin your event location, or use the search / My Location button.
                        </p>
                        @error('event_location_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('event_location_latitude')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('event_location_longitude')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                <div id="tab2" class="tab-content hidden space-y-4 md:space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Event Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="event_start_date" id="event_start_date"
                                value="{{ old('event_start_date', $event->event_start_date_formatted ?? $event->event_start_date) }}"
                                class="date-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none">
                            <p class="text-xs text-gray-400 mt-1">Select the start date for your event (no date restrictions for admin)</p>
                            @error('event_start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Event End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="event_end_date" id="event_end_date"
                                value="{{ old('event_end_date', $event->event_end_date_formatted ?? $event->event_end_date) }}"
                                class="date-field w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none">
                            <p class="text-xs text-gray-400 mt-1">Can be same as start date or later</p>
                            @error('event_end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Session Selection (Hidden until dates are selected) -->
                    <div id="sessionSelectionWrapper" class="hidden">
                        <!-- Start Session -->
                        <div id="sameDateSessionPanel">
                            <label id="startSessionLabel" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Session <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-3">
                                Multi-day events include the selected start session through Evening on the first day,
                                all sessions on intermediate days, and Morning through the selected end session on the final day.
                            </p>
                            <div id="startSessionBoxes" class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
                                <div class="start-session-box border-2 border-gray-300 bg-white rounded-lg p-3 md:p-4 cursor-pointer transition-all" data-session="Morning">
                                    <div class="text-center">
                                        <i class="fas fa-sun text-2xl mb-2 text-yellow-500"></i>
                                        <h3 class="font-semibold text-base md:text-lg">Morning</h3>
                                        <p class="text-xs md:text-sm text-gray-500">8:00 AM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                                <div class="start-session-box border-2 border-gray-300 bg-white rounded-lg p-3 md:p-4 cursor-pointer transition-all" data-session="Afternoon">
                                    <div class="text-center">
                                        <i class="fas fa-cloud-sun text-2xl mb-2 text-orange-400"></i>
                                        <h3 class="font-semibold text-base md:text-lg">Afternoon</h3>
                                        <p class="text-xs md:text-sm text-gray-500">1:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                                <div class="start-session-box border-2 border-gray-300 bg-white rounded-lg p-3 md:p-4 cursor-pointer transition-all" data-session="Evening">
                                    <div class="text-center">
                                        <i class="fas fa-moon text-2xl mb-2 text-indigo-400"></i>
                                        <h3 class="font-semibold text-base md:text-lg">Evening</h3>
                                        <p class="text-xs md:text-sm text-gray-500">6:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- End Session -->
                        <div id="endSessionPanel">
                            <label id="endSessionLabel" class="block text-sm font-medium text-gray-700 mb-2">
                                End Session <span class="text-red-500">*</span>
                            </label>
                            <div id="endSessionBoxes" class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
                                <div class="end-session-box border-2 border-gray-300 bg-white rounded-lg p-3 md:p-4 cursor-pointer transition-all" data-session="Morning">
                                    <div class="text-center">
                                        <i class="fas fa-sun text-2xl mb-2 text-yellow-500"></i>
                                        <h3 class="font-semibold text-base md:text-lg">Morning</h3>
                                        <p class="text-xs md:text-sm text-gray-500">12:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                                <div class="end-session-box border-2 border-gray-300 bg-white rounded-lg p-3 md:p-4 cursor-pointer transition-all" data-session="Afternoon">
                                    <div class="text-center">
                                        <i class="fas fa-cloud-sun text-2xl mb-2 text-orange-400"></i>
                                        <h3 class="font-semibold text-base md:text-lg">Afternoon</h3>
                                        <p class="text-xs md:text-sm text-gray-500">5:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                                <div class="end-session-box border-2 border-gray-300 bg-white rounded-lg p-3 md:p-4 cursor-pointer transition-all" data-session="Evening">
                                    <div class="text-center">
                                        <i class="fas fa-moon text-2xl mb-2 text-indigo-400"></i>
                                        <h3 class="font-semibold text-base md:text-lg">Evening</h3>
                                        <p class="text-xs md:text-sm text-gray-500">10:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Visual Timeline -->
                        <div id="visualTimeline" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 hidden">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-week text-primary mr-2"></i> Session Timeline Preview
                            </h4>
                            <div id="timelineContent" class="space-y-2"></div>
                        </div>

                        <input type="hidden" name="event_start_session" id="event_start_session" value="{{ old('event_start_session', $event->event_start_session) }}">
                        <input type="hidden" name="event_end_session"   id="event_end_session"   value="{{ old('event_end_session', $event->event_end_session) }}">

                        @error('event_start_session')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('event_end_session')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Conflict warning moved to popup on next click -->
                        <div id="sessionConflictWarning" class="hidden"></div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4">
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
                <div id="tab3" class="tab-content hidden space-y-4 md:space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Maximum Participants <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="event_maximum_participant" id="event_maximum_participant"
                            value="{{ old('event_maximum_participant', $event->event_maximum_participant) }}"
                            min="0" max="1000"
                            class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary p-3 outline-none"
                            placeholder="Enter maximum number of participants (0 if none)" required>
                        <p class="text-xs text-gray-500 mt-1">Set to 0 if you don't need participants now.</p>
                        @error('event_maximum_participant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Participants</label>
                            <input type="number"
                                value="{{ $event->event_current_participant }}"
                                class="w-full rounded-lg border border-gray-300 bg-gray-100 p-3 text-gray-600 cursor-not-allowed"
                                readonly disabled>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <h3 class="font-semibold text-yellow-800 mb-2">
                            <i class="fas fa-users-slash mr-2"></i> Participant Information
                        </h3>
                        <div class="text-sm text-yellow-700 space-y-1">
                            <p>• Participants can register through the event page once approved.</p>
                            <p>• Current participants: <span class="font-semibold">{{ $event->event_current_participant }}</span> / {{ $event->event_maximum_participant }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4">
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
                <div id="tab4" class="tab-content hidden space-y-4 md:space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supporting Document (PDF)</label>
                        @if($event->event_document)
                            <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 mr-2 text-lg"></i>
                                    <span class="text-sm text-gray-700">Current document uploaded</span>
                                </div>
                                <a href="{{ $event->event_document }}" target="_blank" class="text-primary hover:underline text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i> View
                                </a>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Upload a new file to replace the current document.</p>
                        @endif
                        <label for="event_document"
                            class="relative cursor-pointer mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                            <input id="event_document" name="event_document" type="file" class="sr-only" accept=".pdf">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center flex-wrap">
                                    <span class="font-medium text-primary">Upload a file</span>
                                    <span class="hidden sm:inline pl-1">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500">PDF up to 10 MB</p>
                            </div>
                        </label>
                        <div id="documentFileName" class="text-sm text-gray-600 mt-2 hidden">
                            <i class="fas fa-file-alt text-primary mr-1"></i> <span id="fileName"></span>
                        </div>
                        @error('event_document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Banner / Picture</label>
                        @if($event->event_picture)
                            <div class="mb-2">
                                <img src="{{ $event->event_picture }}" alt="Current banner" class="h-24 w-auto object-cover rounded-lg border">
                                <p class="text-xs text-gray-500 mt-1">Current banner image</p>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Upload a new image to replace the current banner.</p>
                        @endif
                        <label for="event_picture"
                            class="relative cursor-pointer mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                            <input id="event_picture" name="event_picture" type="file" class="sr-only" accept="image/*">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center flex-wrap">
                                    <span class="font-medium text-primary">Upload an image</span>
                                    <span class="hidden sm:inline pl-1">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5 MB</p>
                            </div>
                        </label>
                        <div id="picturePreview" class="mt-3 hidden">
                            <img id="previewImage" src="#" alt="Preview" class="h-32 w-auto object-cover rounded-lg border">
                            <button type="button" id="removeImage" class="text-red-500 text-xs mt-1 hover:underline">Remove image</button>
                        </div>
                        @error('event_picture')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                            <p class="text-sm text-blue-700 ml-3">
                                <strong>Note:</strong> Only other admins can approve this event. 
                                You can edit events for any date (no date restrictions).
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4">
                        <button type="button" class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous: Volunteer Settings
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
// ─── State ────────────────────────────────────────────────────────────────────
let basicInfoValid   = false;
let dateSessionValid = false;
let volunteerValid   = false;
let locationPinned   = {{ !empty($event->event_location_latitude) && !empty($event->event_location_longitude) ? 'true' : 'false' }};

let startDate            = '{{ Carbon\Carbon::parse($event->event_start_date)->format("Y-m-d") }}' || null;
let endDate              = '{{ Carbon\Carbon::parse($event->event_end_date)->format("Y-m-d") }}' || null;
let selectedStartSession = '{{ old('event_start_session', $event->event_start_session) }}' || null;
let selectedEndSession   = '{{ old('event_end_session', $event->event_end_session) }}' || null;
let selectedSessions     = [];
let bookedSessionsByDate = {};
let hasSessionConflict   = false;
let conflictDetails      = [];

const sessionOrder = ['Morning', 'Afternoon', 'Evening'];
const startTimes   = { Morning: '8:00 AM',  Afternoon: '1:00 PM',  Evening: '6:00 PM'  };
const endTimes     = { Morning: '12:00 PM', Afternoon: '5:00 PM',  Evening: '10:00 PM' };

// ─── SweetAlert Error Helper ──────────────────────────────────────────────────
function showTabError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Cannot Switch Tab',
        text: message,
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function getBookedSessionsForDate(date) {
    return bookedSessionsByDate[date] || [];
}

// ─── Location Validation ─────────────────────────────────────────────────────
function validateLocation() {
    const lat = document.getElementById('event_location_latitude').value;
    const lng = document.getElementById('event_location_longitude').value;
    const address = document.getElementById('event_location_address').value;
    
    locationPinned = (lat && lng && address);
    
    const errorEl = document.getElementById('locationError');
    if (errorEl) {
        if (!locationPinned) {
            errorEl.classList.remove('hidden');
        } else {
            errorEl.classList.add('hidden');
        }
    }
    
    validateBasicInfo();
    return locationPinned;
}

// ─── Basic Info Validation ────────────────────────────────────────────────────
const basicInfoFields = document.querySelectorAll('.basic-info-field');
const nextToTab2Btn   = document.getElementById('nextToTab2');

function validateBasicInfo() {
    let allFilled = true;
    basicInfoFields.forEach(f => { 
        if (!f.value.trim()) allFilled = false; 
    });
    if (!locationPinned) allFilled = false;
    
    basicInfoValid = allFilled;
    if (nextToTab2Btn) {
        nextToTab2Btn.disabled = !basicInfoValid;
        nextToTab2Btn.classList.toggle('opacity-50',     !basicInfoValid);
        nextToTab2Btn.classList.toggle('cursor-not-allowed', !basicInfoValid);
    }
}

basicInfoFields.forEach(f => {
    f.addEventListener('input',  validateBasicInfo);
    f.addEventListener('change', validateBasicInfo);
});
validateBasicInfo();

// ─── Tab Switching ────────────────────────────────────────────────────────────
const tabs = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

function switchTab(tabId) {
    contents.forEach(c => c.classList.add('hidden'));
    
    tabs.forEach(t => {
        t.classList.remove('border-primary', 'text-primary');
        t.classList.remove('active');
        t.classList.add('border-transparent', 'text-gray-500');
    });
    
    const sel = document.getElementById(tabId);
    if (sel) sel.classList.remove('hidden');
    
    const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
    if (activeTab) {
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-primary', 'text-primary');
        activeTab.classList.add('active');
    }
    
    if (tabId === 'tab1' && map) {
        setTimeout(() => map.invalidateSize(), 100);
    }
}

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const tabId = tab.getAttribute('data-tab');
        
        if (tabId === 'tab2' && !basicInfoValid) {
            showTabError('Please fill in all required fields in Basic Information first.');
            return;
        }
        if (tabId === 'tab3' && !dateSessionValid) {
            showTabError('Please complete Date & Session section first.');
            return;
        }
        if (tabId === 'tab4' && !volunteerValid) {
            showTabError('Please complete Volunteer Settings first.');
            return;
        }
        switchTab(tabId);
    });
});

// ─── Next button with conflict popup ─────────────────────────────────────────
document.querySelectorAll('.next-tab').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const btnId = this.id;
        
        if (btnId === 'nextToTab2' && !basicInfoValid) {
            if (!locationPinned) {
                showTabError('Please pin a location on the map first.');
            } else {
                showTabError('Please fill in all required fields in Basic Information.');
            }
            return;
        }
        
        if (btnId === 'nextToTab3' && !dateSessionValid) {
            showTabError('Please complete Date & Session section.');
            return;
        }
        
        if (btnId === 'nextToTab4' && !volunteerValid) {
            showTabError('Please complete Volunteer Settings section.');
            return;
        }

        if (btnId === 'nextToTab3') {
            const hasConflict = checkForConflicts();
            
            if (hasConflict) {
                let conflictHtml = '';
                if (conflictDetails.length > 0) {
                    conflictHtml = `<div class="bg-yellow-50 p-3 rounded-lg text-sm max-h-60 overflow-y-auto">
                        ${conflictDetails.join('<hr class="my-2 border-yellow-200">')}
                    </div>`;
                }
                
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Session Conflict Detected!',
                    html: `
                        <div class="text-left">
                            <p class="text-sm text-gray-700 mb-3">The selected date and session range overlaps with existing events:</p>
                            ${conflictHtml}
                            <p class="text-sm text-gray-700 mt-3 font-medium">Do you want to continue anyway?</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue',
                    cancelButtonText: 'Cancel',
                    width: 650
                }).then((result) => {
                    if (result.isConfirmed) {
                        const cur = btn.closest('.tab-content');
                        const next = cur ? cur.nextElementSibling : null;
                        if (next && next.classList.contains('tab-content')) {
                            switchTab(next.id);
                        }
                    }
                });
                return;
            }
        }

        const cur = btn.closest('.tab-content');
        const next = cur ? cur.nextElementSibling : null;
        if (next && next.classList.contains('tab-content')) {
            switchTab(next.id);
        }
    });
});

// ─── Check for conflicts ─────────────────────────────────────────────────────
function checkForConflicts() {
    conflictDetails = [];
    hasSessionConflict = false;

    if (!startDate || !endDate || !selectedStartSession || !selectedEndSession) {
        return false;
    }

    const isSameDate = startDate === endDate;
    const bookedStart = getBookedSessionsForDate(startDate);
    const bookedEnd = getBookedSessionsForDate(endDate);

    if (isSameDate) {
        const si = sessionOrder.indexOf(selectedStartSession);
        const ei = sessionOrder.indexOf(selectedEndSession);
        const sessions = sessionOrder.slice(si, ei + 1);
        const overlapping = sessions.filter(s => bookedStart.includes(s));
        
        if (overlapping.length > 0) {
            hasSessionConflict = true;
            let detailsHtml = `<div class="font-semibold text-yellow-800">📅 ${startDate}</div>
                <div class="text-sm">Booked sessions: <span class="font-semibold text-red-600">${overlapping.join(', ')}</span></div>
                <div class="text-xs text-gray-500 mt-1">These sessions are already booked on this date.</div>`;
            conflictDetails.push(detailsHtml);
        }
    } else {
        const si = sessionOrder.indexOf(selectedStartSession);
        const ei = sessionOrder.indexOf(selectedEndSession);
        const startSessions = sessionOrder.slice(si);
        const endSessions = sessionOrder.slice(0, ei + 1);
        
        const overlappingStart = startSessions.filter(s => bookedStart.includes(s));
        const overlappingEnd = endSessions.filter(s => bookedEnd.includes(s));
        
        if (overlappingStart.length > 0) {
            hasSessionConflict = true;
            let detailsHtml = `<div class="font-semibold text-yellow-800">📅 ${startDate} (Start Date)</div>
                <div class="text-sm">Booked sessions: <span class="font-semibold text-red-600">${overlappingStart.join(', ')}</span></div>
                <div class="text-xs text-gray-500 mt-1">These sessions are already booked on the start date.</div>`;
            conflictDetails.push(detailsHtml);
        }
        
        if (overlappingEnd.length > 0) {
            hasSessionConflict = true;
            let detailsHtml = `<div class="font-semibold text-yellow-800">📅 ${endDate} (End Date)</div>
                <div class="text-sm">Booked sessions: <span class="font-semibold text-red-600">${overlappingEnd.join(', ')}</span></div>
                <div class="text-xs text-gray-500 mt-1">These sessions are already booked on the end date.</div>`;
            conflictDetails.push(detailsHtml);
        }
        
        const cur = new Date(startDate);
        const fin = new Date(endDate);
        cur.setDate(cur.getDate() + 1);
        while (cur < fin) {
            const ds = cur.toISOString().split('T')[0];
            const booked = getBookedSessionsForDate(ds);
            if (booked.length > 0) {
                hasSessionConflict = true;
                let detailsHtml = `<div class="font-semibold text-yellow-800">📅 ${ds}</div>
                    <div class="text-sm">Booked sessions: <span class="font-semibold text-red-600">${booked.join(', ')}</span></div>
                    <div class="text-xs text-gray-500 mt-1">These sessions are already booked on this date.</div>`;
                conflictDetails.push(detailsHtml);
            }
            cur.setDate(cur.getDate() + 1);
        }
    }

    return hasSessionConflict;
}

// ─── Prev buttons ─────────────────────────────────────────────────────────────
document.querySelectorAll('.prev-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const cur  = btn.closest('.tab-content');
        const prev = cur ? cur.previousElementSibling : null;
        if (prev && prev.classList.contains('tab-content')) switchTab(prev.id);
    });
});

// ─── Booked Sessions ──────────────────────────────────────────────────────────
async function loadBookedSessionsForRange(start, end) {
    try {
        const res  = await fetch('{{ route("events.getBookedSessionsForRange") }}', {
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
    const isSameDate = startDate && endDate && startDate === endDate;
    const startDateBooked = startDate ? getBookedSessionsForDate(startDate) : [];
    const endDateBooked = endDate ? getBookedSessionsForDate(endDate) : [];

    // Update start session boxes
    document.querySelectorAll('.start-session-box').forEach(box => {
        const s = box.getAttribute('data-session');
        const booked = startDateBooked.includes(s);
        const isSelected = s === selectedStartSession || (isSameDate && selectedSessions.includes(s));
        
        // Remove all selection classes first
        box.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
        box.classList.remove('border-yellow-400', 'bg-yellow-50');
        
        if (booked) {
            box.classList.add('border-yellow-400', 'bg-yellow-50');
        } else if (!booked && !isSelected) {
            box.classList.add('border-gray-300', 'bg-white');
        }
        
        if (isSelected) {
            box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
            box.classList.remove('border-gray-300', 'bg-white');
        }
        
        box.style.pointerEvents = 'auto';
        const statusDiv = box.querySelector('.session-status');
        if (statusDiv) {
            statusDiv.innerHTML = '';
            statusDiv.classList.add('hidden');
        }
    });

    // Show/hide end session panel
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

    // Update end session boxes
    document.querySelectorAll('.end-session-box').forEach(box => {
        const s = box.getAttribute('data-session');
        const booked = endDateBooked.includes(s);
        const isSelected = s === selectedEndSession && !isSameDate;
        
        box.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
        box.classList.remove('border-yellow-400', 'bg-yellow-50');
        
        if (booked) {
            box.classList.add('border-yellow-400', 'bg-yellow-50');
        } else if (!booked && !isSelected) {
            box.classList.add('border-gray-300', 'bg-white');
        }
        
        if (isSelected) {
            box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
            box.classList.remove('border-gray-300', 'bg-white');
        }
        
        box.style.pointerEvents = 'auto';
        const statusDiv = box.querySelector('.session-status');
        if (statusDiv) {
            statusDiv.innerHTML = '';
            statusDiv.classList.add('hidden');
        }
    });
}

function setDateSessionValid(val) {
    dateSessionValid = val;
    const btn = document.getElementById('nextToTab3');
    if (!btn) return;
    btn.disabled = !val;
    btn.classList.toggle('opacity-50', !val);
    btn.classList.toggle('cursor-not-allowed', !val);
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

    document.getElementById('visualTimeline').classList.remove('hidden');

    function sessionRow(session, isBooked, showTime = true) {
        const color = isBooked ? 'bg-yellow-100 border-yellow-400' : 'bg-green-50 border-green-500';
        const label = isBooked ? '⚠️ Booked' : '✅ Included';
        const tClass = isBooked ? 'text-yellow-600' : 'text-green-600';
        const time = showTime ? `<span class="text-xs text-gray-500 ml-2">${startTimes[session]} – ${endTimes[session]}</span>` : '';
        return `<div class="px-3 py-2 ${color} border-l-4 mb-1 rounded flex justify-between items-center">
                    <div><span class="font-semibold">${session}</span>${time}</div>
                    <span class="text-xs ${tClass}">${label}</span>
                </div>`;
    }

    let html = '<div class="border rounded-lg overflow-hidden">';

    if (isSameDate) {
        html += `<div class="bg-gray-100 px-3 py-2 font-semibold text-sm"><i class="fas fa-calendar-day mr-1"></i>${startDate}</div><div class="p-2">`;
        for (let i = startIndex; i <= endIndex; i++) {
            html += sessionRow(sessionOrder[i], getBookedSessionsForDate(startDate).includes(sessionOrder[i]));
        }
        html += '</div>';
    } else {
        html += `<div class="bg-gray-100 px-3 py-2 font-semibold text-sm"><i class="fas fa-calendar-alt mr-1"></i>${startDate} – ${endDate}</div>`;

        html += `<div class="p-2 bg-blue-50 border-l-4 border-blue-500 m-2 rounded">
                     <div class="flex justify-between mb-1"><span class="font-semibold">📅 ${startDate}</span><span class="text-xs text-blue-600">Start Date ▼</span></div>`;
        sessionOrder.slice(startIndex).forEach(s => { html += sessionRow(s, getBookedSessionsForDate(startDate).includes(s)); });
        html += '</div>';

        const cur = new Date(startDate);
        const fin = new Date(endDate);
        cur.setDate(cur.getDate() + 1);
        while (cur < fin) {
            const ds = cur.toISOString().split('T')[0];
            html += `<div class="p-2 bg-yellow-50 border-l-4 border-yellow-500 m-2 rounded">
                         <div class="flex justify-between mb-1"><span class="font-semibold">📅 ${ds}</span><span class="text-xs text-yellow-600">Full day</span></div>`;
            sessionOrder.forEach(s => { html += sessionRow(s, getBookedSessionsForDate(ds).includes(s)); });
            html += '</div>';
            cur.setDate(cur.getDate() + 1);
        }

        html += `<div class="p-2 bg-green-50 border-l-4 border-green-500 m-2 rounded">
                     <div class="flex justify-between mb-1"><span class="font-semibold">📅 ${endDate}</span><span class="text-xs text-green-600">▲ End Date</span></div>`;
        sessionOrder.slice(0, endIndex + 1).forEach(s => { html += sessionRow(s, getBookedSessionsForDate(endDate).includes(s)); });
        html += '</div>';
    }

    html += '</div>';
    document.getElementById('timelineContent').innerHTML = html;
}

// ─── Session Selection UI ─────────────────────────────────────────────────────
function updateSameDateSelectionUI() {
    const active = new Set(selectedSessions);
    document.querySelectorAll('.start-session-box').forEach(box => {
        const s = box.getAttribute('data-session');
        const isActive = active.has(s);
        const isBooked = box.classList.contains('border-yellow-400');
        
        // Remove selection classes
        box.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
        
        if (isActive) {
            box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
            if (isBooked) {
                box.classList.remove('border-yellow-400', 'bg-yellow-50');
            }
        } else if (!isBooked) {
            box.classList.add('border-gray-300', 'bg-white');
        }
    });
    
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
}

function validateSelection() {
    const isSameDate = startDate && endDate && startDate === endDate;

    if (isSameDate) {
        if (selectedSessions.length === 0) {
            setDateSessionValid(false);
            return;
        }
        setDateSessionValid(true);
        updateVisualTimeline();
        return;
    }

    if (!selectedStartSession || !selectedEndSession) {
        setDateSessionValid(false);
        return;
    }
    
    setDateSessionValid(true);
    updateVisualTimeline();
}

// Start session boxes
document.querySelectorAll('.start-session-box').forEach(box => {
    box.addEventListener('click', function() {
        const session = this.getAttribute('data-session');
        const isSameDate = startDate && endDate && startDate === endDate;
        
        // Remove selected class from all start boxes
        document.querySelectorAll('.start-session-box').forEach(b => {
            if (!b.classList.contains('border-yellow-400')) {
                b.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
                b.classList.add('border-gray-300', 'bg-white');
            }
        });
        
        if (isSameDate) {
            // Toggle session selection for same date
            const idx = sessionOrder.indexOf(session);
            if (selectedSessions.includes(session)) {
                // Remove session from selection
                selectedSessions = selectedSessions.filter(s => s !== session);
                // If removing breaks continuity, reset
                if (selectedSessions.length > 0) {
                    // Check if selection is still contiguous
                    const indices = selectedSessions.map(s => sessionOrder.indexOf(s)).sort((a, b) => a - b);
                    let isContiguous = true;
                    for (let i = 1; i < indices.length; i++) {
                        if (indices[i] !== indices[i-1] + 1) {
                            isContiguous = false;
                            break;
                        }
                    }
                    if (!isContiguous) {
                        selectedSessions = [session];
                    }
                }
            } else {
                // Add session to selection
                if (selectedSessions.length === 0) {
                    selectedSessions = [session];
                } else {
                    const existingIndices = selectedSessions.map(s => sessionOrder.indexOf(s));
                    const newIdx = sessionOrder.indexOf(session);
                    const minIdx = Math.min(...existingIndices, newIdx);
                    const maxIdx = Math.max(...existingIndices, newIdx);
                    // Select contiguous range
                    selectedSessions = sessionOrder.slice(minIdx, maxIdx + 1);
                }
            }
            
            // Reapply selection
            updateSameDateSelectionUI();
            validateSelection();
            return;
        }
        
        // For multi-day: select start session
        if (selectedStartSession === session) {
            // Deselect if already selected
            selectedStartSession = null;
            document.getElementById('event_start_session').value = '';
            this.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
            if (!this.classList.contains('border-yellow-400')) {
                this.classList.add('border-gray-300', 'bg-white');
            }
        } else {
            // Select new start session
            selectedStartSession = session;
            document.getElementById('event_start_session').value = session;
            this.classList.add('border-blue-500', 'bg-blue-100', 'selected');
            this.classList.remove('border-gray-300', 'bg-white');
        }
        
        if (selectedStartSession && selectedEndSession) {
            validateSelection();
        } else {
            updateVisualTimeline();
        }
    });
});

// End session boxes
document.querySelectorAll('.end-session-box').forEach(box => {
    box.addEventListener('click', function() {
        if (startDate === endDate) return;
        const session = this.getAttribute('data-session');
        
        // Remove selected class from all end boxes
        document.querySelectorAll('.end-session-box').forEach(b => {
            if (!b.classList.contains('border-yellow-400')) {
                b.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
                b.classList.add('border-gray-300', 'bg-white');
            }
        });
        
        if (selectedEndSession === session) {
            // Deselect if already selected
            selectedEndSession = null;
            document.getElementById('event_end_session').value = '';
            this.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
            if (!this.classList.contains('border-yellow-400')) {
                this.classList.add('border-gray-300', 'bg-white');
            }
        } else {
            // Select new end session
            selectedEndSession = session;
            document.getElementById('event_end_session').value = session;
            this.classList.add('border-blue-500', 'bg-blue-100', 'selected');
            this.classList.remove('border-gray-300', 'bg-white');
        }
        
        if (selectedStartSession && selectedEndSession) {
            validateSelection();
        } else {
            updateVisualTimeline();
        }
    });
});

// ─── Date Change ──────────────────────────────────────────────────────────────
const startDateInput = document.getElementById('event_start_date');
const endDateInput = document.getElementById('event_end_date');
const sessionSelectionWrapper = document.getElementById('sessionSelectionWrapper');

// Remove min date restriction - admins can select any date
if (startDateInput) {
    startDateInput.removeAttribute('min');
}

async function onDateChange() {
    startDate = startDateInput.value;
    endDate = endDateInput.value;
    if (!startDate || !endDate) {
        sessionSelectionWrapper.classList.add('hidden');
        return;
    }
    if (endDate < startDate) {
        endDateInput.value = startDate;
        endDate = startDate;
    }

    sessionSelectionWrapper.classList.remove('hidden');

    // Reset selections
    selectedStartSession = selectedEndSession = null;
    selectedSessions = [];
    setDateSessionValid(false);
    document.getElementById('event_start_session').value = '';
    document.getElementById('event_end_session').value = '';
    
    document.querySelectorAll('.start-session-box, .end-session-box').forEach(b => {
        b.classList.remove('border-blue-500', 'bg-blue-100', 'selected');
        b.classList.add('border-gray-300', 'bg-white');
    });

    const bookedMap = await loadBookedSessionsForRange(startDate, endDate);
    updateSessionAvailability(bookedMap);
    document.getElementById('visualTimeline').classList.add('hidden');
    
    // After loading booked sessions, check if we need to restore selected sessions
    if (selectedStartSession && selectedEndSession) {
        // Re-apply selection
        document.querySelectorAll('.start-session-box').forEach(box => {
            if (box.getAttribute('data-session') === selectedStartSession) {
                box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
                box.classList.remove('border-gray-300', 'bg-white');
            }
        });
        if (startDate !== endDate) {
            document.querySelectorAll('.end-session-box').forEach(box => {
                if (box.getAttribute('data-session') === selectedEndSession) {
                    box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
                    box.classList.remove('border-gray-300', 'bg-white');
                }
            });
        }
        setDateSessionValid(true);
        updateVisualTimeline();
    }
}

if (startDateInput) {
    startDateInput.addEventListener('change', () => {
        if (endDateInput) {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value && endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        }
        onDateChange();
    });
}
if (endDateInput) {
    endDateInput.addEventListener('change', onDateChange);
}

// ─── Volunteer Validation ─────────────────────────────────────────────────────
const volunteerField = document.getElementById('event_maximum_participant');
const nextToTab4Btn = document.getElementById('nextToTab4');

function validateVolunteer() {
    const val = volunteerField ? parseInt(volunteerField.value, 10) : NaN;
    volunteerValid = !isNaN(val) && val >= 0;
    if (nextToTab4Btn) {
        nextToTab4Btn.disabled = !volunteerValid;
        nextToTab4Btn.classList.toggle('opacity-50', !volunteerValid);
        nextToTab4Btn.classList.toggle('cursor-not-allowed', !volunteerValid);
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
        if (this.files[0]) {
            fileNameSpan.textContent = this.files[0].name;
            docFileName.classList.remove('hidden');
        } else {
            docFileName.classList.add('hidden');
        }
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
            reader.onload = e => {
                previewImage.src = e.target.result;
                picturePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            picturePreview.classList.add('hidden');
        }
    });
}
if (removeImageBtn) {
    removeImageBtn.addEventListener('click', () => {
        pictureInput.value = '';
        picturePreview.classList.add('hidden');
    });
}

// ─── Flash messages ───────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#554994',
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route('admin.pendingevent') }}';
            }
        });
    @endif
    
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
    
    @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: errorMessages,
            confirmButtonColor: '#d33'
        });
    @endif
    
    // Initialize with existing data - FIX: Set date values directly
    const startDateInput = document.getElementById('event_start_date');
    const endDateInput   = document.getElementById('event_end_date');
    
    if (startDateInput) {
    startDateInput.value = '{{ Carbon\Carbon::parse($event->event_start_date)->format("Y-m-d") }}';
    }

    if (endDateInput) {
    endDateInput.value = '{{ Carbon\Carbon::parse($event->event_end_date)->format("Y-m-d") }}';
    }
    
    // Show session wrapper and restore existing selections
    if (startDate && endDate) {
        sessionSelectionWrapper.classList.remove('hidden');

        loadBookedSessionsForRange(startDate, endDate).then(bookedMap => {
            bookedSessionsByDate = bookedMap;
            updateSessionAvailability(bookedMap);

            // Restore selected start session box highlight
            if (selectedStartSession) {
                document.querySelectorAll('.start-session-box').forEach(box => {
                    if (box.getAttribute('data-session') === selectedStartSession) {
                        box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
                        box.classList.remove('border-gray-300', 'bg-white');
                    }
                });
            }

            // Restore selected end session box highlight (only for multi-day)
            if (startDate !== endDate && selectedEndSession) {
                document.querySelectorAll('.end-session-box').forEach(box => {
                    if (box.getAttribute('data-session') === selectedEndSession) {
                        box.classList.add('border-blue-500', 'bg-blue-100', 'selected');
                        box.classList.remove('border-gray-300', 'bg-white');
                    }
                });
            }

            // For same-day, rebuild selectedSessions array from start/end
            if (startDate === endDate && selectedStartSession && selectedEndSession) {
                const si = sessionOrder.indexOf(selectedStartSession);
                const ei = sessionOrder.indexOf(selectedEndSession);
                selectedSessions = sessionOrder.slice(si, ei + 1);
                updateSameDateSelectionUI();
            }

            // Mark as valid since we're editing an existing event with sessions already set
            if (selectedStartSession && selectedEndSession) {
                setDateSessionValid(true);
                updateVisualTimeline();
            }
        });
    }
});

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
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    if (existingLat && existingLng) {
        marker = L.marker([existingLat, existingLng], { draggable: true }).addTo(map);
        marker.on('dragend', e => setLocation(e.target.getLatLng().lat, e.target.getLatLng().lng));
        const existingAddr = document.getElementById('event_location_address').value;
        if (existingAddr) {
            document.getElementById('event_location_address_display').value = existingAddr;
            locationPinned = true;
            validateBasicInfo();
        }
    }

    map.on('click', e => setLocation(e.latlng.lat, e.latlng.lng));
}

async function setLocation(lat, lng) {
    document.getElementById('event_location_latitude').value = lat;
    document.getElementById('event_location_longitude').value = lng;

    if (marker) {
        marker.setLatLng([lat, lng]);
    } else {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', e => setLocation(e.target.getLatLng().lat, e.target.getLatLng().lng));
    }

    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=en&addressdetails=1`);
        const data = await res.json();
        const fullAddress = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        
        document.getElementById('event_location_address').value = fullAddress;
        document.getElementById('event_location_address_display').value = fullAddress;
        
        locationPinned = true;
        validateBasicInfo();
    } catch (err) {
        console.error('Reverse geocoding error:', err);
        const fallback = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        document.getElementById('event_location_address').value = fallback;
        document.getElementById('event_location_address_display').value = fallback;
        locationPinned = true;
        validateBasicInfo();
    }

    Swal.fire({
        icon: 'success',
        title: 'Location Updated!',
        text: 'The map address has been updated.',
        confirmButtonColor: '#554994',
        timer: 1500,
        showConfirmButton: false
    });
}

async function searchLocation() {
    const q = document.getElementById('location_search').value.trim();
    if (!q) {
        Swal.fire({ icon: 'warning', title: 'Empty Search', text: 'Please enter a location to search.', confirmButtonColor: '#554994' });
        return;
    }

    Swal.fire({ title: 'Searching…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${encodeURIComponent(q)}&limit=5&countrycodes=my`);
        const results = await res.json();
        Swal.close();

        if (results.length > 0) {
            const r = results[0];
            map.setView([parseFloat(r.lat), parseFloat(r.lon)], 15);

            const fullAddress = r.display_name || '';

            document.getElementById('event_location_latitude').value = parseFloat(r.lat);
            document.getElementById('event_location_longitude').value = parseFloat(r.lon);
            document.getElementById('event_location_address').value = fullAddress;
            document.getElementById('event_location_address_display').value = fullAddress;

            if (marker) {
                marker.setLatLng([parseFloat(r.lat), parseFloat(r.lon)]);
            } else {
                marker = L.marker([parseFloat(r.lat), parseFloat(r.lon)], { draggable: true }).addTo(map);
                marker.on('dragend', e => setLocation(e.target.getLatLng().lat, e.target.getLatLng().lng));
            }

            locationPinned = true;
            validateBasicInfo();

            Swal.fire({
                icon: 'success',
                title: 'Location Found!',
                text: 'The address has been saved to the map.',
                confirmButtonColor: '#554994',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Not Found', text: 'Try a different search term.', confirmButtonColor: '#d33' });
        }
    } catch (err) {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Search Failed', text: 'Please try again.', confirmButtonColor: '#d33' });
    }
}

function getCurrentLocation() {
    if (!navigator.geolocation) {
        Swal.fire({ icon: 'error', title: 'Not Supported', text: 'Geolocation is not supported by your browser.', confirmButtonColor: '#d33' });
        return;
    }

    Swal.fire({ title: 'Getting Your Location…', text: 'Please allow location access when prompted.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            Swal.close();
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            map.setView([lat, lng], 15);
            await setLocation(lat, lng);
        },
        () => {
            Swal.fire({ icon: 'error', title: 'Location Error', text: 'Unable to get your location. Please allow location access and try again.', confirmButtonColor: '#d33' });
        },
        { timeout: 10000, maximumAge: 0 }
    );
}

// Initialize map on page load
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initMap, 200);

    document.getElementById('search_btn')?.addEventListener('click', searchLocation);
    document.getElementById('current_location_btn')?.addEventListener('click', getCurrentLocation);
    document.getElementById('location_search')?.addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchLocation();
        }
    });
});

</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    .tab-btn {
        cursor: pointer;
    }
    .tab-btn.active {
        border-bottom-color: #554994;
        color: #554994;
    }
    .start-session-box.selected, .end-session-box.selected {
        border-color: #554994 !important;
        background-color: #eef2ff !important;
    }
    .start-session-box.booked, .end-session-box.booked {
        border-color: #f59e0b;
        background-color: #fef3c7;
        cursor: pointer;
    }
    #map {
        z-index: 1;
    }
    .leaflet-control-container {
        z-index: 2;
    }
    .swal2-popup {
        max-width: 650px !important;
    }
    .swal2-html-container {
        max-height: 400px;
        overflow-y: auto;
    }
    .swal2-html-container a {
        color: #554994 !important;
        text-decoration: underline !important;
    }
    .swal2-html-container a:hover {
        color: #3d3a6b !important;
    }
</style>
@endpush