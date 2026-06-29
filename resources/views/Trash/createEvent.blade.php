@extends('user.layouts.userLayouts')

@section('title', 'Create Event | Kasih Istimewa')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Create New Event</h1>
        <p class="text-lg opacity-90 max-w-2xl mx-auto">
            Organize an event and make a difference in our community. Fill in the details below to get started.
        </p>
    </div>
</section>

<!-- Create Event Form Section -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-plus text-primary mr-2"></i> Event Information
                </h2>
                <p class="text-sm text-gray-500 mt-1">Please fill in all required fields marked with <span class="text-red-500">*</span></p>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6 pt-4" aria-label="Tabs">
                    <button type="button" class="tab-btn active mr-8 py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm transition" data-tab="tab1">
                        <i class="fas fa-info-circle mr-2"></i> Basic Information
                    </button>
                    <button type="button" class="tab-btn mr-8 py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition" data-tab="tab2">
                        <i class="fas fa-calendar-alt mr-2"></i> Date & Session
                    </button>
                    <button type="button" class="tab-btn mr-8 py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition" data-tab="tab3">
                        <i class="fas fa-users mr-2"></i> Volunteer Settings
                    </button>
                    <button type="button" class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition" data-tab="tab4">
                        <i class="fas fa-file-upload mr-2"></i> Documents & Media
                    </button>
                </nav>
            </div>

            <!-- Form Body -->
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="eventForm">
                @csrf

                <!-- Hidden fields -->
                <input type="hidden" name="event_created_by_id" value="{{ auth()->user()->user_id }}">
                <input type="hidden" name="event_current_participant" value="0">
                <input type="hidden" name="event_approval_status" value="Pending">
                <input type="hidden" name="event_publish" value="0">

                <!-- Tab 1: Basic Information -->
                <div id="tab1" class="tab-content space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            User ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            value="{{ auth()->user()->user_id }}" 
                            class="w-full rounded-lg border-gray-300 bg-gray-100 p-3 text-gray-600 cursor-not-allowed"
                            readonly disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Organizer Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            value="{{ auth()->user()->name }}" 
                            class="w-full rounded-lg border-gray-300 bg-gray-100 p-3 text-gray-600 cursor-not-allowed"
                            readonly disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Company/Organization Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="event_company_name" id="event_company_name" 
                            value="{{ old('event_company_name') }}"
                            class="basic-info-field w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary p-3"
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
                            value="{{ old('event_name') }}"
                            class="basic-info-field w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary p-3"
                            placeholder="Enter event title" required>
                        @error('event_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event Location <span class="text-red-500">*</span>
                        </label>
                        <textarea name="event_location_name" id="event_location_name" rows="3"
                            class="basic-info-field w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary p-3"
                            placeholder="Enter physical venue address or digital meeting link" required>{{ old('event_location_name') }}</textarea>
                        @error('event_location_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" id="nextToTab2" class="next-tab px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition opacity-50 cursor-not-allowed" disabled>
                            Next: Date & Session <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Date & Session -->
                <div id="tab2" class="tab-content hidden space-y-6">
                    <!-- Event Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_start_date" id="event_start_date" 
                            min="{{ date('Y-m-d', strtotime('+10 days')) }}"
                            class="date-field w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary p-3">
                        <p class="text-xs text-gray-400 mt-1">Must be at least 10 days from today</p>
                    </div>

                    <!-- Event End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_end_date" id="event_end_date" 
                            class="date-field w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary p-3">
                        <p class="text-xs text-gray-400 mt-1">Can be same as start date or later</p>
                    </div>

                    <!-- Dynamic Session Selection Area -->
                    <div id="sessionContainer" class="space-y-4">
                        <div id="singleSessionArea" class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Session <span class="text-red-500">*</span>
                            </label>
                            <div id="singleSessionBoxes" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Morning Session Box -->
                                <div class="single-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Morning">
                                    <div class="text-center">
                                        <i class="fas fa-sun text-2xl mb-2"></i>
                                        <h3 class="font-semibold text-lg">Morning</h3>
                                        <p class="text-sm text-gray-500">8:00 AM - 12:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                                <!-- Afternoon Session Box -->
                                <div class="single-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Afternoon">
                                    <div class="text-center">
                                        <i class="fas fa-cloud-sun text-2xl mb-2"></i>
                                        <h3 class="font-semibold text-lg">Afternoon</h3>
                                        <p class="text-sm text-gray-500">1:00 PM - 5:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                                <!-- Night Session Box -->
                                <div class="single-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Night">
                                    <div class="text-center">
                                        <i class="fas fa-moon text-2xl mb-2"></i>
                                        <h3 class="font-semibold text-lg">Night</h3>
                                        <p class="text-sm text-gray-500">6:00 PM - 10:00 PM</p>
                                        <div class="mt-2 session-status hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="differentSessionArea" class="hidden space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Session <span class="text-red-500">*</span>
                                </label>
                                <div id="startSessionBoxes" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="start-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Morning">
                                        <div class="text-center">
                                            <i class="fas fa-sun text-2xl mb-2"></i>
                                            <h3 class="font-semibold text-lg">Morning</h3>
                                            <p class="text-sm text-gray-500">8:00 AM</p>
                                            <div class="mt-2 session-status hidden"></div>
                                        </div>
                                    </div>
                                    <div class="start-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Afternoon">
                                        <div class="text-center">
                                            <i class="fas fa-cloud-sun text-2xl mb-2"></i>
                                            <h3 class="font-semibold text-lg">Afternoon</h3>
                                            <p class="text-sm text-gray-500">1:00 PM</p>
                                            <div class="mt-2 session-status hidden"></div>
                                        </div>
                                    </div>
                                    <div class="start-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Night">
                                        <div class="text-center">
                                            <i class="fas fa-moon text-2xl mb-2"></i>
                                            <h3 class="font-semibold text-lg">Night</h3>
                                            <p class="text-sm text-gray-500">6:00 PM</p>
                                            <div class="mt-2 session-status hidden"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    End Session <span class="text-red-500">*</span>
                                </label>
                                <div id="endSessionBoxes" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="end-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Morning">
                                        <div class="text-center">
                                            <i class="fas fa-sun text-2xl mb-2"></i>
                                            <h3 class="font-semibold text-lg">Morning</h3>
                                            <p class="text-sm text-gray-500">12:00 PM</p>
                                            <div class="mt-2 session-status hidden"></div>
                                        </div>
                                    </div>
                                    <div class="end-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Afternoon">
                                        <div class="text-center">
                                            <i class="fas fa-cloud-sun text-2xl mb-2"></i>
                                            <h3 class="font-semibold text-lg">Afternoon</h3>
                                            <p class="text-sm text-gray-500">5:00 PM</p>
                                            <div class="mt-2 session-status hidden"></div>
                                        </div>
                                    </div>
                                    <div class="end-session-box border-2 rounded-lg p-4 cursor-pointer transition-all" data-session="Night">
                                        <div class="text-center">
                                            <i class="fas fa-moon text-2xl mb-2"></i>
                                            <h3 class="font-semibold text-lg">Night</h3>
                                            <p class="text-sm text-gray-500">10:00 PM</p>
                                            <div class="mt-2 session-status hidden"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs for sessions -->
                    <input type="hidden" name="event_start_session" id="event_start_session" value="">
                    <input type="hidden" name="event_end_session" id="event_end_session" value="">

                    <div id="sessionConflictWarning" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    The selected session(s) are already booked for this date range. Please choose different sessions.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous: Basic Info
                        </button>
                        <button type="button" id="nextToTab3" class="next-tab px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition opacity-50 cursor-not-allowed" disabled>
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
                            value="{{ old('event_maximum_participant') }}"
                            min="1" max="1000"
                            class="volunteer-field w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary p-3"
                            placeholder="Enter maximum number of volunteers" required>
                        <div class="mt-2 flex items-center space-x-2">
                            <i class="fas fa-info-circle text-gray-400"></i>
                            <p class="text-xs text-gray-500">This determines how many volunteers can join your event</p>
                        </div>
                        @error('event_maximum_participant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <h3 class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-chart-line mr-2"></i> Volunteer Capacity Info
                        </h3>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p>• Current registered volunteers: <strong>0</strong> (will be updated automatically)</p>
                            <p>• Available spots: <strong id="availableSpots">0</strong></p>
                            <p class="text-xs mt-2">Note: You cannot edit this after the event is approved</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas-arrow-left mr-2"></i> Previous: Date & Session
                        </button>
                        <button type="button" id="nextToTab4" class="next-tab px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition opacity-50 cursor-not-allowed" disabled>
                            Next: Documents & Media <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 4: Documents & Media -->
                <div id="tab4" class="tab-content hidden space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Supporting Document (PDF)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="event_document" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input id="event_document" name="event_document" type="file" class="sr-only" accept=".pdf">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF up to 10MB</p>
                            </div>
                        </div>
                        <div id="documentFileName" class="text-sm text-gray-600 mt-2 hidden">
                            <i class="fas fa-file-alt text-primary mr-1"></i> <span id="fileName"></span>
                        </div>
                        @error('event_document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Event Banner/Picture
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="event_picture" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none">
                                        <span>Upload an image</span>
                                        <input id="event_picture" name="event_picture" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                            </div>
                        </div>
                        <div id="picturePreview" class="mt-3 hidden">
                            <img id="previewImage" src="#" alt="Preview" class="h-32 w-auto object-cover rounded-lg border">
                            <button type="button" id="removeImage" class="text-red-500 text-xs mt-1 hover:underline">Remove image</button>
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
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition shadow-md flex items-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Submit for Approval</span>
                        </button>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Note:</strong> Your event will be reviewed by our admin team. You will be notified once it's approved. 
                                Events must be scheduled at least 10 days in advance.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Variables
    let basicInfoValid = false;
    let dateSessionValid = false;
    let volunteerValid = false;
    let isSameDate = true;
    let startSessionSelected = false;
    let endSessionSelected = false;

    // Basic Info Validation
    const basicInfoFields = document.querySelectorAll('.basic-info-field');
    const nextToTab2Btn = document.getElementById('nextToTab2');

    function validateBasicInfo() {
        let allFilled = true;
        basicInfoFields.forEach(field => {
            if (!field.value.trim()) allFilled = false;
        });
        basicInfoValid = allFilled;
        if (nextToTab2Btn) {
            if (basicInfoValid) {
                nextToTab2Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                nextToTab2Btn.disabled = false;
            } else {
                nextToTab2Btn.classList.add('opacity-50', 'cursor-not-allowed');
                nextToTab2Btn.disabled = true;
            }
        }
    }

    basicInfoFields.forEach(field => {
        field.addEventListener('input', validateBasicInfo);
        field.addEventListener('change', validateBasicInfo);
    });
    validateBasicInfo();

    // Tab switching
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    function switchTab(tabId) {
        contents.forEach(content => content.classList.add('hidden'));
        tabs.forEach(tab => {
            tab.classList.remove('active', 'border-primary', 'text-primary');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        const selectedContent = document.getElementById(tabId);
        if (selectedContent) selectedContent.classList.remove('hidden');
        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        if (activeTab) {
            activeTab.classList.add('active', 'border-primary', 'text-primary');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');
            if (tabId === 'tab2' && !basicInfoValid) {
                alert('Please fill in all required fields in Basic Information first.');
                return;
            }
            if (tabId === 'tab3' && !dateSessionValid) {
                alert('Please complete Date & Session section first.');
                return;
            }
            if (tabId === 'tab4' && !volunteerValid) {
                alert('Please complete Volunteer Settings first.');
                return;
            }
            switchTab(tabId);
        });
    });

    // Next/Previous buttons
    document.querySelectorAll('.next-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.id === 'nextToTab2' && !basicInfoValid) {
                alert('Please fill in all required fields.');
                return;
            }
            if (btn.id === 'nextToTab3' && !dateSessionValid) {
                alert('Please complete Date & Session section.');
                return;
            }
            if (btn.id === 'nextToTab4' && !volunteerValid) {
                alert('Please complete Volunteer Settings section.');
                return;
            }
            const currentTab = btn.closest('.tab-content');
            const nextTab = currentTab.nextElementSibling;
            if (nextTab && nextTab.classList.contains('tab-content')) {
                switchTab(nextTab.getAttribute('id'));
            }
        });
    });

    document.querySelectorAll('.prev-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            const currentTab = btn.closest('.tab-content');
            const prevTab = currentTab.previousElementSibling;
            if (prevTab && prevTab.classList.contains('tab-content')) {
                switchTab(prevTab.getAttribute('id'));
            }
        });
    });

    // Date handling
    const startDateInput = document.getElementById('event_start_date');
    const endDateInput = document.getElementById('event_end_date');
    const singleSessionArea = document.getElementById('singleSessionArea');
    const differentSessionArea = document.getElementById('differentSessionArea');
    const startSessionHidden = document.getElementById('event_start_session');
    const endSessionHidden = document.getElementById('event_end_session');
    const nextToTab3Btn = document.getElementById('nextToTab3');
    const conflictWarning = document.getElementById('sessionConflictWarning');

    const singleSessionBoxes = document.querySelectorAll('.single-session-box');
    const startSessionBoxes = document.querySelectorAll('.start-session-box');
    const endSessionBoxes = document.querySelectorAll('.end-session-box');

    // Function to load booked sessions for a date
    async function loadBookedSessions(date) {
        if (!date) return [];
        
        try {
            const response = await fetch('{{ route("events.getBookedSessions") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ date: date })
            });
            const data = await response.json();
            return data.booked_sessions || [];
        } catch (error) {
            console.error('Error loading booked sessions:', error);
            return [];
        }
    }

    // Function to update session boxes based on booked sessions
    function updateSessionBoxes(boxes, bookedSessions) {
        const sessions = ['Morning', 'Afternoon', 'Night'];
        
        boxes.forEach((box, index) => {
            const session = sessions[index];
            if (bookedSessions.includes(session)) {
                // Booked - red, unclickable
                box.classList.add('unavailable', 'border-red-500', 'bg-red-50', 'cursor-not-allowed');
                box.classList.remove('border-green-500', 'bg-green-50', 'available', 'selected', 'border-blue-500', 'bg-blue-100');
                box.style.pointerEvents = 'none';
                const statusDiv = box.querySelector('.session-status');
                if (statusDiv) {
                    statusDiv.innerHTML = '<span class="text-red-600 text-xs font-semibold">✗ Booked</span>';
                    statusDiv.classList.remove('hidden');
                }
            } else {
                // Available - green, clickable
                box.classList.add('available', 'border-green-500', 'bg-green-50');
                box.classList.remove('border-red-500', 'bg-red-50', 'unavailable');
                box.style.pointerEvents = 'auto';
                const statusDiv = box.querySelector('.session-status');
                if (statusDiv) {
                    statusDiv.innerHTML = '<span class="text-green-600 text-xs font-semibold">✓ Available</span>';
                    statusDiv.classList.remove('hidden');
                }
            }
        });
    }

    // Function to check date range conflict
    async function checkDateRangeConflict(startDate, endDate) {
        if (!startDate || !endDate) return false;
        
        try {
            const response = await fetch('{{ route("events.checkConflict") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ start_date: startDate, end_date: endDate })
            });
            const data = await response.json();
            
            if (data.exists && data.events && data.events.length > 0) {
                let conflictHtml = '<div class="space-y-2">';
                conflictHtml += '<p class="font-semibold">Conflicting event(s):</p>';
                data.events.forEach(event => {
                    conflictHtml += `<div class="border-t border-yellow-200 pt-2 mt-2 text-sm">
                        <p><strong>Event ID:</strong> ${event.event_id}</p>
                        <p><strong>Event:</strong> ${event.event_name}</p>
                        <p><strong>Date:</strong> ${event.event_start_date} to ${event.event_end_date}</p>
                        <p><strong>Sessions:</strong> ${event.event_start_session} - ${event.event_end_session}</p>
                    </div>`;
                });
                conflictHtml += '</div>';
                conflictWarning.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            ${conflictHtml}
                        </div>
                    </div>
                `;
                conflictWarning.classList.remove('hidden');
                return true;
            } else {
                conflictWarning.classList.add('hidden');
                return false;
            }
        } catch (error) {
            console.error('Error checking conflict:', error);
            return false;
        }
    }

    // Handle date changes
    async function onDateChange() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) return;
        
        isSameDate = startDate === endDate;
        
        // Reset selections
        startSessionSelected = false;
        endSessionSelected = false;
        startSessionHidden.value = '';
        endSessionHidden.value = '';
        dateSessionValid = false;
        nextToTab3Btn.classList.add('opacity-50', 'cursor-not-allowed');
        nextToTab3Btn.disabled = true;
        
        if (isSameDate) {
            // Same date mode
            singleSessionArea.classList.remove('hidden');
            differentSessionArea.classList.add('hidden');
            
            // Load booked sessions for this date
            const bookedSessions = await loadBookedSessions(startDate);
            updateSessionBoxes(singleSessionBoxes, bookedSessions);
        } else {
            // Different dates mode
            singleSessionArea.classList.add('hidden');
            differentSessionArea.classList.remove('hidden');
            
            // Load booked sessions for start date
            const startBooked = await loadBookedSessions(startDate);
            updateSessionBoxes(startSessionBoxes, startBooked);
            
            // Load booked sessions for end date
            const endBooked = await loadBookedSessions(endDate);
            updateSessionBoxes(endSessionBoxes, endBooked);
        }
        
        // Check date range conflict
        await checkDateRangeConflict(startDate, endDate);
    }
    
    // Single date session selection
    singleSessionBoxes.forEach(box => {
        box.addEventListener('click', async () => {
            if (box.classList.contains('unavailable')) {
                alert('This session is already booked. Please choose another session.');
                return;
            }
            
            const session = box.getAttribute('data-session');
            
            // Remove selection from all
            singleSessionBoxes.forEach(b => {
                b.classList.remove('selected', 'border-blue-500', 'bg-blue-100');
                b.classList.add('border-green-500', 'bg-green-50');
            });
            
            // Select this box
            box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
            box.classList.remove('border-green-500', 'bg-green-50');
            
            startSessionHidden.value = session;
            endSessionHidden.value = session;
            dateSessionValid = true;
            nextToTab3Btn.classList.remove('opacity-50', 'cursor-not-allowed');
            nextToTab3Btn.disabled = false;
        });
    });
    
    // Start session selection (different dates)
    startSessionBoxes.forEach(box => {
        box.addEventListener('click', async () => {
            if (box.classList.contains('unavailable')) {
                alert('This start session is already booked. Please choose another session.');
                return;
            }
            
            const session = box.getAttribute('data-session');
            
            startSessionBoxes.forEach(b => {
                b.classList.remove('selected', 'border-blue-500', 'bg-blue-100');
                b.classList.add('border-green-500', 'bg-green-50');
            });
            
            box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
            box.classList.remove('border-green-500', 'bg-green-50');
            
            startSessionSelected = true;
            startSessionHidden.value = session;
            
            if (startSessionSelected && endSessionSelected) {
                dateSessionValid = true;
                nextToTab3Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                nextToTab3Btn.disabled = false;
            }
        });
    });
    
    // End session selection (different dates)
    endSessionBoxes.forEach(box => {
        box.addEventListener('click', async () => {
            if (box.classList.contains('unavailable')) {
                alert('This end session is already booked. Please choose another session.');
                return;
            }
            
            const session = box.getAttribute('data-session');
            
            endSessionBoxes.forEach(b => {
                b.classList.remove('selected', 'border-blue-500', 'bg-blue-100');
                b.classList.add('border-green-500', 'bg-green-50');
            });
            
            box.classList.add('selected', 'border-blue-500', 'bg-blue-100');
            box.classList.remove('border-green-500', 'bg-green-50');
            
            endSessionSelected = true;
            endSessionHidden.value = session;
            
            if (startSessionSelected && endSessionSelected) {
                dateSessionValid = true;
                nextToTab3Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                nextToTab3Btn.disabled = false;
            }
        });
    });
    
    // Event listeners
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

    // Volunteer Validation
    const volunteerField = document.getElementById('event_maximum_participant');
    const nextToTab4Btn = document.getElementById('nextToTab4');

    function validateVolunteer() {
        if (volunteerField && volunteerField.value && volunteerField.value >= 1) {
            volunteerValid = true;
            if (nextToTab4Btn) {
                nextToTab4Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                nextToTab4Btn.disabled = false;
            }
        } else {
            volunteerValid = false;
            if (nextToTab4Btn) {
                nextToTab4Btn.classList.add('opacity-50', 'cursor-not-allowed');
                nextToTab4Btn.disabled = true;
            }
        }
        document.getElementById('availableSpots').textContent = volunteerField?.value || 0;
    }

    if (volunteerField) {
        volunteerField.addEventListener('input', validateVolunteer);
        validateVolunteer();
    }

    // File upload handlers
    const docInput = document.getElementById('event_document');
    const docFileName = document.getElementById('documentFileName');
    const fileNameSpan = document.getElementById('fileName');

    if (docInput) {
        docInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
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
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
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
        removeImageBtn.addEventListener('click', function() {
            pictureInput.value = '';
            picturePreview.classList.add('hidden');
        });
    }
</script>
@endpush
