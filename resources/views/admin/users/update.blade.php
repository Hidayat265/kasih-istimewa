@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - Edit User')

@section('content')
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
        <a href="{{ route('admin.users.index', $user->user_id) }}"
           class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg shadow hover:bg-secondary transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Users Details 
        </a>
    </div>

    {{-- Profile Edit Form --}}
    <div class="bg-bg-light p-6 rounded-lg shadow-soft border">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Information</h2>
        <p class="text-gray-600 mb-6">Update this user's profile information.</p>

        <form action="{{ route('admin.updateDetails.User', $user->user_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="editUserForm">
            @csrf
            @method('PATCH')

            {{-- Profile Picture - SAME FUNCTIONALITY AS ADMIN PROFILE --}}
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6 mb-5">
                <label for="Profile_Picture" class="w-40 text-sm font-medium text-gray-700">
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
                                        <x-avatar :user="$user" size="80" />
                                    </div>
                                    
                                    {{-- Preview Overlay --}}
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-full transition-all duration-200 flex items-center justify-center">
                                        <span class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">Change</span>
                                    </div>
                                </div>
                                
                                {{-- Remove Button - FIXED POSITIONING FOR MOBILE --}}
                                @if($user->user_profile_picture)
                                <button 
                                    type="button"
                                    onclick="confirmRemoveUserProfilePicture()"
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
                            <div class="flex items-center gap-1">
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
                                
                                {{-- Clear Button - Only shows when file is selected --}}
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

            {{-- Full Name --}}
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <label for="user_name" class="w-40 text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="user_name" id="user_name" value="{{ old('user_name', $user->user_name) }}" required
                       class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
            </div>

            {{-- Email --}}
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <label for="user_email" class="w-40 text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="user_email" id="user_email" value="{{ old('user_email', $user->user_email) }}" required
                       class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
            </div>

            {{-- Phone Number --}}
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <label for="user_phone_number" class="w-40 text-sm font-medium text-gray-700">Phone Number</label>
                <div class="flex-1">
                    <input type="text" name="user_phone_number" id="user_phone_number"
                           value="{{ old('user_phone_number', $user->user_phone_number) }}"
                           inputmode="numeric" pattern="[0-9]*" minlength="10" maxlength="11"
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                    <p id="phoneError" class="mt-2 text-sm text-red-600 hidden" role="alert"></p>
                </div>
            </div>

            {{-- Date of Birth --}}
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <label for="user_dob" class="w-40 text-sm font-medium text-gray-700">Date of Birth</label>
                <div class="flex-1">
                    <input type="date" name="user_dob" id="user_dob" value="{{ old('user_dob', $user->user_dob) }}"
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                    <p id="dobError" class="mt-2 text-sm text-red-600 hidden" role="alert"></p>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="inline-flex justify-center py-2 px-7 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Hidden form for profile picture removal --}}
    @if($user->user_profile_picture)
    <form id="removeUserProfilePictureForm" action="{{ route('admin.removePicture.user', $user->user_id) }}" method="POST" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function to show success message
    function showSuccessMessage(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            confirmButtonColor: '#554994',
            timer: 3000,
            timerProgressBar: true
        });
    }

    // Function to show error message
    function showErrorMessage(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message,
            confirmButtonColor: '#d33'
        });
    }

    // Show messages immediately when page loads
    @if(session('status'))
        console.log('Status message found');
        showSuccessMessage('{{ session('status') }}');
    @endif

    @if(session('success'))
        console.log('Success message found');
        showSuccessMessage('{{ session('success') }}');
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Notice',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#ff9800'
        });
    @endif

    @if(session('error'))
        showErrorMessage('{{ session('error') }}');
    @endif

    @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: '<pre style="text-align: left; white-space: pre-wrap;">' + errorMessages + '</pre>',
            confirmButtonColor: '#d33'
        });
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - SweetAlert2 initialized');
        
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
            // Validate file
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File',
                    text: 'Please select an image file (JPEG or PNG)',
                    confirmButtonColor: '#d33'
                });
                fileInput.value = '';
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be less than 2MB',
                    confirmButtonColor: '#d33'
                });
                fileInput.value = '';
                return;
            }

            // Show clear button
            if (clearButton) {
                clearButton.classList.remove('hidden');
            }
            
            // Hide remove button
            if (removeButton) {
                removeButton.style.display = 'none';
            }

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                createLargePreview(e.target.result);
            };
            reader.readAsDataURL(file);
        }

        // Create large preview overlay on avatar
        function createLargePreview(imageSrc) {
            // Remove existing large preview
            const existingLargePreview = document.getElementById('large-preview');
            if (existingLargePreview) {
                existingLargePreview.remove();
            }

            // Create new large preview
            const largePreview = document.createElement('img');
            largePreview.id = 'large-preview';
            largePreview.src = imageSrc;
            largePreview.className = 'w-20 h-20 rounded-full object-cover border-2 border-green-500 absolute top-0 left-0 z-10 shadow-lg';
            largePreview.alt = 'Preview';
            
            // Add to avatar container
            avatarContainer.appendChild(largePreview);
            
            // Dim the original avatar
            const currentAvatar = document.getElementById('current-avatar');
            if (currentAvatar) {
                currentAvatar.style.opacity = '0.4';
            }
        }

        // ===== DATE OF BIRTH VALIDATION =====
        const dobInput = document.getElementById('user_dob');
        const dobError = document.getElementById('dobError');

        // Real-time phone validation
        const phoneInput = document.getElementById('user_phone_number');
        const phoneError = document.getElementById('phoneError');

        if (phoneInput && phoneError) {
            phoneInput.addEventListener('input', () => {
                phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
                if (phoneInput.value.length < 10 || phoneInput.value.length > 11) {
                    phoneError.textContent = "Phone number must be 10-11 digits only.";
                    phoneError.classList.remove("hidden");
                } else {
                    phoneError.classList.add("hidden");
                }
            });
        }
    });

    // Remove preview function - MUST BE GLOBAL
    function removePreview() {
        const fileInput = document.getElementById('Profile_Picture');
        const largePreview = document.getElementById('large-preview');
        const currentAvatar = document.getElementById('current-avatar');
        const clearButton = document.getElementById('clear-button');
        const removeButton = document.getElementById('remove-button');
        
        // Remove large preview
        if (largePreview) {
            largePreview.remove();
        }
        
        // Hide clear button
        if (clearButton) {
            clearButton.classList.add('hidden');
        }
        
        // Reset file input
        if (fileInput) {
            fileInput.value = '';
        }
        
        // Restore original avatar
        if (currentAvatar) {
            currentAvatar.style.opacity = '1';
        }
        
        // Show remove button again
        if (removeButton) {
            removeButton.style.display = 'block';
        }
    }

    // Profile picture removal - MUST BE GLOBAL
    function confirmRemoveUserProfilePicture() {
        Swal.fire({
            title: 'Remove Profile Picture?',
            text: 'This will replace the profile picture with a default avatar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#554994',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Removing...',
                    text: 'Please wait while we remove the profile picture.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const form = document.getElementById('removeUserProfilePictureForm');
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
</style>
@endpush