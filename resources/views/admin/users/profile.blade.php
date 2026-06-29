@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - User Details')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">User Details</h1>

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
                    <x-avatar :user="$user" size="128" />
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            <i class="fas fa-user mr-1.5"></i>
                            User
                        </span>
                    </p>

                    <p class="font-medium text-gray-700">Account Created:</p>
                    <p class="text-gray-900">{{ $user->created_at ? $user->created_at->format('d F Y, h:i A') : 'N/A' }}</p>

                    <p class="font-medium text-gray-700">Last Updated:</p>
                    <p class="text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d F Y, h:i A') : 'N/A' }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 pt-4 border-t border-gray-300">
                <div class="flex flex-wrap gap-3 justify-start sm:justify-end">
                    <!-- Edit User -->
                    <a href="{{ route('admin.users.update', $user->user_id) }}"
                       class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-4 py-2.5 text-sm transition-colors shadow-sm">
                        <i class="fas fa-edit mr-2"></i>
                        Edit User
                    </a>

                    <!-- Change Password -->
                    <button type="button" 
                            onclick="openChangePasswordModal()" 
                            class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg px-4 py-2.5 text-sm transition-colors shadow-sm">
                        <i class="fas fa-key mr-2"></i>
                        Change Password
                    </button>

                    <!-- Deactivate User -->
                    @if($user->isActive())
                        <button onclick="openDeactivateModal()" 
                                class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg px-4 py-2.5 text-sm transition-colors shadow-sm">
                            <i class="fas fa-user-slash mr-2"></i>
                            Deactivate User
                        </button>
                    @else
                        <button onclick="activateUser('{{ $user->user_id }}')" 
                                class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg px-4 py-2.5 text-sm transition-colors shadow-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            Activate User
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Event & Activity Information --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Event & Activity Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            <div class="flex items-center gap-6 mb-4">
                <div>
                    <p class="text-sm text-gray-500">Events Created</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $eventCount ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Events Participated</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $participantCount ?? 0 }}</p>
                </div>
            </div>

            <div id="events-container">
                @include('admin.users.partials.events-table', ['events' => $events])
            </div>
        </div>

        {{-- Donation Information --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-soft border">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Donation Information</h2>
            <hr class="border-t border-gray-300 mb-6">

            <div class="flex items-center gap-6 mb-4">
                <div>
                    <p class="text-sm text-gray-500">Total Donations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $donationCount ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-2xl font-bold text-green-600">RM {{ number_format($donationAmount ?? 0, 2) }}</p>
                </div>
            </div>

            <div id="donations-container">
                @include('admin.users.partials.donations-table', ['donations' => $donations])
            </div>
        </div>
    </div>

    <!-- 🔐 Change Password Modal -->
    <div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl relative transform transition-all duration-300 scale-95">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-amber-100 rounded-lg">
                            <i class="fas fa-key text-amber-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Change User Password</h3>
                            <p class="text-sm text-gray-500 mt-1">Update password for {{ $user->user_name }}</p>
                        </div>
                    </div>
                    <button onclick="closeChangePasswordModal()" 
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.updatePassword.user', $user->user_id) }}" id="changePasswordForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="new_password" id="new_password" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors placeholder-gray-400"
                                placeholder="Enter new password">
                            <button type="button" onclick="togglePassword('new_password')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Minimum 8 characters with letters and numbers</p>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors placeholder-gray-400"
                                placeholder="Confirm new password">
                            <button type="button" onclick="togglePassword('new_password_confirmation')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Your Admin Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="admin_password" id="admin_password" required
                                placeholder="Enter your admin password to confirm"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors placeholder-gray-400">
                            <button type="button" onclick="togglePassword('admin_password')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeChangePasswordModal()"
                                class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 shadow-sm">
                            <i class="fas fa-save mr-2"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 🔴 Deactivate Modal -->
    <div id="deactivateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl relative transform transition-all duration-300 scale-95">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <i class="fas fa-user-slash text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Deactivate User</h3>
                            <p class="text-sm text-gray-500 mt-1">Deactivate {{ $user->user_name }}'s account</p>
                        </div>
                    </div>
                    <button onclick="closeDeactivateModal()" 
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800">Warning: This action will deactivate the user</p>
                            <p class="text-sm text-red-600 mt-1">You are about to deactivate: <strong>{{ $user->user_name }}</strong> ({{ $user->user_email }})</p>
                        </div>
                    </div>
                </div>

                <form id="deactivateForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="deactivate_admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your admin password to confirm <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="admin_password" id="deactivate_admin_password" required
                                placeholder="Your admin password"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors placeholder-gray-400">
                            <button type="button" onclick="togglePassword('deactivate_admin_password')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeDeactivateModal()"
                                class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button type="button" onclick="confirmDeactivate('{{ $user->user_id }}')"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm">
                            <i class="fas fa-user-slash mr-2"></i>
                            Deactivate User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ============================================
    // BACK BUTTON
    // ============================================
    function goBack() {
        if (document.referrer && document.referrer.length > 0) {
            window.location.href = document.referrer;
        } else {
            window.location.href = '{{ route('admin.users.index') }}';
        }
    }

    // ============================================
    // TOGGLE PASSWORD VISIBILITY
    // ============================================
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // ============================================
    // CHANGE PASSWORD MODAL
    // ============================================
    function openChangePasswordModal() {
        const modal = document.getElementById('changePasswordModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        document.getElementById('new_password').focus();
    }

    function closeChangePasswordModal() {
        const modal = document.getElementById('changePasswordModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        document.getElementById('changePasswordForm').reset();
    }

    document.getElementById('changePasswordModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeChangePasswordModal();
        }
    });

    // ============================================
    // DEACTIVATE MODAL
    // ============================================
    function openDeactivateModal() {
        const modal = document.getElementById('deactivateModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        document.getElementById('deactivate_admin_password').focus();
    }

    function closeDeactivateModal() {
        const modal = document.getElementById('deactivateModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        document.getElementById('deactivateForm').reset();
    }

    document.getElementById('deactivateModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeactivateModal();
        }
    });

    // ============================================
    // CONFIRM DEACTIVATE
    // ============================================
    function confirmDeactivate(userId) {
        const password = document.getElementById('deactivate_admin_password').value;
        
        if (!password) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please enter your admin password.',
                confirmButtonColor: '#d33'
            });
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
        
        Swal.fire({
            title: 'Deactivate User?',
            text: 'Are you sure you want to deactivate this user account?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, deactivate it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we deactivate the user.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/users/${userId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ 
                        status: 'deactivated',
                        admin_password: password 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'User deactivated successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to deactivate user.'
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

    // ============================================
    // ACTIVATE USER
    // ============================================
    function activateUser(userId) {
        Swal.fire({
            title: 'Activate User?',
            text: 'Are you sure you want to activate this user account?',
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
                    text: 'Please wait while we activate the user.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/users/${userId}/toggle-status`, {
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
                            text: data.message || 'User activated successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to activate user.'
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

    // ============================================
    // EVENTS PAGINATION (AJAX)
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        // Events Pagination
        document.getElementById('events-container')?.addEventListener('click', function(e) {
            const link = e.target.closest('.events-pagination-link');
            if (link) {
                e.preventDefault();
                const page = link.getAttribute('data-page');
                if (page) {
                    fetchEvents(page);
                }
            }
        });

        // Donations Pagination
        document.getElementById('donations-container')?.addEventListener('click', function(e) {
            const link = e.target.closest('.donations-pagination-link');
            if (link) {
                e.preventDefault();
                const page = link.getAttribute('data-page');
                if (page) {
                    fetchDonations(page);
                }
            }
        });
    });

    function fetchEvents(page) {
        const container = document.getElementById('events-container');
        if (!container) return;
        
        container.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-primary"></i> Loading events...</div>';
        
        const url = `{{ route('admin.users.profile', $user->user_id) }}?events_page=${page}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading events:', error);
            container.innerHTML = '<div class="text-center py-4 text-red-500">Failed to load events.</div>';
        });
    }

    function fetchDonations(page) {
        const container = document.getElementById('donations-container');
        if (!container) return;
        
        container.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-primary"></i> Loading donations...</div>';
        
        const url = `{{ route('admin.users.profile', $user->user_id) }}?donations_page=${page}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading donations:', error);
            container.innerHTML = '<div class="text-center py-4 text-red-500">Failed to load donations.</div>';
        });
    }

    // ============================================
    // SESSION MESSAGES
    // ============================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#554994',
            timer: 3000,
            showConfirmButton: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            timer: 3000
        });
    @endif
</script>
@endpush

@push('styles')
<style>
    .shadow-soft {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    .events-pagination-link, .donations-pagination-link {
        cursor: pointer;
    }
</style>
@endpush