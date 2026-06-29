@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - Manage Users')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Users</h1>
    </div>

    <!-- User Statistics Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Users</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="totalUsersStat">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">New Users (This Month)</p>
                    <p class="text-xl md:text-2xl font-bold text-purple-600" id="newUsersStat">{{ $newUsersCount ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-purple-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Active Users</p>
                    <p class="text-xl md:text-2xl font-bold text-green-600" id="activeUsersStat">{{ $activeUsers ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Deactivated Users</p>
                    <p class="text-xl md:text-2xl font-bold text-red-600" id="deactivatedUsersStat">{{ $deactivatedUsers ?? 0 }}</p>
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
                <button onclick="generateUserReport()" 
                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                    <i class="fas fa-chart-line mr-1"></i> Report
                </button>
            </div>
        </div>
    </div>

    <div class="bg-bg-light p-4 md:p-6 rounded-lg shadow-soft border">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">User List</h2>
        </div>

        {{-- Loading Spinner --}}
        <div id="tableLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
            <p class="mt-2 text-gray-500">Loading...</p>
        </div>

        {{-- User Table - Responsive Overflow --}}
        <div id="users-table-container" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="usersTable">
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
                <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                    @include('admin.users.partials.users-table', ['users' => $users])
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Showing <span id="usersShowingStart">{{ $users->firstItem() ?? 0 }}</span> 
                to <span id="usersShowingEnd">{{ $users->lastItem() ?? 0 }}</span> 
                of <span id="usersTotalResults">{{ $users->total() ?? 0 }}</span> users
            </div>
            <div id="usersPaginationLinks" class="flex justify-center space-x-2">
                @include('admin.users.partials.pagination', ['users' => $users])
            </div>
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

    function fetchUsers(page = 1) {
        const tableLoading = document.getElementById('tableLoading');
        const usersTableContainer = document.getElementById('users-table-container');
        
        if (tableLoading) tableLoading.classList.remove('hidden');
        if (usersTableContainer) {
            usersTableContainer.style.opacity = '0.5';
        }

        const params = new URLSearchParams();
        params.append('page', page);
        if (currentFilters.status) params.append('status', currentFilters.status);
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentSortColumn) {
            params.append('sort', currentSortColumn);
            params.append('direction', currentSortDirection);
        }

        const fetchUrl = `{{ route('admin.users.index') }}?${params.toString()}`;

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
                const tbody = document.querySelector('#usersTable tbody');
                if (tbody) {
                    tbody.innerHTML = data.html;
                }
                
                // Update pagination links
                const paginationContainer = document.getElementById('usersPaginationLinks');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationHandlers();
                }
                
                // Update showing info
                document.getElementById('usersShowingStart').textContent = data.from || 0;
                document.getElementById('usersShowingEnd').textContent = data.to || 0;
                document.getElementById('usersTotalResults').textContent = data.total || 0;
                
                // Update statistics cards
                if (data.stats) {
                    document.getElementById('totalUsersStat').textContent = data.stats.totalUsers || 0;
                    document.getElementById('newUsersStat').textContent = data.stats.newUsersCount || 0;
                    document.getElementById('activeUsersStat').textContent = data.stats.activeUsers || 0;
                    document.getElementById('deactivatedUsersStat').textContent = data.stats.deactivatedUsers || 0;
                }
                
                // Update sort icons
                updateSortIcons(currentSortColumn);
                
                if (tableLoading) tableLoading.classList.add('hidden');
                if (usersTableContainer) {
                    usersTableContainer.style.opacity = '1';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (tableLoading) tableLoading.classList.add('hidden');
            if (usersTableContainer) {
                usersTableContainer.style.opacity = '1';
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
        
        fetchUsers(1);
    }

    function attachPaginationHandlers() {
        document.querySelectorAll('#usersPaginationLinks .pagination-link').forEach(link => {
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }

    function paginationClickHandler(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) {
            fetchUsers(parseInt(page));
        }
        return false;
    }

    // ============================================
    // GENERATE USER REPORT
    // ============================================
    window.generateUserReport = function() {
        Swal.fire({
            title: 'Generate User Report',
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
                let url = `/admin/users/report?format=${format}`;
                window.open(url, '_blank');
                return true;
            }
        });
    };

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
            fetchUsers(1);
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
                    fetchUsers(1);
                }, 500);
            });
        }


        // Initial load
        setTimeout(() => {
            attachPaginationHandlers();
            updateSortIcons('user_id');
        }, 100);
    });
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
    #usersTableBody tr:hover {
        background-color: #f9fafb;
    }
</style>
@endpush