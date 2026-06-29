@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - ' . ($user->user_name ?? 'User Details'))

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Admin Details</h1>

        <button onclick="goBack()" 
                class="ml-auto md:mt-0 inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg shadow bg-primary hover:bg-primary/90 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Profile Information Card --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-soft border">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Profile Information</h2>
                
                {{-- Status Badge --}}
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $user->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <i class="fas fa-circle text-[6px] mr-1.5 {{ $user->isActive() ? 'text-green-500' : 'text-red-500' }}"></i>
                    {{ $user->isActive() ? 'Active' : 'Deactivated' }}
                </span>
            </div>
            <hr class="border-t border-gray-300 mb-6">

            {{-- Layout wrapper --}}
            <div class="flex flex-col md:flex-row md:items-start md:gap-8">

                {{-- Profile Picture --}}
                <div class="flex justify-center md:justify-start mb-6 md:mb-0">
                    <div id="current-avatar" class="rounded-full border border-gray-300 shadow">
                        <x-avatar :user="$user" size="128" />
                    </div>
                </div>

                {{-- User Info --}}
                <div class="flex-1 grid grid-cols-1 md:grid-cols-[150px_1fr] gap-y-3 gap-x-4 text-sm">
                    
                    <p class="font-medium text-gray-700">User ID:</p>
                    <p class="text-gray-900">{{ $user->user_id }}</p>

                    <p class="font-medium text-gray-700">Full Name:</p>
                    <p class="text-gray-900">{{ $user->user_name }}</p>

                    <p class="font-medium text-gray-700">Email:</p>
                    <p class="text-gray-900">{{ $user->user_email }}</p>

                    <p class="font-medium text-gray-700">Phone Number:</p>
                    <p class="text-gray-900">{{ $user->user_phone_number ?? 'Not Provided' }}</p>

                    <p class="font-medium text-gray-700">Age:</p>
                    <p class="text-gray-900">
                        @if ($user->user_dob)
                            @php
                                try {
                                    $dob = new DateTime($user->user_dob);
                                    $now = new DateTime();
                                    $age = $now->diff($dob)->y;
                                    echo $age . ' years old';
                                } catch (Exception $e) {
                                    echo 'N/A';
                                }
                            @endphp
                        @else
                            N/A
                        @endif
                    </p>

                    <p class="font-medium text-gray-700">Date of Birth:</p>
                    <p class="text-gray-900">
                        @if ($user->user_dob)
                            {{ \Carbon\Carbon::parse($user->user_dob)->format('d F Y') }}
                        @else
                            N/A
                        @endif
                    </p>

                    <p class="font-medium text-gray-700">Role:</p>
                    <p class="text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-user-shield mr-1.5"></i>
                            Administrator
                        </span>
                    </p>

                    <p class="font-medium text-gray-700">Account Created:</p>
                    <p class="text-gray-900">{{ $user->created_at ? $user->created_at->format('d F Y, h:i A') : 'N/A' }}</p>

                    <p class="font-medium text-gray-700">Last Updated:</p>
                    <p class="text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d F Y, h:i A') : 'N/A' }}</p>
                </div>
            </div>

            {{-- Admin Action Buttons --}}
            @if(auth()->user()->is_admin && auth()->user()->user_id != $user->user_id)
                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    {{-- ONLY SHOW ACTIVATE BUTTON FOR DEACTIVATED ADMINS --}}
                    @if(!$user->isActive())
                        <button onclick="activateAdmin('{{ $user->user_id }}')" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-check-circle mr-2"></i>
                            Activate Admin
                        </button>
                    @endif
                </div>
            @endif
        </div>

        {{-- Event & Activity Information --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Event & Activity Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-6">
                    <div>
                        <p class="text-sm text-gray-500">Total Events Created</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $eventCount ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Events Participated</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $participantCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            @if(isset($events) && $events->count() > 0)
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($events as $event)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $event->event_name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $event->event_start_date ? \Carbon\Carbon::parse($event->event_start_date)->format('d M Y') : 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $event->event_approval_status === 'Approved' ? 'bg-green-100 text-green-800' : 
                                               ($event->event_approval_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $event->event_approval_status ?? 'Draft' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">No event or activity records available yet.</p>
            @endif
        </div>

        {{-- Donation Information --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Donation Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-6">
                    <div>
                        <p class="text-sm text-gray-500">Total Donations</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $donationCount ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-green-600">RM {{ number_format($donationAmount ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            @if(isset($donations) && $donations->count() > 0)
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($donations as $donation)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->donation_id }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->donor_name }}</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-green-600">RM {{ number_format($donation->donation_amount, 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $donation->created_at ? $donation->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $donation->donation_status === 'success' ? 'bg-green-100 text-green-800' : 
                                               ($donation->donation_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($donation->donation_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">No donation records available yet.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ============================================
    // BACK BUTTON FUNCTION
    // ============================================
    function goBack() {
        // Check if there's a previous page in history
        if (document.referrer && document.referrer.length > 0) {
            window.location.href = document.referrer;
            window.history.back();
        } else {
            // Fallback to admin list
            window.location.href = '{{ route('admin.admins.index') }}';
        }
    }

    function activateAdmin(userId) {
        Swal.fire({
            title: 'Activate Admin?',
            text: 'Are you sure you want to activate this admin account?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, activate it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
                
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we activate the admin.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/admins/${userId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ status: 'active' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Admin activated successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            // Use replace instead of reload to keep history
                            window.location.reload();
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to activate admin.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.'
                    });
                });
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