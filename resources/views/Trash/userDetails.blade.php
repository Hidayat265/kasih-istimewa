@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - User Details') 

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">User Details</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Profile Information Card --}}
        <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border space-y-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            
                <img class="h-28 w-28 rounded-full object-cover"
                    src="{{ $user->user_profile_picture ?? 'https://placehold.co/200x200/CCCCCC/FFFFFF?text=' . strtoupper(substr($user->user_name, 0, 1)) }}"
                    alt="{{ $user->user_name }}'s profile picture"
                    onerror="this.onerror=null; this.src='https://placehold.co/200x200/CCCCCC/FFFFFF?text=??';">
            


            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                
                    <p class="w-40 text-sm font-medium text-gray-700">User ID:</p>
                    <p class="text-gray-900">{{ $user->user_id }}</p>
                
            </div>


            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <p class="w-40 text-sm font-medium text-gray-700">Full Name:</p>
                    <p class="text-gray-900">{{ $user->user_name }}</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <p class="w-40 text-sm font-medium text-gray-700">Email:</p>
                    <p class="text-gray-900">{{ $user->user_email }}</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <p class="w-40 text-sm font-medium text-gray-700">Phone Number:</p>
                    <p class="text-gray-900">{{ $user->user_phone_number ?? 'Not Provided' }}</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <p class="w-40 text-sm font-medium text-gray-700">Age:</p>
                <p class="text-gray-900">
                    @if ($user->user_dob)
                        @php
                            try {
                                $dob = new DateTime($user->dob);
                                $now = new DateTime();
                                $age = $now->diff($dob)->y;
                                    echo $age;
                                    } catch (Exception $e) {
                                    echo 'N/A';
                            }
                        @endphp
                    @else
                        N/A
                    @endif
                </p>
            </div>
                
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <p class="w-40 text-sm font-medium text-gray-700">Account Created:</p>
                <p class="text-gray-900">{{ $user->created_at ? $user->created_at->format('d F Y, h:i A') : 'N/A' }}</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <p class="w-40 text-sm font-medium text-gray-700">Last Updated:</p>
                <p class="text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d F Y, h:i A') : 'N/A' }}</p>
            </div>
            
        </div>

        {{-- Profile Information Card --}}
        <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border space-y-4">

            <h2 class="text-xl font-semibold text-gray-800 mb-6">Event & Activities Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">

                
                <label for="name" class="w-40 text-sm font-medium text-gray-700">Full Name</label>
                

            </div>

            <p class="text-gray-500 text-sm">No event or activity records available yet.</p>

        </div>

        {{-- <div class="lg:col-span-2 bg-bg-light p-6 rounded-lg shadow-soft border space-y-4">

            <h2 class="text-xl font-semibold text-gray-800 mb-6">Donations Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            <p class="text-gray-500 text-sm">No donation records available yet.</p>

        </div> --}}
    </div>
@endsection