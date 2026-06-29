@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - My Profile')    
{{-- <style>
/* Submenu styles */
    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background-color: rgba(0, 0, 0, 0.1); /* Slightly darker background for submenu */
    }
    .submenu-open {
            max-height: 500px; /* Adjust as needed, should be large enough for content */
            transition: max-height 0.4s ease-in;
    }
    .submenu a {
        padding-left: 3rem; /* Indent submenu items */
    }
    .sidebar-collapsed .submenu { /* Hide submenu when sidebar collapsed */
        display: none;
    }
    .dropdown-indicator {
        margin-left: auto;
        transition: transform 0.3s ease;
    }
    .rotate-180 {
            transform: rotate(180deg);
    }
    /* Add focus ring styles */
    input:focus, select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 2px #CB80AB; /* secondary color focus ring */
        border-color: #554994; /* primary color border */
    }
</style> --}}
@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Profile</h1>

    {{-- Success/Error Messages --}}
    @if (session('status'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        </div>
    @endif


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
        {{-- Profile Information Card --}}
        <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Information</h2>
            <p class="text-gray-600 mb-6">Update your account's profile information.</p>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH') {{-- Or PUT depending on your route definition --}}

                
                {{-- Full Name --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="name" class="w-40 text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name ?? '') }}" required 
                    class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                {{-- Email --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="email" class="w-40 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email ?? '') }}" required
                    class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                </div>

                {{-- Phone Number --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="phone_number" class="w-40 text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="flex-1">
                        <input type="text" name="phone_number" id="phone_number"
                        value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}"
                        inputmode="numeric" pattern="[0-9]*" minlength="10" maxlength="11"
                        class="mt-1 md:mt-0 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                        <p id="phoneError" class="mt-2 text-sm text-red-600 hidden" role="alert"></p>
                    </div>
                </div>

                {{-- Date of Birth --}}
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="dob" class="w-40 text-sm font-medium text-gray-700">Date of Birth</label>
                    <div class="flex-1">
                        <input type="date" name="dob" id="dob" value="{{ old('dob', Auth::user()->dob ?? '') }}"
                        class="mt-1 md:mt-0 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm">
                        <p id="dobError" class="mt-2 text-sm text-red-600 {{ $errors->has('dob') ? '' : 'hidden' }}" role="alert">
                        {{ $errors->first('dob') }}</p>
                    </div>
                </div>   

                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-7 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>

        <!-- Update Password Card -->
        <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Password</h2>
            <p class="text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

            <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-6">
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
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm"
                    >
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="password" class="w-40 text-sm font-medium text-gray-700">New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        autocomplete="new-password"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm"
                    >
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="password_confirmation" class="w-40 text-sm font-medium text-gray-700">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm"
                    >
                </div>

                <div class="flex justify-end mt-4">
                    <button 
                        type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    >
                        Update Password
                    </button>
                </div>
            </form>
        </div>

            {{-- Delete Account Card --}}
        <div class="lg:col-span-2 bg-red-100 p-6 rounded-lg shadow-soft border border-red-300">
            <h2 class="text-xl font-semibold text-red-700 mb-4">Delete Account</h2>
            <p class="text-gray-600 mb-6">
                Once your account is deleted, all of its resources and data will be permanently deleted. 
                Before deleting your account, please save any data or information that you wish to retain.
            </p>
            
            {{-- Form requiring current password --}}
            <form action="{{ route('admin.profile.destroy') }}" method="POST" class="space-y-6">
                @csrf
                @method('DELETE')

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <label for="current_password_delete" class="w-40 text-sm font-medium text-gray-700">
                        Current Password
                    </label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password_delete" 
                        required
                        placeholder="Enter your current password to confirm"
                        class="mt-1 md:mt-0 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-primary sm:text-sm"
                    >
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')"
                    >
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- <script>
// document.addEventListener('DOMContentLoaded', () => {
//     const dobInput = document.getElementById('dob');
//     const dobError = document.getElementById('dobError');
//     const form = dobInput.closest('form');

//     const today = new Date();
//     const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
//     const maxRecommendedAgeDate = new Date(today.getFullYear() - 65, today.getMonth(), today.getDate());

//     form.addEventListener('submit', function (e) {
//         const dobValue = dobInput.value;
//         if (!dobValue) {
//             e.preventDefault();
//             dobError.textContent = "Please enter your date of birth.";
//             dobError.classList.remove("hidden");
//             return;
//         }

//         const userDob = new Date(dobValue);

//         // HARD BLOCK — UNDER 18
//         if (userDob > minAgeDate) {
//             e.preventDefault();
//             dobError.textContent = "You must be at least 18 years old.";
//             dobError.classList.remove("hidden");
//             dobInput.classList.add("border-red-500");
//             return;
//         }

//         dobError.classList.add("hidden");
//         dobInput.classList.remove("border-red-500");

//         // SOFT WARNING — ABOVE 65
//         if (userDob < maxRecommendedAgeDate) {
//             alert("Note: Volunteers above 65 are welcome, but some roles may be limited.");
//         }
//     });
// });

// PHONE NUMBER VALIDATION
// const phoneInput = document.getElementById('phone_number');
// const phoneError = document.getElementById('phoneError');

// phoneInput.addEventListener('input', () => {
//     phoneInput.value = phoneInput.value.replace(/[^0-9]/g, ''); // force digits only

//     if (phoneInput.value.length < 10 || phoneInput.value.length > 11) {
//         phoneError.textContent = "Phone number must be 10-11 digits only.";
//         phoneError.classList.remove("hidden");
//     } else {
//         phoneError.classList.add("hidden");
//     }
// });
</script> --}}
