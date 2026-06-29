@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - My Profile')    
@section('content')

    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
        {{-- Profile Information Card --}}
        <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Information</h2>
            <p class="text-gray-600 mb-6">Update your account's profile information.</p>
            
            <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Profile Picture --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6 mb-5">
                    <label for="profile_photo" class="w-40 text-sm font-medium text-gray-700">
                        Profile Photo
                    </label>

                    <div class="mt-1 md:mt-0 flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
                            {{-- Avatar Preview Container --}}
                            <div class="flex-shrink-0 relative flex justify-center sm:justify-start">
                                <div class="relative">
                                    <div id="avatar-container" class="relative cursor-pointer group">
                                        {{-- Current Avatar --}}
                                        <div id="current-avatar">
                                            <x-avatar :user="Auth::user()" size="80" />
                                        </div>
                                        
                                        {{-- Preview Overlay --}}
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-full transition-all duration-200 flex items-center justify-center">
                                            <span class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">Change</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Remove Button --}}
                                    @if(Auth::user()->user_profile_picture)
                                    <button 
                                        type="button"
                                        onclick="confirmRemoveProfilePicture()"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200"
                                        title="Remove profile picture"
                                        id="remove-button"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            {{-- File Input Section --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    {{-- File Input --}}
                                    <input 
                                        type="file" 
                                        name="user_profile_picture" 
                                        id="Profile_Picture"
                                        accept="image/png, image/jpeg, image/jpg"
                                        class="flex-1 text-sm text-gray-700 cursor-pointer
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0 file:text-sm file:font-semibold
                                            file:bg-primary/10 file:text-primary hover:file:bg-primary/20
                                            focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50
                                            min-w-0"
                                    />
                                    
                                    {{-- Clear Button --}}
                                    <button 
                                        type="button" 
                                        id="clear-button"
                                        onclick="removePreview()"
                                        class="hidden items-center px-2 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors duration-200 shadow-sm"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <p class="text-xs text-gray-500 mt-2">
                                    Click avatar or choose file • PNG, JPG • Max 2MB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Full Name with Live Capitalization --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="name" class="w-40 text-sm font-medium text-gray-700">Full Name</label>
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="user_name" 
                            id="name" 
                            value="{{ old('user_name', Auth::user()->user_name ?? '') }}" 
                            required 
                            class="mt-1 md:mt-0 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm"
                            oninput="capitalizeName(this)"
                        >
                        <p class="text-xs text-gray-400 mt-1">Auto-capitalizes each word (e.g., john doe → John Doe)</p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="email" class="w-40 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="user_email" id="email" value="{{ old('user_email', Auth::user()->user_email ?? '') }}" required
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                {{-- Phone Number --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="phone_number" class="w-40 text-sm font-medium text-gray-700">Phone Number</label> 
                    <div class="flex-1">
                        <input type="text" name="user_phone_number" id="phone_number"
                            value="{{ old('user_phone_number', Auth::user()->user_phone_number ?? '') }}" 
                            inputmode="numeric" pattern="[0-9]*" minlength="10" maxlength="11"
                            class="mt-1 md:mt-0 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                        <p id="phoneError" class="mt-2 text-sm text-red-600 hidden" role="alert"></p>
                    </div>
                </div>

                {{-- Date of Birth --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="dob" class="w-40 text-sm font-medium text-gray-700">Date of Birth</label>
                    <div class="flex-1">
                        <input type="date" name="user_dob" id="dob" value="{{ old('user_dob', Auth::user()->user_dob ?? '') }}"
                            class="mt-1 md:mt-0 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                        <p id="dobError" class="mt-2 text-sm text-red-600 hidden" role="alert"></p>
                    </div>
                </div>   

                <div class="flex justify-end mt-6">
                    <button type="submit" class="save-profile-btn inline-flex justify-center py-2 px-7 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password Card -->
        <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Password</h2>
            <p class="text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

            <form id="passwordForm" action="{{ route('admin.password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="current_password" class="w-40 text-sm font-medium text-gray-700">Current Password</label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password" 
                        required 
                        autocomplete="current-password"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="password" class="w-40 text-sm font-medium text-gray-700">New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        autocomplete="new-password"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="password_confirmation" class="w-40 text-sm font-medium text-gray-700">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                <div class="flex justify-end mt-4">
                    <button 
                        type="submit" 
                        class="update-password-btn inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Deactivate Account Card --}}
        <div class="lg:col-span-2 bg-red-100 p-6 rounded-lg shadow-soft border border-red-300">
            <h2 class="text-xl font-semibold text-red-700 mb-4">Deactivate Account</h2>
            <p class="text-gray-600 mb-6">
                Once your account is deactivated, you will not be able to access your account. 
                You can reactivate your account by contacting support.
            </p>
            
            <form id="deactivateForm" action="{{ route('admin.profile.destroy') }}" method="POST" class="space-y-6">
                @csrf
                @method('DELETE')

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="current_password_deactivate" class="w-40 text-sm font-medium text-gray-700">
                        Current Password
                    </label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password_deactivate" 
                        required
                        placeholder="Enter your current password to confirm"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        id="deactivateAccountBtn"
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        Deactivate Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden form for removal --}}
    @if(Auth::user()->user_profile_picture)
    <form id="removeProfilePictureForm" action="{{ route('admin.profile.remove-picture') }}" method="POST" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== LIVE CAPITALIZATION FUNCTION =====
    function capitalizeName(input) {
        if (!input || !input.value) return;
        
        // Split by spaces and capitalize each part
        let value = input.value;
        let parts = value.split(' ');
        let capitalizedParts = parts.map(function(part) {
            if (part.length === 0) return part;
            
            // Handle hyphenated names (e.g., "Jane-Doe")
            if (part.includes('-')) {
                let hyphenParts = part.split('-');
                let capitalizedHyphenParts = hyphenParts.map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                return capitalizedHyphenParts.join('-');
            }
            
            // Handle apostrophe names (e.g., "O'Brien")
            if (part.includes("'")) {
                let apostropheParts = part.split("'");
                let capitalizedApostropheParts = apostropheParts.map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                return capitalizedApostropheParts.join("'");
            }
            
            // Regular name
            return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
        });
        
        let capitalized = capitalizedParts.join(' ');
        
        // Only update if changed to avoid cursor jumping
        if (input.value !== capitalized) {
            const cursorPosition = input.selectionStart;
            input.value = capitalized;
            // Restore cursor position
            input.setSelectionRange(cursorPosition, cursorPosition);
        }
    }

    // Make capitalizeName globally accessible for oninput
    window.capitalizeName = capitalizeName;

    // ===== LIVE PREVIEW FUNCTIONALITY =====
    const fileInput = document.getElementById('Profile_Picture');
    const avatarContainer = document.getElementById('avatar-container');
    const clearButton = document.getElementById('clear-button');
    const removeButton = document.getElementById('remove-button');

    // File input change event
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                handleFileSelection(file);
            } else {
                removePreview();
            }
        });
    }

    // Click avatar to trigger file input
    if (avatarContainer) {
        avatarContainer.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // Handle file selection
    function handleFileSelection(file) {
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: 'Please select an image file (JPEG or PNG)',
                confirmButtonColor: '#554994'
            });
            fileInput.value = '';
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'File size must be less than 2MB',
                confirmButtonColor: '#554994'
            });
            fileInput.value = '';
            return;
        }

        clearButton.classList.remove('hidden');
        if (removeButton) {
            removeButton.style.display = 'none';
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            createLargePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    function createLargePreview(imageSrc) {
        const existingLargePreview = document.getElementById('large-preview');
        if (existingLargePreview) existingLargePreview.remove();

        const largePreview = document.createElement('img');
        largePreview.id = 'large-preview';
        largePreview.src = imageSrc;
        largePreview.className = 'w-20 h-20 rounded-full object-cover border-2 border-green-500 absolute top-0 left-0 z-10 shadow-lg';
        
        avatarContainer.appendChild(largePreview);
        const currentAvatar = document.getElementById('current-avatar');
        if (currentAvatar) currentAvatar.style.opacity = '0.4';
    }

    window.removePreview = function() {
        const largePreview = document.getElementById('large-preview');
        const currentAvatar = document.getElementById('current-avatar');
        if (largePreview) largePreview.remove();
        if (clearButton) clearButton.classList.add('hidden');
        if (fileInput) fileInput.value = '';
        if (currentAvatar) currentAvatar.style.opacity = '1';
        if (removeButton) removeButton.style.display = 'block';
    }

    // ===== DATE OF BIRTH VALIDATION =====
    const dobInput = document.getElementById('dob');
    const dobError = document.getElementById('dobError');
    const profileForm = document.getElementById('profileForm');

    if (dobInput && profileForm) {
        const today = new Date();
        const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        const maxRecommendedAgeDate = new Date(today.getFullYear() - 65, today.getMonth(), today.getDate());

        profileForm.addEventListener('submit', function (e) {
            const dobValue = dobInput.value;
            if (!dobValue) {
                e.preventDefault();
                dobError.textContent = "Please enter your date of birth.";
                dobError.classList.remove("hidden");
                Swal.fire({
                    icon: 'error',
                    title: 'Date of Birth Required',
                    text: 'Please enter your date of birth.',
                    confirmButtonColor: '#554994'
                });
                return;
            }

            const userDob = new Date(dobValue);

            if (userDob > minAgeDate) {
                e.preventDefault();
                dobError.textContent = "You must be at least 18 years old.";
                dobError.classList.remove("hidden");
                dobInput.classList.add("border-red-500");
                Swal.fire({
                    icon: 'error',
                    title: 'Age Restriction',
                    text: 'You must be at least 18 years old.',
                    confirmButtonColor: '#554994'
                });
                return;
            }

            dobError.classList.add("hidden");
            dobInput.classList.remove("border-red-500");

            if (userDob < maxRecommendedAgeDate) {
                Swal.fire({
                    icon: 'info',
                    title: 'Note',
                    text: 'Volunteers above 65 are welcome, but some roles may be limited for safety reasons.',
                    confirmButtonColor: '#554994'
                });
            }
        });
    }

    // ===== PHONE NUMBER VALIDATION =====
    const phoneInput = document.getElementById('phone_number');
    const phoneError = document.getElementById('phoneError');

    if (phoneInput && phoneError) {
        phoneInput.addEventListener('input', function() {
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
            
            if (phoneInput.value.length > 0 && (phoneInput.value.length < 10 || phoneInput.value.length > 11)) {
                phoneError.textContent = "Phone number must be 10-11 digits only.";
                phoneError.classList.remove("hidden");
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Phone Number',
                    text: 'Phone number must be 10-11 digits only.',
                    confirmButtonColor: '#554994',
                    timer: 2000
                });
            } else {
                phoneError.classList.add("hidden");
            }
        });
    }

    // ===== PROFILE PICTURE REMOVAL =====
    window.confirmRemoveProfilePicture = function() {
        Swal.fire({
            title: 'Remove Profile Picture?',
            text: 'This will remove your current profile picture and use the default avatar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('removeProfilePictureForm').submit();
                
                Swal.fire({
                    title: 'Removed!',
                    text: 'Your profile picture has been removed successfully.',
                    icon: 'success',
                    confirmButtonColor: '#554994',
                    timer: 2000
                });
            }
        });
    }

    // ===== PROFILE UPDATE =====
    const saveProfileBtn = document.querySelector('.save-profile-btn');
    if (saveProfileBtn && profileForm) {
        saveProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const phoneValue = phoneInput ? phoneInput.value : '';
            if (phoneValue && (phoneValue.length < 10 || phoneValue.length > 11)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter a valid phone number (10-11 digits).',
                    confirmButtonColor: '#554994'
                });
                return;
            }
            
            // Capitalize name before submit
            const nameInput = document.getElementById('name');
            if (nameInput) {
                capitalizeName(nameInput);
            }
            
            Swal.fire({
                title: 'Updating Profile...',
                text: 'Please wait while we update your information.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            profileForm.submit();
        });
    }

    // ===== PASSWORD UPDATE CONFIRMATION =====
    const passwordForm = document.getElementById('passwordForm');
    const updatePasswordBtn = document.querySelector('.update-password-btn');
    
    if (passwordForm && updatePasswordBtn) {
        updatePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (!currentPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Current Password',
                    text: 'Please enter your current password.',
                    confirmButtonColor: '#554994'
                });
                return;
            }
            
            if (!newPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing New Password',
                    text: 'Please enter your new password.',
                    confirmButtonColor: '#554994'
                });
                return;
            }
            
            if (newPassword.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Too Short',
                    text: 'Password must be at least 8 characters long.',
                    confirmButtonColor: '#554994'
                });
                return;
            }
            
            if (newPassword !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'New password and confirmation password do not match.',
                    confirmButtonColor: '#554994'
                });
                return;
            }
            
            Swal.fire({
                title: 'Update Password?',
                text: 'Are you sure you want to change your password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Updating Password...',
                        text: 'Please wait.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    passwordForm.submit();
                }
            });
        });
    }

    // ===== DEACTIVATE ACCOUNT CONFIRMATION =====
    const deactivateForm = document.getElementById('deactivateForm');
    const deactivateBtn = document.getElementById('deactivateAccountBtn');
    
    if (deactivateForm && deactivateBtn) {
        deactivateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Deactivate Account?',
                text: 'This action cannot be undone! Your account will be deactivated and you will lose access.',
                icon: 'warning',
                input: 'password',
                inputLabel: 'Enter your current password to confirm',
                inputPlaceholder: 'Your password',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate my account!',
                cancelButtonText: 'Cancel',
                preConfirm: (password) => {
                    if (!password) {
                        Swal.showValidationMessage('Please enter your password');
                        return false;
                    }
                    document.getElementById('current_password_deactivate').value = password;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deactivating Account...',
                        text: 'Please wait.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    deactivateForm.submit();
                }
            });
        });
    }

    // ===== DISPLAY SUCCESS/ERROR/WARNING MESSAGES FROM SESSION =====
    @if (session('status') || session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}',
            confirmButtonColor: '#554994',
            timer: 3000,
            showConfirmButton: true
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            timer: 3000
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            icon: 'info',
            title: 'Note',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#554994'
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
});
</script>
@endpush

@push('styles')
<style>
    /* Optional: Add a subtle animation for the capitalization */
    #name {
        transition: all 0.1s ease;
    }
</style>
@endpush