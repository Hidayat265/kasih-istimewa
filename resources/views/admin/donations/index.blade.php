@extends('admin.layouts.adminLayouts')

@section('title', 'Admin - List Donations')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Donations</h1>
    </div>

    <!-- Donation Statistics Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Donations</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $totalDonationCount ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-green-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Total Amount</p>
                    <p class="text-xl md:text-2xl font-bold text-green-600">RM {{ number_format($totalAmount ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Cash Donations</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">RM {{ number_format($cashTotal ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill text-yellow-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-soft p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs md:text-sm">Online Donations</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">RM {{ number_format($onlineTotal ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-purple-600 text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters Bar - Responsive -->
    <div class="bg-bg-light p-4 rounded-lg shadow-soft border mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Date Range Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                <select id="filterDateRange" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <!-- Custom Date Range (hidden by default) -->
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
            
            <!-- Filter by Status -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Status</option>
                    <option value="success">Success</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            
            <!-- Filter by Payment Method -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Payment Method</label>
                <select id="filterMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Methods</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>
            
            <!-- Filter by Received By -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Received By</label>
                <select id="filterReceivedBy" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary text-sm">
                    <option value="">All Received By</option>
                    @foreach($receivedByOptions as $option)
                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Reset Filters Button -->
            <div class="flex items-end">
                <button id="resetFilters" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fas fa-undo-alt mr-2"></i> Reset
                </button>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div id="activeFilters" class="flex flex-wrap gap-2 mt-3"></div>
    </div>

    <div class="bg-bg-light p-4 md:p-6 rounded-lg shadow-soft border">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Donation Records</h2>
            
            <!-- All Buttons Together -->
            <div class="flex flex-wrap gap-2">
                <button onclick="generateDonationReport()" 
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-chart-line mr-1"></i> Report
                </button>
                <a href="{{ route('admin.donations.allocations') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-layer-group mr-1"></i> Allocations
                </a>
                <button type="button" id="open-donation-modal-btn"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-primary hover:bg-primary/90 text-white text-xs font-medium rounded-lg transition">
                    <i class="fas fa-plus mr-1"></i> Cash
                </button>
            </div>
        </div>
        
        {{-- Search Bar --}}
        <div class="mb-4">
            <div class="relative w-full">
                <input 
                    type="text" 
                    name="search" 
                    id="search"
                    placeholder="Search by name, ID, or amount..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        {{-- Loading Spinner --}}
        <div id="tableLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
            <p class="mt-2 text-gray-500">Loading...</p>
        </div>

        {{-- Donation Table with Sortable Headers - Responsive Overflow --}}
        <div id="donations-table-container" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="donationsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="no">
                            No. <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_id">
                            ID <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donor_name">
                            Name <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donor_email">
                            Email <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donor_phone">
                            Phone <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_amount">
                            Amount <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="created_at">
                            Date <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_payment_method">
                            Method <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_received_by">
                            Received By <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_transaction_id">
                            Transaction ID <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition sortable" data-sort="donation_status">
                            Status <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </th>
                        <th class="px-3 md:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="donationsTableBody">
                    @include('admin.donations.partials.table-rows', ['donations' => $donations])
                </tbody>
            </table>
            </div>
            
            {{-- Pagination - AJAX enabled --}}
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Showing <span id="showingStart">{{ $donations->firstItem() ?? 0 }}</span> 
                    to <span id="showingEnd">{{ $donations->lastItem() ?? 0 }}</span> 
                    of <span id="totalResults">{{ $donations->total() ?? 0 }}</span> results
                </div>
                <div id="paginationLinks">
                    @include('admin.donations.partials.pagination', ['donations' => $donations])
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Donation Modal - Mobile Responsive -->
    <div id="donationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl relative transform transition-all duration-300 scale-95 max-h-[90vh] overflow-y-auto">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <i class="fas fa-hand-holding-heart text-primary text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Add Cash Donation</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Record a manual cash donation</p>
                        </div>
                    </div>
                    <button onclick="closeDonationModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.storeDonation') }}" id="donationForm">
                    @csrf
                    <div class="mb-4">
                        <label for="donor_name" class="block text-sm font-medium text-gray-700 mb-2">Donor Name <span class="text-red-500">*</span></label>
                        <input type="text" name="donor_name" id="donor_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            placeholder="Enter donor's full name">
                    </div>
                    <div class="mb-4">
                        <label for="donor_email" class="block text-sm font-medium text-gray-700 mb-2">Donor Email</label>
                        <input type="email" name="donor_email" id="donor_email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            placeholder="Enter donor's email (optional)">
                    </div>
                    <div class="mb-4">
                        <label for="donor_phone" class="block text-sm font-medium text-gray-700 mb-2">Donor Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="donor_phone" id="donor_phone" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            placeholder="Enter donor's phone number">
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (RM) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">RM</span>
                            <input type="number" name="amount" id="amount" required min="1" step="0.01"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                placeholder="0.00">
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" value="cash">
                    <div class="mb-4">
                        <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Receipt/Reference Number</label>
                        <input type="text" name="transaction_id" id="transaction_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            placeholder="Optional receipt/reference number">
                    </div>
                    <div class="mb-4">
                        <label for="received_by" class="block text-sm font-medium text-gray-700 mb-2">Received By <span class="text-red-500">*</span></label>
                        <input type="text" name="received_by" id="received_by" 
                            value="{{ auth()->user()->user_name }}" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row justify-end space-y-2 space-y-reverse sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closeDonationModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                            <i class="fas fa-save mr-2"></i> Save Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentSortColumn = null;
    let currentSortDirection = 'asc';
    let currentPage = 1;
    let currentFilters = {
        status: '',
        method: '',
        receivedBy: '',
        dateRange: '',
        startDate: '',
        endDate: '',
        search: ''
    };
    const receivedByMapping = @json($receivedByMapping ?? []);
    
    // Fetch function
    function fetchDonations(page) {
        if (page !== undefined && page !== null) {
            currentPage = parseInt(page);
        }
        
        const loading = document.getElementById('tableLoading');
        const tbody = document.getElementById('donationsTableBody');
        
        if (loading) loading.classList.remove('hidden');
        if (tbody) {
            tbody.style.opacity = '0.5';
            tbody.style.pointerEvents = 'none';
        }
        
        const params = new URLSearchParams();
        params.append('page', currentPage);
        if (currentFilters.status) params.append('status', currentFilters.status);
        if (currentFilters.method) params.append('method', currentFilters.method);
        if (currentFilters.receivedBy) params.append('received_by', currentFilters.receivedBy);
        if (currentFilters.dateRange) params.append('date_range', currentFilters.dateRange);
        if (currentFilters.startDate) params.append('start_date', currentFilters.startDate);
        if (currentFilters.endDate) params.append('end_date', currentFilters.endDate);
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentSortColumn) {
            params.append('sort', currentSortColumn);
            params.append('direction', currentSortDirection);
        }
        
        fetch(`/admin/donations/data?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (tbody) {
                    tbody.innerHTML = data.html;
                    tbody.style.opacity = '1';
                    tbody.style.pointerEvents = 'auto';
                }
                
                document.getElementById('showingStart').textContent = data.from || 0;
                document.getElementById('showingEnd').textContent = data.to || 0;
                document.getElementById('totalResults').textContent = data.total || 0;
                
                const paginationContainer = document.getElementById('paginationLinks');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationHandlers();
                }
                
                if (data.stats) {
                    updateStatisticsCards(data.stats);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="12" class="px-4 py-8 text-center text-red-500">Error loading data. Please refresh.</td></tr>`;
                tbody.style.opacity = '1';
                tbody.style.pointerEvents = 'auto';
            }
        })
        .finally(() => {
            if (loading) loading.classList.add('hidden');
        });
    }
    
    function attachPaginationHandlers() {
        document.querySelectorAll('#paginationLinks .pagination-link').forEach(link => {
            link.removeEventListener('click', paginationClickHandler);
            link.addEventListener('click', paginationClickHandler);
        });
    }
    
    function paginationClickHandler(e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) {
            fetchDonations(parseInt(page));
        }
        return false;
    }
    
    function updateStatisticsCards(stats) {
        const cards = document.querySelectorAll('.bg-white.rounded-xl.shadow-soft');
        if (cards.length >= 4) {
            const totalDonationsEl = cards[0].querySelector('.text-xl.md\\:text-2xl');
            if (totalDonationsEl) totalDonationsEl.textContent = stats.totalDonations || 0;
            const totalAmountEl = cards[1].querySelector('.text-xl.md\\:text-2xl');
            if (totalAmountEl) totalAmountEl.textContent = `RM ${formatNumber(stats.totalAmount || 0)}`;
            const cashTotalEl = cards[2].querySelector('.text-xl.md\\:text-2xl');
            if (cashTotalEl) cashTotalEl.textContent = `RM ${formatNumber(stats.cashTotal || 0)}`;
            const onlineTotalEl = cards[3].querySelector('.text-xl.md\\:text-2xl');
            if (onlineTotalEl) onlineTotalEl.textContent = `RM ${formatNumber(stats.onlineTotal || 0)}`;
        }
    }
    
    function formatNumber(num) {
        return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    function applyFilters() {
        fetchDonations(1);
        updateActiveFiltersDisplay();
    }
    
    function updateActiveFiltersDisplay() {
        const container = document.getElementById('activeFilters');
        if (!container) return;
        
        const activeFiltersList = [];
        
        if (currentFilters.dateRange === 'custom' && currentFilters.startDate && currentFilters.endDate) {
            const start = new Date(currentFilters.startDate + 'T00:00:00').toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
            const end = new Date(currentFilters.endDate + 'T00:00:00').toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
            activeFiltersList.push({ 
                key: 'dateRange', 
                label: `Custom: ${start} - ${end}`
            });
        } else if (currentFilters.dateRange) {
            const rangeLabels = {
                'today': 'Today',
                'this_week': 'This Week',
                'this_month': 'This Month',
                'last_month': 'Last Month',
                'this_year': 'This Year'
            };
            activeFiltersList.push({ 
                key: 'dateRange', 
                label: rangeLabels[currentFilters.dateRange] || currentFilters.dateRange
            });
        }
        
        if (currentFilters.status) {
            activeFiltersList.push({ 
                key: 'status', 
                label: `Status: ${currentFilters.status.charAt(0).toUpperCase() + currentFilters.status.slice(1)}`
            });
        }
        if (currentFilters.method) {
            activeFiltersList.push({ 
                key: 'method', 
                label: `Method: ${currentFilters.method === 'cash' ? 'Cash' : 'Online'}`
            });
        }
        if (currentFilters.receivedBy) {
            let displayName = receivedByMapping[currentFilters.receivedBy] || currentFilters.receivedBy;
            activeFiltersList.push({ 
                key: 'receivedBy', 
                label: `Received By: ${displayName}`
            });
        }
        if (currentFilters.search) {
            activeFiltersList.push({ 
                key: 'search', 
                label: `Search: "${currentFilters.search}"`
            });
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
            case 'method':
                currentFilters.method = '';
                document.getElementById('filterMethod').value = '';
                break;
            case 'receivedBy':
                currentFilters.receivedBy = '';
                document.getElementById('filterReceivedBy').value = '';
                break;
            case 'search':
                currentFilters.search = '';
                document.getElementById('search').value = '';
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
        
        fetchDonations();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => {
                sortTable(header.getAttribute('data-sort'));
            });
        });
        
        document.getElementById('filterStatus')?.addEventListener('change', function() {
            currentFilters.status = this.value;
            applyFilters();
        });
        
        document.getElementById('filterMethod')?.addEventListener('change', function() {
            currentFilters.method = this.value;
            applyFilters();
        });
        
        document.getElementById('filterReceivedBy')?.addEventListener('change', function() {
            currentFilters.receivedBy = this.value;
            applyFilters();
        });
        
        document.getElementById('filterDateRange')?.addEventListener('change', function() {
            const value = this.value;
            const customRange = document.getElementById('customDateRange');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            
            if (value === 'custom') {
                customRange.classList.remove('hidden');
                startDate.setAttribute('required', 'required');
                endDate.setAttribute('required', 'required');
            } else {
                customRange.classList.add('hidden');
                startDate.removeAttribute('required');
                endDate.removeAttribute('required');
                startDate.value = '';
                endDate.value = '';
                currentFilters.startDate = '';
                currentFilters.endDate = '';
            }
            currentFilters.dateRange = value;
            applyFilters();
        });
        
        document.getElementById('startDate')?.addEventListener('change', function() {
            currentFilters.startDate = this.value;
            if (currentFilters.dateRange === 'custom') applyFilters();
        });
        
        document.getElementById('endDate')?.addEventListener('change', function() {
            currentFilters.endDate = this.value;
            if (currentFilters.dateRange === 'custom') applyFilters();
        });
        
        let searchTimeout;
        document.getElementById('search')?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentFilters.search = this.value;
                applyFilters();
            }, 500);
        });
        
        document.getElementById('resetFilters')?.addEventListener('click', function() {
            currentFilters = { status: '', method: '', receivedBy: '', dateRange: '', startDate: '', endDate: '', search: '' };
            currentSortColumn = null;
            currentSortDirection = 'asc';
            
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterMethod').value = '';
            document.getElementById('filterReceivedBy').value = '';
            document.getElementById('filterDateRange').value = '';
            document.getElementById('search').value = '';
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
            fetchDonations(1);
        }, 100);
    });
    
    // Modal functions
    const modal = document.getElementById('donationModal');
    const openModalBtn = document.getElementById('open-donation-modal-btn');
    
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    }
    
    window.closeDonationModal = function() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        document.getElementById('donationForm')?.reset();
    }
    
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeDonationModal();
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDonationModal();
    });
    
    document.getElementById('donationForm')?.addEventListener('submit', function(e) {
        const name = document.getElementById('donor_name').value;
        const phone = document.getElementById('donor_phone').value;
        const amount = document.getElementById('amount').value;
        
        if (!name || !phone || !amount || amount < 1) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please fill in all required fields.', confirmButtonColor: '#d33' });
            return false;
        }
    });
    
    // ============================================
    // GENERATE REPORT - UPDATED WITH MONTH PICKER 
    // ============================================
    window.generateDonationReport = function() {
        Swal.fire({
            title: 'Generate Report',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-700">Select report period:</p>
                    <select id="reportPeriod" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-3">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_year">This Year</option>
                        <option value="custom">Custom Month</option>
                    </select>
                    <div id="reportCustomMonth" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Month</label>
                        <input type="month" id="reportMonth" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                        <select id="reportFormat" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="html">HTML Report</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#554994',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Generate Report',
            preConfirm: () => {
                const period = document.getElementById('reportPeriod').value;
                let startDate = '';
                let endDate = '';
                
                if (period === 'custom') {
                    const month = document.getElementById('reportMonth').value;
                    if (!month) {
                        Swal.showValidationMessage('Please select a month');
                        return false;
                    }
                    // Set start and end date to the selected month
                    startDate = month + '-01';
                    endDate = month + '-' + new Date(month + '-01').getDaysInMonth();
                }
                
                const format = document.getElementById('reportFormat').value;
                window.open(`/admin/donations/report?period=${period}&start=${startDate}&end=${endDate}&format=${format}`, '_blank');
                return true;
            }
        });
        
        document.getElementById('reportPeriod')?.addEventListener('change', function() {
            const customDiv = document.getElementById('reportCustomMonth');
            if (this.value === 'custom') {
                customDiv.classList.remove('hidden');
            } else {
                customDiv.classList.add('hidden');
            }
        });
    };
    
    
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', confirmButtonColor: '#554994', timer: 3000 });
    @endif
    
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: '{{ session('error') }}', confirmButtonColor: '#d33', timer: 3000 });
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
</style>
@endpush