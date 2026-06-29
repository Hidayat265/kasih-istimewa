@extends('admin.layouts.adminLayouts')
@section('title', 'Admin - Dashboard')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <div class="flex gap-3">
        <button onclick="window.location.reload()" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition text-sm font-medium">
            <i class="fas fa-sync-alt mr-2"></i> Refresh
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-soft border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Donations (This Month)</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">RM {{ number_format($donationsThisMonth ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hand-holding-heart text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="w-full h-1.5 bg-gray-200 rounded-full">
                <div class="h-1.5 bg-blue-500 rounded-full" style="width: 75%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-soft border-l-4 border-teal-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Events</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalEvents ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-alt text-teal-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="w-full h-1.5 bg-gray-200 rounded-full">
                <div class="h-1.5 bg-teal-500 rounded-full" style="width: 60%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-soft border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Participants</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalParticipants ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="w-full h-1.5 bg-gray-200 rounded-full">
                <div class="h-1.5 bg-green-500 rounded-full" style="width: 80%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-soft border-l-4 border-amber-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">New Users (Month)</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $newUsersCount ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-amber-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="w-full h-1.5 bg-gray-200 rounded-full">
                <div class="h-1.5 bg-amber-500 rounded-full" style="width: 40%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Donut Chart - Event Status Distribution -->
    <div class="bg-white p-6 rounded-xl shadow-soft border">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-primary mr-2"></i> Event Status Distribution
        </h3>
        <div class="h-64">
            <canvas id="eventStatusChart"></canvas>
        </div>
        <div class="flex flex-wrap justify-center gap-4 mt-4" id="eventStatusLegend"></div>
    </div>

    <!-- Bar Chart - Monthly Events -->
    <div class="bg-white p-6 rounded-xl shadow-soft border">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-primary mr-2"></i> Monthly Events (Last 6 Months)
        </h3>
        <div class="h-64">
            <canvas id="monthlyEventsChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Events Table -->
<div class="bg-white p-6 rounded-xl shadow-soft border">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h2 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-clock text-primary mr-2"></i> Recent Events
        </h2>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <input type="text" id="searchEvents" placeholder="Search events..." 
                   class="w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="NeedUpdate">Needs Update</option>
            </select>
        </div>
    </div>

    <div id="tableLoading" class="hidden text-center py-8">
        <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
        <p class="mt-2 text-gray-500">Loading...</p>
    </div>

    <div id="events-table-container" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="no">No.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_id">Event ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_name">Event Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_company_name">Organizer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_start_date">Start Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_end_date">End Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="event_approval_status">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="eventsTableBody">
                @include('admin.dashboard.partials.events-table', ['events' => $events])
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-700">
            Showing <span id="eventsShowingStart">{{ $events->firstItem() ?? 0 }}</span> 
            to <span id="eventsShowingEnd">{{ $events->lastItem() ?? 0 }}</span> 
            of <span id="eventsTotalResults">{{ $events->total() ?? 0 }}</span> events
        </div>
        <div id="eventsPaginationLinks" class="flex justify-center space-x-2">
            @include('admin.dashboard.partials.pagination', ['events' => $events])
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // CHART: Event Status Distribution (Donut)
        // ============================================
        const statusCtx = document.getElementById('eventStatusChart');
        if (statusCtx) {
            const statusData = @json($eventStatusData ?? []);
            const colors = ['#554994', '#22c55e', '#dc2626', '#f59e0b', '#6b7280'];
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: colors.slice(0, Object.keys(statusData).length),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        // ============================================
        // CHART: Monthly Events (Bar)
        // ============================================
        const monthlyCtx = document.getElementById('monthlyEventsChart');
        if (monthlyCtx) {
            const monthlyData = @json($monthlyEventsData ?? []);
            const months = monthlyData.map(item => item.month);
            const counts = monthlyData.map(item => item.count);
            
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Events',
                        data: counts,
                        backgroundColor: 'rgba(85, 73, 148, 0.7)',
                        borderColor: '#554994',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // ============================================
        // AJAX TABLE FUNCTIONS
        // ============================================
        let currentPage = 1;
        let currentSort = 'created_at';
        let currentDirection = 'desc';
        let currentSearch = '';
        let currentStatus = '';

        function fetchEvents(page = 1) {
            currentPage = page;
            
            const loading = document.getElementById('tableLoading');
            const container = document.getElementById('events-table-container');
            
            if (loading) loading.classList.remove('hidden');
            if (container) container.style.opacity = '0.5';

            const params = new URLSearchParams();
            params.append('page', page);
            params.append('sort', currentSort);
            params.append('direction', currentDirection);
            if (currentSearch) params.append('search', currentSearch);
            if (currentStatus) params.append('status', currentStatus);

            const url = `{{ route('admin.dashboard.events.data') }}?${params.toString()}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('eventsTableBody').innerHTML = data.html;
                    document.getElementById('eventsPaginationLinks').innerHTML = data.pagination;
                    document.getElementById('eventsShowingStart').textContent = data.from || 0;
                    document.getElementById('eventsShowingEnd').textContent = data.to || 0;
                    document.getElementById('eventsTotalResults').textContent = data.total || 0;
                    
                    attachPaginationHandlers();
                }
                if (loading) loading.classList.add('hidden');
                if (container) container.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                if (loading) loading.classList.add('hidden');
                if (container) container.style.opacity = '1';
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
        }

        // Sortable headers
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const sort = this.getAttribute('data-sort');
                if (sort === 'no') return;
                
                if (currentSort === sort) {
                    currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort = sort;
                    currentDirection = 'asc';
                }
                
                document.querySelectorAll('.sortable i').forEach(icon => {
                    icon.className = 'fas fa-sort text-gray-400';
                });
                
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = currentDirection === 'asc' 
                        ? 'fas fa-sort-up text-primary' 
                        : 'fas fa-sort-down text-primary';
                }
                
                fetchEvents(1);
            });
        });

        // Search
        const searchInput = document.getElementById('searchEvents');
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    currentSearch = this.value.trim();
                    fetchEvents(1);
                }, 500);
            });
        }

        // Status Filter
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                currentStatus = this.value;
                fetchEvents(1);
            });
        }

        // Initial load
        setTimeout(() => {
            attachPaginationHandlers();
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
        cursor: pointer;
    }
    .sortable:hover {
        background-color: #f3f4f6;
    }
    #eventsTableBody tr:hover {
        background-color: #f9fafb;
    }
    .pagination-link {
        cursor: pointer;
        transition: all 0.2s;
    }
    .pagination-link:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush