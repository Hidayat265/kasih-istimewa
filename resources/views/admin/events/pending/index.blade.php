@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - Pending Events')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Pending Events</h1>
    </div>

    <!-- Event Statistics Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Pending</p>
                    <p class="text-xl md:text-2xl font-bold text-yellow-600" id="totalPendingStat">{{ $totalPending ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Awaiting Review</p>
                    <p class="text-xl md:text-2xl font-bold text-blue-600" id="awaitingStat">{{ $pendingCount ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-blue-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Needs Update</p>
                    <p class="text-xl md:text-2xl font-bold text-orange-600" id="needUpdateStat">{{ $needUpdateCount ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-edit text-orange-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar - Responsive -->
    <div class="bg-bg-light p-4 rounded-lg shadow-soft border mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <!-- Date Range Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                <select id="filterDateRange" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Time</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <!-- Custom Date Range -->
            <div id="customDateRange" class="hidden col-span-2">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Pending</option>
                    <option value="Pending">Awaiting Review</option>
                    <option value="NeedsUpdate">Needs Update</option>
                </select>
            </div>

            <!-- Organizer Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Organizer</label>
                <select id="filterOrganizer" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Organizers</option>
                    @foreach($organizers ?? [] as $org)
                        <option value="{{ $org->user_id }}">{{ $org->user_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="searchInput" placeholder="Name, ID, Organizer, Location..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors duration-200 text-sm">
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
            <div id="activeFilters" class="flex flex-wrap gap-2"></div>
            <button id="resetFilters" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                <i class="fas fa-undo-alt mr-2"></i> Reset
            </button>
        </div>
    </div>

    <div class="bg-bg-light p-4 md:p-6 rounded-lg shadow-soft border">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Pending Events</h2>
            
            <!-- All Buttons Together -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.events.create') }}" 
                class="inline-flex items-center justify-center px-3 py-1.5 bg-primary hover:bg-primary/90 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-plus mr-1"></i> Create Event
                </a>
            </div>
        </div>

        {{-- Loading Spinner --}}
        <div id="tableLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
            <p class="mt-2 text-gray-500">Loading...</p>
        </div>

        {{-- Events Table - Responsive Overflow --}}
        <div id="events-table-container" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="eventsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="no">
                            No.
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_id">
                            ID <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_name">
                            Name / Location <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_company_name">
                            Organizer <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="created_at">
                            Submitted <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_start_date">
                            Start Date <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_end_date">
                            End Date <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_approval_status">
                            Status <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="eventsTableBody">
                    @include('admin.events.pending.partials.events-table', ['events' => $events])
                </tbody>
            </table>
        </div>

        {{-- Pagination - AJAX enabled --}}
        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Showing <span id="eventsShowingStart">{{ $events->firstItem() ?? 0 }}</span> 
                to <span id="eventsShowingEnd">{{ $events->lastItem() ?? 0 }}</span> 
                of <span id="eventsTotalResults">{{ $events->total() ?? 0 }}</span> events
            </div>
            <div id="eventsPaginationLinks" class="flex justify-center space-x-2">
                @include('admin.events.pending.partials.pagination', ['events' => $events])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentSortColumn = 'created_at';
    let currentSortDirection = 'desc';
    let currentPage = 1;
    let currentFilters = {
        search: '',
        dateRange: '',
        status: '',
        organizer: '',
        startDate: '',
        endDate: ''
    };

    function fetchEvents(page = 1) {
        if (page !== null && page !== undefined) {
            currentPage = parseInt(page);
        }
        
        const tableLoading = document.getElementById('tableLoading');
        const tableContainer = document.getElementById('events-table-container');
        
        if (tableLoading) tableLoading.classList.remove('hidden');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        const params = new URLSearchParams();
        params.append('page', currentPage);
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentFilters.dateRange) params.append('date_range', currentFilters.dateRange);
        if (currentFilters.status) params.append('status', currentFilters.status);
        if (currentFilters.organizer) params.append('organizer', currentFilters.organizer);
        if (currentFilters.startDate) params.append('start_date', currentFilters.startDate);
        if (currentFilters.endDate) params.append('end_date', currentFilters.endDate);
        if (currentSortColumn) {
            params.append('sort', currentSortColumn);
            params.append('direction', currentSortDirection);
        }

        const fetchUrl = `{{ route('admin.events.pending.data') }}?${params.toString()}`;

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#eventsTable tbody');
                if (tbody) {
                    tbody.innerHTML = data.html;
                }
                
                const paginationContainer = document.getElementById('eventsPaginationLinks');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationHandlers();
                }
                
                document.getElementById('eventsShowingStart').textContent = data.from || 0;
                document.getElementById('eventsShowingEnd').textContent = data.to || 0;
                document.getElementById('eventsTotalResults').textContent = data.total || 0;
                
                if (data.stats) {
                    document.getElementById('totalPendingStat').textContent = data.stats.total || 0;
                    document.getElementById('awaitingStat').textContent = data.stats.pending || 0;
                    document.getElementById('needUpdateStat').textContent = data.stats.need_update || 0;
                }
                
                if (tableLoading) tableLoading.classList.add('hidden');
                if (tableContainer) {
                    tableContainer.style.opacity = '1';
                }
                
                updateActiveFiltersDisplay();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (tableLoading) tableLoading.classList.add('hidden');
            if (tableContainer) {
                tableContainer.style.opacity = '1';
            }
        });
    }

    function attachPaginationHandlers() {
        document.querySelectorAll('#eventsPaginationLinks .pagination-link').forEach(link => {
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }

    function paginationClickHandler(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) {
            fetchEvents(parseInt(page));
        }
        return false;
    }

    function applyFilters() {
        currentPage = 1;
        fetchEvents(1);
        updateActiveFiltersDisplay();
    }

    function updateActiveFiltersDisplay() {
        const container = document.getElementById('activeFilters');
        if (!container) return;

        const activeFiltersList = [];

        if (currentFilters.search) {
            activeFiltersList.push({ key: 'search', label: `Search: "${currentFilters.search}"` });
        }
        if (currentFilters.dateRange === 'custom' && currentFilters.startDate && currentFilters.endDate) {
            const start = new Date(currentFilters.startDate + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            const end = new Date(currentFilters.endDate + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            activeFiltersList.push({ key: 'dateRange', label: `Custom: ${start} - ${end}` });
        } else if (currentFilters.dateRange) {
            const rangeLabels = {
                'this_week': 'This Week',
                'this_month': 'This Month',
                'last_month': 'Last Month'
            };
            activeFiltersList.push({ key: 'dateRange', label: `Date: ${rangeLabels[currentFilters.dateRange] || currentFilters.dateRange}` });
        }
        if (currentFilters.status) {
            const statusLabels = {
                'Pending': 'Awaiting Review',
                'NeedsUpdate': 'Needs Update'
            };
            activeFiltersList.push({ key: 'status', label: `Status: ${statusLabels[currentFilters.status] || currentFilters.status}` });
        }
        if (currentFilters.organizer) {
            const select = document.getElementById('filterOrganizer');
            const option = select?.querySelector(`option[value="${currentFilters.organizer}"]`);
            const label = option ? option.textContent : currentFilters.organizer;
            activeFiltersList.push({ key: 'organizer', label: `Organizer: ${label}` });
        }

        if (activeFiltersList.length === 0) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = activeFiltersList.map(filter => `
            <span class="inline-flex items-center px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                ${filter.label}
                <button onclick="removeFilter('${filter.key}')" class="ml-1 hover:text-primary/70">
                    <i class="fas fa-times-circle text-xs"></i>
                </button>
            </span>
        `).join('');
    }

    window.removeFilter = function(key) {
        switch(key) {
            case 'search':
                currentFilters.search = '';
                document.getElementById('searchInput').value = '';
                break;
            case 'dateRange':
                currentFilters.dateRange = '';
                document.getElementById('filterDateRange').value = '';
                document.getElementById('customDateRange').classList.add('hidden');
                document.getElementById('startDate').value = '';
                document.getElementById('endDate').value = '';
                currentFilters.startDate = '';
                currentFilters.endDate = '';
                break;
            case 'status':
                currentFilters.status = '';
                document.getElementById('filterStatus').value = '';
                break;
            case 'organizer':
                currentFilters.organizer = '';
                document.getElementById('filterOrganizer').value = '';
                break;
        }
        applyFilters();
    };

    function sortTable(column) {
        if (currentSortColumn === column) {
            currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortColumn = column;
            currentSortDirection = 'asc';
        }
        
        document.querySelectorAll('.sortable i').forEach(icon => {
            icon.className = 'fas fa-sort ml-1 text-gray-400';
        });
        
        const activeHeader = document.querySelector(`.sortable[data-sort="${column}"] i`);
        if (activeHeader) {
            activeHeader.className = currentSortDirection === 'asc' 
                ? 'fas fa-sort-up ml-1 text-primary' 
                : 'fas fa-sort-down ml-1 text-primary';
        }
        
        fetchEvents(1);
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

        // Date Range Filter - Show/Hide Custom Date Range
        const filterDateRange = document.getElementById('filterDateRange');
        const customDateRange = document.getElementById('customDateRange');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        if (filterDateRange) {
            filterDateRange.addEventListener('change', function() {
                const value = this.value;
                if (value === 'custom') {
                    customDateRange.classList.remove('hidden');
                    startDateInput.setAttribute('required', 'required');
                    endDateInput.setAttribute('required', 'required');
                } else {
                    customDateRange.classList.add('hidden');
                    startDateInput.removeAttribute('required');
                    endDateInput.removeAttribute('required');
                    startDateInput.value = '';
                    endDateInput.value = '';
                    currentFilters.startDate = '';
                    currentFilters.endDate = '';
                }
                currentFilters.dateRange = value;
                applyFilters();
            });
        }

        if (startDateInput) {
            startDateInput.addEventListener('change', function() {
                currentFilters.startDate = this.value;
                if (currentFilters.dateRange === 'custom') {
                    applyFilters();
                }
            });
        }

        if (endDateInput) {
            endDateInput.addEventListener('change', function() {
                currentFilters.endDate = this.value;
                if (currentFilters.dateRange === 'custom') {
                    applyFilters();
                }
            });
        }

        // Search
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                currentFilters.search = searchTerm;
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });
        }

        // Status Filter
        document.getElementById('filterStatus')?.addEventListener('change', function() {
            currentFilters.status = this.value;
            applyFilters();
        });

        // Organizer Filter
        document.getElementById('filterOrganizer')?.addEventListener('change', function() {
            currentFilters.organizer = this.value;
            applyFilters();
        });

        // Reset Filters
        document.getElementById('resetFilters')?.addEventListener('click', function() {
            currentFilters = { search: '', dateRange: '', status: '', organizer: '', startDate: '', endDate: '' };
            currentSortColumn = 'created_at';
            currentSortDirection = 'desc';
            
            document.getElementById('searchInput').value = '';
            document.getElementById('filterDateRange').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterOrganizer').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('customDateRange').classList.add('hidden');
            
            document.querySelectorAll('.sortable i').forEach(icon => {
                icon.className = 'fas fa-sort ml-1 text-gray-400';
            });
            
            applyFilters();
        });

        // Initial load
        setTimeout(() => {
            attachPaginationHandlers();
            fetchEvents(1);
        }, 100);
    });

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
    .sortable {
        user-select: none;
        cursor: pointer;
    }
    .sortable:hover {
        background-color: #f3f4f6;
    }
    #eventsTableBody tr:hover {
        background-color: #f9fafb;
    }
</style>
@endpush