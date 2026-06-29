@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - Dashboard')
@section('content')
<main>
    <!-- Event Form Section -->
    <section class="py-12 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-lg border-t-4 border-primary">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">Plan a New Event</h2>
                    <p class="mt-3 text-lg text-gray-600">Let's create a memorable experience for our community.</p>
                </div>

                <!-- For now, this form just previews UI only -->
                <form 
                
                class="mt-10 space-y-8">
                        

                    <!-- Event Details Section -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Event Details</h3>

                        <!-- Event Organiser -->
                        {{-- <%-- TODO: Replace with logged-in user's actual name later --%> --}}
                        <div>
                            <label for="event_organiser" class="block text-sm font-medium text-gray-600">Event Organizer</label>
                            <p class="block w-full px-4 py-3 mt-1 text-gray-500 bg-gray-100 border border-gray-200 rounded-lg">
                                Muhammad Hidayat (Sample User)
                            </p>
                        </div>

                        <!-- Organiser Email -->
                        {{-- <%-- TODO: Replace with logged-in user's email later --%> --}}
                        <div>
                            <label for="organiser_email" class="block text-sm font-medium text-gray-600">Organizer Email</label>
                            <p class="block w-full px-4 py-3 mt-1 text-gray-500 bg-gray-100 border border-gray-200 rounded-lg">
                                muhammadhidayat703@gmail.com
                            </p>
                        </div>

                        <!-- Event Name -->
                        <div>
                            <label for="event_name" class="block text-sm font-medium text-gray-600">Event Name</label>
                            <input type="text" name="event_name" id="event_name"
                                class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                required placeholder="e.g., Annual Charity Gala">
                        </div>

                        <!-- Organiser Company Name -->
                        <div>
                            <label for="event_companyName" class="block text-sm font-medium text-gray-600">Organizer Company Name</label>
                            <input type="text" name="event_companyName" id="event_companyName"
                                class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                required placeholder="e.g., Good Deeds Organization">
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-600">Start Date</label>
                                <input type="date" name="start_date" id="start_date"
                                    class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                    required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-600">End Date</label>
                                <input type="date" name="end_date" id="end_date"
                                    class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                    required>
                            </div>
                        </div>

                        <!-- Times -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-600">Start Time</label>
                                <input type="time" name="start_time" id="start_time"
                                    class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                    required>
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-600">End Time</label>
                                <input type="time" name="end_time" id="end_time"
                                    class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Volunteer Section -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Volunteer Information</h3>

                        <fieldset class="space-y-2">
                            <legend class="block text-sm font-medium text-gray-600">Do you need volunteers?</legend>
                            <div class="flex items-center gap-x-6 pt-2">
                                <div class="flex items-center">
                                    <input id="volunteers_yes" name="needs_volunteers" type="radio" value="yes"
                                        class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="volunteers_yes" class="ml-2 block text-sm text-gray-900">Yes</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="volunteers_no" name="needs_volunteers" type="radio" value="no"
                                        class="h-4 w-4 border-gray-300 text-primary focus:ring-primary" checked>
                                    <label for="volunteers_no" class="ml-2 block text-sm text-gray-900">No</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Number of Volunteers -->
                        <div id="volunteer_count_container" class="hidden">
                            <label for="volunteer_count" class="block text-sm font-medium text-gray-600">How many volunteers are needed?</label>
                            <input type="number" name="volunteer_count" id="volunteer_count"
                                class="block w-full px-4 py-3 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                                min="1" placeholder="e.g., 10">
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Attachments</h3>

                        <!-- Supporting Document -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Supporting Document</label>
                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-300 px-6 py-10 hover:border-primary transition">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                        <label class="relative cursor-pointer rounded-md bg-white font-semibold text-secondary hover:text-blue-700">
                                            <span>Upload a file (disabled)</span>
                                            <input id="document" name="document" type="file" class="sr-only" accept=".pdf" disabled>
                                        </label>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">PDF up to 10MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Event Image</label>
                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-300 px-6 py-10 hover:border-primary transition">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                        <label class="relative cursor-pointer rounded-md bg-white font-semibold text-secondary hover:text-blue-700">
                                            <span>Upload an image (disabled)</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*" disabled>
                                        </label>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-x-2 py-3 px-4 border border-transparent rounded-full shadow-md text-lg font-medium text-white bg-primary hover:bg-red-600 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Volunteer Count Toggle ---
        const volunteerRadios = document.querySelectorAll('input[name="needs_volunteers"]');
        const volunteerCountContainer = document.getElementById('volunteer_count_container');
        const volunteerCountInput = document.getElementById('volunteer_count');

        volunteerRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if (e.target.value === 'yes') {
                    volunteerCountContainer.classList.remove('hidden');
                    volunteerCountInput.setAttribute('required', 'required');
                } else {
                    volunteerCountContainer.classList.add('hidden');
                    volunteerCountInput.removeAttribute('required');
                    volunteerCountInput.value = ''; // Clear the value when hidden
                }
            });
        });

        // Initialize volunteer section state
        const initialVolunteerValue = document.querySelector('input[name="needs_volunteers"]:checked').value;
        if (initialVolunteerValue === 'yes') {
            volunteerCountContainer.classList.remove('hidden');
            volunteerCountInput.setAttribute('required', 'required');
        }

        // --- Custom File Input Display ---
        function setupFileInput(inputId, filenameId, allowedTypes, maxSizeMB = 10) {
            const fileInput = document.getElementById(inputId);
            const filenameDisplay = document.getElementById(filenameId);
            const errorDisplay = document.getElementById(`${inputId}-error`);
            
            if(fileInput) {
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    
                    if (file) {
                        // Validate file type
                        if (allowedTypes && !allowedTypes.includes(file.type)) {
                            showError(`${inputId}-error`, `Invalid file type. Please upload: ${allowedTypes.join(', ')}`);
                            fileInput.value = '';
                            filenameDisplay.textContent = '';
                            return;
                        }
                        
                        // Validate file size (convert MB to bytes)
                        const maxSizeBytes = maxSizeMB * 1024 * 1024;
                        if (file.size > maxSizeBytes) {
                            showError(`${inputId}-error`, `File too large. Maximum size is ${maxSizeMB}MB`);
                            fileInput.value = '';
                            filenameDisplay.textContent = '';
                            return;
                        }
                        
                        // Clear any previous errors
                        clearError(`${inputId}-error`);
                        filenameDisplay.textContent = file.name;
                    } else {
                        filenameDisplay.textContent = '';
                        clearError(`${inputId}-error`);
                    }
                });
            }
        }

        // Initialize file inputs with validation
        setupFileInput('document', 'document-filename', ['application/pdf']);
        setupFileInput('image', 'image-filename', ['image/jpeg', 'image/png', 'image/gif']);

        // --- Date and Time Validation ---
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        // Set minimum start date (7 days from today)
        if(startDateInput) {
            const today = new Date();
            today.setDate(today.getDate() + 7);
            const minDate = today.toISOString().split('T')[0];
            startDateInput.setAttribute('min', minDate);
            
            // Update end date min when start date changes
            startDateInput.addEventListener('change', () => {
                if (endDateInput) {
                    endDateInput.setAttribute('min', startDateInput.value);
                    
                    // If end date is before new start date, clear it
                    if (endDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = '';
                    }
                }
            });
        }

        // Validate end date and time
        if (endDateInput && startDateInput && endTimeInput && startTimeInput) {
            endDateInput.addEventListener('change', validateDateTime);
            endTimeInput.addEventListener('change', validateDateTime);
            
            function validateDateTime() {
                if (startDateInput.value && endDateInput.value && startTimeInput.value && endTimeInput.value) {
                    const startDateTime = new Date(`${startDateInput.value}T${startTimeInput.value}`);
                    const endDateTime = new Date(`${endDateInput.value}T${endTimeInput.value}`);
                    
                    if (endDateTime <= startDateTime) {
                        showError('datetime-error', 'End date/time must be after start date/time');
                    } else {
                        clearError('datetime-error');
                    }
                }
            }
        }

        // --- Real-time Character Counter for Event Name ---
        const eventNameInput = document.getElementById('event_name');
        const charCounter = document.getElementById('char-counter');
        
        if (eventNameInput && charCounter) {
            eventNameInput.addEventListener('input', () => {
                const maxLength = eventNameInput.getAttribute('maxlength') || 100;
                const currentLength = eventNameInput.value.length;
                charCounter.textContent = `${currentLength}/${maxLength}`;
                
                // Change color when approaching limit
                if (currentLength > maxLength * 0.8) {
                    charCounter.classList.add('text-red-500');
                } else {
                    charCounter.classList.remove('text-red-500');
                }
            });
        }

        // --- Form Submission Handler ---
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!validateForm()) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = document.querySelector('.error-message');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }

        // --- Utility Functions ---
        function showError(elementId, message) {
            let errorElement = document.getElementById(elementId);
            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.id = elementId;
                errorElement.className = 'error-message text-red-500 text-sm mt-1';
                // Insert after the relevant input
                const inputElement = document.getElementById(elementId.replace('-error', ''));
                if (inputElement) {
                    inputElement.parentNode.appendChild(errorElement);
                }
            }
            errorElement.textContent = message;
        }

        function clearError(elementId) {
            const errorElement = document.getElementById(elementId);
            if (errorElement) {
                errorElement.remove();
            }
        }

        function validateForm() {
            let isValid = true;
            
            // Clear all previous errors
            document.querySelectorAll('.error-message').forEach(error => error.remove());
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    showError(`${field.id}-error`, 'This field is required');
                    isValid = false;
                }
            });
            
            // Add your custom validation logic here
            
            return isValid;
        }

        // --- Auto-format Company Name ---
        const companyNameInput = document.getElementById('event_companyName');
        if (companyNameInput) {
            companyNameInput.addEventListener('blur', () => {
                if (companyNameInput.value) {
                    // Capitalize first letter of each word
                    companyNameInput.value = companyNameInput.value
                        .toLowerCase()
                        .split(' ')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }
            });
        }
    });
</script>


