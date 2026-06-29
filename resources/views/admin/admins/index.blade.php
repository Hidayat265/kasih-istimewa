@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - List Admins')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Administrators</h1>
    </div>

    <!-- Admin Statistics Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Admins</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="totalAdminsStat">{{ $totalAdmins ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-blue-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">New Admins (This Month)</p>
                    <p class="text-xl md:text-2xl font-bold text-purple-600" id="newAdminsStat">{{ $newAdminsCount ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-purple-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Active Admins</p>
                    <p class="text-xl md:text-2xl font-bold text-green-600" id="activeAdminsStat">{{ $activeAdmins ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Deactivated Admins</p>
                    <p class="text-xl md:text-2xl font-bold text-red-600" id="deactivatedAdminsStat">{{ $deactivatedAdmins ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-slash text-red-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-bg-light p-4 rounded-lg shadow-soft border mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <!-- Search Bar -->
            <div class="relative flex-1 w-full sm:max-w-md">
                <input 
                    type="text" 
                    name="search" 
                    id="search"
                    placeholder="Search by name, email, phone, or ID..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors duration-200"
                    value="{{ $search ?? '' }}"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>

            <!-- Status Filter & Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap">
                <label class="text-xs font-medium text-gray-700 whitespace-nowrap">Status:</label>
                <select id="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm w-full sm:w-auto">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="deactivated">Deactivated</option>
                </select>
                <button onclick="generateAdminReport()" 
                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                    <i class="fas fa-chart-line mr-1"></i> Report
                </button>
                <button type="button" id="open-modal-btn"
                    class="px-3 py-2 bg-primary hover:bg-primary/90 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> Add Admin
                </button>
            </div>
        </div>
    </div>

    <div class="bg-bg-light p-4 md:p-6 rounded-lg shadow-soft border">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Administrator List</h2>
        </div>

        {{-- Loading Spinner --}}
        <div id="tableLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
            <p class="mt-2 text-gray-500">Loading...</p>
        </div>

        {{-- Admin Table - Responsive Overflow --}}
        <div id="admins-table-container" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="adminsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="no">
                            No. <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-1 md:px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Profile
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="user_id">
                            ID <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="user_name">
                            Name <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="user_email">
                            Email <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="user_phone_number">
                            Phone <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="user_dob">
                            Age <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="user_status">
                            Status <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="adminsTableBody">
                    @include('admin.admins.partials.admins-table', ['admins' => $users])
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Showing <span id="adminsShowingStart">{{ $users->firstItem() ?? 0 }}</span> 
                to <span id="adminsShowingEnd">{{ $users->lastItem() ?? 0 }}</span> 
                of <span id="adminsTotalResults">{{ $users->total() ?? 0 }}</span> admins
            </div>
            <div id="adminsPaginationLinks" class="flex justify-center space-x-2">
                @include('admin.admins.partials.pagination', ['admins' => $users])
            </div>
        </div>
    </div>

    {{-- ADD ADMIN MODAL --}}
    <div id="add-admin-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden p-4">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-soft border p-4 md:p-6 max-h-[90vh] overflow-y-auto">
            
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <i class="fas fa-user-shield text-primary text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-semibold text-gray-800">Create Admin Account</h3>
                        <p class="text-xs md:text-sm text-gray-500 mt-1">Add a new administrator to the system</p>
                    </div>
                </div>
                <button type="button" id="close-modal-btn" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form id="createAdminForm" method="POST" action="{{ route('admin.storeAdmin') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input id="user_name" name="user_name" type="text" value="{{ old('user_name') }}" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        placeholder="Enter admin's full name">
                    @error('user_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input id="user_email" name="user_email" type="email" value="{{ old('user_email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        placeholder="Enter admin's email">
                    @error('user_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input id="user_phone_number" name="user_phone_number" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="11" minlength="10"
                        value="{{ old('user_phone_number') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        placeholder="Enter phone number (10-11 digits)">
                    @error('user_phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                    <input id="user_dob" name="user_dob" type="date" value="{{ old('user_dob') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                    <p id="dobError" class="mt-1 text-sm text-red-600 hidden" role="alert"></p>
                    @error('user_dob')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input id="user_password" name="user_password" type="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        placeholder="Minimum 8 characters">
                    @error('user_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="user_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input id="user_password_confirmation" name="user_password_confirmation" type="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        placeholder="Confirm password">
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end space-y-2 space-y-reverse sm:space-y-0 sm:space-x-3 pt-2">
                    <button type="button" id="close-modal-btn-2"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitAdminBtn"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="fas fa-user-shield mr-2"></i> Create Admin Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentSortColumn = 'user_id';
    let currentSortDirection = 'desc';
    let currentPage = 1;
    let currentFilters = {
        status: '',
        search: ''
    };

    function fetchAdmins(page = 1) {
        const tableLoading = document.getElementById('tableLoading');
        const adminsTableContainer = document.getElementById('admins-table-container');
        
        if (tableLoading) tableLoading.classList.remove('hidden');
        if (adminsTableContainer) {
            adminsTableContainer.style.opacity = '0.5';
        }

        const params = new URLSearchParams();
        params.append('page', page);
        if (currentFilters.status) params.append('status', currentFilters.status);
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentSortColumn) {
            params.append('sort', currentSortColumn);
            params.append('direction', currentSortDirection);
        }

        const fetchUrl = `{{ route('admin.admins.index') }}?${params.toString()}`;

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update table body
                const tbody = document.querySelector('#adminsTable tbody');
                if (tbody) {
                    tbody.innerHTML = data.html;
                }
                
                // Update pagination links
                const paginationContainer = document.getElementById('adminsPaginationLinks');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationHandlers();
                }
                
                // Update showing info
                document.getElementById('adminsShowingStart').textContent = data.from || 0;
                document.getElementById('adminsShowingEnd').textContent = data.to || 0;
                document.getElementById('adminsTotalResults').textContent = data.total || 0;
                
                // Update statistics cards
                if (data.stats) {
                    document.getElementById('totalAdminsStat').textContent = data.stats.totalAdmins || 0;
                    document.getElementById('newAdminsStat').textContent = data.stats.newAdminsCount || 0;
                    document.getElementById('activeAdminsStat').textContent = data.stats.activeAdmins || 0;
                    document.getElementById('deactivatedAdminsStat').textContent = data.stats.deactivatedAdmins || 0;
                }
                
                // Update sort icons
                updateSortIcons(currentSortColumn);
                
                if (tableLoading) tableLoading.classList.add('hidden');
                if (adminsTableContainer) {
                    adminsTableContainer.style.opacity = '1';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (tableLoading) tableLoading.classList.add('hidden');
            if (adminsTableContainer) {
                adminsTableContainer.style.opacity = '1';
            }
        });
    }

    function updateSortIcons(activeColumn) {
        document.querySelectorAll('.sortable i').forEach(icon => {
            icon.className = 'fas fa-sort ml-1 text-gray-400';
        });
        
        const activeHeader = document.querySelector(`.sortable[data-sort="${activeColumn}"] i`);
        if (activeHeader) {
            activeHeader.className = currentSortDirection === 'asc' 
                ? 'fas fa-sort-up ml-1 text-primary' 
                : 'fas fa-sort-down ml-1 text-primary';
        }
    }

    function sortTable(column) {
        if (currentSortColumn === column) {
            currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortColumn = column;
            currentSortDirection = 'asc';
        }
        
        fetchAdmins(1);
    }

    function attachPaginationHandlers() {
        document.querySelectorAll('#adminsPaginationLinks .pagination-link').forEach(link => {
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }

    function paginationClickHandler(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) {
            fetchAdmins(parseInt(page));
        }
        return false;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Sortable headers
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => {
                const sortColumn = header.getAttribute('data-sort');
                if (sortColumn !== 'no') {
                    sortTable(sortColumn);
                }
            });
        });

        // Filter: Status
        document.getElementById('filterStatus')?.addEventListener('change', function() {
            currentFilters.status = this.value;
            fetchAdmins(1);
        });

        // Search
        const searchInput = document.getElementById('search');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                currentFilters.search = searchTerm;

                searchTimeout = setTimeout(() => {
                    fetchAdmins(1);
                }, 500);
            });
        }

        // ===== MODAL CONTROL =====
        const modal = document.getElementById('add-admin-modal');
        const openBtn = document.getElementById('open-modal-btn');
        const closeBtn = document.getElementById('close-modal-btn');
        const closeBtn2 = document.getElementById('close-modal-btn-2');
        const form = document.getElementById('createAdminForm');

        // Open modal
        if (openBtn && modal) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }

        // Close modal
        function closeModal() {
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        if (closeBtn2) {
            closeBtn2.addEventListener('click', closeModal);
        }

        // Close modal when clicking on background
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // Re-open modal if there are validation errors
        @if ($errors->any())
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        @endif

        // ===== DOB CLIENT-SIDE VALIDATION =====
        const dobInput = document.getElementById('user_dob');
        const dobError = document.getElementById('dobError');
        const today = new Date();
        const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        const maxRecommendedAgeDate = new Date(today.getFullYear() - 65, today.getMonth(), today.getDate());

        // ===== CAPITALIZE NAME - Every First Letter Capital =====
        const nameInput = document.getElementById('user_name');

        if (nameInput) {
            nameInput.addEventListener('blur', function() {
                this.value = this.value.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
            });
            
            // Also capitalize on input for real-time
            nameInput.addEventListener('input', function() {
                const cursorPosition = this.selectionStart;
                const words = this.value.split(' ');
                const capitalized = words.map(word => {
                    if (word.length > 0) {
                        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                    }
                    return word;
                }).join(' ');
                this.value = capitalized;
                this.setSelectionRange(cursorPosition, cursorPosition);
            });
        }

        // ===== PHONE NUMBER VALIDATION =====
        const phoneInput = document.getElementById('user_phone_number');
        
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // ===== FORM SUBMISSION WITH SWEETALERT =====
        if (form) {
            form.addEventListener('submit', function(e) {
                // DOB validation
                const dobValue = dobInput.value;
                if (!dobValue) {
                    e.preventDefault();
                    if (dobError) {
                        dobError.textContent = "Please enter the date of birth.";
                        dobError.classList.remove("hidden");
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Date of Birth Required',
                        text: 'Please enter the date of birth.',
                        confirmButtonColor: '#554994'
                    });
                    return;
                }

                const userDob = new Date(dobValue);
                if (userDob > minAgeDate) {
                    e.preventDefault();
                    if (dobError) {
                        dobError.textContent = "Admin must be at least 18 years old to register.";
                        dobError.classList.remove("hidden");
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Age Restriction',
                        text: 'Admin must be at least 18 years old.',
                        confirmButtonColor: '#554994'
                    });
                    return;
                }
                
                if (dobError) dobError.classList.add("hidden");
                
                // Soft warning for 65+
                if (userDob < maxRecommendedAgeDate) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Note',
                        text: 'Admin above 65 is welcome, but some roles may be limited for safety reasons.',
                        confirmButtonColor: '#554994'
                    });
                }
            });
        }

        // Initial load
        setTimeout(() => {
            attachPaginationHandlers();
            updateSortIcons('user_id');
        }, 100);
    });

    // ============================================
    // GENERATE ADMIN REPORT
    // ============================================
    window.generateAdminReport = function() {
        Swal.fire({
            title: 'Generate Admin Report',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-700">Select report format:</p>
                    <select id="reportFormat" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="html">HTML Report (View in Browser)</option>
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Generate Report',
            width: 450,
            preConfirm: () => {
                const format = document.getElementById('reportFormat').value;
                let url = `/admin/admins/report?format=${format}`;
                window.open(url, '_blank');
                return true;
            }
        });
    };

    // ===== DISPLAY SUCCESS/ERROR MESSAGES FROM SESSION =====
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
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

    @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: errorMessages.replace(/\n/g, '<br>'),
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
    .sortable {
        user-select: none;
    }
    .sortable:hover {
        background-color: #f3f4f6;
    }
    #adminsTableBody tr:hover {
        background-color: #f9fafb;
    }
</style>
@endpush