@extends('user.layouts.userLayouts')

@section('title', 'My Profile | Kasih Istimewa')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary/90 to-secondary/80 py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">My Profile</h1>
            <p class="text-base md:text-lg lg:text-xl opacity-90 max-w-2xl mx-auto px-4">
                Manage your personal information and account settings
            </p>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="py-8 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 px-6 py-8 md:py-10 border-b">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <!-- Avatar with Live Preview -->
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

                        <!-- User Info -->
                        <div class="text-center sm:text-left flex-1">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ Auth::user()->user_name }}</h2>
                            <p class="text-sm md:text-base text-gray-600">{{ Auth::user()->user_email }}</p>
                            <p class="text-xs text-gray-500 mt-1">User ID: {{ Auth::user()->user_id }}</p>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                                @if(Auth::user()->is_admin)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                                        <i class="fas fa-user-shield mr-1"></i> Admin
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Form -->
                <form id="profileForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')

                    {{-- Profile Picture Upload --}}
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-6 mb-5">
                        <label for="Profile_Picture" class="w-40 text-sm font-medium text-gray-700">
                            Profile Photo
                        </label>

                        <div class="mt-1 md:mt-0 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User ID (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                User ID
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                                {{ Auth::user()->user_id }}
                            </div>
                        </div>

                        <!-- Email (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="user_email" id="user_email" 
                                   value="{{ old('user_email', Auth::user()->user_email) }}" 
                                   class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed"
                                   readonly>
                            <p class="text-xs text-gray-400 mt-1">Email cannot be changed</p>
                            @error('user_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="user_name" id="user_name" 
                                   value="{{ old('user_name', Auth::user()->user_name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                   required
                                   oninput="capitalizeName(this)">
                            @error('user_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="user_phone_number" id="user_phone_number" 
                                   value="{{ old('user_phone_number', Auth::user()->user_phone_number) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                   placeholder="0123456789"
                                   minlength="10" maxlength="11"
                                   required
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <p class="text-xs text-gray-400 mt-1">Must be 10-11 digits</p>
                            @error('user_phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Birth
                            </label>
                            <input type="date" name="user_dob" id="user_dob" 
                                   value="{{ old('user_dob', Auth::user()->user_dob) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            @error('user_dob')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Status (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Account Status
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                                <span class="inline-flex items-center">
                                    <span class="w-2 h-2 rounded-full {{ Auth::user()->user_status === 'active' ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                                    {{ ucfirst(Auth::user()->user_status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Member Since (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Member Since
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                                {{ Auth::user()->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden mt-6">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-key text-primary mr-2"></i> Change Password
                    </h3>
                </div>
                <div class="p-6 md:p-8">
                    <form id="passwordForm" action="{{ route('user.password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="current_password" id="current_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                       placeholder="Enter current password" required>
                            </div>
                            <div></div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="new_password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                       placeholder="Enter new password" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                       placeholder="Confirm new password" required>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-medium text-gray-700 mb-2">Password requirements:</p>
                            <ul class="space-y-1 text-xs text-gray-600">
                                <li id="lengthCheck" class="flex items-center gap-2">
                                    <i class="fas fa-circle text-gray-300 text-[8px]"></i>
                                    <span>At least 8 characters</span>
                                </li>
                                <li id="caseCheck" class="flex items-center gap-2">
                                    <i class="fas fa-circle text-gray-300 text-[8px]"></i>
                                    <span>Contains BOTH uppercase & lowercase letters</span>
                                </li>
                                <li id="numberCheck" class="flex items-center gap-2">
                                    <i class="fas fa-circle text-gray-300 text-[8px]"></i>
                                    <span>Contains at least 1 number</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition font-medium">
                                <i class="fas fa-key mr-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Remove Profile Picture Form -->
<form id="removeProfilePictureForm" action="{{ route('user.profile.remove-picture') }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    // ============================================
    // CAPITALIZE NAME FUNCTION
    // ============================================
    function capitalizeName(input) {
        if (!input || !input.value) return;
        
        let value = input.value;
        let parts = value.split(' ');
        let capitalizedParts = parts.map(function(part) {
            if (part.length === 0) return part;
            
            if (part.includes('-')) {
                let hyphenParts = part.split('-');
                let capitalizedHyphenParts = hyphenParts.map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                return capitalizedHyphenParts.join('-');
            }
            
            if (part.includes("'")) {
                let apostropheParts = part.split("'");
                let capitalizedApostropheParts = apostropheParts.map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                return capitalizedApostropheParts.join("'");
            }
            
            return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
        });
        
        let capitalized = capitalizedParts.join(' ');
        
        if (input.value !== capitalized) {
            const cursorPosition = input.selectionStart;
            input.value = capitalized;
            input.setSelectionRange(cursorPosition, cursorPosition);
        }
    }

    // ============================================
    // PHONE NUMBER VALIDATION
    // ============================================
    document.getElementById('user_phone_number')?.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 0 && (this.value.length < 10 || this.value.length > 11)) {
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else if (this.value.length >= 10 && this.value.length <= 11) {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        } else {
            this.classList.remove('border-red-500', 'border-green-500');
        }
    });

    // ============================================
    // LIVE PREVIEW FUNCTIONALITY
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('Profile_Picture');
        const avatarContainer = document.getElementById('avatar-container');
        const clearButton = document.getElementById('clear-button');
        const removeButton = document.getElementById('remove-button');

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
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

                    if (clearButton) clearButton.classList.remove('hidden');
                    if (removeButton) removeButton.style.display = 'none';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        createLargePreview(e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    removePreview();
                }
            });
        }

        if (avatarContainer) {
            avatarContainer.addEventListener('click', function() {
                fileInput.click();
            });
        }

        function createLargePreview(imageSrc) {
            const existingLargePreview = document.getElementById('large-preview');
            if (existingLargePreview) existingLargePreview.remove();

            const largePreview = document.createElement('img');
            largePreview.id = 'large-preview';
            largePreview.src = imageSrc;
            largePreview.className = 'w-20 h-20 rounded-full object-cover border-2 border-green-500 absolute top-0 left-0 z-10 shadow-lg';
            largePreview.alt = 'Preview';
            
            avatarContainer.appendChild(largePreview);
            
            const currentAvatar = document.getElementById('current-avatar');
            if (currentAvatar) currentAvatar.style.opacity = '0.4';
        }
    });

    function removePreview() {
        const fileInput = document.getElementById('Profile_Picture');
        const largePreview = document.getElementById('large-preview');
        const currentAvatar = document.getElementById('current-avatar');
        const clearButton = document.getElementById('clear-button');
        const removeButton = document.getElementById('remove-button');
        
        if (largePreview) largePreview.remove();
        if (clearButton) clearButton.classList.add('hidden');
        if (fileInput) fileInput.value = '';
        if (currentAvatar) currentAvatar.style.opacity = '1';
        if (removeButton) removeButton.style.display = 'block';
    }

    // ============================================
    // REMOVE PROFILE PICTURE
    // ============================================
    function confirmRemoveProfilePicture() {
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
                Swal.fire({
                    title: 'Removing...',
                    text: 'Please wait while we remove the profile picture.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                document.getElementById('removeProfilePictureForm').submit();
            }
        });
    }

    // ============================================
    // PASSWORD STRENGTH CHECKER
    // ============================================
    const newPassword = document.getElementById('new_password');
    
    function checkPasswordStrength(password) {
        const checks = {
            length: password.length >= 8,
            hasUppercase: /[A-Z]/.test(password),
            hasLowercase: /[a-z]/.test(password),
            hasNumber: /\d/.test(password)
        };
        
        const lengthCheck = document.getElementById('lengthCheck');
        if (lengthCheck) {
            if (checks.length) {
                lengthCheck.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xs"></i><span class="text-green-700 ml-2">At least 8 characters ✓</span>';
            } else {
                lengthCheck.innerHTML = '<i class="fas fa-circle text-gray-300 text-[8px]"></i><span class="text-gray-600 ml-2">At least 8 characters</span>';
            }
        }
        
        const caseCheck = document.getElementById('caseCheck');
        if (caseCheck) {
            if (checks.hasUppercase && checks.hasLowercase) {
                caseCheck.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xs"></i><span class="text-green-700 ml-2">Contains BOTH uppercase & lowercase letters ✓</span>';
            } else if (checks.hasUppercase || checks.hasLowercase) {
                caseCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 text-xs"></i><span class="text-yellow-700 ml-2">Must contain BOTH uppercase AND lowercase letters</span>';
            } else {
                caseCheck.innerHTML = '<i class="fas fa-circle text-gray-300 text-[8px]"></i><span class="text-gray-600 ml-2">Contains BOTH uppercase & lowercase letters</span>';
            }
        }
        
        const numberCheck = document.getElementById('numberCheck');
        if (numberCheck) {
            if (checks.hasNumber) {
                numberCheck.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xs"></i><span class="text-green-700 ml-2">Contains at least 1 number ✓</span>';
            } else {
                numberCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 text-xs"></i><span class="text-yellow-700 ml-2">Must contain at least 1 number</span>';
            }
        }
    }

    if (newPassword) {
        newPassword.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }

    // ============================================
    // PASSWORD FORM SUBMISSION
    // ============================================
    document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
        const password = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'New password and confirmation do not match.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Too Short',
                text: 'Password must be at least 8 characters long.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (!hasUppercase || !hasLowercase) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Password Format',
                text: 'Password must contain BOTH uppercase and lowercase letters.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        if (!hasNumber) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Password Format',
                text: 'Password must contain at least 1 number.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    });

    // ============================================
    // PROFILE FORM SUBMISSION VALIDATION
    // ============================================
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('user_phone_number');
        const phoneValue = phoneInput.value.replace(/[^0-9]/g, '');
        
        if (phoneValue.length < 10 || phoneValue.length > 11) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Phone Number',
                text: 'Phone number must be between 10-11 digits.',
                confirmButtonColor: '#d33'
            });
            phoneInput.focus();
            return;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
    });

    // ============================================
    // FLASH MESSAGES - FIXED TO SHOW SUCCESS
    // ============================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#554994',
            timer: 3000
        });
    @endif

    @if(session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}',
            confirmButtonColor: '#554994',
            timer: 3000
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

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Note',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#f59e0b'
        });
    @endif

    @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: errorMessages,
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    
    input:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    
    input:focus {
        outline: none;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endpush
@endsection